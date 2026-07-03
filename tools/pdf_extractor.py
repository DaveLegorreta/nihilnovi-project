# /// script
# requires-python = ">=3.9"
# dependencies = [
#     "pypdf>=4.0.0",
#     "pdfplumber>=0.10.0",
# ]
# ///

import os
import sys
import re
import argparse
from pathlib import Path
import pypdf
import pdfplumber

def clean_text(text: str) -> str:
    """
    Limpia el texto extraído aplicando reglas heurísticas:
    - Une palabras cortadas al final de la línea por guión (filo-\nsofía -> filosofía).
    - Remueve múltiples espacios consecutivos.
    - Normaliza los saltos de línea.
    """
    if not text:
        return ""
    
    # Unir palabras cortadas por guiones al final de línea
    text = re.sub(r'(\w+)-\s*\n\s*(\w+)', r'\1\2', text)
    
    # Remover múltiples espacios en blanco y conservar saltos de línea ordenados
    text = re.sub(r'[ \t]+', ' ', text)
    
    return text.strip()

def extract_digital_pdf(pdf_path: Path, output_dir: Path) -> int:
    """
    Extrae texto de un PDF digital seleccionable página por página.
    """
    print(f"[*] Analizando PDF digital: {pdf_path.name}")
    
    output_dir.mkdir(parents=True, exist_ok=True)
    pages_extracted = 0
    
    with pdfplumber.open(pdf_path) as pdf:
        total_pages = len(pdf.pages)
        print(f"[*] Total de páginas detectadas: {total_pages}")
        
        for i, page in enumerate(pdf.pages):
            page_num = i + 1
            text = page.extract_text()
            
            # Si no hay texto, podría ser una página escaneada (imagen)
            if not text or len(text.strip()) < 50:
                # Intentamos con pypdf como fallback
                reader = pypdf.PdfReader(pdf_path)
                text = reader.pages[i].extract_text()
            
            cleaned = clean_text(text)
            
            if cleaned:
                page_file = output_dir / f"page_{page_num:03d}.txt"
                with open(page_file, "w", encoding="utf-8") as f:
                    # Guardamos con marcador de página
                    f.write(f"--- [PÁGINA {page_num}] ---\n\n")
                    f.write(cleaned)
                    f.write("\n")
                pages_extracted += 1
            else:
                print(f"[!] Advertencia: No se extrajo texto de la página {page_num} (¿es una imagen/escaneado?)")
                # Escribimos un archivo de advertencia
                page_file = output_dir / f"page_{page_num:03d}.txt"
                with open(page_file, "w", encoding="utf-8") as f:
                    f.write(f"--- [PÁGINA {page_num} - REQUIERE OCR] ---\n\n")
                    f.write("[Esta página parece ser un escaneado o imagen. Se requiere OCR para procesarla.]\n")
    
    return pages_extracted

def main():
    parser = argparse.ArgumentParser(description="Extractor híbrido de PDFs para Nihil Novi Academia.")
    parser.add_argument("pdf_path", type=str, help="Ruta al archivo PDF de origen.")
    parser.add_argument("--output", "-o", type=str, default=None, help="Ruta del directorio de salida.")
    
    args = parser.parse_args()
    
    pdf_path = Path(args.pdf_path)
    if not pdf_path.exists():
        print(f"[Erro] El archivo PDF no existe en la ruta: {pdf_path}")
        sys.exit(1)
        
    pdf_name = pdf_path.stem
    if args.output:
        output_dir = Path(args.output)
    else:
        output_dir = pdf_path.parent / "extracted_raw" / pdf_name
        
    print(f"[*] Destino de extracción: {output_dir.absolute()}")
    
    try:
        pages = extract_digital_pdf(pdf_path, output_dir)
        print(f"[+] Proceso completado. Se extrajeron {pages} páginas con texto digital.")
        print(f"[+] Puedes encontrar los archivos crudos en: {output_dir}")
        print("\n[Instrucciones siguientes]:")
        print("1. Revisa las páginas extraídas.")
        print("2. Copia el contenido de la página que desees pulir.")
        print("3. Pégalo en el chat con Antigravity siguiendo el protocolo de revisión.")
    except Exception as e:
        print(f"[Erro] Ocurrió un fallo al procesar el PDF: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
