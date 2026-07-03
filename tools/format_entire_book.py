import os
import re
from pathlib import Path

# Configuración de rutas
BASE_DIR = Path(__file__).resolve().parent.parent
RAW_DIR = BASE_DIR / "data" / "pdfs" / "extracted_raw" / "Platón. - Diálogos I [ocr] [G] [1981]"
OUTPUT_FILE = BASE_DIR / "data" / "academic_md" / "platon" / "dialogos_i.md"

SECTIONS = [
    {"name": "Introducción General", "key": "intro_general", "start": 5, "end": 134},
    {"name": "Apología de Sócrates", "key": "apologia", "start": 135, "end": 184},
    {"name": "Critón", "key": "criton", "start": 185, "end": 209},
    {"name": "Eutifrón", "key": "eutifron", "start": 210, "end": 240},
    {"name": "Ion", "key": "ion", "start": 241, "end": 268},
    {"name": "Lisis", "key": "lisis", "start": 269, "end": 314},
    {"name": "Cármides", "key": "carmides", "start": 315, "end": 366},
    {"name": "Hipias Menor", "key": "hipias_menor", "start": 367, "end": 394},
    {"name": "Hipias Mayor", "key": "hipias_mayor", "start": 395, "end": 440},
    {"name": "Laques", "key": "laques", "start": 441, "end": 484},
    {"name": "Protágoras", "key": "protagoras", "start": 485, "end": 591}
]

INTERLOCUTORES = [
    "SÓCRATES", "MELETO", "QUEREFONTE", "ANITO", "ÁNITO", "LICÓN",
    "CRITÓN", "EUTIFRÓN", "ION", "LISIS", "MENEXENO", "HIPOTALES", "CTESIPO",
    "CÁRMIDES", "CRITIAS", "COLA", "HIPIAS", "EUDICO",
    "LAQUES", "NICIAS", "LISÍMACO", "MELESIAS", "ARÍSTIDES",
    "PROTÁGORAS", "ALCIBÍADES", "CALIAS", "HIPÓNICO", "HIPÓCRATES"
]

def clean_page(text: str, page_num: int) -> tuple[str, list[str]]:
    lines = text.split("\n")
    cleaned_lines = []
    footnotes = []
    
    # Cabeceras comunes a eliminar
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
        r"^\d+\s+PROTÁGORAS$"
    ]
    
    in_footnotes = False
    # En Gredos las notas inician típicamente con número seguido de espacio
    footnote_start_pattern = r"^(\d+)\s+([A-ZÁÉÍÓÚa-záéíóú].*)$"
    
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
        if line_strip.isdigit() and 1 <= int(line_strip) <= 600:
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

def format_section_text(text: str, section_key: str) -> str:
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
    
    # 3. Formatear las marcas Stephanus
    full_text = re.sub(r'\b(\d{2,3}[a-e])\b', r'**[\1]**', full_text)
    full_text = re.sub(r'\s+([b-e])\s+', r' **[\1]** ', full_text)
    full_text = re.sub(r'\s+([b-e])$', r' **[\1]**', full_text, flags=re.MULTILINE)
    
    # 4. Reemplazar referencias numéricas de notas al pie para que apunten a la clave de la sección
    # Ej: 'acusación 2 ' -> 'acusación[^apologia_2] '
    full_text = re.sub(r'(\b[a-zA-Záéíóúñí]+)\s+(\d{1,2})\b', r'\1[^\2]', full_text)
    
    # Reemplazar [^X] por [^section_key_X] para evitar colisiones
    def rep_fn(match):
        num = match.group(1)
        return f"[^{section_key}_{num}]"
    full_text = re.sub(r'\[\^(\d+)\]', rep_fn, full_text)
    
    # Limpieza final de puntuación y espacios
    full_text = re.sub(r'\s+([,\.\?\!\:;])', r'\1', full_text)
    full_text = re.sub(r' {2,}', ' ', full_text)
    
    return full_text

def main():
    if not RAW_DIR.exists():
        print(f"[!] Error: El directorio {RAW_DIR} no existe.")
        return
        
    print(f"[*] Iniciando maquetación de Diálogos I en un solo archivo .md...")
    
    yaml_header = """---
title: "Diálogos I: Apología, Critón, Eutifrón, Ion, Lisis, Cármides, Hipias Menor, Hipias Mayor, Laques, Protágoras"
author: "Platón"
translator: "J. Calonge Ruiz, E. Lledó Iñigo, C. García Gual"
publisher: "Editorial Gredos"
original_year: 1981
citation_style: "Stephanus"
category: "Filosofía Antigua / Diálogos Platónicos"
tags: ["Platón", "Sócrates", "Diálogos", "Gredos", "Filosofía Griega"]
---

# Diálogos I

"""

    OUTPUT_FILE.parent.mkdir(parents=True, exist_ok=True)
    
    with open(OUTPUT_FILE, "w", encoding="utf-8") as out:
        out.write(yaml_header)
        
        for sec in SECTIONS:
            name = sec["name"]
            key = sec["key"]
            start = sec["start"]
            end = sec["end"]
            
            print(f"[*] Procesando sección: {name} (páginas {start} a {end})...")
            
            section_cleaned_text = []
            section_footnotes = []
            
            for p in range(start, end + 1):
                page_file = RAW_DIR / f"page_{p:03d}.txt"
                if page_file.exists():
                    with open(page_file, "r", encoding="utf-8") as f:
                        text, footnotes = clean_page(f.read(), p)
                        section_cleaned_text.append(text)
                        section_footnotes.extend(footnotes)
            
            combined_sec_text = "\n\n".join(section_cleaned_text)
            formatted_sec_text = format_section_text(combined_sec_text, key)
            
            # Escribir el contenido de la sección
            out.write(f"\n\n## {name}\n\n")
            out.write(formatted_sec_text)
            
            # Escribir las notas al pie específicas de la sección
            if section_footnotes:
                out.write(f"\n\n### Notas al Pie - {name}\n\n")
                consolidated_fns = []
                current_fn = ""
                for fn in section_footnotes:
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
                        note = re.sub(r'(\w+)-\s+(\w+)', r'\1\2', note)
                        # Reemplazar también saltos de línea internos en notas
                        note = note.replace("\n", " ").strip()
                        out.write(f"[^{key}_{num}]: {note}\n")
            
            out.write("\n\n---\n")
            
    print(f"[+] ¡Libro completo finalizado! Guardado en: {OUTPUT_FILE.absolute()}")

if __name__ == "__main__":
    main()
