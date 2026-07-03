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

INTERLOCUTORES = [
    "SÓCRATES", "MELETO", "QUEREFONTE", "ANITO", "ÁNITO", "LICÓN",
    "CRITÓN", "EUTIFRÓN", "ION", "LISIS", "MENEXENO", "HIPOTALES", "CTESIPO",
    "CÁRMIDES", "CRITIAS", "COLA", "HIPIAS", "EUDICO",
    "LAQUES", "NICIAS", "LISÍMACO", "MELESIAS", "ARÍSTIDES",
    "PROTÁGORAS", "ALCIBÍADES", "CALIAS", "HIPÓNICO", "HIPÓCRATES",
    "JENO", "JENOFONTE", "HERMÓGENES"
]

def clean_page_text(text: str, page_num: int) -> tuple[str, list[tuple[int, int, str]]]:
    """
    Limpia el texto de una página eliminando cabeceras y números físicos,
    y extrae las notas al pie de página.
    Retorna (texto_limpio, lista_de_notas) donde lista_de_notas es [(page_num, fn_num, note_text)]
    """
    if not text:
        return "", []
        
    lines = text.split("\n")
    cleaned_lines = []
    footnotes = []
    
    # Patrones para omitir cabeceras comunes y números físicos de página
    header_patterns = [
        r"^---\s*\[PÁGINA\s+\d+\]\s*---$",
        r"^APOLOGÍA DE SÓCRATES\s+\d+$",
        r"^\d+\s+DIÁLOGOS$",
        r"^CRITÓN\s+\d+$",
        r"^EUTIFRÓN\s+\d+$",
        r"^ION\s+\d+$",
        r"^LISIS\s+\d+$",
        r"^CÁRMIDES\s+\d+$",
        r"^HIPIAS MENOR\s+\d+$",
        r"^HIPIAS MAYOR\s+\d+$",
        r"^LAQUES\s+\d+$",
        r"^PROTÁGORAS\s+\d+$",
        r"^\d+\s+APOLOGÍA DE SÓCRATES$",
        r"^\d+\s+CRITÓN$",
        r"^\d+\s+EUTIFRÓN$",
        r"^\d+\s+ION$",
        r"^\d+\s+LISIS$",
        r"^\d+\s+CÁRMIDES$",
        r"^\d+\s+HIPIAS MENOR$",
        r"^\d+\s+HIPIAS MAYOR$",
        r"^\d+\s+LAQUES$",
        r"^\d+\s+PROTÁGORAS$",
        # Patrones para Jenofonte, Guthrie, Jaeger
        r"^RECUERDOS DE SÓCRATES\s+\d+$",
        r"^\d+\s+RECUERDOS DE SÓCRATES$",
        r"^HISTORIA DE LA FILOSOFÍA GRIEGA\s+\d+$",
        r"^\d+\s+HISTORIA DE LA FILOSOFÍA GRIEGA$",
        r"^PAIDEIA\s+\d+$",
        r"^\d+\s+PAIDEIA$"
    ]
    
    in_footnotes = False
    footnote_start_pattern = r"^(\d+)\s+([A-ZÁÉÍÓÚa-záéíóú].*)$"
    
    raw_footnotes = []
    for line in lines:
        line_strip = line.strip()
        if not line_strip:
            continue
            
        # Saltarse cabeceras físicas y nombres de capítulos repetitivos
        is_header = False
        for pattern in header_patterns:
            if re.match(pattern, line_strip, re.IGNORECASE):
                is_header = True
                break
        if is_header:
            continue
            
        # Omitir números de página física aislados
        if line_strip.isdigit() and 1 <= int(line_strip) <= 1200:
            continue
            
        # Detectar inicio de notas al pie
        match_fn = re.match(footnote_start_pattern, line_strip)
        if match_fn:
            in_footnotes = True
            
        if in_footnotes:
            raw_footnotes.append(line_strip)
        else:
            cleaned_lines.append(line)
            
    # Consolidar notas al pie multilínea de la página
    consolidated_fns = []
    current_fn = ""
    for fn in raw_footnotes:
        if re.match(r'^\d+\s+', fn):
            if current_fn:
                consolidated_fns.append(current_fn)
            current_fn = fn
        else:
            current_fn += " " + fn
    if current_fn:
        consolidated_fns.append(current_fn)
        
    # Estructurar notas al pie con la clave de página para evitar colisiones
    formatted_footnotes = []
    for fn in consolidated_fns:
        match = re.match(r'^(\d+)\s+(.*)$', fn)
        if match:
            num = int(match.group(1))
            note_text = match.group(2).replace("\n", " ").strip()
            # Limpiar guiones en notas
            note_text = re.sub(r'(\w+)-\s+(\w+)', r'\1\2', note_text)
            formatted_footnotes.append((page_num, num, note_text))
            
    cleaned_text = "\n".join(cleaned_lines)
    return cleaned_text, formatted_footnotes

def format_clean_text(text: str, page_num: int) -> str:
    """
    Une guiones al final de línea, formatea Stephanus y enlaza las notas al pie de forma unívoca.
    """
    # 1. Unir palabras rotas por guion al final de línea
    text = re.sub(r'(\w+)-\s*\n\s*(\w+)', r'\1\2', text)
    
    # 2. Unir líneas sencillas que no terminan con puntuación fuerte
    lines = text.split("\n")
    processed_lines = []
    current_para = []
    
    for line in lines:
        line_strip = line.strip()
        if not line_strip:
            continue
            
        # Si la línea es un nombre de interlocutor en mayúsculas
        if line_strip in INTERLOCUTORES:
            if current_para:
                processed_lines.append(" ".join(current_para))
                current_para = []
            processed_lines.append(f"\n### {line_strip}\n")
            continue
            
        current_para.append(line_strip)
        
        # Puntuación fuerte indica fin de párrafo
        if line_strip.endswith(('.', ':', '?', '!')) or len(line_strip) < 30:
            processed_lines.append(" ".join(current_para))
            current_para = []
            
    if current_para:
        processed_lines.append(" ".join(current_para))
        
    full_text = "\n\n".join(processed_lines)
    
    # 3. Formatear las marcas Stephanus (ej: 17a -> **[17a]**, b -> **[b]**)
    full_text = re.sub(r'\b(\d{2,3}[a-e])\b', r'**[\1]**', full_text)
    full_text = re.sub(r'\s+([b-e])\s+', r' **[\1]** ', full_text)
    full_text = re.sub(r'\s+([b-e])$', r' **[\1]**', full_text, flags=re.MULTILINE)
    
    # 4. Enlazar notas al pie unívocamente usando el número de página como prefijo
    # Ejemplo: 'palabra 3' -> 'palabra[^p146_3]'
    full_text = re.sub(r'(\b[a-zA-Záéíóúñí]+)\s+(\d{1,2})\b', rf'\1[^p{page_num}_\2]', full_text)
    
    # Limpieza final de puntuación y espacios
    full_text = re.sub(r'\s+([,\.\?\!\:;])', r'\1', full_text)
    full_text = re.sub(r' {2,}', ' ', full_text)
    
    return full_text

def process_book(pdf_path: Path, output_md_path: Path, title: str):
    """
    Lee un PDF, extrae su texto, limpia, formatea y guarda el resultado como Markdown.
    """
    print(f"[*] Iniciando extracción de: {pdf_path.name}")
    
    # Estructura del frontmatter
    yaml_header = f"""---
title: "{title}"
source_file: "{pdf_path.name}"
citation_style: "APA 7 / Stephanus (si aplica)"
category: "Filosofía y Clásicos"
---

# {title}

"""
    
    all_pages_text = []
    all_book_footnotes = []
    
    with pdfplumber.open(pdf_path) as pdf:
        total_pages = len(pdf.pages)
        print(f"[*] Total de páginas detectadas: {total_pages}")
        
        # Leemos el texto de cada página
        for i, page in enumerate(pdf.pages):
            page_num = i + 1
            if page_num % 50 == 0:
                print(f"    [+] Procesadas {page_num}/{total_pages} páginas...")
                
            text = page.extract_text()
            
            # Fallback a pypdf si pdfplumber retorna vacío
            if not text or len(text.strip()) < 50:
                try:
                    reader = pypdf.PdfReader(pdf_path)
                    text = reader.pages[i].extract_text()
                except:
                    text = ""
            
            if text:
                cleaned_text, footnotes = clean_page_text(text, page_num)
                formatted_page_text = format_clean_text(cleaned_text, page_num)
                
                # Agregamos marcador de página física al inicio del bloque de texto
                page_block = f"\n\n<!-- PÁGINA FÍSICA PDF: {page_num} -->\n\n" + formatted_page_text
                all_pages_text.append(page_block)
                all_book_footnotes.extend(footnotes)
    
    # Escribir el archivo final unificado
    output_md_path.parent.mkdir(parents=True, exist_ok=True)
    with open(output_md_path, "w", encoding="utf-8") as out:
        out.write(yaml_header)
        out.write("\n\n".join(all_pages_text))
        
        # Escribir todas las notas al pie consolidadas al final del libro
        if all_book_footnotes:
            out.write("\n\n## Notas al Pie\n\n")
            for page_num, num, note_text in all_book_footnotes:
                out.write(f"[^p{page_num}_{num}]: {note_text}\n")
                
    print(f"[+] ¡Libro maquetado con éxito! Guardado en: {output_md_path.absolute()}")

def main():
    parser = argparse.ArgumentParser(description="Maquetador universal de PDFs a Markdown para Nihil Novi.")
    parser.add_argument("pdf_path", type=str, help="Ruta al archivo PDF original.")
    parser.add_argument("output_md", type=str, help="Ruta al archivo Markdown de salida.")
    parser.add_argument("--title", "-t", type=str, default=None, help="Título del libro para el frontmatter.")
    
    args = parser.parse_args()
    
    pdf_path = Path(args.pdf_path)
    output_md = Path(args.output_md)
    title = args.title if args.title else pdf_path.stem
    
    if not pdf_path.exists():
        print(f"[Erro] El archivo PDF no existe en: {pdf_path}")
        sys.exit(1)
        
    try:
        process_book(pdf_path, output_md, title)
    except Exception as e:
        print(f"[Erro] Ocurrió un fallo al maquetar el libro: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
