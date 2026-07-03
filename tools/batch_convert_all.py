import os
import sys
from pathlib import Path
import time

# Importar el procesador universal
sys.path.append(str(Path(__file__).resolve().parent))
from format_any_book import process_book

# Configuración de libros a procesar
BASE_DIR = Path(__file__).resolve().parent.parent
PDF_DIR = BASE_DIR / "data" / "pdfs"
MD_DIR = BASE_DIR / "data" / "academic_md"

BOOKS = [
    {
        "pdf": "473.Jenofonte-RecuerdosDeSocrates-Economico-Banquete-ApologiaDeSocrates-gredos_text.pdf",
        "output": "jenofonte/obras_socraticas.md",
        "title": "Obras Socáticas (Recuerdos de Sócrates, Económico, Banquete, Apología) - Jenofonte"
    },
    {
        "pdf": "Platón. - Apología de Sócrates [1997].pdf",
        "output": "platon/apologia_1997.md",
        "title": "Apología de Sócrates (Edición 1997) - Platón"
    },
    {
        "pdf": "468.HistoriaDeLaFilosofiaGriegaIiVWKCGuthrie-Gredos.pdf",
        "output": "guthrie/historia_filosofia_iii.md",
        "title": "Historia de la Filosofía Griega III: Los Sofistas y Sócrates - W.K.C. Guthrie"
    },
    {
        "pdf": "WernerJeger-Paideia.LosIdealesDeLaCulturaGriegalibrosI-ii-iiiYIv_text.pdf",
        "output": "jaeger/paideia.md",
        "title": "Paideia: Los Ideales de la Cultura Griega - Werner Jaeger"
    },
    {
        "pdf": "509.VegettiMario-Platon-Gredos.pdf",
        "output": "vegetti/platon.md",
        "title": "Platón - Mario Vegetti"
    }
]

def main():
    print("==================================================")
    print("  Nihil Novi - Procesador Masivo de Libros a Markdown ")
    print("==================================================")
    print(f"Total de libros a maquetar: {len(BOOKS)}")
    
    start_time = time.time()
    
    for idx, b in enumerate(BOOKS, 1):
        pdf_path = PDF_DIR / b["pdf"]
        output_path = MD_DIR / b["output"]
        title = b["title"]
        
        print(f"\n[{idx}/{len(BOOKS)}] Iniciando: {title}")
        
        if output_path.exists() and output_path.stat().st_size > 10 * 1024:
            print(f"[+] Omitido (ya procesado): {b['output']}")
            continue
            
        if not pdf_path.exists():
            print(f"[!] Error: El PDF {pdf_path.name} no se encuentra en {PDF_DIR}")
            continue
            
        try:
            t0 = time.time()
            process_book(pdf_path, output_path, title)
            t1 = time.time()
            print(f"[+] Completado en {t1 - t0:.1f} segundos.")
        except Exception as e:
            print(f"[!] Ocurrió un error al procesar {b['pdf']}: {e}")
            
    print("\n==================================================")
    print(f"Maquetación masiva completada en {time.time() - start_time:.1f} segundos.")
    print(f"Los archivos resultantes están en: {MD_DIR.absolute()}")

if __name__ == "__main__":
    main()
