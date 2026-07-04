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
  'title' => __( '① Hero — Portada principal', 'nihilnovi' ),
  'fields' => [
    [
      'key'           => 'field_hero_eyebrow',
      'label'         => __( 'Eyebrow (texto pequeño sobre el título)', 'nihilnovi' ),
      'name'          => 'hero_eyebrow',
      'type'          => 'text',
      'instructions'  => __( 'Ejemplo: David Legorreta — nihilnovi.xyz', 'nihilnovi' ),
      'default_value' => __( 'David Legorreta — nihilnovi.xyz', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_title_main',
      'label'         => __( 'Título — Línea 1', 'nihilnovi' ),
      'name'          => 'hero_title_main',
      'type'          => 'text',
      'instructions'  => __( 'Primera línea del H1 grande. Ejemplo: La historia del pensamiento,', 'nihilnovi' ),
      'default_value' => __( 'La historia del pensamiento,', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_title_highlight',
      'label'         => __( 'Título — Línea 2 (en dorado e itálica)', 'nihilnovi' ),
      'name'          => 'hero_title_highlight',
      'type'          => 'text',
      'instructions'  => __( 'Segunda línea, aparece en dorado e itálica. Ejemplo: reconstruida en público.', 'nihilnovi' ),
      'default_value' => __( 'reconstruida en público.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_subtitle',
      'label'         => __( 'Subtítulo', 'nihilnovi' ),
      'name'          => 'hero_subtitle',
      'type'          => 'textarea',
      'rows'          => 3,
      'instructions'  => __( 'Texto descriptivo bajo el título principal.', 'nihilnovi' ),
      'default_value' => __( 'Filosofía. Economía. Matemáticas. Historia. Ciencia. El mapa de 3,000 años de intentar entender cómo funciona el mundo — con todo el material, la bibliografía y las preguntas sin resolver incluidos.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_cta1_text',
      'label'         => __( 'Botón 1 — Texto (dorado)', 'nihilnovi' ),
      'name'          => 'hero_cta1_text',
      'type'          => 'text',
      'default_value' => __( 'Explorar las disciplinas', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_cta1_url',
      'label'         => __( 'Botón 1 — Link', 'nihilnovi' ),
      'name'          => 'hero_cta1_url',
      'type'          => 'url',
      'default_value' => '#disciplinas',
    ],
    [
      'key'           => 'field_hero_cta2_text',
      'label'         => __( 'Botón 2 — Texto (contorno)', 'nihilnovi' ),
      'name'          => 'hero_cta2_text',
      'type'          => 'text',
      'default_value' => __( 'El Viaje del Economista', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_hero_cta2_url',
      'label'         => __( 'Botón 2 — Link', 'nihilnovi' ),
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
  'title' => __( '② Manifiesto — La cita central', 'nihilnovi' ),
  'fields' => [
    [
      'key'           => 'field_manifesto_quote',
      'label'         => __( 'Cita / Blockquote', 'nihilnovi' ),
      'name'          => 'manifesto_quote',
      'type'          => 'textarea',
      'rows'          => 5,
      'instructions'  => __( 'La cita central del sitio. Puedes usar <strong>texto</strong> para negritas y <br> para saltos de línea.', 'nihilnovi' ),
      'default_value' => __( 'Los humanos llevamos tres mil años intentando entender cómo funciona el mundo. Este sitio es el <strong>mapa de ese intento.</strong> No el destino. El camino.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_manifesto_citation',
      'label'         => __( 'Autor de la cita', 'nihilnovi' ),
      'name'          => 'manifesto_citation',
      'type'          => 'text',
      'default_value' => __( '— David Legorreta, nihilnovi.xyz — 2026', 'nihilnovi' ),
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
  'title' => __( '③ Disciplinas — Las 5 tarjetas de portales', 'nihilnovi' ),
  'fields' => [

    // ── Mensaje informativo
    [
      'key'     => 'field_disc_info',
      'label'   => __( 'Instrucción', 'nihilnovi' ),
      'name'    => '',
      'type'    => 'message',
      'message' => __( '<strong>Edita el nombre, descripción y link de cada una de las 5 disciplinas.</strong><br>El color y código (FIL, ECO...) se asignan automáticamente por orden.', 'nihilnovi' ),
    ],

    // ── Disciplina 1 (Filosofía)
    ['key' => 'field_disc_1_name',    'label' => __( 'Disciplina 1 — Nombre', 'nihilnovi' ),       'name' => 'disc_1_name',    'type' => 'text',    'default_value' => __( 'Filosofía', 'nihilnovi' )],
    ['key' => 'field_disc_1_tagline', 'label' => __( 'Disciplina 1 — Descripción', 'nihilnovi' ),  'name' => 'disc_1_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => __( 'Las preguntas que ninguna otra disciplina se atreve a hacer. El origen de todo lo demás.', 'nihilnovi' )],
    ['key' => 'field_disc_1_code',    'label' => __( 'Disciplina 1 — Código lección', 'nihilnovi' ),'name' => 'disc_1_code',   'type' => 'text',    'default_value' => 'FIL-01'],
    ['key' => 'field_disc_1_url',     'label' => __( 'Disciplina 1 — Link', 'nihilnovi' ),          'name' => 'disc_1_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 2 (Economía)
    ['key' => 'field_disc_2_name',    'label' => __( 'Disciplina 2 — Nombre', 'nihilnovi' ),       'name' => 'disc_2_name',    'type' => 'text',    'default_value' => __( 'Economía', 'nihilnovi' )],
    ['key' => 'field_disc_2_tagline', 'label' => __( 'Disciplina 2 — Descripción', 'nihilnovi' ),  'name' => 'disc_2_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => __( 'Las consecuencias materiales de las ideas. Cómo se distribuye lo que se produce y por qué.', 'nihilnovi' )],
    ['key' => 'field_disc_2_code',    'label' => __( 'Disciplina 2 — Código lección', 'nihilnovi' ),'name' => 'disc_2_code',   'type' => 'text',    'default_value' => 'ECO-01'],
    ['key' => 'field_disc_2_url',     'label' => __( 'Disciplina 2 — Link', 'nihilnovi' ),          'name' => 'disc_2_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 3 (Matemáticas)
    ['key' => 'field_disc_3_name',    'label' => __( 'Disciplina 3 — Nombre', 'nihilnovi' ),       'name' => 'disc_3_name',    'type' => 'text',    'default_value' => __( 'Matemáticas', 'nihilnovi' )],
    ['key' => 'field_disc_3_tagline', 'label' => __( 'Disciplina 3 — Descripción', 'nihilnovi' ),  'name' => 'disc_3_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => __( 'El lenguaje de la precisión. No para calcular — para pensar sin margen de ambigüedad.', 'nihilnovi' )],
    ['key' => 'field_disc_3_code',    'label' => __( 'Disciplina 3 — Código lección', 'nihilnovi' ),'name' => 'disc_3_code',   'type' => 'text',    'default_value' => 'MAT-01'],
    ['key' => 'field_disc_3_url',     'label' => __( 'Disciplina 3 — Link', 'nihilnovi' ),          'name' => 'disc_3_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 4 (Historia)
    ['key' => 'field_disc_4_name',    'label' => __( 'Disciplina 4 — Nombre', 'nihilnovi' ),       'name' => 'disc_4_name',    'type' => 'text',    'default_value' => __( 'Historia', 'nihilnovi' )],
    ['key' => 'field_disc_4_tagline', 'label' => __( 'Disciplina 4 — Descripción', 'nihilnovi' ),  'name' => 'disc_4_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => __( 'El contexto sin el cual las ideas parecen naturales. Nada en el presente es inevitable.', 'nihilnovi' )],
    ['key' => 'field_disc_4_code',    'label' => __( 'Disciplina 4 — Código lección', 'nihilnovi' ),'name' => 'disc_4_code',   'type' => 'text',    'default_value' => 'HIS-01'],
    ['key' => 'field_disc_4_url',     'label' => __( 'Disciplina 4 — Link', 'nihilnovi' ),          'name' => 'disc_4_url',    'type' => 'url',     'default_value' => ''],

    // ── Disciplina 5 (Ciencia)
    ['key' => 'field_disc_5_name',    'label' => __( 'Disciplina 5 — Nombre', 'nihilnovi' ),       'name' => 'disc_5_name',    'type' => 'text',    'default_value' => __( 'Ciencia', 'nihilnovi' )],
    ['key' => 'field_disc_5_tagline', 'label' => __( 'Disciplina 5 — Descripción', 'nihilnovi' ),  'name' => 'disc_5_tagline', 'type' => 'textarea','rows' => 2, 'default_value' => __( 'El método. Cómo se construye conocimiento que resiste el error y la ideología.', 'nihilnovi' )],
    ['key' => 'field_disc_5_code',    'label' => __( 'Disciplina 5 — Código lección', 'nihilnovi' ),'name' => 'disc_5_code',   'type' => 'text',    'default_value' => 'CIE-01'],
    ['key' => 'field_disc_5_url',     'label' => __( 'Disciplina 5 — Link', 'nihilnovi' ),          'name' => 'disc_5_url',    'type' => 'url',     'default_value' => ''],
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
  'title' => __( '④ El Viaje del Economista', 'nihilnovi' ),
  'fields' => [
    [
      'key'           => 'field_viaje_title',
      'label'         => __( 'Título de la sección', 'nihilnovi' ),
      'name'          => 'viaje_title',
      'type'          => 'text',
      'default_value' => __( 'El Viaje del Economista', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_viaje_text1',
      'label'         => __( 'Párrafo 1', 'nihilnovi' ),
      'name'          => 'viaje_text1',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => __( 'Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_viaje_text2',
      'label'         => __( 'Párrafo 2', 'nihilnovi' ),
      'name'          => 'viaje_text2',
      'type'          => 'textarea',
      'rows'          => 2,
      'default_value' => __( 'No es un curso. No hay certificado al final. Es el camino que estoy recorriendo.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_viaje_cta_text',
      'label'         => __( 'Texto del botón', 'nihilnovi' ),
      'name'          => 'viaje_cta_text',
      'type'          => 'text',
      'default_value' => __( 'Ver el mapa completo', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_viaje_cta_url',
      'label'         => __( 'Link del botón', 'nihilnovi' ),
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
  'title' => __( '⑤ Sobre — Perfil de autor', 'nihilnovi' ),
  'fields' => [
    [
      'key'           => 'field_about_name_1',
      'label'         => __( 'Nombre — Línea 1', 'nihilnovi' ),
      'name'          => 'about_name_1',
      'type'          => 'text',
      'default_value' => __( 'David', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_name_2',
      'label'         => __( 'Nombre — Línea 2 (en dorado)', 'nihilnovi' ),
      'name'          => 'about_name_2',
      'type'          => 'text',
      'default_value' => __( 'Legorreta', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_1_k',
      'label'         => __( 'Dato 1 — Etiqueta', 'nihilnovi' ),
      'name'          => 'about_fact_1_k',
      'type'          => 'text',
      'default_value' => __( 'Base', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_1_v',
      'label'         => __( 'Dato 1 — Valor', 'nihilnovi' ),
      'name'          => 'about_fact_1_v',
      'type'          => 'text',
      'default_value' => __( 'México / LATAM', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_2_k',
      'label'         => __( 'Dato 2 — Etiqueta', 'nihilnovi' ),
      'name'          => 'about_fact_2_k',
      'type'          => 'text',
      'default_value' => __( 'Formación', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_2_v',
      'label'         => __( 'Dato 2 — Valor', 'nihilnovi' ),
      'name'          => 'about_fact_2_v',
      'type'          => 'text',
      'default_value' => __( 'Filosofía · Economía', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_3_k',
      'label'         => __( 'Dato 3 — Etiqueta', 'nihilnovi' ),
      'name'          => 'about_fact_3_k',
      'type'          => 'text',
      'default_value' => __( 'Experiencia', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_3_v',
      'label'         => __( 'Dato 3 — Valor', 'nihilnovi' ),
      'name'          => 'about_fact_3_v',
      'type'          => 'text',
      'default_value' => __( 'BPO · Retail · Tech', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_4_k',
      'label'         => __( 'Dato 4 — Etiqueta', 'nihilnovi' ),
      'name'          => 'about_fact_4_k',
      'type'          => 'text',
      'default_value' => __( 'Idiomas', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_fact_4_v',
      'label'         => __( 'Dato 4 — Valor', 'nihilnovi' ),
      'name'          => 'about_fact_4_v',
      'type'          => 'text',
      'default_value' => __( 'ES · EN · IT (en curso)', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_text_1',
      'label'         => __( 'Párrafo 1', 'nihilnovi' ),
      'name'          => 'about_text_1',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => __( 'Estudié filosofía. Después trabajé catorce años en operaciones — BPO, retail, logística, tecnología. Cash App, TikTok, Walmart, Avis. Gestión de equipos, entrega de resultados, problemas estructurales disfrazados de problemas personales.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_text_2',
      'label'         => __( 'Párrafo 2', 'nihilnovi' ),
      'name'          => 'about_text_2',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => __( 'En algún momento me di cuenta de que las preguntas que me importaban eran preguntas económicas. La filosofía me enseñó a preguntar. La economía, si se estudia en serio, enseña a responder con evidencia.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_text_3',
      'label'         => __( 'Párrafo 3', 'nihilnovi' ),
      'name'          => 'about_text_3',
      'type'          => 'textarea',
      'rows'          => 3,
      'default_value' => __( 'Nihil Novi es donde hago eso en público. Sin pretender que ya llegué. Sin vender certezas que no tengo.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_cta_text',
      'label'         => __( 'Botón — Texto', 'nihilnovi' ),
      'name'          => 'about_cta_text',
      'type'          => 'text',
      'default_value' => __( 'Leer más sobre el proyecto', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_about_cta_url',
      'label'         => __( 'Botón — Link', 'nihilnovi' ),
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
  'title' => __( '⑥ Newsletter', 'nihilnovi' ),
  'fields' => [
    [
      'key'           => 'field_nl_title_1',
      'label'         => __( 'Título — Línea 1', 'nihilnovi' ),
      'name'          => 'nl_title_1',
      'type'          => 'text',
      'default_value' => __( 'Una entrega por semana.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_nl_title_2',
      'label'         => __( 'Título — Línea 2 (en dorado)', 'nihilnovi' ),
      'name'          => 'nl_title_2',
      'type'          => 'text',
      'default_value' => __( 'Sin algoritmos.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_nl_body',
      'label'         => __( 'Texto descriptivo', 'nihilnovi' ),
      'name'          => 'nl_body',
      'type'          => 'textarea',
      'rows'          => 2,
      'default_value' => __( 'Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.', 'nihilnovi' ),
    ],
    [
      'key'           => 'field_nl_note',
      'label'         => __( 'Nota pequeña bajo el formulario', 'nihilnovi' ),
      'name'          => 'nl_note',
      'type'          => 'text',
      'default_value' => __( 'Sin spam · Sin venta de datos · Baja cuando quieras', 'nihilnovi' ),
    ],
  ],
  'location' => [[[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ]]],
  'menu_order' => 6,
  'style' => 'seamless',
]);
