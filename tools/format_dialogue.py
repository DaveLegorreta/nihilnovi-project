import os
import re
from pathlib import Path

# Configuración de rutas
BASE_DIR = Path(__file__).resolve().parent.parent
RAW_DIR = BASE_DIR / "data" / "pdfs" / "extracted_raw" / "Platón. - Diálogos I [ocr] [G] [1981]"
OUTPUT_FILE = BASE_DIR / "data" / "academic_md" / "platon" / "apologia.md"

def clean_page(text: str, page_num: int) -> tuple[str, list[str]]:
    lines = text.split("\n")
    cleaned_lines = []
    footnotes = []
    
    # 1. Identificar cabeceras comunes
    # Ej: "--- [PÁGINA 146] ---", "APOLOGÍA DE SÓCRATES 147", "148 DIÁLOGOS"
    header_patterns = [
        r"^---\s*\[PÁGINA\s+\d+\]\s*---$",
        r"^APOLOGÍA DE SÓCRATES\s+\d+$",
        r"^\d+\s+DIÁLOGOS$"
    ]
    
    # 2. Separar notas al pie
    # En Gredos, las notas están al final de la página y suelen comenzar con un número
    in_footnotes = False
    footnote_start_pattern = r"^(\d+)\s+([A-ZÁÉÍÓÚa-záéíóú].*)$"
    
    for line in lines:
        line_strip = line.strip()
        if not line_strip:
            continue
            
        # Omitir cabeceras
        is_header = False
        for pattern in header_patterns:
            if re.match(pattern, line_strip, re.IGNORECASE):
                is_header = True
                break
        if is_header:
            continue
            
        # Detectar inicio de notas al pie
        match_fn = re.match(footnote_start_pattern, line_strip)
        if match_fn:
            in_footnotes = True
            
        if in_footnotes:
            footnotes.append(line_strip)
        else:
            cleaned_lines.append(line)
            
    cleaned_text = "\n".join(cleaned_lines)
    return cleaned_text, footnotes

def format_text(text: str) -> str:
    # 1. Unir palabras rotas por guion al final de línea (ej: experi-\nmentado -> experimentado)
    text = re.sub(r'(\w+)-\s*\n\s*(\w+)', r'\1\2', text)
    
    # 2. Limpiar saltos de línea intermedios en párrafos, pero mantener estructura de diálogos
    # En los diálogos platónicos, el interlocutor suele estar en mayúsculas (ej: SÓCRATES, MELETO)
    # y los párrafos suelen separarse por saltos de línea dobles.
    # Vamos a unir líneas sencillas que no terminen en punto o comillas.
    lines = text.split("\n")
    processed_lines = []
    current_para = []
    
    interlocutores = ["SÓCRATES", "MELETO", "QUEREFONTE", "ANITO", "ÁNITO", "LICÓN"]
    
    for line in lines:
        line_strip = line.strip()
        if not line_strip:
            continue
            
        # Si la línea es un nombre de interlocutor en mayúsculas
        if line_strip in interlocutores:
            if current_para:
                processed_lines.append(" ".join(current_para))
                current_para = []
            processed_lines.append(f"\n### {line_strip}\n")
            continue
            
        current_para.append(line_strip)
        
        # Si termina con puntuación fuerte, asumimos fin de párrafo
        if line_strip.endswith(('.', ':', '?', '!')) or len(line_strip) < 30:
            processed_lines.append(" ".join(current_para))
            current_para = []
            
    if current_para:
        processed_lines.append(" ".join(current_para))
        
    full_text = "\n\n".join(processed_lines)
    
    # 3. Formatear las marcas Stephanus
    # Identificar letras sueltas 'b', 'c', 'd', 'e' que estén al final o inicio de palabra
    # y convertirlas a marcas en negrita.
    # Ej: "palabras de mis acusadores. Cierta b" -> "palabras de mis acusadores. Cierta **[b]**"
    # Ej: "17a No sé..." -> "**[17a]** No sé..."
    
    # Primero las marcas compuestas como 17a, 18b, etc.
    full_text = re.sub(r'\b(\d{2,3}[a-e])\b', r'**[\1]**', full_text)
    
    # Luego las letras de sección solas
    # Evitar cambiar preposiciones de una sola letra (como 'a', 'y', 'o', 'e')
    full_text = re.sub(r'\s+([b-d])\s+', r' **[\1]** ', full_text)
    # Letra de sección al final de línea
    full_text = re.sub(r'\s+([b-e])$', r' **[\1]**', full_text, flags=re.MULTILINE)
    
    # Limpieza de espacios múltiples
    full_text = re.sub(r'[ \t]+', ' ', full_text)
    
    return full_text

def main():
    if not RAW_DIR.exists():
        print(f"[!] Error: El directorio {RAW_DIR} no existe.")
        return
        
    print(f"[*] Procesando Apología de Sócrates (páginas 146 a 184)...")
    
    full_cleaned_text = []
    all_footnotes = []
    
    for p in range(146, 185):
        page_file = RAW_DIR / f"page_{p:03d}.txt"
        if page_file.exists():
            with open(page_file, "r", encoding="utf-8") as f:
                text, footnotes = clean_page(f.read(), p)
                full_cleaned_text.append(text)
                all_footnotes.extend(footnotes)
                
    combined_text = "\n\n".join(full_cleaned_text)
    formatted_text = format_text(combined_text)
    
    # Estructura del frontmatter
    yaml_header = """---
title: "Apología de Sócrates"
author: "Platón"
translator: "Julio Calonge Ruiz"
source_work: "Diálogos I: Apología, Critón, Eutifrón, Ion, Lisis, Cármides, Hipias Menor, Hipias Mayor, Laques, Protágoras"
publisher: "Editorial Gredos"
original_year: 1981
citation_style: "Stephanus"
category: "Filosofía Antigua / Diálogos Platónicos"
tags: ["Platón", "Sócrates", "Apología", "Juicio", "Gredos"]
---

# Apología de Sócrates

"""
    
    # Escribir el archivo final
    OUTPUT_FILE.parent.mkdir(parents=True, exist_ok=True)
    with open(OUTPUT_FILE, "w", encoding="utf-8") as out:
        out.write(yaml_header)
        out.write(formatted_text)
        out.write("\n\n## Notas al Pie\n\n")
        
        # Formatear notas al pie como Markdown [^N]
        # Procesamos la lista para consolidar notas multilínea
        consolidated_fns = []
        current_fn = ""
        for fn in all_footnotes:
            if re.match(r'^\d+\s+', fn):
                if current_fn:
                    consolidated_fns.append(current_fn)
                current_fn = fn
            else:
                current_fn += " " + fn
        if current_fn:
            consolidated_fns.append(current_fn)
            
        for fn in consolidated_fns:
            match = re.match(r'^(\d+)\s+(.*)$', fn)
            if match:
                num, note = match.groups()
                # Limpiar texto de notas
                note = re.sub(r'(\w+)-\s+(\w+)', r'\1\2', note)
                out.write(f"[^{num}]: {note}\n")
                
    print(f"[+] Archivo unificado de la obra guardado con éxito en: {OUTPUT_FILE.absolute()}")

if __name__ == "__main__":
    main()
