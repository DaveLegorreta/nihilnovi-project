#!/usr/bin/env python3
import os
import re
import html
import json
from pathlib import Path
from datetime import datetime, timedelta
import hashlib

BASE_DIR = Path(__file__).resolve().parent.parent
ARTICLES_DIR = BASE_DIR / "articulos_publicacion"
OUTPUT_FILE = BASE_DIR / "data" / "nihilnovi_articles_import.xml"

DISCIPLINE_MAP = {
    "FIL": ("filosofia", "Filosofía"),
    "ECO": ("economia", "Economía"),
    "MAT": ("matematicas", "Matemáticas"),
    "HIS": ("historia", "Historia"),
    "CIE": ("ciencia", "Ciencia"),
}

def slugify(text: str) -> str:
    accents = {
        'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'ü': 'u', 'ñ': 'n',
        'Á': 'a', 'É': 'e', 'Í': 'i', 'Ó': 'o', 'Ú': 'u', 'Ü': 'u', 'Ñ': 'n'
    }
    for k, v in accents.items():
        text = text.replace(k, v)
    text = text.lower()
    text = re.sub(r"[^\w\s-]", "", text)
    text = re.sub(r"[\s-]+", "-", text).strip("-")
    return text

def md_to_html(text: str) -> str:
    # Normalizar retornos de carro (Windows \r\n -> \n)
    text = text.replace("\r\n", "\n").replace("\r", "\n")

    # Convertir reglas horizontales ---
    text = re.sub(r"^---$", r'<hr class="section-divider" />', text, flags=re.MULTILINE)

    # 1. Extraer temporalmente bloques de HTML/style/script para que no se separen por \n\n
    html_blocks = []
    def placeholder_replacer(match):
        html_blocks.append(match.group(0))
        return f"\n\n__HTML_BLOCK_{len(html_blocks)-1}__\n\n"

    # Capturar bloques de Gutenberg wp:html
    text = re.sub(r"<!-- wp:html -->.*?<!-- /wp:html -->", placeholder_replacer, text, flags=re.DOTALL)
    # También capturar style o script sueltos si los hubiera
    text = re.sub(r"<style>.*?</style>", placeholder_replacer, text, flags=re.DOTALL)
    text = re.sub(r"<script>.*?</script>", placeholder_replacer, text, flags=re.DOTALL)

    text = re.sub(r"^#### (.*?)$", r"<h4>\1</h4>", text, flags=re.MULTILINE)
    text = re.sub(r"^### (.*?)$", r"<h3>\1</h3>", text, flags=re.MULTILINE)
    text = re.sub(r"^## (.*?)$", r"<h2>\1</h2>", text, flags=re.MULTILINE)
    text = re.sub(r"^# (.*?)$", r"<h1>\1</h1>", text, flags=re.MULTILINE)
    text = re.sub(r"\*\*\*(.*?)\*\*\*", r"<strong><em>\1</em></strong>", text)
    text = re.sub(r"\*\*(.*?)\*\*", r"<strong>\1</strong>", text)
    text = re.sub(r"\*(.*?)\*", r"<em>\1</em>", text)
    
    # Convertir enlaces Markdown [texto](url) a HTML <a href="url">texto</a>
    text = re.sub(r"\[([^\]]+)\]\(([^)]+)\)", r'<a href="\2">\1</a>', text)
    
    lines = text.split("\n")
    in_quote = False
    out_lines = []
    for line in lines:
        if line.strip().startswith("> "):
            content = line.strip()[2:]
            if not in_quote:
                out_lines.append("<blockquote>")
                in_quote = True
            out_lines.append(content + "<br/>")
        else:
            if in_quote:
                out_lines.append("</blockquote>")
                in_quote = False
            out_lines.append(line)
    if in_quote:
        out_lines.append("</blockquote>")
    text = "\n".join(out_lines)
    
    text = re.sub(r"^\*   (.*?)$", r"<li>\1</li>", text, flags=re.MULTILINE)
    text = re.sub(r"(<li>.*?</li>\n?)+", lambda m: f"<ul>{m.group(0)}</ul>", text, flags=re.DOTALL)
    
    paragraphs = text.split("\n\n")
    wrapped = []
    for p in paragraphs:
        p = p.strip()
        if not p:
            continue
        if p.startswith("__HTML_BLOCK_") and p.endswith("__"):
            wrapped.append(p)
        elif p.startswith("<") and not p.startswith("<blockquote>"):
            wrapped.append(p)
        else:
            wrapped.append(f"<p>{p}</p>")
    text = "\n\n".join(wrapped)

    # Restaurar bloques de HTML extraídos
    for idx, block in enumerate(html_blocks):
        text = text.replace(f"__HTML_BLOCK_{idx}__", block)
        
    return text

def parse_yaml_frontmatter(content: str):
    """Parses basic YAML frontmatter and removes it from the content."""
    meta = {}
    if not content.startswith("---"):
        return meta, content
        
    parts = content.split("---", 2)
    if len(parts) < 3:
        return meta, content
        
    yaml_text = parts[1]
    remaining_content = parts[2].strip()
    
    current_list_key = None
    list_items = []
    
    for line in yaml_text.splitlines():
        line = line.strip()
        if not line: continue
        
        # Check if it's a list item
        if line.startswith("- ") and current_list_key:
            val = line[2:].strip().strip('"').strip("'")
            list_items.append(val)
            meta[current_list_key] = "\n".join(list_items)
            continue
            
        # Or a dictionary key for Yoast SEO
        if current_list_key == 'yoast_seo' and ":" in line and not line.startswith("-"):
            sub_k, sub_v = line.split(":", 1)
            sub_k = sub_k.strip()
            sub_v = sub_v.strip().strip('"').strip("'")
            meta[f"yoast_seo_{sub_k}"] = sub_v
            continue
            
        # Match standard key: value
        if ":" in line:
            k, v = line.split(":", 1)
            k = k.strip()
            v = v.strip()
            
            if not v:
                current_list_key = k
                list_items = []
            else:
                v = v.strip('"').strip("'")
                meta[k] = v
                current_list_key = None
                
    return meta, remaining_content

def find_plan_path():
    base_brain = Path('C:/Users/david/.gemini/antigravity/brain')
    if base_brain.exists():
        for p in base_brain.rglob('editorial_plan_filosofia_detallado.md'):
            return p
    return None

def load_plan_metadata():
    plan_path = find_plan_path()
    mapping = {}
    if plan_path and plan_path.exists():
        content = plan_path.read_text(encoding='utf-8').replace('\r', '')
        pattern = r'\*\s+\*\*([A-Z0-9-]+):\s*(.*?)\*\*\s*(.*)'
        for code, title, subtitle in re.findall(pattern, content):
            title = title.rstrip(':').strip()
            title = re.sub(r'\*(.*?)\*', r'\1', title)
            subtitle = subtitle.strip()
            subtitle = re.sub(r'\*(.*?)\*', r'\1', subtitle)
            mapping[code] = {
                'title': title,
                'subtitle': subtitle
            }
    return mapping

CUSTOM_MAPPINGS = {
    'FIL-02-A': {
        'title': 'Sócrates: El Giro Antropológico, la Mayéutica y el Intelectualismo Moral',
        'subtitle': 'La reorientación ética de la filosofía, el método dialéctico y la definición del bien.'
    },
    'FIL-04-A': {
        'title': 'Aristóteles: La Política (Introducción)',
        'subtitle': 'Génesis, estructura general y mapeo inductivo de la obra política aristotélica.'
    },
    'FIL-04-01': {
        'title': 'Aristóteles - Política Libro I: La Comunidad Natural y la Oikonomía',
        'subtitle': 'Definición de pólis, el zoon politikon y la crematística doméstica.'
    },
    'FIL-04-02': {
        'title': 'Aristóteles - Política Libro II: Examen Crítico de Constituciones',
        'subtitle': 'Estudio comparativo y refutación de la República de Platón y otros regímenes reales.'
    },
    'FIL-04-03': {
        'title': 'Aristóteles - Política Libro III: El Ciudadano y los Regímenes Políticos',
        'subtitle': 'Estructura ontológica del Estado y clasificación de las seis formas de gobierno.'
    },
    'FIL-04-04': {
        'title': 'Aristóteles - Política Libro IV: Tipología Real de las Politeiai',
        'subtitle': 'Física social de la democracia, la oligarquía y el término medio de la politeia mixta.'
    },
    'FIL-04-05': {
        'title': 'Aristóteles - Política Libro V: Las Revoluciones y la Estabilidad',
        'subtitle': 'Manual de patología cívica: causas de las sediciones y recetas para preservar el poder.'
    },
    'FIL-04-06': {
        'title': 'Aristóteles - Política Libro VI: El Diseño de las Instituciones',
        'subtitle': 'Organización deliberativa, judicial y ejecutiva de las democracias y oligarquías reales.'
    },
    'FIL-04-07': {
        'title': 'Aristóteles - Política Libro VII: El Estado Ideal y la Geografía Humana',
        'subtitle': 'Bosquejo del mejor régimen cívico posible y los fundamentos de la educación pública.'
    },
    'FIL-04-08': {
        'title': 'Aristóteles - Política Libro VIII: La Pedagogía y la Música en la Pólis',
        'subtitle': 'Culminación teleológica del Estado a través del cultivo musical de los jóvenes.'
    }
}

PLAN_MAP = load_plan_metadata()
ALL_MAPPINGS = {**PLAN_MAP, **CUSTOM_MAPPINGS}

SHORT_SEO_TITLES = {
    # Módulo I
    'FIL-01-A': 'El Paso del Mito al Logos - Origen de la Razón | Nihil Novi',
    'FIL-01-B': 'Tales de Mileto - El Agua y el Hilozoísmo | Nihil Novi',
    'FIL-01-C': 'Anaximandro - El Apeiron y lo Infinito | Nihil Novi',
    'FIL-01-D': 'Anaxímenes - El Aire y la Condensación | Nihil Novi',
    'FIL-01-E': 'Pitágoras - La Ontología del Número | Nihil Novi',
    'FIL-01-F': 'Heráclito - El Devenir y el Logos | Nihil Novi',
    'FIL-01-G': 'Parménides - La Ontología del Ser | Nihil Novi',
    'FIL-01-H': 'Zenón - Las Paradojas del Movimiento | Nihil Novi',
    'FIL-01-I': 'Empédocles - Las Cuatro Raíces y el Cosmos | Nihil Novi',
    'FIL-01-J': 'Anaxágoras - Las Homeomerías y el Nous | Nihil Novi',
    'FIL-01-K': 'Demócrito y Leucipo - El Atomismo Primitivo | Nihil Novi',
    'FIL-01-L': 'Los Sofistas - Protágoras, Gorgias y la Retórica | Nihil Novi',
    
    # Módulo II
    'FIL-02-A': 'Sócrates - El Método Dialéctico y la Ética | Nihil Novi',
    
    # Módulo III (Diálogos de Platón)
    'FIL-03-01': 'Platón - Apología de Sócrates: Defensa y Muerte | Nihil Novi',
    'FIL-03-02': 'Platón - Critón: El Deber y las Leyes | Nihil Novi',
    'FIL-03-03': 'Platón - Eutifrón: La Paradoja de la Piedad | Nihil Novi',
    'FIL-03-04': 'Platón - Cármides: La Templanza y el Autoconocimiento | Nihil Novi',
    'FIL-03-05': 'Platón - Laques: El Valor y la Prudencia | Nihil Novi',
    'FIL-03-06': 'Platón - Lisis: La Amistad y el Amor | Nihil Novi',
    'FIL-03-07': 'Platón - Protágoras: ¿Se puede enseñar la Virtud? | Nihil Novi',
    'FIL-03-08': 'Platón - Ion: La Inspiración Poética Divina | Nihil Novi',
    'FIL-03-09': 'Platón - Hipias Mayor: La Búsqueda de lo Bello | Nihil Novi',
    'FIL-03-10': 'Platón - Hipias Menor: La Paradoja de la Mentira | Nihil Novi',
    'FIL-03-14': 'Platón - Eutidemo: La Crítica a la Erística | Nihil Novi',
    'FIL-03-15': 'Platón - Menexeno: La Oración Fúnebre Patriótica | Nihil Novi',
    'FIL-03-16': 'Platón - Fedón: La Inmortalidad del Alma | Nihil Novi',
    'FIL-03-17': 'Platón - Banquete: La Dialéctica del Amor | Nihil Novi',
    
    # Módulo IV (Aristóteles)
    'FIL-04-A': 'Aristóteles - Introducción a la Política | Nihil Novi',
    'FIL-04-01': 'Aristóteles - Política I: Comunidad y Oikonomía | Nihil Novi',
    'FIL-04-02': 'Aristóteles - Política II: Examen de Constituciones | Nihil Novi',
    'FIL-04-03': 'Aristóteles - Política III: El Ciudadano y el Estado | Nihil Novi',
    'FIL-04-04': 'Aristóteles - Política IV: Variedades de Regímenes | Nihil Novi',
    'FIL-04-05': 'Aristóteles - Política V: Sedición y Cambio Político | Nihil Novi',
    'FIL-04-06': 'Aristóteles - Política VI: Estabilidad de Regímenes | Nihil Novi',
    'FIL-04-07': 'Aristóteles - Política VII: El Estado Ideal | Nihil Novi',
    'FIL-04-08': 'Aristóteles - Política VIII: Educación y Música | Nihil Novi',
    
    # Economía
    'ECO-01-A': 'Economía Antigua - Grecia, Roma y Escolástica | Nihil Novi',
    'ECO-02-A': 'Mercantilismo - Acumulación y Comercio Exterior | Nihil Novi',
    'ECO-03-A': 'Adam Smith - La Riqueza de las Naciones | Nihil Novi',
    'ECO-04-A': 'Karl Marx - El Capital y la Plusvalía | Nihil Novi',
    'ECO-05-A': 'Marginalismo - La Revolución de la Utilidad | Nihil Novi',
    'ECO-06-A': 'Keynes - La Demanda Agregada y el Empleo | Nihil Novi',
    'ECO-07-A': 'Escuela Austríaca y Monetarismo - Teoría del Ciclo | Nihil Novi',
    'ECO-08-A': 'Economía Conductual - Racionalidad Limitada | Nihil Novi',
}

def parse_article(path: Path) -> dict:
    content = path.read_text(encoding="utf-8-sig")
    meta, raw_content = parse_yaml_frontmatter(content)
    
    parts = path.stem.split("_")
    prefix = parts[0].upper() if parts else "FIL"
    discipline_slug, discipline_name = DISCIPLINE_MAP.get(prefix, ("filosofia", "Filosofía"))

    stem_code = path.stem.upper().replace('_', '-')
    lesson_code = meta.get('lesson_code') or stem_code

    # 1. Resolver título base y subtítulo
    if 'title' in meta:
        base_title = meta['title'].strip('"').strip("'").strip()
    elif stem_code in ALL_MAPPINGS:
        base_title = ALL_MAPPINGS[stem_code]['title']
    else:
        base_title = path.stem.replace("_", " ")

    if 'post_subtitle' in meta:
        post_subtitle = meta['post_subtitle'].strip('"').strip("'").strip()
    elif stem_code in ALL_MAPPINGS:
        post_subtitle = ALL_MAPPINGS[stem_code]['subtitle']
    else:
        post_subtitle = ''

    # Guardar en meta para el mapeador JSON
    meta['lesson_code'] = lesson_code
    meta['post_subtitle'] = post_subtitle

    # Resolver número de artículo
    if 'article_num' not in meta:
        # Intentar extraer del final de la lección
        num_match = re.search(r'(\d+)$', stem_code)
        if num_match:
            meta['article_num'] = num_match.group(1)
        else:
            # Letras A, B, C...
            let_match = re.search(r'-([A-Z])$', stem_code)
            if let_match:
                meta['article_num'] = str(ord(let_match.group(1)) - ord('A') + 1)
            else:
                meta['article_num'] = '01'

    # Resolver tiempo de lectura si no está en meta
    if 'read_time' not in meta:
        word_count = len(raw_content.split())
        meta['read_time'] = f"{max(1, round(word_count / 200))} min"

    # 2. Extraer "Lo esencial" y Bibliografía de forma robusta
    if not meta.get('lesson_essentials'):
        ess_list = []
        for line in raw_content.splitlines():
            clean_line = line.strip()
            if (clean_line.startswith('*') or clean_line.startswith('-')) and '**' in clean_line:
                marker_removed = re.sub(r'^[*-]\s+\*\*', '', clean_line)
                if '**' in marker_removed:
                    sub_parts = marker_removed.split('**', 1)
                    t_part = sub_parts[0].strip()
                    rest = sub_parts[1].strip()
                    if t_part.endswith(':'):
                        t_part = t_part[:-1].strip()
                        tx_part = rest
                        ess_list.append(f"{t_part}: {tx_part}")
                    elif rest.startswith(':'):
                        tx_part = rest[1:].strip()
                        ess_list.append(f"{t_part}: {tx_part}")
        if ess_list:
            meta['lesson_essentials'] = "\n".join(ess_list[:4])

    if not meta.get('bibliography'):
        bib_match = re.search(r'<section class=["\']bibliography["\'][^>]*>.*?(<ul[^>]*>.*?</ul>)', raw_content, re.DOTALL)
        if bib_match:
            li_items = re.findall(r'<li[^>]*>(.*?)</li>', bib_match.group(0), re.DOTALL)
            bib_list = []
            for it in li_items:
                clean = re.sub(r'<[^>]+>', '', it).strip()
                clean = re.sub(r'\s+', ' ', clean)
                clean = re.sub(r'\[Ver en Amazon\]', '', clean).strip()
                bib_list.append(clean)
            if bib_list:
                meta['bibliography'] = "\n".join(bib_list)

    # 4. Resolver Yoast SEO y Título Corto
    # seo_title: relacionado al autor/tema, sin el código de la lección
    if lesson_code in SHORT_SEO_TITLES:
        seo_title_val = SHORT_SEO_TITLES[lesson_code]
        title_content = seo_title_val.split(' | ')[0]
    else:
        # Normalizar separadores en el título base
        if ':' in base_title:
            title_parts = base_title.split(':', 1)
            personaje = title_parts[0].strip()
            sub = title_parts[1].strip()
            title_content = f"{personaje} - {sub}"
        elif ' - ' in base_title:
            title_content = base_title
        else:
            if post_subtitle:
                title_content = f"{base_title} - {post_subtitle}"
            else:
                title_content = base_title
                
        seo_title_val = meta.get('seo_title') or meta.get('yoast_seo_seo_title')
        if not seo_title_val:
            seo_title_val = f"{title_content} | Nihil Novi"
        else:
            # Asegurar que no lleva el código al inicio
            seo_title_val = re.sub(r'^[A-Z0-9-]+:\s*', '', seo_title_val)

    # 3. Formatear título final: "Código: Personaje o Tema - Subtítulo"
    # Eliminar formato markdown del título final
    title_content = re.sub(r'\*(.*?)\*', r'\1', title_content)
    title = f"{lesson_code}: {title_content}"

    # focus_keyphrase: frase de enfoque del tema o autor
    focus_kw = meta.get('focus_keyphrase') or meta.get('yoast_seo_focus_keyphrase')
    if not focus_kw:
        # Usar la primera parte del título content (generalmente el autor/tema)
        if ' - ' in title_content:
            focus_kw = title_content.split(' - ', 1)[0].strip()
        else:
            focus_kw = title_content

    # meta_description
    meta_desc = meta.get('meta_description') or meta.get('yoast_seo_meta_description')
    if not meta_desc:
        meta_desc = f"Análisis profundo de {base_title} en Nihil Novi. {post_subtitle}" if post_subtitle else f"Análisis y estudio detallado de {base_title} en Nihil Novi."
    
    # Asegurar límites de longitud en meta_description (recomendado ~155 caracteres)
    meta_desc = meta_desc[:155].strip()

    slug_val = meta.get('slug') or meta.get('yoast_seo_slug') or slugify(base_title) or slugify(path.stem)

    # Guardar los valores Yoast resueltos de vuelta al meta dict para el meta_mapping
    meta['_resolved_seo_title'] = seo_title_val
    meta['_resolved_focuskw'] = focus_kw
    meta['_resolved_metadesc'] = meta_desc

    is_lesson = "Modulo_I_Mito_al_Logos" in str(path)
    categories = [(discipline_slug, discipline_name)]
    if is_lesson:
        categories.append(("lecciones", "Lecciones"))

    html_content = md_to_html(raw_content)

    return {
        "title": title,
        "slug": slug_val,
        "content": html_content,
        "categories": categories,
        "is_lesson": is_lesson,
        "filename": path.name,
        "meta": meta
    }

def build_wxr(articles: list[dict]) -> str:
    pub_date = datetime.utcnow().strftime("%a, %d %b %Y %H:%M:%S +0000")
    site_url = "https://nihilnovi.xyz"

    categories_xml = []
    seen_cats = set()
    for art in articles:
        for slug, name in art["categories"]:
            if slug not in seen_cats:
                seen_cats.add(slug)
                categories_xml.append(
                    f'  <wp:category><wp:category_nicename>{slug}</wp:category_nicename>'
                    f'<wp:cat_name><![CDATA[{name}]]></wp:cat_name></wp:category>'
                )

    items_xml = []
    base_date = datetime(2023, 1, 1, 12, 0, 0)
    for idx, art in enumerate(articles):
        post_date = (base_date + timedelta(weeks=idx)).strftime("%Y-%m-%d %H:%M:%S")
        post_date_gmt = post_date
        stable_id = int(hashlib.md5(art['slug'].encode('utf-8')).hexdigest()[:8], 16) % 1000000 + 1000
        cats = "\n".join(
            f'      <category domain="category" nicename="{slug}"><![CDATA[{name}]]></category>'
            for slug, name in art["categories"]
        )
        
        meta_json_dict = {}
        
        meta_mapping = {
            'article_num':          '_article_num',
            'lesson_code':          '_lesson_code',
            'read_time':            '_read_time',
            'post_subtitle':        '_post_subtitle',
            'lesson_essentials':    '_lesson_essentials',
            'bibliography':         '_bibliography',
            '_resolved_focuskw':    '_yoast_wpseo_focuskw',
            '_resolved_seo_title':  '_yoast_wpseo_title',
            '_resolved_metadesc':   '_yoast_wpseo_metadesc',
        }
        
        for yaml_k, wp_k in meta_mapping.items():
            if yaml_k in art['meta'] and art['meta'][yaml_k]:
                meta_json_dict[wp_k] = art['meta'][yaml_k]

        meta_json_dict['_nihilnovi_import_source'] = art['filename']
        
        meta_json_str = json.dumps(meta_json_dict)

        items_xml.append(f"""    <item>
      <title>{html.escape(art['title'])}</title>
      <link>{site_url}/{art['slug']}/</link>
      <pubDate>{pub_date}</pubDate>
      <dc:creator><![CDATA[davidlegorreta]]></dc:creator>
      <guid isPermaLink="false">{site_url}/?p={stable_id}</guid>
      <description></description>
      <content:encoded><![CDATA[{art['content']}]]></content:encoded>
      <excerpt:encoded><![CDATA[]]></excerpt:encoded>
      <wp:post_id>{stable_id}</wp:post_id>
      <wp:post_date>{post_date}</wp:post_date>
      <wp:post_date_gmt>{post_date_gmt}</wp:post_date_gmt>
      <wp:post_name>{art['slug']}</wp:post_name>
      <wp:status>publish</wp:status>
      <wp:post_type>post</wp:post_type>
{cats}
      <meta_json><![CDATA[{meta_json_str}]]></meta_json>
    </item>""")

    wxr = f"""<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
  <channel>
    <title>Nihil Novi</title>
    <link>{site_url}</link>
    <description>Hub editorial y académico</description>
    <pubDate>{pub_date}</pubDate>
    <language>es</language>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>{site_url}</wp:base_site_url>
    <wp:base_blog_url>{site_url}</wp:base_blog_url>
{chr(10).join(categories_xml)}
{chr(10).join(items_xml)}
  </channel>
</rss>
"""
    return wxr

def main():
    articles = []
    for root, _, files in os.walk(ARTICLES_DIR):
        for fname in sorted(files):
            if not fname.endswith(".md") or fname.endswith(".prompt.md"):
                continue
            path = Path(root) / fname
            try:
                # Omitir borradores sin contenido (stubs con placeholder)
                if "[Contenido en desarrollo" in path.read_text(encoding="utf-8-sig"):
                    print(f"Omitido (borrador sin contenido): {path.name}")
                    continue
                articles.append(parse_article(path))
            except Exception as e:
                print(f"Error procesando {path}: {e}")

    if not articles:
        print("No se encontraron artículos.")
        return

    OUTPUT_FILE.parent.mkdir(parents=True, exist_ok=True)
    OUTPUT_FILE.write_text(build_wxr(articles), encoding="utf-8")
    print(f"Generado {OUTPUT_FILE} con {len(articles)} artículos.")

if __name__ == "__main__":
    main()
