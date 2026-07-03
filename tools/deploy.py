import os
import sys
import json
import ftplib
from pathlib import Path

def load_credentials():
    # Look for sftp_credentials.json in the current dir or parent dir
    paths_to_check = [
        Path('sftp_credentials.json'),
        Path('../sftp_credentials.json'),
        Path(__file__).parent / 'sftp_credentials.json',
        Path(__file__).parent.parent / 'sftp_credentials.json'
    ]
    for p in paths_to_check:
        if p.exists():
            with open(p, 'r', encoding='utf-8') as f:
                return json.load(f)
    print("Error: No se encontró sftp_credentials.json")
    sys.exit(1)

def upload_file_ftp(ftp, local_path, remote_path):
    print(f"Subiendo: {local_path} -> {remote_path}")
    try:
        # Create remote directories if they don't exist
        remote_dir = os.path.dirname(remote_path)
        parts = [p for p in remote_dir.split('/') if p]
        
        # Go to root
        ftp.cwd('/')
        
        for part in parts:
            try:
                ftp.cwd(part)
            except ftplib.error_perm:
                print(f"Creando directorio remoto: {part}")
                ftp.mkd(part)
                ftp.cwd(part)
                
        # Upload file in binary mode
        with open(local_path, 'rb') as f:
            ftp.storbinary(f'STOR {os.path.basename(remote_path)}', f)
    except Exception as e:
        print(f"Error al subir {local_path}: {e}")

def main():
    creds = load_credentials()
    host = creds.get('host')
    user = creds.get('username')
    password = creds.get('password')
    port = creds.get('port', 21)
    
    print("Iniciando despliegue de Nihil Novi...")
    print(f"Host: {host}")
    print(f"Usuario: {user}")
    
    # cPanel FTP sub-accounts must use FTP (port 21). 
    # If the username contains "@" and port is set to 2222, we correct it to 21.
    if '@' in user and port == 2222:
        print("Aviso: El nombre de usuario tiene formato de cuenta FTP adicional (@).")
        print("       Cambiando puerto a 21 (FTP estándar) ya que SFTP solo es para el usuario cPanel principal.")
        port = 21

    # Connect using FTP / FTPS
    try:
        print(f"Conectando a {host}:{port} usando FTPS (FTP Seguro)...")
        ftp = ftplib.FTP_TLS()
        ftp.connect(host, port, timeout=15)
        ftp.login(user, password)
        ftp.prot_p() # Secure data connection
        print("Conexión segura FTPS establecida.")
    except Exception as e:
        print(f"FTPS falló ({e}). Reintentando con FTP estándar (no cifrado)...")
        try:
            ftp = ftplib.FTP()
            ftp.connect(host, port, timeout=15)
            ftp.login(user, password)
            print("Conexión FTP estándar establecida.")
        except Exception as err:
            print(f"Error crítico: No se pudo conectar al servidor: {err}")
            sys.exit(1)
            
    # Auto-detect target root directory
    ftp.cwd('/')
    dirs = ftp.nlst()
    
    remote_theme_base = ""
    # Case 1: We are already mapped directly inside the theme directory
    if 'style.css' in dirs or 'functions.php' in dirs:
        remote_theme_base = '/'
    # Case 2: FTP account root is set to public_html/ (the user sees wp-content directly at root)
    elif 'wp-content' in dirs:
        remote_theme_base = '/wp-content/themes/nihilnovi-theme'
    # Case 3: FTP account root is the system home / (the user sees public_html)
    elif 'public_html' in dirs:
        remote_theme_base = '/public_html/wp-content/themes/nihilnovi-theme'
    else:
        # Check if we are already inside public_html (some systems map root directly here)
        try:
            ftp.cwd('wp-content')
            ftp.cwd('themes')
            dirs_themes = ftp.nlst()
            if 'nihilnovi-theme' in dirs_themes or 'twentytwentyfour' in dirs_themes:
                remote_theme_base = '/wp-content/themes/nihilnovi-theme'
        except Exception:
            pass
            
    if not remote_theme_base:
        # If the root folder is empty or only contains '.ftpquota', assume the user mapped the FTP account directly to the theme folder.
        filtered_dirs = [d for d in dirs if d not in ('.', '..', '.ftpquota')]
        if len(filtered_dirs) == 0:
            print("Aviso: Carpeta raíz vacía. Asumiendo que la cuenta FTP está mapeada directamente a 'nihilnovi-theme'.")
            remote_theme_base = '/'
        else:
            print("Error: No se pudo localizar la carpeta 'wp-content/themes/' de WordPress.")
            print(f"Directorios en la raíz del FTP: {dirs}")
            ftp.quit()
            sys.exit(1)
        
    print(f"Carpeta destino detectada en servidor: {remote_theme_base}")
    
    # Ensure remote theme directory exists
    try:
        ftp.cwd('/')
        parts = [p for p in remote_theme_base.split('/') if p]
        for part in parts:
            try:
                ftp.cwd(part)
            except ftplib.error_perm:
                ftp.mkd(part)
                ftp.cwd(part)
    except Exception as e:
        print(f"Error al asegurar el directorio del tema: {e}")
        ftp.quit()
        sys.exit(1)
        
    # Local theme path
    # Find local theme directory path
    local_base = Path('nihilnovi-theme')
    if not local_base.exists():
        local_base = Path('../nihilnovi-theme')
    if not local_base.exists():
        # Look relative to file
        local_base = Path(__file__).parent.parent / 'nihilnovi-theme'
        
    if not local_base.exists():
        print("Error: No se encontró la carpeta local 'nihilnovi-theme'.")
        ftp.quit()
        sys.exit(1)
        
    print(f"Subiendo archivos desde carpeta local: {local_base.resolve()}")
    
    # Walk local theme files and upload
    success_count = 0
    error_count = 0
    
    for root, _, files in os.walk(local_base):
        for file in files:
            # Skip hidden files or backups
            if file.startswith('.') or file.endswith('~') or file.endswith('.zip'):
                continue
                
            local_file_path = os.path.join(root, file)
            # Calculate remote path relative to theme base
            rel_path = os.path.relpath(local_file_path, local_base).replace('\\', '/')
            if remote_theme_base == '/':
                remote_file_path = f"/{rel_path}"
            else:
                remote_file_path = f"{remote_theme_base}/{rel_path}"
            
            try:
                upload_file_ftp(ftp, local_file_path, remote_file_path)
                success_count += 1
            except Exception as ex:
                print(f"Error al procesar archivo {file}: {ex}")
                error_count += 1
                
    ftp.quit()
    print("\n--- Proceso finalizado ---")
    print(f"Archivos subidos con éxito: {success_count}")
    if error_count > 0:
        print(f"Archivos fallidos: {error_count}")
    else:
        print("¡Despliegue completado sin errores!")

if __name__ == '__main__':
    main()
