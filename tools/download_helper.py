# /// script
# requires-python = ">=3.9"
# dependencies = [
#     "urllib3>=2.0.0",
# ]
# ///

import os
import sys
from pathlib import Path
import urllib.request
import urllib.parse

# Configuración de rutas
BASE_DIR = Path(__file__).resolve().parent.parent
LIST_FILE = BASE_DIR / "data" / "gredos_pdfs_list.txt"
PDF_DIR = BASE_DIR / "data" / "pdfs"

def load_pdf_list():
    """Carga la lista de PDFs desde el archivo de datos."""
    if not LIST_FILE.exists():
        print(f"[!] Error: No se encontró la lista de PDFs en: {LIST_FILE}")
        print("Asegúrate de que el archivo 'data/gredos_pdfs_list.txt' exista.")
        sys.exit(1)
        
    pdf_dict = {}
    with open(LIST_FILE, "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if not line or "\t" not in line:
                continue
            name, url = line.split("\t", 1)
            pdf_dict[name] = url
    return pdf_dict

def search_pdfs(pdf_dict, query):
    """Busca coincidencias en la lista de PDFs."""
    query_lower = query.lower()
    matches = []
    for name, url in pdf_dict.items():
        if query_lower in name.lower():
            matches.append((name, url))
    return sorted(matches, key=lambda x: x[0])

def download_pdf(name, url):
    """Descarga un PDF mostrando una barra de progreso simple."""
    PDF_DIR.mkdir(parents=True, exist_ok=True)
    dest_path = PDF_DIR / name
    
    print(f"\n[*] Iniciando descarga de: {name}")
    print(f"[*] Origen: {url}")
    print(f"[*] Destino: {dest_path.absolute()}")
    
    def report_hook(block_num, block_size, total_size):
        read_so_far = block_num * block_size
        if total_size > 0:
            percent = min(100, (read_so_far * 100) // total_size)
            sys.stdout.write(f"\r[+] Descargando: {percent}% ({read_so_far // 1024 // 1024} MB / {total_size // 1024 // 1024} MB)")
        else:
            sys.stdout.write(f"\r[+] Descargando: {read_so_far // 1024 // 1024} MB")
        sys.stdout.flush()
        
    try:
        # Usar la URL original que ya viene codificada
        safe_url = url
        
        urllib.request.urlretrieve(safe_url, dest_path, reporthook=report_hook)
        print(f"\n[+] Descarga completada con éxito: {dest_path.name}")
        return True
    except Exception as e:
        print(f"\n[!] Error al descargar el archivo: {e}")
        return False

def main():
    pdf_dict = load_pdf_list()
    print("==================================================")
    print("      Nihil Novi - Descargador de PDFs Gredos     ")
    print("==================================================")
    print(f"Total de obras disponibles: {len(pdf_dict)}")
    
    while True:
        print("\nOpciones:")
        print("1. Buscar libro por título o autor")
        print("2. Ver todos los libros disponibles")
        print("3. Salir")
        
        opcion = input("\nSelecciona una opción (1-3): ").strip()
        
        if opcion == "1":
            query = input("Escribe el texto a buscar (ej. Platon, Aristoteles, Epicteto): ").strip()
            if not query:
                print("[!] Búsqueda vacía.")
                continue
                
            matches = search_pdfs(pdf_dict, query)
            if not matches:
                print(f"[!] No se encontraron resultados para: '{query}'")
                continue
                
            print(f"\nResultados encontrados ({len(matches)}):")
            for idx, (name, _) in enumerate(matches, 1):
                print(f"{idx:3d}. {name}")
                
            selection = input(f"\nIngresa el número para descargar (1-{len(matches)}) o pulsa Enter para volver: ").strip()
            if not selection.isdigit():
                continue
            
            sel_idx = int(selection) - 1
            if 0 <= sel_idx < len(matches):
                name, url = matches[sel_idx]
                download_pdf(name, url)
            else:
                print("[!] Selección no válida.")
                
        elif opcion == "2":
            print("\nListado completo de obras:")
            sorted_names = sorted(pdf_dict.keys())
            for idx, name in enumerate(sorted_names, 1):
                print(f"{idx:3d}. {name}")
                if idx % 50 == 0:
                    cont = input("\n--- Presiona Enter para ver más, o escribe 'q' para volver --- ")
                    if cont.strip().lower() == 'q':
                        break
                        
            selection = input(f"\nIngresa el número para descargar (1-{len(pdf_dict)}) o pulsa Enter para volver: ").strip()
            if not selection.isdigit():
                continue
            
            sel_idx = int(selection) - 1
            if 0 <= sel_idx < len(sorted_names):
                name = sorted_names[sel_idx]
                url = pdf_dict[name]
                download_pdf(name, url)
            else:
                print("[!] Selección no válida.")
                
        elif opcion == "3":
            print("¡Hasta luego!")
            break
        else:
            print("[!] Opción no válida.")

if __name__ == "__main__":
    main()
