#!/usr/bin/env python3
"""
translate_article.py — Nihil Novi
Prepara un artículo español para traducción crítica con LLM.

Uso:
    python tools/translate_article.py articulos_publicacion/FIL_02_A.md en
    python tools/translate_article.py articulos_publicacion/FIL_02_A.md it

Salida:
    - Genera archivo en articulos_publicacion/en/ o articulos_publicacion/it/
    - Imprime el prompt completo listo para copiar en Claude/GPT
"""

import sys
import re
from pathlib import Path

# Configuración
BASE_DIR = Path('c:/Users/david/OneDrive/Documentos/Skills/Projecto MKT')
PROMPT_FILE = BASE_DIR / 'templates' / 'translation_prompt.md'
ARTICLES_DIR = BASE_DIR / 'articulos_publicacion'

LANG_CONFIG = {
    'en': {
        'name': 'English (en_US)',
        'dir': 'en',
        'prefix': 'PHI',
        'module_prefix': 'Module_I',
    },
    'it': {
        'name': 'Italiano (it_IT)',
        'dir': 'it',
        'prefix': 'FIL',
        'module_prefix': 'Modulo_I',
    },
}


def read_article(path: Path) -> str:
    """Lee el contenido del artículo."""
    with open(path, 'r', encoding='utf-8') as f:
        return f.read()


def read_prompt_template() -> str:
    """Lee el prompt maestro."""
    with open(PROMPT_FILE, 'r', encoding='utf-8') as f:
        return f.read()


def generate_full_prompt(article_content: str, lang_code: str) -> str:
    """Genera el prompt completo con el artículo insertado."""
    prompt_template = read_prompt_template()
    lang_name = LANG_CONFIG[lang_code]['name']
    
    # Reemplazar placeholder de idioma
    prompt = prompt_template.replace('{idioma_destino}', lang_name)
    prompt = prompt.replace('{English (en_US) / Italiano (it_IT)}', lang_name)
    
    # Insertar artículo
    full_prompt = prompt.replace(
        '[Pegar aquí el contenido del archivo .md en español]',
        article_content
    )
    
    return full_prompt


def generate_output_filename(input_path: Path, lang_code: str) -> Path:
    """Genera la ruta de salida para la traducción."""
    lang_dir = LANG_CONFIG[lang_code]['dir']
    
    # Crear directorio de idioma si no existe
    output_base = ARTICLES_DIR / lang_dir
    output_base.mkdir(parents=True, exist_ok=True)
    
    # Determinar subdirectorio (Modulo_I o raíz)
    relative = input_path.relative_to(ARTICLES_DIR)
    if relative.parts[0] == 'Modulo_I_Mito_al_Logos':
        output_dir = output_base / f"{LANG_CONFIG[lang_code]['module_prefix']}_Myth_to_Logos"
    else:
        output_dir = output_base
    
    output_dir.mkdir(parents=True, exist_ok=True)
    
    # Generar nombre de archivo
    stem = input_path.stem
    # Reemplazar prefijo si es necesario
    if stem.startswith('FIL_'):
        new_stem = stem.replace('FIL_', LANG_CONFIG[lang_code]['prefix'] + '_', 1)
    elif stem.startswith('ECO_'):
        new_stem = stem.replace('ECO_', 'ECO_', 1)  # Mantener ECO
    else:
        new_stem = stem
    
    return output_dir / f"{new_stem}.md"


def print_usage():
    print("Uso: python tools/translate_article.py <ruta_articulo> <idioma>")
    print("")
    print("Idiomas disponibles:")
    print("  en  - English (en_US)")
    print("  it  - Italiano (it_IT)")
    print("")
    print("Ejemplo:")
    print("  python tools/translate_article.py articulos_publicacion/FIL_02_A.md en")
    print("  python tools/translate_article.py articulos_publicacion/Modulo_I_Mito_al_Logos/FIL_01_A.md it")


def main():
    if len(sys.argv) < 3:
        print_usage()
        sys.exit(1)
    
    article_path = Path(sys.argv[1])
    lang_code = sys.argv[2].lower()
    
    if lang_code not in LANG_CONFIG:
        print(f"Error: idioma '{lang_code}' no soportado.")
        print_usage()
        sys.exit(1)
    
    # Verificar que el artículo existe
    if not article_path.exists():
        # Intentar ruta relativa al proyecto
        article_path = BASE_DIR / article_path
        if not article_path.exists():
            print(f"Error: no se encontró {article_path}")
            sys.exit(1)
    else:
        # Si existe pero es relativa, convertir a absoluta
        article_path = article_path.resolve()
    
    # Leer artículo
    print(f"Leyendo: {article_path}")
    article_content = read_article(article_path)
    
    # Generar prompt
    print(f"Generando prompt para traducción a {LANG_CONFIG[lang_code]['name']}...")
    full_prompt = generate_full_prompt(article_content, lang_code)
    
    # Guardar prompt en archivo temporal
    output_path = generate_output_filename(article_path, lang_code)
    prompt_file = output_path.with_suffix('.prompt.md')
    
    with open(prompt_file, 'w', encoding='utf-8') as f:
        f.write(full_prompt)
    
    print(f"")
    print("=" * 70)
    print("PROMPT GENERADO")
    print("=" * 70)
    print(f"")
    print(f"Archivo guardado: {prompt_file}")
    print(f"")
    print("INSTRUCCIONES:")
    print("1. Abre el archivo .prompt.md generado")
    print("2. Copia TODO el contenido")
    print("3. Pégalo en Claude (claude.ai) o ChatGPT")
    print("4. La IA traducirá el artículo completo")
    print("5. Copia la respuesta y guárdala como:")
    print(f"   {output_path}")
    print("")
    print("=" * 70)
    print(f"Longitud del prompt: {len(full_prompt):,} caracteres")
    print(f"Estimación de tokens: ~{len(full_prompt) // 4:,} tokens")
    print("=" * 70)


if __name__ == '__main__':
    main()
