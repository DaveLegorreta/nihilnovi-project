# AGENTS.md — Nihil Novi

Este documento resume la arquitectura, convenciones y flujo de trabajo del proyecto **Nihil Novi** (nihilnovi.xyz), un hub editorial y académico construido sobre WordPress, centrado en la Historia del Pensamiento Humano (Filosofía, Economía, Matemáticas, Historia y Ciencia).

> **Nota para agentes:** La documentación, los comentarios del código y los metadatos del tema están principalmente en **español**. Se prefiere mantener ese idioma para cualquier comentario, mensaje de commit o documentación que se añada.

---

## 1. Resumen del proyecto

Nihil Novi es un sitio de publicación a largo plazo que combina:

- Un **tema de WordPress** personalizado (`nihilnovi-theme`) con diseño editorial oscuro, tipografía serif y acentos dorados.
- **Artículos y lecciones** redactados en Markdown bajo plantillas académicas propias (`templates/`).
- Una **biblioteca digital** de fuentes primarias (PDFs clásicos de Gredos) y su conversión a Markdown (`data/`).
- **Guiones para TikTok** (`tiktok_scripts/`) derivados de los contenidos filosóficos.
- Herramientas Python locales para descargar, extraer y formatear PDFs académicos (`tools/`).

El objetivo editorial es publicar contenido riguroso con citación clásica (Stephanus, Bekker, Akademie-Ausgabe, APA 7) y material de estudio accesible.

---

## 2. Pila tecnológica y arquitectura

### 2.1. Frontend y tema

- **CMS:** WordPress (tema requiere al menos WordPress 6.0; probado hasta 6.5).
- **Lenguaje del servidor:** PHP 7.4+ (se recomienda 8.x).
- **Tema activo:** `nihilnovi-theme/` (versión 2.0.0-2026-06-28).
- **Snapshots/legado (guardados como tags de Git):**
  - `theme-v2.0.0-2026-06-28` — estado inicial del tema activo en `main`.
  - `theme-v2.0.0-2026-06-27` — versión anterior congelada.
  - `theme-v1.0.0-legacy` — versión 1.0.0, ya no se usa en producción.
- **JavaScript:** Vanilla JS (IIFE), GSAP 3.12.5 + ScrollTrigger vía CDN.
- **CSS:** Hoja de estilos única (`style.css`) con variables CSS (design tokens) y media queries.
- **Fuentes:** Google Fonts (Playfair Display, Source Serif 4, Inter, JetBrains Mono).

### 2.2. Plugins esperados en producción

- **Advanced Custom Fields (ACF):** opcional pero recomendado. Define los campos editables del homepage (`inc/acf-fields.php`). Si no está activo, el tema usa valores por defecto.
- **Mailchimp for WordPress (mc4wp_show_form):** opcional para el formulario de newsletter. Si no está, se muestra un formulario HTML estático.
- **Polylang (gratuito):** recomendado para la versión multilingüe. Gestiona los idiomas, las traducciones de contenido y el selector de idioma.

### 2.3. Multilingüe e internacionalización (i18n)

El tema está preparado para soportar varios idiomas mediante el mecanismo estándar de WordPress:

- **Textdomain:** `nihilnovi`.
- **Carpeta de traducciones:** `nihilnovi-theme/languages/`.
- **Idiomas objetivo:** español (idioma base), inglés, italiano y alemán.
- **Plugin recomendado:** [Polylang](https://wordpress.org/plugins/polylang/) (versión gratuita).

#### Añadir nuevas cadenas traducibles

1. En PHP, envuelve cualquier string visible con las funciones de WordPress y el textdomain `nihilnovi`:
   - `__( 'Texto', 'nihilnovi' )` o `esc_html__( 'Texto', 'nihilnovi' )` para salida HTML.
   - `esc_attr__( 'Texto', 'nihilnovi' )` para atributos HTML.
   - `wp_kses_post( __( 'Texto con <em>HTML</em>', 'nihilnovi' ) )` cuando el string contenga markup permitido.
   - `_n( 'singular', 'plural', $count, 'nihilnovi' )` para cadenas con pluralización.
2. En ACF (`inc/acf-fields.php`), aplica `__()` a `label`, `instructions` y `default_value`.
3. En el Customizer (`inc/customizer.php`), aplica `__()` a títulos, descripciones y valores por defecto.

#### Dónde van las traducciones

- La plantilla maestra de cadenas está en `nihilnovi-theme/languages/nihilnovi.pot`.
- Los archivos `.po` y `.mo` de cada idioma (p. ej. `nihilnovi-en_US.po`, `nihilnovi-it_IT.po`, `nihilnovi-de_DE.po`) deben guardarse en `nihilnovi-theme/languages/`.
- Polylang también gestiona las traducciones de posts, páginas, categorías y menús desde el panel de administración de WordPress.

#### Selector de idioma

El `header.php` incluye un marcador visual deshabilitado con los códigos de idioma (`ES`, `EN`, `IT`, `DE`). Cuando Polylang esté activo, se puede reemplazar por `pll_the_languages()` o por un widget de Polylang; mientras tanto, los textos ya son traducibles y el CSS permanece intacto.

### 2.4. Herramientas locales (Python)

- **Python:** ≥ 3.9.
- **Dependencias declaradas** en metadatos inline PEP 723 dentro de cada script:
  - `pypdf>=4.0.0`
  - `pdfplumber>=0.10.0`
  - `urllib3>=2.0.0` (solo descargadores)
- **No hay gestor de dependencias** (`requirements.txt`, `pyproject.toml`, etc.). Los scripts se pueden ejecutar directamente con `python`, `uv run` o instalando manualmente los paquetes anteriores.

---

## 3. Estructura de directorios

```
.
├── nihilnovi-theme/              ← Tema activo de WordPress
│   ├── style.css                 ← Hoja de estilos principal + cabecera del tema
│   ├── functions.php             ← Setup, enqueue, meta boxes, helpers
│   ├── header.php / footer.php
│   ├── front-page.php            ← Homepage editable vía ACF
│   ├── single.php                ← Plantilla de artículos y lecciones
│   ├── archive.php               ← Listados por categoría/disciplina
│   ├── index.php                 ← Blog genérico
│   ├── page.php                  ← Páginas estáticas
│   ├── 404.php
│   ├── inc/
│   │   ├── acf-fields.php        ← Campos ACF del homepage
│   │   └── customizer.php        ← Colores, redes sociales, footer
│   ├── template-parts/
│   │   ├── content-article.php
│   │   ├── content-lesson.php
│   │   └── content-none.php
│   ├── languages/                ← Traducciones del tema (.pot, .po, .mo)
│   │   └── nihilnovi.pot         ← Plantilla de cadenas traducibles
│   └── js/main.js                ← Interacciones vanilla + GSAP
│
├── articulos_publicacion/        ← Contenidos listos para publicar en WP
│   ├── Modulo_I_Mito_al_Logos/   ← Lecciones del módulo I
│   ├── base_datos_personajes/    ← Directorio reservado (vacío actualmente)
│   └── FIL_*.md, ECO_*.md, etc.  ← Artículos y lecciones en Markdown
│
├── data/
│   ├── pdfs/                     ← PDFs de fuentes académicas (Gredos)
│   ├── academic_md/              ← PDFs convertidos a Markdown
│   └── gredos_pdfs_list.txt      ← Lista tabulada nombre<TAB>url
│
├── templates/
│   ├── philosophy_template.md    ← Plantilla de citado y frontmatter
│   └── review_protocol.md        ← Protocolo de edición con Antigravity
│
├── tiktok_scripts/               ← Guiones de video verticales
│
├── tools/                        ← Scripts Python de soporte
│   ├── deploy.py                 ← Despliegue FTP/FTPS del tema
│   ├── batch_convert_all.py      ← Convierte PDFs académicos a Markdown
│   ├── pdf_extractor.py          ← Extrae texto plano de un PDF
│   ├── format_any_book.py        ← Limpia y formatea PDF → Markdown
│   ├── format_entire_book.py     ← Diálogos I de Platón (específico)
│   ├── format_dialogue.py        ← Apología de Sócrates (específico)
│   ├── download_helper.py        ← Buscador/descargador interactivo Gredos
│   ├── download_all.py           ← Descarga masiva de la lista Gredos
│   └── download_single.py        ← Descarga directa de una URL
│
├── nihilnovi-homepage-template.php  ← Plantilla de página WP standalone (prototipo)
├── nihilnovi_homepage.html       ← Prototipo HTML estático anterior
├── nihilnovi_v2.html             ← Prototipo HTML estático más reciente
├── Nihilnovi-theme.zip           ← Empaquetado del tema (despliegue manual)
├── nihilnovi-theme-v2.0-deploy.zip
└── sftp_credentials.json         ← Credenciales de despliegue (SECRETO)
```

---

## 4. Comandos y flujo de trabajo

### 4.1. Trabajar con el tema de WordPress

El tema no tiene paso de build ni compilación. Para probarlo:

1. Copiar la carpeta `nihilnovi-theme/` dentro de `wp-content/themes/` de una instalación WordPress local.
2. Activar el tema desde Apariencia → Temas.
3. Crear las categorías: `filosofia`, `economia`, `matematicas`, `historia`, `ciencia`, `lecciones`, `el-viaje`.
4. (Opcional) Instalar ACF para editar el homepage desde Páginas → Inicio.

Validación rápida de sintaxis PHP:

```bash
php -l nihilnovi-theme/*.php
php -l nihilnovi-theme/inc/*.php
php -l nihilnovi-theme/template-parts/*.php
```

### 4.2. Extraer y convertir PDFs

Extraer texto crudo página por página:

```bash
python tools/pdf_extractor.py "data/pdfs/MiLibro.pdf"
```

Convertir un libro completo a Markdown:

```bash
python tools/format_any_book.py "data/pdfs/MiLibro.pdf" "data/academic_md/autor/obra.md" --title "Título"
```

Procesar todos los libros configurados en `batch_convert_all.py`:

```bash
python tools/batch_convert_all.py
```

### 4.3. Descargar PDFs de Gredos

Buscador/descargador interactivo:

```bash
python tools/download_helper.py
```

Descarga masiva (puede tardar y consumir decenas de GB):

```bash
python tools/download_all.py
```

### 4.4. Desplegar el tema

```bash
python tools/deploy.py
```

El script:

- Lee `sftp_credentials.json` (host, usuario, contraseña, puerto).
- Intenta conexión FTPS; si falla, usa FTP plano.
- Detecta automáticamente si la raíz remota es el tema, `wp-content` o `public_html`.
- Sube recursivamente los archivos de `nihilnovi-theme/` omitiendo `.zip`, backups y archivos ocultos.

También se puede desplegar manualmente subiendo `Nihilnovi-theme.zip` o `nihilnovi-theme-v2.0-deploy.zip` por FTP/SFTP y descomprimiendo en el servidor.

---

## 5. Convenciones de código

### 5.1. PHP

- **Prefijo de funciones:** todas las funciones del tema usan el prefijo `nihilnovi_` para evitar colisiones.
- **Sanitización en plantillas:** usar siempre `esc_html()`, `esc_attr()`, `esc_url()` y `wp_kses()` en salidas dinámicas.
- **Meta boxes:** los campos personalizados se guardan con nonces (`nihilnovi_meta_nonce`) y se sanitizan con `sanitize_text_field()` / `sanitize_textarea_field()`.
- **Comentarios:** se mezclan comentarios en español e inglés; se prefiere mantener la coherencia del archivo original.
- **Estructura de archivos:** separar helpers en `functions.php`, campos ACF en `inc/acf-fields.php` y opciones del Customizer en `inc/customizer.php`.

### 5.2. JavaScript

- Vanilla JS, encapsulado en una IIFE.
- Sin jQuery.
- GSAP/ScrollTrigger solo se ejecuta si la librería está cargada (`if (typeof gsap !== 'undefined')`).
- Se respeta `prefers-reduced-motion` en el scroll suave.

### 5.3. CSS

- Design tokens en `:root` (`--black`, `--ivory`, `--gold`, `--fil`, `--eco`, `--mat`, `--his`, `--cie`, etc.).
- Clases de disciplina: `fil`, `eco`, `mat`, `his`, `cie`.
- Estilos responsivos con media queries al final de `style.css`.
- CSS inline es aceptable en plantillas PHP para estilos puntuales, pero se prefiere mantener la coherencia con las variables del tema.

### 5.4. Markdown editorial

- Los artículos deben seguir `templates/philosophy_template.md`.
- Frontmatter YAML con `title`, `author`, `source_work`, `citation_style`, `category`, `tags`.
- Citas clásicas según el autor (Stephanus, Bekker, Akademie-Ausgabe, § Hegel).
- Términos técnicos en idioma original en cursiva, con traducción sugerida entre corchetes: `*Dasein* [ser-ahí]`.
- Notas al pie con sintaxis `[^1]: ...`.

### 5.5. Nombres de archivos

- Artículos: `DISC_NUM_TITULO.md` (ej. `FIL_02_A.md`, `ECO_01_Introduccion.md`).
- Lecciones: prefijo de disciplina + número (ej. `FIL-01`, `ECO-01`).

---

## 6. Instrucciones de prueba

- **No hay suite de tests automatizados** (no PHPUnit, no Jest, no PyTest).
- Validaciones manuales recomendadas:
  1. Verificar que el homepage carga sin errores con y sin ACF.
  2. Comprobar el listado de categorías (`/categoria/filosofia`, `/categoria/lecciones`).
  3. Abrir un artículo (`single.php`) y verificar meta boxes: código, tiempo de lectura, subtítulo, "Lo esencial" y bibliografía.
  4. Revisar el menú móvil, tabs de artículos/lecciones y scroll suave.
  5. Activar `WP_DEBUG` en `wp-config.php` y revisar que no aparezcan warnings ni notices.
  6. Ejecutar `php -l` en todos los archivos `.php` antes de desplegar.

---

## 7. Despliegue

- **Automático:** `python tools/deploy.py` (lee `sftp_credentials.json`).
- **Manual:** subir `Nihilnovi-theme.zip` o `nihilnovi-theme-v2.0-deploy.zip` vía FTP/SFTP y descomprimir en `/wp-content/themes/`.
- **Tema activo:** `nihilnovi-theme/`. Los otros directorios de tema y los archivos `.zip` son copias de respaldo o prototipos; no se suben como temas independientes salvo que se decida explícitamente.

---

## 8. Consideraciones de seguridad

- **`sftp_credentials.json` contiene credenciales de producción.** No debe subirse a repositorios públicos ni compartirse. Asegúrate de que esté en `.gitignore` si el proyecto se versiona con Git.
- Las meta boxes usan nonces y sanitización básica. No se permiten scripts arbitrarios en los campos personalizados.
- En plantillas se utiliza `esc_html()`, `esc_attr()`, `esc_url()` y `wp_kses()` para prevenir XSS.
- Los scripts Python descargan archivos desde URLs de terceros (archive.org). Verifica la integridad de los PDFs antes de procesarlos.
- No ejecutes `tools/deploy.py` en redes no confiables si usa FTP plano como fallback; prefiere FTPS.

---

## 9. Control de versiones con Git

El proyecto usa **Git** para rastrear cambios. La rama principal es `main` y contiene el estado de trabajo actual.

### Tags de versiones del tema

- `theme-v2.0.0-2026-06-28` — tema activo actual (`nihilnovi-theme/`).
- `theme-v2.0.0-2026-06-27` — snapshot congelado de la versión anterior.
- `theme-v1.0.0-legacy` — versión 1.0.0 del tema.

Para ver todas las versiones:

```bash
git tag -l
git log --oneline --all --decorate
```

Para recuperar una versión antigua del tema en una carpeta temporal:

```bash
mkdir /tmp/nihilnovi-legacy
git archive theme-v1.0.0-legacy | tar -x -C /tmp/nihilnovi-legacy
```

### Qué NO se versiona

El archivo `.gitignore` excluye:

- `data/pdfs/` — PDFs académicos grandes (5.2 GB+).
- `*.zip` — artefactos de despliegue.
- `sftp_credentials.json` — credenciales de producción.
- `__pycache__/`, `*.pyc`, `.env`, etc.

### Conexión con GitHub

Para vincular el repo local con un repositorio remoto de GitHub:

```bash
git remote add origin https://github.com/<usuario>/nihilnovi.git
git branch -M main
git push -u origin main --tags
```

> **Importante:** antes de hacer push, verifica que `sftp_credentials.json` y los PDFs no estén en el índice con `git ls-files | grep -E 'sftp_credentials|\.pdf$|\.zip$'`.

### Flujo de trabajo recomendado

1. Trabajar en `main` para cambios pequeños y probados.
2. Para experimentos o funciones grandes, crear una rama: `git checkout -b feature/nueva-seccion`.
3. Hacer commits atómicos con mensajes en español.
4. Antes de desplegar, etiquetar la versión del tema: `git tag -a theme-v2.0.1-YYYY-MM-DD -m "Descripción"`.
5. Subir cambios y tags: `git push origin main --tags`.

### Flujo editorial con Antigravity

Antigravity es la herramienta usada para la **producción y revisión de artículos** y la generación de **widgets/snippets** del sitio. Para no mezclar el desarrollo del tema con la experimentación editorial, se usa una rama dedicada.

- **Rama `editorial`:** trabajo en curso de artículos, prompts, protocolos y formatos de Antigravity.
- **Rama `main`:** solo recibe contenido editorial revisado y listo para publicar.
- **Tag `antigravity-vN`:** marca una versión estable del flujo editorial (prompts, protocolo y estructura de artículos).

#### Crear o actualizar la rama editorial

```bash
git checkout -b editorial main
# Trabaja en articulos_publicacion/, templates/, prompts, etc.
git add .
git commit -m "editorial: [descripción del cambio]"
git push origin editorial
```

#### Publicar contenido revisado en main

```bash
git checkout main
git merge --no-ff editorial -m "Publica contenido editorial revisado"
git push origin main
```

#### Preparación para versión multilingüe

Cuando el proyecto crezca a otros idiomas, se propone esta estructura dentro de `articulos_publicacion/`:

```text
articulos_publicacion/
├── es/                       ← Español (idioma base)
│   ├── Modulo_I_Mito_al_Logos/
│   └── FIL_*.md
├── en/                       ← Inglés
│   ├── Module_I_Myth_to_Logo/
│   └── PHI_*.md
└── ...
```

La rama `editorial` es el lugar idóneo para desarrollar y validar traducciones antes de mergearlas a `main`.

### Convención de nombres

- **Ramas de tema:** `feature/nombre-corto` o `fix/descripcion`.
- **Ramas editoriales:** `editorial` para la línea principal; `editorial/idioma-nuevo` para expansiones (ej. `editorial/en-traduccion`).
- **Tags del tema:** `theme-VERSION-YYYY-MM-DD`.
- **Tags editoriales:** `antigravity-vN` (ej. `antigravity-v1`, `antigravity-v2`).

## 10. Recursos y contexto adicional

- `templates/philosophy_template.md` — estándares de citado y plantilla de artículo.
- `templates/review_protocol.md` — flujo de edición de textos extraídos de PDF con Antigravity.
- `nihilnovi-theme/preview_antigravity.html` — preview estática del diseño usada para validar artículos formateados.
- `tiktok_scripts/TIKTOK_*.md` — ejemplos de formato y tono para contenido corto.
- `data/gredos_pdfs_list.txt` — catálogo de fuentes disponibles para descarga.

Si llegas a un archivo sin contexto, recuerda que el español es el idioma principal del proyecto y que las cinco disciplinas (`fil`, `eco`, `mat`, `his`, `cie`) son el eje organizador de todo el contenido.
