#!/usr/bin/env python3
"""
generate_translations.py — Nihil Novi
Genera archivos .po y .mo para EN e IT a partir del .pot maestro.

Uso:
    python tools/generate_translations.py

Requiere: polib (pip install polib)
"""

import polib
from pathlib import Path

# ─── CONFIGURACIÓN ──────────────────────────────────────────
BASE_DIR = Path('c:/Users/david/OneDrive/Documentos/Skills/Projecto MKT')
LANG_DIR = BASE_DIR / 'nihilnovi-theme' / 'languages'
POT_FILE = LANG_DIR / 'nihilnovi.pot'

# Diccionarios de traducción: msgid -> msgstr
# Solo traducimos strings de UI/navegación. Las cadenas de contenido ACF
# se traducen vía Polylang en el panel de WordPress.

TRANSLATIONS_EN = {
    # Navegación y UI
    "Navegación principal": "Main navigation",
    "Nihil Novi — Inicio": "Nihil Novi — Home",
    "Selector de idioma": "Language selector",
    "ES": "ES",
    "EN": "EN",
    "IT": "IT",
    "DE": "DE",
    "Explorar": "Explore",
    "Abrir menú": "Open menu",
    "Redes sociales": "Social media",
    
    # Homepage
    "Portada": "Homepage",
    "Scroll": "Scroll",
    "Manifiesto": "Manifesto",
    "Las cinco disciplinas": "The five disciplines",
    "Los territorios": "The territories",
    "Cinco disciplinas.<br>Un solo proyecto.": "Five disciplines.<br>One project.",
    "Cada disciplina tiene su propia ruta de estudio, sus lecciones y su bibliografía.": "Each discipline has its own study path, lessons, and bibliography.",
    "Artículos y lecciones recientes": "Recent articles and lessons",
    "Lo más reciente": "Latest",
    "Artículos y <em>Lecciones</em>": "Articles and <em>Lessons</em>",
    "Ver todo el archivo": "View all archives",
    "Artículos": "Articles",
    "Lecciones": "Lessons",
    "El Viaje": "The Journey",
    "Pronto": "Soon",
    "Próximamente": "Coming soon",
    
    # Disciplinas
    "Economía": "Economics",
    "Filosofía": "Philosophy",
    "Matemáticas": "Mathematics",
    "Historia": "History",
    "Ciencia": "Science",
    
    # El Viaje / Secciones
    "El Viaje del Pensamiento": "The Journey of Thought",
    "La serie principal": "The main series",
    "El Viaje del": "The Journey of the",
    "Sobre el autor": "About the author",
    "Quién escribe": "Who writes",
    "Suscripción": "Subscription",
    "El viaje, en tu correo": "The journey, in your inbox",
    "tu@correo.com": "your@email.com",
    "Tu correo": "Your email",
    "Suscribirme": "Subscribe",
    "Leer más sobre el proyecto": "Read more about the project",
    
    # Newsletter
    "Una entrega por semana.": "One delivery per week.",
    "Sin algoritmos.": "No algorithms.",
    "Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.": "Articles, lessons, and that week's study material. Direct. No platform curation.",
    "Sin spam · Sin venta de datos · Baja cuando quieras": "No spam · No data selling · Unsubscribe anytime",
    
    # Archive / Blog
    "El archivo": "The archive",
    "Todos los <em>artículos</em>": "All <em>articles</em>",
    "Aún no hay artículos publicados.<br>El viaje empieza pronto.": "No articles published yet.<br>The journey begins soon.",
    "Volver al inicio": "Back to home",
    "Inicio": "Home",
    "Disciplina": "Discipline",
    "lección": "lesson",
    "lecciones": "lessons",
    "entrada": "entry",
    "entradas": "entries",
    "Ver todos →": "View all →",
    "Por época": "By era",
    "Por tema": "By theme",
    "artículo": "article",
    "artículos": "articles",
    "← Todas las disciplinas": "← All disciplines",
    "← Anterior": "← Previous",
    "Siguiente →": "Next →",
    
    # Single article
    "Navegación entre artículos": "Article navigation",
    "← Inicio de serie": "← Series start",
    "Continúa pronto →": "Continues soon →",
    "Artículos relacionados": "Related articles",
    "Seguir leyendo": "Continue reading",
    "Leer": "Read",
    
    # Content none
    "Todavía no hay contenido aquí": "No content here yet",
    "No se encontraron resultados para \"%s\". Prueba con otras palabras.": "No results found for \"%s\". Try other words.",
    "Esta sección está en construcción. El contenido llega pronto — semana a semana.": "This section is under construction. Content arrives soon — week by week.",
    "El contenido de esta sección llegará pronto. El viaje empieza.": "Content for this section will arrive soon. The journey begins.",
    
    # Meta boxes
    "Código de lección (ej: ECO-01)": "Lesson code (e.g. ECO-01)",
    "Número de artículo (ej: 00, 01, 02)": "Article number (e.g. 00, 01, 02)",
    "Tiempo de lectura (ej: 3 min)": "Reading time (e.g. 3 min)",
    "Subtítulo o frase de apertura": "Subtitle or opening phrase",
    "Lo esencial — Puntos clave (uno por línea)": "The essentials — Key points (one per line)",
    "Bibliografía y fuentes (una por línea)": "Bibliography and sources (one per line)",
    "Si se deja vacío, se calcula automáticamente.": "If left empty, it will be calculated automatically.",
    "Frase o subtítulo que aparece bajo el título principal...": "Phrase or subtitle that appears under the main title...",
    "Escribe un punto por línea. Aparecen en la caja dorada \"Lo esencial\" dentro de la lección.": "Write one point per line. They appear in the gold \"The essentials\" box within the lesson.",
    "Una referencia por línea. Ej: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.": "One reference per line. E.g.: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.",
    "Un libro o fuente por línea...": "One book or source per line...",
    
    # Customizer
    "Nihil Novi — Configuración": "Nihil Novi — Settings",
    "Nihil Novi — Colores": "Nihil Novi — Colors",
    "Color de acento (dorado)": "Accent color (gold)",
    "Color de fondo": "Background color",
    "Color de texto principal": "Main text color",
    "Nihil Novi — Redes Sociales": "Nihil Novi — Social Media",
    "Twitter / X": "Twitter / X",
    "Instagram": "Instagram",
    "LinkedIn": "LinkedIn",
    "YouTube / NotebookLM": "YouTube / NotebookLM",
    "Nihil Novi — Footer": "Nihil Novi — Footer",
    "© 2026 David Legorreta · nihilnovi.xyz": "© 2026 David Legorreta · nihilnovi.xyz",
    "Texto de copyright": "Copyright text",
    "X": "X",
    "IG": "IG",
    "LI": "LI",
    "YT": "YT",
    
    # ACF Fields (labels e instructions)
    "① Hero — Portada principal": "① Hero — Main homepage",
    "Eyebrow (texto pequeño sobre el título)": "Eyebrow (small text above title)",
    "Título — Línea 1": "Title — Line 1",
    "Título — Línea 2 (en dorado e itálica)": "Title — Line 2 (gold and italic)",
    "Subtítulo": "Subtitle",
    "Texto descriptivo bajo el título principal.": "Descriptive text under the main title.",
    "Botón 1 — Texto (dorado)": "Button 1 — Text (gold)",
    "Botón 1 — Link": "Button 1 — Link",
    "Botón 2 — Texto (contorno)": "Button 2 — Text (outline)",
    "Botón 2 — Link": "Button 2 — Link",
    "② Manifiesto — La cita central": "② Manifesto — The central quote",
    "Cita / Blockquote": "Quote / Blockquote",
    "La cita central del sitio. Puedes usar <strong>texto</strong> para negritas y <br> para saltos de línea.": "The site's central quote. You can use <strong>text</strong> for bold and <br> for line breaks.",
    "Autor de la cita": "Quote author",
    "③ Disciplinas — Las 5 tarjetas de portales": "③ Disciplines — The 5 portal cards",
    "Instrucción": "Instruction",
    "<strong>Edita el nombre, descripción y link de cada una de las 5 disciplinas.</strong><br>El color y código (FIL, ECO...) se asignan automáticamente por orden.": "<strong>Edit the name, description, and link of each of the 5 disciplines.</strong><br>Color and code (FIL, ECO...) are assigned automatically by order.",
    "Disciplina 1 — Nombre": "Discipline 1 — Name",
    "Disciplina 1 — Descripción": "Discipline 1 — Description",
    "Disciplina 1 — Código lección": "Discipline 1 — Lesson code",
    "Disciplina 1 — Link": "Discipline 1 — Link",
    "Disciplina 2 — Nombre": "Discipline 2 — Name",
    "Disciplina 2 — Descripción": "Discipline 2 — Description",
    "Disciplina 2 — Código lección": "Discipline 2 — Lesson code",
    "Disciplina 2 — Link": "Discipline 2 — Link",
    "Disciplina 3 — Nombre": "Discipline 3 — Name",
    "Disciplina 3 — Descripción": "Discipline 3 — Description",
    "Disciplina 3 — Código lección": "Discipline 3 — Lesson code",
    "Disciplina 3 — Link": "Discipline 3 — Link",
    "Disciplina 4 — Nombre": "Discipline 4 — Name",
    "Disciplina 4 — Descripción": "Discipline 4 — Description",
    "Disciplina 4 — Código lección": "Discipline 4 — Lesson code",
    "Disciplina 4 — Link": "Discipline 4 — Link",
    "Disciplina 5 — Nombre": "Discipline 5 — Name",
    "Disciplina 5 — Descripción": "Discipline 5 — Description",
    "Disciplina 5 — Código lección": "Discipline 5 — Lesson code",
    "Disciplina 5 — Link": "Discipline 5 — Link",
    "④ El Viaje del Economista": "④ The Journey of the Economist",
    "Título de la sección": "Section title",
    "Párrafo 1": "Paragraph 1",
    "Párrafo 2": "Paragraph 2",
    "Texto del botón": "Button text",
    "Link del botón": "Button link",
    "⑤ Sobre — Perfil de autor": "⑤ About — Author profile",
    "Nombre — Línea 1": "Name — Line 1",
    "Nombre — Línea 2 (en dorado)": "Name — Line 2 (gold)",
    "Dato 1 — Etiqueta": "Data 1 — Label",
    "Dato 1 — Valor": "Data 1 — Value",
    "Dato 2 — Etiqueta": "Data 2 — Label",
    "Dato 2 — Valor": "Data 2 — Value",
    "Dato 3 — Etiqueta": "Data 3 — Label",
    "Dato 3 — Valor": "Data 3 — Value",
    "Dato 4 — Etiqueta": "Data 4 — Label",
    "Dato 4 — Valor": "Data 4 — Value",
    "Párrafo 3": "Paragraph 3",
    "Botón — Texto": "Button — Text",
    "Botón — Link": "Button — Link",
    "⑥ Newsletter": "⑥ Newsletter",
    "Título — Línea 1": "Title — Line 1",
    "Título — Línea 2 (en dorado)": "Title — Line 2 (gold)",
    "Texto descriptivo": "Descriptive text",
    "Nota pequeña bajo el formulario": "Small note under the form",
    
    # Misc
    "Barra lateral del blog": "Blog sidebar",
    "Pie de página": "Footer",
    "Migas de pan": "Breadcrumbs",
    
    # Default values (ACF)
    "David Legorreta — nihilnovi.xyz": "David Legorreta — nihilnovi.xyz",
    "La historia del pensamiento,": "The history of thought,",
    "reconstruida en público.": "reconstructed in public.",
    "Filosofía. Economía. Matemáticas. Historia. Ciencia. El mapa de 3,000 años de intentar entender cómo funciona el mundo — con todo el material, la bibliografía y las preguntas sin resolver incluidos.": "Philosophy. Economics. Mathematics. History. Science. The map of 3,000 years of trying to understand how the world works — with all the material, bibliography, and unanswered questions included.",
    "Explorar las disciplinas": "Explore the disciplines",
    "El Viaje del Economista": "The Journey of the Economist",
    "Los humanos llevamos tres mil años intentando entender cómo funciona el mundo. Este sitio es el <strong>mapa de ese intento.</strong> No el destino. El camino.": "We humans have been trying to understand how the world works for three thousand years. This site is the <strong>map of that attempt.</strong> Not the destination. The path.",
    "— David Legorreta, nihilnovi.xyz — 2026": "— David Legorreta, nihilnovi.xyz — 2026",
    "Las preguntas que ninguna otra disciplina se atreve a hacer. El origen de todo lo demás.": "The questions no other discipline dares to ask. The origin of everything else.",
    "Las consecuencias materiales de las ideas. Cómo se distribuye lo que se produce y por qué.": "The material consequences of ideas. How what is produced is distributed and why.",
    "El lenguaje de la precisión. No para calcular — para pensar sin margen de ambigüedad.": "The language of precision. Not to calculate — to think without margin of ambiguity.",
    "El contexto sin el cual las ideas parecen naturales. Nada en el presente es inevitable.": "The context without which ideas seem natural. Nothing in the present is inevitable.",
    "El método. Cómo se construye conocimiento que resiste el error y la ideología.": "The method. How knowledge that resists error and ideology is built.",
    "Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.": "A study path built in public. Week by week, subject by subject. With real material, complete bibliography, and the questions each topic generates.",
    "No es un curso. No hay certificado al final. Es el camino que estoy recorriendo.": "It's not a course. There's no certificate at the end. It's the path I'm walking.",
    "Ver el mapa completo": "View the full map",
    "David": "David",
    "Legorreta": "Legorreta",
    "Base": "Base",
    "México / LATAM": "Mexico / LATAM",
    "Formación": "Education",
    "Filosofía · Economía": "Philosophy · Economics",
    "Experiencia": "Experience",
    "BPO · Retail · Tech": "BPO · Retail · Tech",
    "Idiomas": "Languages",
    "ES · EN · IT (en curso)": "ES · EN · IT (in progress)",
    "Estudié filosofía. Después trabajé catorce años en operaciones — BPO, retail, logística, tecnología. Cash App, TikTok, Walmart, Avis. Gestión de equipos, entrega de resultados, problemas estructurales disfrazados de problemas personales.": "I studied philosophy. Then I worked fourteen years in operations — BPO, retail, logistics, technology. Cash App, TikTok, Walmart, Avis. Team management, delivering results, structural problems disguised as personal problems.",
    "En algún momento me di cuenta de que las preguntas que me importaban eran preguntas económicas. La filosofía me enseñó a preguntar. La economía, si se estudia en serio, enseña a responder con evidencia.": "At some point I realized the questions that mattered to me were economic questions. Philosophy taught me to ask. Economics, if studied seriously, teaches to answer with evidence.",
    "Nihil Novi es donde hago eso en público. Sin pretender que ya llegué. Sin vender certezas que no tengo.": "Nihil Novi is where I do that in public. Without pretending I've already arrived. Without selling certainties I don't have.",
    
    # Date formats
    "25 de mayo de 2026. Hoy empieza.": "May 25, 2026. Today begins.",
    "No el día en que llegué a algún destino. El día en que decidí hacer el camino en público.": "Not the day I reached some destination. The day I decided to walk the path in public.",
    "25 mayo 2026": "May 25 2026",
    "Jun 2026": "Jun 2026",
    
    # Content descriptions (ACF)
    "Si te dijeron que la economía es la ciencia de la oferta y la demanda, te timaron.": "If they told you economics is the science of supply and demand, you were fooled.",
    "La economía no empezó con Adam Smith en 1776. Empezó con los griegos.": "Economics didn't start with Adam Smith in 1776. It started with the Greeks.",
    "Las tres preguntas que toda economía tiene que responder.": "The three questions every economy must answer.",
    "Qué producir. Cómo producirlo. Para quién.": "What to produce. How to produce it. For whom.",
    "¿Qué estudia realmente la economía?": "What does economics really study?",
    "No es la ciencia del dinero. Es la ciencia de las decisiones bajo escasez.": "It's not the science of money. It's the science of decisions under scarcity.",
    "¿Qué es la filosofía y para qué sirve?": "What is philosophy and what is it for?",
    "El único instrumento para cuestionar los supuestos que todas las demás disciplinas dan por sentados.": "The only instrument to question the assumptions all other disciplines take for granted.",
    "Las matemáticas son un lenguaje, no un cálculo.": "Mathematics is a language, not a calculation.",
    "La mayoría aprende a calcular y cree que eso es matemáticas. No lo es.": "Most people learn to calculate and think that's mathematics. It's not.",
    "Cómo leer la historia sin que te manipulen.": "How to read history without being manipulated.",
    "Toda historia es una selección. Alguien decidió qué incluir y qué omitir.": "All history is a selection. Someone decided what to include and what to omit.",
    "El método científico de verdad.": "The real scientific method.",
    "No es el diagrama del libro de secundaria. Es reducir el error sistemático del pensamiento.": "It's not the high school textbook diagram. It's reducing the systematic error of thought.",
    
    # Lesson/module descriptions
    "Lógica y Epistemología": "Logic and Epistemology",
    "Cálculo proposicional y de primer orden, falacias formales, la definición clásica de conocimiento, problemas de justificación (Gettier) y empirismo frente a racionalismo.": "Propositional and first-order calculus, formal fallacies, the classical definition of knowledge, justification problems (Gettier), and empiricism vs. rationalism.",
    "Historia de las Ideas y Sistemas Sociales": "History of Ideas and Social Systems",
    "Genealogía ética y política clásica, idealismo alemán (Hegel) y economía política basada en la teoría del valor-trabajo, plusvalía y acumulación de capital.": "Classical ethical and political genealogy, German idealism (Hegel), and political economy based on labor theory of value, surplus value, and capital accumulation.",
    "El Lenguaje Matemático y Rigor Formal": "The Mathematical Language and Formal Rigor",
    "Cálculo infinitesimal (diferencial e integral) multivariable, álgebra lineal (matrices y vectores), optimización estática y leyes de probabilidad aplicada.": "Multivariable infinitesimal calculus (differential and integral), linear algebra (matrices and vectors), static optimization, and applied probability laws.",
    "Economía y Sistemas Materiales": "Economics and Material Systems",
    "Teoría microeconómica del consumidor y la empresa (utilidad y costos), equilibrio general macroeconómico (modelos IS-LM y Solow) e inferencia empírica por Mínimos Cuadrados Ordinarios.": "Microeconomic theory of consumer and firm (utility and costs), macroeconomic general equilibrium (IS-LM and Solow models), and empirical inference by Ordinary Least Squares.",
    "Epistemología de la Ciencia": "Epistemology of Science",
    "El método de prueba científica, falsacionismo de Popper, paradigmas de Kuhn e investigación metodológica.": "The scientific testing method, Popper's falsificationism, Kuhn's paradigms, and methodological research.",
    "Computabilidad y Cómputo Formal": "Computability and Formal Computation",
    "Límites de decidibilidad de sistemas lógicos, máquinas de Turing, teoremas de Gödel y teoría de la información.": "Limits of decidability of logical systems, Turing machines, Gödel's theorems, and information theory.",
    "Síntesis y Fronteras de la IA": "Synthesis and Frontiers of AI",
    "Modelos de redes neuronales, el problema mente-cuerpo, implicaciones epistémicas del cómputo y la física contemporánea.": "Neural network models, the mind-body problem, epistemic implications of computation, and contemporary physics.",
    
    # Footer / Author
    "Filósofo de formación, operador de oficio. Estudiando economía en público en %s.": "Philosopher by training, operator by trade. Studying economics in public at %s.",
}


TRANSLATIONS_IT = {
    # Navegazione e UI
    "Navegación principal": "Navigazione principale",
    "Nihil Novi — Inicio": "Nihil Novi — Home",
    "Selector de idioma": "Selettore di lingua",
    "ES": "ES",
    "EN": "EN",
    "IT": "IT",
    "DE": "DE",
    "Explorar": "Esplorare",
    "Abrir menú": "Apri menu",
    "Redes sociales": "Social media",
    
    # Homepage
    "Portada": "Homepage",
    "Scroll": "Scroll",
    "Manifiesto": "Manifesto",
    "Las cinco disciplinas": "Le cinque discipline",
    "Los territorios": "I territori",
    "Cinco disciplinas.<br>Un solo proyecto.": "Cinque discipline.<br>Un solo progetto.",
    "Cada disciplina tiene su propia ruta de estudio, sus lecciones y su bibliografía.": "Ogni disciplina ha il suo percorso di studio, le sue lezioni e la sua bibliografia.",
    "Artículos y lecciones recientes": "Articoli e lezioni recenti",
    "Lo más reciente": "Più recente",
    "Artículos y <em>Lecciones</em>": "Articoli e <em>Lezioni</em>",
    "Ver todo el archivo": "Vedi tutto l'archivio",
    "Artículos": "Articoli",
    "Lecciones": "Lezioni",
    "El Viaje": "Il Viaggio",
    "Pronto": "Presto",
    "Próximamente": "Prossimamente",
    
    # Discipline
    "Economía": "Economia",
    "Filosofía": "Filosofia",
    "Matemáticas": "Matematica",
    "Historia": "Storia",
    "Ciencia": "Scienza",
    
    # El Viaje / Sezioni
    "El Viaje del Pensamiento": "Il Viaggio del Pensiero",
    "La serie principal": "La serie principale",
    "El Viaje del": "Il Viaggio del",
    "Sobre el autor": "Sull'autore",
    "Quién escribe": "Chi scrive",
    "Suscripción": "Iscrizione",
    "El viaje, en tu correo": "Il viaggio, nella tua email",
    "tu@correo.com": "tua@email.com",
    "Tu correo": "La tua email",
    "Suscribirme": "Iscrivimi",
    "Leer más sobre el proyecto": "Leggi di più sul progetto",
    
    # Newsletter
    "Una entrega por semana.": "Una consegna a settimana.",
    "Sin algoritmos.": "Senza algoritmi.",
    "Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.": "Articoli, lezioni e il materiale di studio di quella settimana. Diretto. Senza curazione della piattaforma.",
    "Sin spam · Sin venta de datos · Baja cuando quieras": "Niente spam · Nessuna vendita di dati · Disiscriviti quando vuoi",
    
    # Archive / Blog
    "El archivo": "L'archivio",
    "Todos los <em>artículos</em>": "Tutti gli <em>articoli</em>",
    "Aún no hay artículos publicados.<br>El viaje empieza pronto.": "Nessun articolo pubblicato ancora.<br>Il viaggio inizia presto.",
    "Volver al inicio": "Torna alla home",
    "Inicio": "Home",
    "Disciplina": "Disciplina",
    "lección": "lezione",
    "lecciones": "lezioni",
    "entrada": "voce",
    "entradas": "voci",
    "Ver todos →": "Vedi tutti →",
    "Por época": "Per epoca",
    "Por tema": "Per tema",
    "artículo": "articolo",
    "artículos": "articoli",
    "← Todas las disciplinas": "← Tutte le discipline",
    "← Anterior": "← Precedente",
    "Siguiente →": "Successivo →",
    
    # Single article
    "Navegación entre artículos": "Navigazione tra articoli",
    "← Inicio de serie": "← Inizio serie",
    "Continúa pronto →": "Continua presto →",
    "Artículos relacionados": "Articoli correlati",
    "Seguir leyendo": "Continua a leggere",
    "Leer": "Leggi",
    
    # Content none
    "Todavía no hay contenido aquí": "Ancora nessun contenuto qui",
    "No se encontraron resultados para \"%s\". Prueba con otras palabras.": "Nessun risultato trovato per \"%s\". Prova con altre parole.",
    "Esta sección está en construcción. El contenido llega pronto — semana a semana.": "Questa sezione è in costruzione. Il contenuto arriva presto — settimana per settimana.",
    "El contenido de esta sección llegará pronto. El viaje empieza.": "Il contenuto di questa sezione arriverà presto. Il viaggio inizia.",
    
    # Meta boxes
    "Código de lección (ej: ECO-01)": "Codice lezione (es: ECO-01)",
    "Número de artículo (ej: 00, 01, 02)": "Numero articolo (es: 00, 01, 02)",
    "Tiempo de lectura (ej: 3 min)": "Tempo di lettura (es: 3 min)",
    "Subtítulo o frase de apertura": "Sottotitolo o frase di apertura",
    "Lo esencial — Puntos clave (uno por línea)": "L'essenziale — Punti chiave (uno per riga)",
    "Bibliografía y fuentes (una por línea)": "Bibliografia e fonti (una per riga)",
    "Si se deja vacío, se calcula automáticamente.": "Se lasciato vuoto, viene calcolato automaticamente.",
    "Frase o subtítulo que aparece bajo el título principal...": "Frase o sottotitolo che appare sotto il titolo principale...",
    "Escribe un punto por línea. Aparecen en la caja dorada \"Lo esencial\" dentro de la lección.": "Scrivi un punto per riga. Appaiono nella casella dorata \"L'essenziale\" all'interno della lezione.",
    "Una referencia por línea. Ej: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.": "Un riferimento per riga. Es: Mankiw, N.G. (2012). Principles of Economics. Cengage Learning.",
    "Un libro o fuente por línea...": "Un libro o fonte per riga...",
    
    # Customizer
    "Nihil Novi — Configuración": "Nihil Novi — Configurazione",
    "Nihil Novi — Colores": "Nihil Novi — Colori",
    "Color de acento (dorado)": "Colore accento (oro)",
    "Color de fondo": "Colore sfondo",
    "Color de texto principal": "Colore testo principale",
    "Nihil Novi — Redes Sociales": "Nihil Novi — Social Media",
    "Twitter / X": "Twitter / X",
    "Instagram": "Instagram",
    "LinkedIn": "LinkedIn",
    "YouTube / NotebookLM": "YouTube / NotebookLM",
    "Nihil Novi — Footer": "Nihil Novi — Footer",
    "© 2026 David Legorreta · nihilnovi.xyz": "© 2026 David Legorreta · nihilnovi.xyz",
    "Texto de copyright": "Testo copyright",
    "X": "X",
    "IG": "IG",
    "LI": "LI",
    "YT": "YT",
    
    # ACF Fields (labels e instructions)
    "① Hero — Portada principale": "① Hero — Homepage principale",
    "Eyebrow (texto pequeño sobre el título)": "Eyebrow (testo piccolo sopra il titolo)",
    "Título — Línea 1": "Titolo — Linea 1",
    "Título — Línea 2 (en dorado e itálica)": "Titolo — Linea 2 (oro e corsivo)",
    "Subtítulo": "Sottotitolo",
    "Texto descriptivo bajo el título principal.": "Testo descrittivo sotto il titolo principale.",
    "Botón 1 — Texto (dorado)": "Pulsante 1 — Testo (oro)",
    "Botón 1 — Link": "Pulsante 1 — Link",
    "Botón 2 — Texto (contorno)": "Pulsante 2 — Testo (contorno)",
    "Botón 2 — Link": "Pulsante 2 — Link",
    "② Manifiesto — La cita central": "② Manifesto — La citazione centrale",
    "Cita / Blockquote": "Citazione / Blockquote",
    "La cita central del sitio. Puedes usar <strong>texto</strong> para negritas y <br> para saltos de línea.": "La citazione centrale del sito. Puoi usare <strong>testo</strong> per il grassetto e <br> per le interruzioni di riga.",
    "Autor de la cita": "Autore della citazione",
    "③ Disciplinas — Las 5 tarjetas de portales": "③ Discipline — Le 5 carte portale",
    "Instrucción": "Istruzione",
    "<strong>Edita el nombre, descripción y link de cada una de las 5 disciplinas.</strong><br>El color y código (FIL, ECO...) se asignan automáticamente por orden.": "<strong>Modifica il nome, la descrizione e il link di ciascuna delle 5 discipline.</strong><br>Il colore e il codice (FIL, ECO...) vengono assegnati automaticamente per ordine.",
    "Disciplina 1 — Nombre": "Disciplina 1 — Nome",
    "Disciplina 1 — Descripción": "Disciplina 1 — Descrizione",
    "Disciplina 1 — Código lección": "Disciplina 1 — Codice lezione",
    "Disciplina 1 — Link": "Disciplina 1 — Link",
    "Disciplina 2 — Nombre": "Disciplina 2 — Nome",
    "Disciplina 2 — Descripción": "Disciplina 2 — Descrizione",
    "Disciplina 2 — Código lección": "Disciplina 2 — Codice lezione",
    "Disciplina 2 — Link": "Disciplina 2 — Link",
    "Disciplina 3 — Nombre": "Disciplina 3 — Nome",
    "Disciplina 3 — Descripción": "Disciplina 3 — Descrizione",
    "Disciplina 3 — Código lección": "Disciplina 3 — Codice lezione",
    "Disciplina 3 — Link": "Disciplina 3 — Link",
    "Disciplina 4 — Nombre": "Disciplina 4 — Nome",
    "Disciplina 4 — Descripción": "Disciplina 4 — Descrizione",
    "Disciplina 4 — Código lección": "Disciplina 4 — Codice lezione",
    "Disciplina 4 — Link": "Disciplina 4 — Link",
    "Disciplina 5 — Nombre": "Disciplina 5 — Nome",
    "Disciplina 5 — Descripción": "Disciplina 5 — Descrizione",
    "Disciplina 5 — Código lección": "Disciplina 5 — Codice lezione",
    "Disciplina 5 — Link": "Disciplina 5 — Link",
    "④ El Viaje del Economista": "④ Il Viaggio dell'Economista",
    "Título de la sección": "Titolo della sezione",
    "Párrafo 1": "Paragrafo 1",
    "Párrafo 2": "Paragrafo 2",
    "Texto del botón": "Testo del pulsante",
    "Link del botón": "Link del pulsante",
    "⑤ Sobre — Perfil de autor": "⑤ Chi Siamo — Profilo autore",
    "Nombre — Línea 1": "Nome — Linea 1",
    "Nombre — Línea 2 (en dorado)": "Nome — Linea 2 (oro)",
    "Dato 1 — Etiqueta": "Dato 1 — Etichetta",
    "Dato 1 — Valor": "Dato 1 — Valore",
    "Dato 2 — Etiqueta": "Dato 2 — Etichetta",
    "Dato 2 — Valor": "Dato 2 — Valore",
    "Dato 3 — Etiqueta": "Dato 3 — Etichetta",
    "Dato 3 — Valor": "Dato 3 — Valore",
    "Dato 4 — Etiqueta": "Dato 4 — Etichetta",
    "Dato 4 — Valor": "Dato 4 — Valore",
    "Párrafo 3": "Paragrafo 3",
    "Botón — Texto": "Pulsante — Testo",
    "Botón — Link": "Pulsante — Link",
    "⑥ Newsletter": "⑥ Newsletter",
    "Título — Línea 1": "Titolo — Linea 1",
    "Título — Línea 2 (en dorado)": "Titolo — Linea 2 (oro)",
    "Texto descriptivo": "Testo descrittivo",
    "Nota pequeña bajo el formulario": "Nota piccola sotto il modulo",
    
    # Misc
    "Barra lateral del blog": "Barra laterale del blog",
    "Pie de página": "Piè di pagina",
    "Migas de pan": "Briciole di pane",
    
    # Default values (ACF)
    "David Legorreta — nihilnovi.xyz": "David Legorreta — nihilnovi.xyz",
    "La historia del pensamiento,": "La storia del pensiero,",
    "reconstruida en público.": "ricostruita in pubblico.",
    "Filosofía. Economía. Matemáticas. Historia. Ciencia. El mapa de 3,000 años de intentar entender cómo funciona el mundo — con todo el material, la bibliografía y las preguntas sin resolver incluidos.": "Filosofia. Economia. Matematica. Storia. Scienza. La mappa di 3.000 anni di tentativi di capire come funziona il mondo — con tutto il materiale, la bibliografia e le domande senza risposta incluse.",
    "Explorar las disciplinas": "Esplora le discipline",
    "El Viaje del Economista": "Il Viaggio dell'Economista",
    "Los humanos llevamos tres mil años intentando entender cómo funciona el mundo. Este sitio es el <strong>mapa de ese intento.</strong> No el destino. El camino.": "Noi umani portiamo tremila anni cercando di capire come funziona il mondo. Questo sito è la <strong>mappa di quel tentativo.</strong> Non la destinazione. Il cammino.",
    "— David Legorreta, nihilnovi.xyz — 2026": "— David Legorreta, nihilnovi.xyz — 2026",
    "Las preguntas que ninguna otra disciplina se atreve a hacer. El origen de todo lo demás.": "Le domande che nessun'altra disciplina osa fare. L'origine di tutto il resto.",
    "Las consecuencias materiales de las ideas. Cómo se distribuye lo que se produce y por qué.": "Le conseguenze materiali delle idee. Come si distribuisce ciò che si produce e perché.",
    "El lenguaje de la precisión. No para calcular — para pensar sin margen de ambigüedad.": "Il linguaggio della precisione. Non per calcolare — per pensare senza margine di ambiguità.",
    "El contexto sin el cual las ideas parecen naturales. Nada en el presente es inevitable.": "Il contesto senza il quale le idee sembrano naturali. Niente nel presente è inevitabile.",
    "El método. Cómo se construye conocimiento que resiste el error y la ideología.": "Il metodo. Come si costruisce conoscenza che resiste all'errore e all'ideologia.",
    "Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.": "Un percorso di studio costruito in pubblico. Settimana per settimana, materia per materia. Con il materiale reale, la bibliografia completa e le domande che ogni argomento genera.",
    "No es un curso. No hay certificado al final. Es el camino que estoy recorriendo.": "Non è un corso. Non c'è certificato alla fine. È il cammino che sto percorrendo.",
    "Ver el mapa completo": "Vedi la mappa completa",
    "David": "David",
    "Legorreta": "Legorreta",
    "Base": "Base",
    "México / LATAM": "Messico / LATAM",
    "Formación": "Formazione",
    "Filosofía · Economía": "Filosofia · Economia",
    "Experiencia": "Esperienza",
    "BPO · Retail · Tech": "BPO · Retail · Tech",
    "Idiomas": "Lingue",
    "ES · EN · IT (en curso)": "ES · EN · IT (in corso)",
    "Estudié filosofía. Después trabajé catorce años en operaciones — BPO, retail, logística, tecnología. Cash App, TikTok, Walmart, Avis. Gestión de equipos, entrega de resultados, problemas estructurales disfrazados de problemas personales.": "Ho studiato filosofia. Poi ho lavorato quattordici anni in operazioni — BPO, retail, logistica, tecnologia. Cash App, TikTok, Walmart, Avis. Gestione di team, consegna di risultati, problemi strutturali travestiti da problemi personali.",
    "En algún momento me di cuenta de que las preguntas que me importaban eran preguntas económicas. La filosofía me enseñó a preguntar. La economía, si se estudia en serio, enseña a responder con evidencia.": "A un certo punto mi sono reso conto che le domande che mi importavano erano domande economiche. La filosofia mi ha insegnato a chiedere. L'economia, se studiata seriamente, insegna a rispondere con evidenza.",
    "Nihil Novi es donde hago eso en público. Sin pretender que ya llegué. Sin vender certezas que no tengo.": "Nihil Novi è dove faccio questo in pubblico. Senza pretendere di essere già arrivato. Senza vendere certezze che non ho.",
    
    # Date formats
    "25 de mayo de 2026. Hoy empieza.": "25 maggio 2026. Oggi inizia.",
    "No el día en que llegué a algún destino. El día en que decidí hacer el camino en público.": "Non il giorno in cui raggiunsi una destinazione. Il giorno in cui decisi di fare il cammino in pubblico.",
    "25 mayo 2026": "25 maggio 2026",
    "Jun 2026": "Giu 2026",
    
    # Content descriptions (ACF)
    "Si te dijeron que la economía es la ciencia de la oferta y la demanda, te timaron.": "Se ti hanno detto che l'economia è la scienza dell'offerta e della domanda, ti hanno ingannato.",
    "La economía no empezó con Adam Smith en 1776. Empezó con los griegos.": "L'economia non è iniziata con Adam Smith nel 1776. È iniziata con i greci.",
    "Las tres preguntas que toda economía tiene que responder.": "Le tre domande che ogni economia deve rispondere.",
    "Qué producir. Cómo producirlo. Para quién.": "Cosa produrre. Come produrlo. Per chi.",
    "¿Qué estudia realmente la economía?": "Cosa studia realmente l'economia?",
    "No es la ciencia del dinero. Es la ciencia de las decisiones bajo escasez.": "Non è la scienza del denaro. È la scienza delle decisioni sotto scarsità.",
    "¿Qué es la filosofía y para qué sirve?": "Cos'è la filosofia e a cosa serve?",
    "El único instrumento para cuestionar los supuestos que todas las demás disciplinas dan por sentados.": "L'unico strumento per mettere in discussione i presupposti che tutte le altre discipline danno per scontati.",
    "Las matemáticas son un lenguaje, no un cálculo.": "La matematica è un linguaggio, non un calcolo.",
    "La mayoría aprende a calcular y cree que eso es matemáticas. No lo es.": "La maggior parte impara a calcolare e pensa che sia matematica. Non lo è.",
    "Cómo leer la historia sin que te manipulen.": "Come leggere la storia senza essere manipolati.",
    "Toda historia es una selección. Alguien decidió qué incluir y qué omitir.": "Tutta la storia è una selezione. Qualcuno ha deciso cosa includere e cosa omettere.",
    "El método científico de verdad.": "Il vero metodo scientifico.",
    "No es el diagrama del libro de secundaria. Es reducir el error sistemático del pensamiento.": "Non è il diagramma del libro di scuola. È ridurre l'errore sistematico del pensiero.",
    
    # Lesson/module descriptions
    "Lógica y Epistemología": "Logica e Epistemologia",
    "Cálculo proposicional y de primer orden, falacias formales, la definición clásica de conocimiento, problemas de justificación (Gettier) y empirismo frente a racionalismo.": "Calcolo proposizionale e del primo ordine, fallacie formali, la definizione classica di conoscenza, problemi di giustificazione (Gettier) e empirismo contro razionalismo.",
    "Historia de las Ideas y Sistemas Sociales": "Storia delle Idee e Sistemi Sociali",
    "Genealogía ética y política clásica, idealismo alemán (Hegel) y economía política basada en la teoría del valor-trabajo, plusvalía y acumulación de capital.": "Genealogia etica e politica classica, idealismo tedesco (Hegel) ed economia politica basata sulla teoria del valore-lavoro, plusvalore e accumulazione di capitale.",
    "El Lenguaje Matemático y Rigor Formal": "Il Linguaggio Matematico e il Rigor Formale",
    "Cálculo infinitesimal (diferencial e integral) multivariable, álgebra lineal (matrices y vectores), optimización estática y leyes de probabilidad aplicada.": "Calcolo infinitesimale (differenziale e integrale) multivariabile, algebra lineare (matrici e vettori), ottimizzazione statica e leggi di probabilità applicata.",
    "Economía y Sistemas Materiales": "Economia e Sistemi Materiali",
    "Teoría microeconómica del consumidor y la empresa (utilidad y costos), equilibrio general macroeconómico (modelos IS-LM y Solow) e inferencia empírica por Mínimos Cuadrados Ordinarios.": "Teoria microeconomica del consumatore e dell'impresa (utilità e costi), equilibrio generale macroeconomico (modelli IS-LM e Solow) e inferenza empirica per Minimi Quadrati Ordinari.",
    "Epistemología de la Ciencia": "Epistemologia della Scienza",
    "El método de prueba científica, falsacionismo de Popper, paradigmas de Kuhn e investigación metodológica.": "Il metodo di prova scientifica, falsificazionismo di Popper, paradigmi di Kuhn e ricerca metodologica.",
    "Computabilidad y Cómputo Formal": "Computabilità e Calcolo Formale",
    "Límites de decidibilidad de sistemas lógicos, máquinas de Turing, teoremas de Gödel y teoría de la información.": "Limiti di decidibilità di sistemi logici, macchine di Turing, teoremi di Gödel e teoria dell'informazione.",
    "Síntesis y Fronteras de la IA": "Sintesi e Frontiere dell'IA",
    "Modelos de redes neuronales, el problema mente-cuerpo, implicaciones epistémicas del cómputo y la física contemporánea.": "Modelli di reti neurali, il problema mente-corpo, implicazioni epistemiche del calcolo e la fisica contemporanea.",
    
    # Footer / Author
    "Filósofo de formación, operador de oficio. Estudiando economía en público en %s.": "Filosofo di formazione, operatore di mestiere. Studiando economia in pubblico su %s.",
}


def generate_po(pot_path, translations, lang_code, lang_name, output_path):
    """Genera un archivo .po a partir del .pot y un diccionario de traducciones."""
    pot = polib.pofile(pot_path)
    
    # Crear nuevo PO
    po = polib.POFile()
    po.metadata = {
        'Project-Id-Version': 'Nihil Novi 2.0.5',
        'Report-Msgid-Bugs-To': 'https://nihilnovi.xyz',
        'POT-Creation-Date': pot.metadata.get('POT-Creation-Date', '2026-07-05 05:42:00-06:00'),
        'PO-Revision-Date': '2026-07-05 05:42:00-06:00',
        'Last-Translator': 'David Legorreta <david@nihilnovi.xyz>',
        'Language-Team': f'{lang_name} <{lang_code}@nihilnovi.xyz>',
        'Language': lang_code,
        'MIME-Version': '1.0',
        'Content-Type': 'text/plain; charset=UTF-8',
        'Content-Transfer-Encoding': '8bit',
        'X-Generator': 'Nihil Novi Translation Tool (polib)',
        'X-Domain': 'nihilnovi',
        'Plural-Forms': 'nplurals=2; plural=(n != 1);',
    }
    
    translated_count = 0
    untranslated_count = 0
    
    for entry in pot:
        new_entry = polib.POEntry(
            msgid=entry.msgid,
            msgstr=translations.get(entry.msgid, ''),
            msgctxt=entry.msgctxt,
            occurrences=entry.occurrences,
            tcomment=entry.tcomment,
            flags=entry.flags,
        )
        
        # Manejar plural
        if entry.msgid_plural:
            new_entry.msgid_plural = entry.msgid_plural
            # Para plural, buscar ambas formas en el diccionario
            # o dejar vacío si no está
            plural_trans = translations.get(entry.msgid_plural, '')
            new_entry.msgstr_plural = {
                0: translations.get(entry.msgid, ''),
                1: plural_trans if plural_trans else translations.get(entry.msgid, ''),
            }
        
        po.append(new_entry)
        
        if entry.msgid in translations and translations[entry.msgid]:
            translated_count += 1
        else:
            untranslated_count += 1
    
    po.save(output_path)
    print(f"  {lang_code}: {translated_count} traducidas, {untranslated_count} pendientes")
    print(f"  Guardado: {output_path}")
    return po


def compile_mo(po_path, mo_path):
    """Compila un .po a .mo usando polib."""
    po = polib.pofile(po_path)
    po.save_as_mofile(mo_path)
    print(f"  Compilado: {mo_path}")


def main():
    print("=" * 60)
    print("Generando traducciones para Nihil Novi")
    print("=" * 60)
    
    # Asegurar que el directorio existe
    LANG_DIR.mkdir(parents=True, exist_ok=True)
    
    # Verificar que el .pot existe
    if not POT_FILE.exists():
        print(f"ERROR: No se encontró {POT_FILE}")
        return
    
    print(f"\nPOT maestro: {POT_FILE}")
    pot = polib.pofile(POT_FILE)
    print(f"Total strings: {len(pot)}\n")
    
    # Generar EN
    print("--- English (en_US) ---")
    po_en = generate_po(POT_FILE, TRANSLATIONS_EN, 'en_US', 'English', LANG_DIR / 'nihilnovi-en_US.po')
    compile_mo(LANG_DIR / 'nihilnovi-en_US.po', LANG_DIR / 'nihilnovi-en_US.mo')
    
    # Generar IT
    print("\n--- Italiano (it_IT) ---")
    po_it = generate_po(POT_FILE, TRANSLATIONS_IT, 'it_IT', 'Italian', LANG_DIR / 'nihilnovi-it_IT.po')
    compile_mo(LANG_DIR / 'nihilnovi-it_IT.po', LANG_DIR / 'nihilnovi-it_IT.mo')
    
    print("\n" + "=" * 60)
    print("¡Traducciones generadas exitosamente!")
    print("=" * 60)
    print(f"\nArchivos creados en {LANG_DIR}:")
    for f in sorted(LANG_DIR.glob('nihilnovi-*.po')):
        print(f"  - {f.name}")
    for f in sorted(LANG_DIR.glob('nihilnovi-*.mo')):
        size = f.stat().st_size
        print(f"  - {f.name} ({size:,} bytes)")


if __name__ == '__main__':
    main()
