import ftplib
import os
import json

def deploy_polylang():
    # Leer credenciales
    with open('sftp_credentials.json', 'r') as f:
        creds = json.load(f)
    
    host = creds['host']
    user = creds['username']
    password = creds['password']
    port = creds.get('port', 21)
    
    local_dir = 'polylang'
    
    if not os.path.exists(local_dir):
        print(f"No se encontró el directorio {local_dir}")
        return
    
    remote_dir = '/public_html/wp-content/plugins/polylang'
    
    print(f"Conectando a {host}:{port}...")
    
    try:
        ftp = ftplib.FTP_TLS()
        ftp.connect(host, port)
        ftp.login(user, password)
        ftp.prot_p()
        print("Conexión FTPS establecida.")
        
        # Crear directorio remoto
        try:
            ftp.mkd(remote_dir)
            print(f"Creado: {remote_dir}")
        except ftplib.error_perm:
            print(f"Directorio ya existe: {remote_dir}")
        
        # Subir archivos recursivamente
        def upload_recursive(local_path, remote_path):
            for item in os.listdir(local_path):
                local_item = os.path.join(local_path, item)
                remote_item = f"{remote_path}/{item}"
                
                if os.path.isdir(local_item):
                    try:
                        ftp.mkd(remote_item)
                    except ftplib.error_perm:
                        pass
                    upload_recursive(local_item, remote_item)
                else:
                    with open(local_item, 'rb') as f:
                        ftp.storbinary(f'STOR {remote_item}', f)
                    print(f"  + {remote_item}")
        
        upload_recursive(local_dir, remote_dir)
        
        ftp.quit()
        print("\nPolylang subido exitosamente.")
        print(f"Activa el plugin desde wp-admin: Plugins → Polylang → Activar")
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == '__main__':
    deploy_polylang()
