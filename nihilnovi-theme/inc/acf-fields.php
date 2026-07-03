<?php
/**
 * acf-fields.php — Nihil Novi
 * Registra todos los campos editables del homepage via ACF.
 * Se activan automáticamente al instalar el plugin "Advanced Custom Fields".
 *
 * Para editar: WordPress Admin → Páginas → Inicio → Editar
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

/* ═══════════════════════════════════════════════
   GRUPO 1 — HERO
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_hero',
  'title' => '① Hero — Portada principal',
  'fields' => [
    [
      'key'           => 'field_hero_eyebrow',
      'label'         => 'Eyebrow (texto pequeño sobre el título)',
      'name'          => 'hero_eyebrow',
      'type'          => 'text',
      'instructions'  => 'Ejemplo: David Legorreta — nihilnovi.xyz',
      'default_value' => 'David Legorreta — nihilnovi.xyz',
    ],
    [
      'key'           => 'field_hero_title_main',
      'label'         => 'Título — Línea 1',
      'name'          => 'hero_title_main',
      'type'          => 'text',
      'instructions'  => 'Primera línea del H1 grande. Ejemplo: La historia del pensamiento,',
      'default_value' => 'La historia del pensamiento,',
    ],
    [
      'key'           => 'field_hero_title_highlight',
      'label'         => 'Título — Línea 2 (en dorado e itálica)',
      'name'          => 'hero_title_highlight',
      'type'          => 'text',
      'instructions'  => 'Segunda línea, aparece en dorado e itálica. Ejemplo: reconstruida en público.',
      'default_value' => 'reconstruida en público.',
    ],
    [
      'key'           => 'field_hero_subtitle',
      'label'         => 'Subtítulo',
      'name'          => 'hero_subtitle',
      'type'          => 'textarea',
      'rows'          => 3,
      'instructions'  => 'Texto descriptivo bajo el título principal.',
      'default_value' => 'Filosofía. Economía. Matemáticas. Historia. Ciencia. El mapa de 3,000 años de intentar entender cómo funciona el mundo — con todo el material, la bibliografía y las preguntas sin resolver incluidos.',
    ],
    [
      'key'           => 'field_hero_cta1_text',
      'label'         => 'Botón 1 — Texto (dorado)',
      'name'          => 'hero_cta1_text',
      'type'          => 'text',
      'default_value' => 'Explorar las disciplinas',
    ],
    [
      'key'           => 'field_hero_cta1_url',
      'label'         => 'Botón 1 — Link',
      'name'          => 'hero_cta1_url',
      'type'          => 'url',
      'default_value' => '#disciplinas',
    ],
    [
      'key'           => 'field_hero_cta2_text',
      'label'         => 'Botón 2 — Texto (contorno)',
      'name'          => 'hero_cta2_text',
      'type'          => 'text',
      'default_value' => 'El Viaje del Economista',
    ],
    [
      'key'           => 'field_hero_cta2_url',
      'label'         => 'Botón 2 — Link',
      'name'          => 'hero_cta2_url',
      'type'          => 'url',
      'default_value' => '',
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 1,
  'style' => 'seamless',
]);

/* ═══════════════════════════════════════════════
   GRUPO 2 — MANIFIESTO
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_manifesto',
  'title' => '② Manifiesto — La cita central',
  'fields' => [
    [
      'key'           => 'field_manifesto_quote',
      'label'         => 'Cita / Blockquote',
      'name'          => 'manifesto_quote',
      'type'          => 'textarea',
      'rows'          => 5,
      'instructions'  => 'La cita central del sitio. Puedes usar <strong>texto</strong> para negritas y <br> para saltos de línea.',
      'default_value' => 'Los humanos llevamos tres mil años intentando entender cómo funciona el mundo. Este sitio es el <strong>mapa de ese intento.</strong> No el destino. El camino.',
    ],
    [
      'key'           => 'field_manifesto_citation',
      'label'         => 'Autor de la cita',
      'name'          => 'manifesto_citation',
      'type'          => 'text',
      'default_value' => '— David Legorreta, nihilnovi.xyz — 2026',
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 2,
  'style' => 'seamless',
]);

/* ═══════════════════════════════════════════════
   GRUPO 3 — DISCIPLINAS (5 tarjetas)
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_disciplines',
  'title' => '③ Disciplinas — Las 5 tarjetas de portales',
  'fields' => [

    // ── Mensaje informativo
    [
      'key'     => 'field_disc_info',
      'label'   => 'Instrucción',
      'name'    => '',
      'type'    => 'message',
      'message' => '<strong>Edita el nombre, descripción y link de cada una de las 5 disciplinas.</strong><br>El color y código (FIL, ECO...) se asignan automáticamente por orden.',
    ],

    // ── Disciplina 1 (Filosofía)
    ['key' => 'field_disc_1_name',    'label' => 'Disciplina 1 — Nombre',       'name' => 'disc_1_name',    'type' => 'text',    'default_value' => 'Filosofía'],
    ['key' => 'field_disc_1_tagline', 'label' => 'Disciplina 1 — Descripción',  'name' => 'disc_1_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => 'Las preguntas que ninguna otra disciplina se atreve a hacer. El origen de todo lo demás.'],
    ['key' => 'field_disc_1_code',    'label' => 'Disciplina 1 — Código lección','name' => 'disc_1_code',   'type' => 'text',    'default_value' => 'FIL-01'],
    ['key' => 'field_disc_1_url',     'label' => 'Disciplina 1 — Link',          'name' => 'disc_1_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 2 (Economía)
    ['key' => 'field_disc_2_name',    'label' => 'Disciplina 2 — Nombre',       'name' => 'disc_2_name',    'type' => 'text',    'default_value' => 'Economía'],
    ['key' => 'field_disc_2_tagline', 'label' => 'Disciplina 2 — Descripción',  'name' => 'disc_2_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => 'Las consecuencias materiales de las ideas. Cómo se distribuye lo que se produce y por qué.'],
    ['key' => 'field_disc_2_code',    'label' => 'Disciplina 2 — Código lección','name' => 'disc_2_code',   'type' => 'text',    'default_value' => 'ECO-01'],
    ['key' => 'field_disc_2_url',     'label' => 'Disciplina 2 — Link',          'name' => 'disc_2_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 3 (Matemáticas)
    ['key' => 'field_disc_3_name',    'label' => 'Disciplina 3 — Nombre',       'name' => 'disc_3_name',    'type' => 'text',    'default_value' => 'Matemáticas'],
    ['key' => 'field_disc_3_tagline', 'label' => 'Disciplina 3 — Descripción',  'name' => 'disc_3_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => 'El lenguaje de la precisión. No para calcular — para pensar sin margen de ambigüedad.'],
    ['key' => 'field_disc_3_code',    'label' => 'Disciplina 3 — Código lección','name' => 'disc_3_code',   'type' => 'text',    'default_value' => 'MAT-01'],
    ['key' => 'field_disc_3_url',     'label' => 'Disciplina 3 — Link',          'name' => 'disc_3_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 4 (Historia)
    ['key' => 'field_disc_4_name',    'label' => 'Disciplina 4 — Nombre',       'name' => 'disc_4_name',    'type' => 'text',    'default_value' => 'Historia'],
    ['key' => 'field_disc_4_tagline', 'label' => 'Disciplina 4 — Descripción',  'name' => 'disc_4_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => 'El contexto sin el cual las ideas parecen naturales. Nada en el presente es inevitable.'],
    ['key' => 'field_disc_4_code',    'label' => 'Disciplina 4 — Código lección','name' => 'disc_4_code',   'type' => 'text',    'default_value' => 'HIS-01'],
    ['key' => 'field_disc_4_url',     'label' => 'Disciplina 4 — Link',          'name' => 'disc_4_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 5 (Ciencia)
    ['key' => 'field_disc_5_name',    'label' => 'Disciplina 5 — Nombre',       'name' => 'disc_5_name',    'type' => 'text',    'default_value' => 'Ciencia'],
    ['key' => 'field_disc_5_tagline', 'label' => 'Disciplina 5 — Descripción',  'name' => 'disc_5_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => 'El método. Cómo se construye conocimiento que resiste el error y la ideología.'],
    ['key' => 'field_disc_5_code',    'label' => 'Disciplina 5 — Código lección','name' => 'disc_5_code',   'type' => 'text',    'default_value' => 'CIE-01'],
    ['key' => 'field_disc_5_url',     'label' => 'Disciplina 5 — Link',          'name' => 'disc_5_url',    'type' => 'url',     'default_value' => ''],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 3,
  'style' => 'seamless',
]);

/* ═══════════════════════════════════════════════
   GRUPO 4 — EL VIAJE
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_viaje',
  'title' => '④ El Viaje del Economista',
  'fields' => [
    [
      'key'           => 'field_viaje_title',
      'label'         => 'Título de la sección',
      'name'          => 'viaje_title',
      'type'          => 'text',
      'default_value' => 'El Viaje del Economista',
    ],
    [
      'key'           => 'field_viaje_text1',
      'label'         => 'Párrafo 1',
      'name'          => 'viaje_text1',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => 'Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.',
    ],
    [
      'key'           => 'field_viaje_text2',
      'label'         => 'Párrafo 2',
      'name'          => 'viaje_text2',
      'type'          => 'textarea',
      'rows'          => 2,
      'default_value' => 'No es un curso. No hay certificado al final. Es el camino que estoy recorriendo.',
    ],
    [
      'key'           => 'field_viaje_cta_text',
      'label'         => 'Texto del botón',
      'name'          => 'viaje_cta_text',
      'type'          => 'text',
      'default_value' => 'Ver el mapa completo',
    ],
    [
      'key'           => 'field_viaje_cta_url',
      'label'         => 'Link del botón',
      'name'          => 'viaje_cta_url',
      'type'          => 'url',
      'default_value' => '',
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 4,
  'style' => 'seamless',
]);

/* ═══════════════════════════════════════════════
   GRUPO 5 — ABOUT / SOBRE
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_about',
  'title' => '⑤ Sobre — Perfil de autor',
  'fields' => [
    [
      'key'           => 'field_about_name_1',
      'label'         => 'Nombre — Línea 1',
      'name'          => 'about_name_1',
      'type'          => 'text',
      'default_value' => 'David',
    ],
    [
      'key'           => 'field_about_name_2',
      'label'         => 'Nombre — Línea 2 (en dorado)',
      'name'          => 'about_name_2',
      'type'          => 'text',
      'default_value' => 'Legorreta',
    ],
    [
      'key'           => 'field_about_fact_1_k',
      'label'         => 'Dato 1 — Etiqueta',
      'name'          => 'about_fact_1_k',
      'type'          => 'text',
      'default_value' => 'Base',
    ],
    [
      'key'           => 'field_about_fact_1_v',
      'label'         => 'Dato 1 — Valor',
      'name'          => 'about_fact_1_v',
      'type'          => 'text',
      'default_value' => 'México / LATAM',
    ],
    [
      'key'           => 'field_about_fact_2_k',
      'label'         => 'Dato 2 — Etiqueta',
      'name'          => 'about_fact_2_k',
      'type'          => 'text',
      'default_value' => 'Formación',
    ],
    [
      'key'           => 'field_about_fact_2_v',
      'label'         => 'Dato 2 — Valor',
      'name'          => 'about_fact_2_v',
      'type'          => 'text',
      'default_value' => 'Filosofía · Economía',
    ],
    [
      'key'           => 'field_about_fact_3_k',
      'label'         => 'Dato 3 — Etiqueta',
      'name'          => 'about_fact_3_k',
      'type'          => 'text',
      'default_value' => 'Experiencia',
    ],
    [
      'key'           => 'field_about_fact_3_v',
      'label'         => 'Dato 3 — Valor',
      'name'          => 'about_fact_3_v',
      'type'          => 'text',
      'default_value' => 'BPO · Retail · Tech',
    ],
    [
      'key'           => 'field_about_fact_4_k',
      'label'         => 'Dato 4 — Etiqueta',
      'name'          => 'about_fact_4_k',
      'type'          => 'text',
      'default_value' => 'Idiomas',
    ],
    [
      'key'           => 'field_about_fact_4_v',
      'label'         => 'Dato 4 — Valor',
      'name'          => 'about_fact_4_v',
      'type'          => 'text',
      'default_value' => 'ES · EN · IT (en curso)',
    ],
    [
      'key'           => 'field_about_text_1',
      'label'         => 'Párrafo 1',
      'name'          => 'about_text_1',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => 'Estudié filosofía. Después trabajé catorce años en operaciones — BPO, retail, logística, tecnología. Cash App, TikTok, Walmart, Avis. Gestión de equipos, entrega de resultados, problemas estructurales disfrazados de problemas personales.',
    ],
    [
      'key'           => 'field_about_text_2',
      'label'         => 'Párrafo 2',
      'name'          => 'about_text_2',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => 'En algún momento me di cuenta de que las preguntas que me importaban eran preguntas económicas. La filosofía me enseñó a preguntar. La economía, si se estudia en serio, enseña a responder con evidencia.',
    ],
    [
      'key'           => 'field_about_text_3',
      'label'         => 'Párrafo 3',
      'name'          => 'about_text_3',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => 'Nihil Novi es donde hago eso en público. Sin pretender que ya llegué. Sin vender certezas que no tengo.',
    ],
    [
      'key'           => 'field_about_cta_text',
      'label'         => 'Botón — Texto',
      'name'          => 'about_cta_text',
      'type'          => 'text',
      'default_value' => 'Leer más sobre el proyecto',
    ],
    [
      'key'           => 'field_about_cta_url',
      'label'         => 'Botón — Link',
      'name'          => 'about_cta_url',
      'type'          => 'url',
      'default_value' => '',
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 5,
  'style' => 'seamless',
]);

/* ═══════════════════════════════════════════════
   GRUPO 6 — NEWSLETTER
═══════════════════════════════════════════════ */
acf_add_local_field_group([
  'key'   => 'group_nn_newsletter',
  'title' => '⑥ Newsletter',
  'fields' => [
    [
      'key'           => 'field_nl_title_1',
      'label'         => 'Título — Línea 1',
      'name'          => 'nl_title_1',
      'type'          => 'text',
      'default_value' => 'Una entrega por semana.',
    ],
    [
      'key'           => 'field_nl_title_2',
      'label'         => 'Título — Línea 2 (en dorado)',
      'name'          => 'nl_title_2',
      'type'          => 'text',
      'default_value' => 'Sin algoritmos.',
    ],
    [
      'key'           => 'field_nl_body',
      'label'         => 'Texto descriptivo',
      'name'          => 'nl_body',
      'type'          => 'textarea',
      'rows'          => 2,
      'default_value' => 'Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.',
    ],
    [
      'key'           => 'field_nl_note',
      'label'         => 'Nota pequeña bajo el formulario',
      'name'          => 'nl_note',
      'type'          => 'text',
      'default_value' => 'Sin spam · Sin venta de datos · Baja cuando quieras',
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 6,
  'style' => 'seamless',
]);
