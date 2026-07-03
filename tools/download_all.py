# /// script
# requires-python = ">=3.9"
# dependencies = [
#     "urllib3>=2.0.0",
# ]
# ///

import os
import sys
import time
from pathlib import Path
import urllib.request
import urllib.parse

# Configuración de rutas
BASE_DIR = Path(__file__).resolve().parent.parent
LIST_FILE = BASE_DIR / "data" / "gredos_pdfs_list.txt"
PDF_DIR = BASE_DIR / "data" / "pdfs"

def load_pdf_list():
    if not LIST_FILE.exists():
        print(f"[!] Error: No se encontró la lista de PDFs en: {LIST_FILE}")
        sys.exit(1)
        
    pdf_list = []
    with open(LIST_FILE, "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if not line or "\t" not in line:
                continue
            name, url = line.split("\t", 1)
            pdf_list.append((name, url))
    return pdf_list

def download_file(name, url, idx, total):
    PDF_DIR.mkdir(parents=True, exist_ok=True)
    dest_path = PDF_DIR / name
    
    # Si el archivo ya existe y tiene un tamaño mayor a 0, lo omitimos para permitir reanudación
    if dest_path.exists() and dest_path.stat().st_size > 1024 * 1024:
        print(f"[{idx}/{total}] Omitido (ya descargado): {name}")
        return True
        
    print(f"[{idx}/{total}] Descargando: {name}")
    
    # Usar la URL original que ya viene codificada
    safe_url = url
    
    retries = 3
    for attempt in range(1, retries + 1):
        try:
            start_time = time.time()
            urllib.request.urlretrieve(safe_url, dest_path)
            duration = time.time() - start_time
            size_mb = dest_path.stat().st_size / (1024 * 1024)
            print(f"    [+] Completado: {size_mb:.2f} MB en {duration:.1f}s")
            return True
        except Exception as e:
            print(f"    [!] Error en intento {attempt}/{retries}: {e}")
            if dest_path.exists():
                try:
                    dest_path.unlink()  # Eliminar archivo parcial corrupto
                except:
                    pass
            if attempt < retries:
                time.sleep(2)
    return False

def main():
    pdf_list = load_pdf_list()
    total = len(pdf_list)
    print(f"[*] Iniciando la descarga masiva de {total} libros en: {PDF_DIR.absolute()}")
    print("[*] Esta operación puede tardar y consumirá bastante espacio en disco (~30-50 GB).")
    print("[*] Puedes cancelar en cualquier momento; el script saltará lo ya descargado al reiniciar.")
    print("==================================================")
    
    success_count = 0
    failed_count = 0
    skipped_count = 0
    
    for idx, (name, url) in enumerate(pdf_list, 1):
        dest_path = PDF_DIR / name
        if dest_path.exists() and dest_path.stat().st_size > 1024 * 1024:
            skipped_count += 1
            continue
            
        success = download_file(name, url, idx, total)
        if success:
            success_count += 1
        else:
            failed_count += 1
            
    print("==================================================")
    print("Descarga masiva finalizada.")
    print(f"- Omitidos (ya existentes): {skipped_count}")
    print(f"- Descargados con éxito: {success_count}")
    print(f"- Fallidos: {failed_count}")

if __name__ == "__main__":
    main()
