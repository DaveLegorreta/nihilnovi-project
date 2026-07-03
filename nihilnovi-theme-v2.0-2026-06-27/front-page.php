<?php
/**
 * front-page.php — Nihil Novi
 * Homepage totalmente editable via ACF (Advanced Custom Fields).
 * Todos los textos tienen valores por defecto — funciona sin ACF también.
 */
get_header();

// ── HERO ──────────────────────────────────────────
$hero_eyebrow    = get_field('hero_eyebrow')         ?: 'David Legorreta — nihilnovi.xyz';
$hero_main       = get_field('hero_title_main')      ?: 'La historia del pensamiento,';
$hero_hi         = get_field('hero_title_highlight') ?: 'reconstruida en público.';
$hero_sub        = get_field('hero_subtitle')        ?: 'Filosofía. Economía. Matemáticas. Historia. Ciencia. El mapa de 3,000 años de intentar entender cómo funciona el mundo — con todo el material, la bibliografía y las preguntas sin resolver incluidos.';
$hero_cta1_text  = get_field('hero_cta1_text')       ?: 'Explorar las disciplinas';
$hero_cta1_url   = get_field('hero_cta1_url')        ?: '#disciplinas';
$hero_cta2_text  = get_field('hero_cta2_text')       ?: 'El Viaje del Economista';
$hero_cta2_url   = get_field('hero_cta2_url')        ?: home_url('/viaje');

// ── MANIFIESTO ────────────────────────────────────
$mani_quote      = get_field('manifesto_quote')      ?: '"Los humanos llevamos tres mil años intentando entender cómo funciona el mundo. Este sitio es el <strong>mapa de ese intento.</strong> No el destino. El camino."';
$mani_cite       = get_field('manifesto_citation')   ?: '— David Legorreta, nihilnovi.xyz — ' . date('Y');

// ── DISCIPLINAS ───────────────────────────────────
$disciplines = [
  [ 'class'=>'fil','numeral'=>'I',  'code_prefix'=>'FIL',
    'name'    => get_field('disc_1_name')    ?: 'Filosofía',
    'tagline' => get_field('disc_1_tagline') ?: 'Las preguntas que ninguna otra disciplina se atreve a hacer. El origen de todo lo demás.',
    'code'    => get_field('disc_1_code')    ?: 'FIL-01',
    'url'     => get_field('disc_1_url')     ?: home_url('/pensamiento/filosofia'),
  ],
  [ 'class'=>'eco','numeral'=>'II', 'code_prefix'=>'ECO',
    'name'    => get_field('disc_2_name')    ?: 'Economía',
    'tagline' => get_field('disc_2_tagline') ?: 'Las consecuencias materiales de las ideas. Cómo se distribuye lo que se produce y por qué.',
    'code'    => get_field('disc_2_code')    ?: 'ECO-01',
    'url'     => get_field('disc_2_url')     ?: home_url('/pensamiento/economia'),
  ],
  [ 'class'=>'mat','numeral'=>'III','code_prefix'=>'MAT',
    'name'    => get_field('disc_3_name')    ?: 'Matemáticas',
    'tagline' => get_field('disc_3_tagline') ?: 'El lenguaje de la precisión. No para calcular — para pensar sin margen de ambigüedad.',
    'code'    => get_field('disc_3_code')    ?: 'MAT-01',
    'url'     => get_field('disc_3_url')     ?: home_url('/pensamiento/matematicas'),
  ],
  [ 'class'=>'his','numeral'=>'IV', 'code_prefix'=>'HIS',
    'name'    => get_field('disc_4_name')    ?: 'Historia',
    'tagline' => get_field('disc_4_tagline') ?: 'El contexto sin el cual las ideas parecen naturales. Nada en el presente es inevitable.',
    'code'    => get_field('disc_4_code')    ?: 'HIS-01',
    'url'     => get_field('disc_4_url')     ?: home_url('/pensamiento/historia'),
  ],
  [ 'class'=>'cie','numeral'=>'V',  'code_prefix'=>'CIE',
    'name'    => get_field('disc_5_name')    ?: 'Ciencia',
    'tagline' => get_field('disc_5_tagline') ?: 'El método. Cómo se construye conocimiento que resiste el error y la ideología.',
    'code'    => get_field('disc_5_code')    ?: 'CIE-01',
    'url'     => get_field('disc_5_url')     ?: home_url('/pensamiento/ciencia'),
  ],
];

// ── EL VIAJE ──────────────────────────────────────
$viaje_title    = get_field('viaje_title')    ?: 'El Viaje del Economista';
$viaje_text1    = get_field('viaje_text1')    ?: 'Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.';
$viaje_text2    = get_field('viaje_text2')    ?: 'No es un curso. No hay certificado al final. Es el camino que estoy recorriendo.';
$viaje_cta_text = get_field('viaje_cta_text') ?: 'Ver el mapa completo';
$viaje_cta_url  = get_field('viaje_cta_url')  ?: home_url('/viaje');

// ── ABOUT ─────────────────────────────────────────
$about_name_1   = get_field('about_name_1')   ?: 'David';
$about_name_2   = get_field('about_name_2')   ?: 'Legorreta';
$about_facts = [
  [ get_field('about_fact_1_k') ?: 'Base',       get_field('about_fact_1_v') ?: 'México / LATAM' ],
  [ get_field('about_fact_2_k') ?: 'Formación',  get_field('about_fact_2_v') ?: 'Filosofía · Economía' ],
  [ get_field('about_fact_3_k') ?: 'Experiencia',get_field('about_fact_3_v') ?: 'BPO · Retail · Tech' ],
  [ get_field('about_fact_4_k') ?: 'Idiomas',    get_field('about_fact_4_v') ?: 'ES · EN · IT (en curso)' ],
];
$about_text_1   = get_field('about_text_1')   ?: 'Vengo de la filosofía. Mi trayectoria no es lineal: he navegado entre la docencia y la investigación, la gestión cultural pública, y la implementación de tecnología en retail. Desde 2020, diseño estrategia y operaciones en BPO y tecnología. He colaborado en proyectos de cine, de impacto social y viví la economía colaborativa desde el asfalto. Actualmente, decidí formalizar mis estudios en economía, contaduría y administración.';
$about_text_2   = get_field('about_text_2')   ?: 'Me di cuenta de que las preguntas que me obsesionan no respetan las fronteras de las disciplinas tradicionales. La filosofía me enseñó a formular las preguntas. La economía, si se estudia en serio, enseña a integrar la evidencia para responderlas.';
$about_text_3   = get_field('about_text_3')   ?: 'Nihil Novi es donde hago eso en público. Sin pretender que ya llegué a ningún destino. Sin vender certezas que no tengo.';
$about_cta_text = get_field('about_cta_text') ?: 'Leer más sobre el proyecto';
$about_cta_url  = get_field('about_cta_url')  ?: home_url('/sobre');

// ── NEWSLETTER ────────────────────────────────────
$nl_title_1 = get_field('nl_title_1') ?: 'Una entrega por semana.';
$nl_title_2 = get_field('nl_title_2') ?: 'Sin algoritmos.';
$nl_body    = get_field('nl_body')    ?: 'Artículos, lecciones y el material de estudio de esa semana. Directo. Sin curation de plataforma.';
$nl_note    = get_field('nl_note')    ?: 'Sin spam · Sin venta de datos · Baja cuando quieras';
?>

<!-- ══════════ HERO ══════════ -->
<section class="nn-hero" id="inicio" aria-label="Portada">
  <div class="blob blob-1" aria-hidden="true"></div>
  <div class="blob blob-2" aria-hidden="true"></div>
  <div class="blob blob-3" aria-hidden="true"></div>
  <div class="hero-grid" aria-hidden="true"></div>

  <div class="hero-content">
    <div class="hero-eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></div>

    <h1>
      <?php echo esc_html( $hero_main ); ?><br>
      <em><?php echo esc_html( $hero_hi ); ?></em>
    </h1>

    <p class="hero-sub"><?php echo esc_html( $hero_sub ); ?></p>

    <div class="hero-disciplines">
      <?php foreach ( $disciplines as $d ) : ?>
        <a href="<?php echo esc_url( $d['url'] ); ?>" class="disc-pill <?php echo esc_attr( $d['class'] ); ?>">
          <?php echo esc_html( $d['name'] ); ?>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="hero-ctas">
      <a href="<?php echo esc_url( $hero_cta1_url ); ?>" class="btn btn-gold"><?php echo esc_html( $hero_cta1_text ); ?></a>
      <a href="<?php echo esc_url( $hero_cta2_url ); ?>" class="btn btn-outline"><?php echo esc_html( $hero_cta2_text ); ?></a>
    </div>
  </div>

  <!-- Gráfico Geométrico Flotante (Lado Derecho) -->
  <div class="hero-graphic" aria-hidden="true">
    <svg viewBox="0 0 500 500" fill="none" stroke="var(--gold)" class="nn-geometry-svg">
      <!-- Concentric Orbit Circles -->
      <circle cx="250" cy="250" r="210" stroke-width="0.6" opacity="0.25" class="geo-ring-1"/>
      <circle cx="250" cy="250" r="170" stroke-width="0.6" opacity="0.25" class="geo-ring-1"/>
      <circle cx="250" cy="250" r="130" stroke-width="0.6" opacity="0.25" class="geo-ring-1"/>
      <circle cx="250" cy="250" r="90" stroke-width="0.6" opacity="0.25" class="geo-ring-1"/>
      <circle cx="250" cy="250" r="50" stroke-width="0.6" opacity="0.25" class="geo-ring-1"/>
      
      <!-- Astronomical Axes -->
      <line x1="250" y1="20" x2="250" y2="480" stroke-width="0.4" stroke-dasharray="2 6" opacity="0.2" class="geo-ring-1"/>
      <line x1="20" y1="250" x2="480" y2="250" stroke-width="0.4" stroke-dasharray="2 6" opacity="0.2" class="geo-ring-1"/>
      
      <line x1="87" y1="87" x2="413" y2="413" stroke-width="0.4" stroke-dasharray="2 6" opacity="0.15" class="geo-ring-1"/>
      <line x1="87" y1="413" x2="413" y2="87" stroke-width="0.4" stroke-dasharray="2 6" opacity="0.15" class="geo-ring-1"/>

      <!-- Group of Concentric Orbits (Rotating Together) -->
      <g class="geo-ring-2">
        <ellipse cx="250" cy="250" rx="210" ry="68" transform="rotate(30 250 250)" stroke-width="0.6" opacity="0.3"/>
        <ellipse cx="250" cy="250" rx="210" ry="68" transform="rotate(-30 250 250)" stroke-width="0.6" opacity="0.3"/>
        <ellipse cx="250" cy="250" rx="210" ry="68" transform="rotate(90 250 250)" stroke-width="0.6" opacity="0.18"/>
      </g>
      
      <!-- Celestial Constellation Nodes -->
      <circle cx="250" cy="40" r="3.5" fill="var(--gold)" opacity="0.75"/>
      <circle cx="433" cy="144" r="3.5" fill="var(--gold)" opacity="0.75"/>
      <circle cx="433" cy="356" r="3.5" fill="var(--gold)" opacity="0.75"/>
      <circle cx="250" cy="460" r="3.5" fill="var(--gold)" opacity="0.75"/>
      <circle cx="102" cy="102" r="2.5" fill="var(--gold)" opacity="0.5"/>
      <circle cx="398" cy="398" r="2.5" fill="var(--gold)" opacity="0.5"/>
      
      <!-- Solar Core -->
      <circle cx="250" cy="250" r="4.5" fill="var(--gold)" opacity="0.9" class="geo-ring-3"/>
    </svg>
  </div>

  <!-- Segundo Gráfico: Sólidos Platónicos Flotantes (Fondo Lado Izquierdo) -->
  <div class="hero-graphic-2" aria-hidden="true">
    <svg viewBox="0 0 500 500" fill="none" stroke="var(--gold)" stroke-width="0.6" class="nn-platonic-svg">
      <polygon points="250,90 402,200 344,380 156,380 98,200" opacity="0.5"/>
      <polygon points="250,160 335,220 303,320 197,320 165,220" opacity="0.8"/>
      <line x1="250" y1="90" x2="250" y2="160"/>
      <line x1="402" y1="200" x2="335" y2="220"/>
      <line x1="344" y1="380" x2="303" y2="320"/>
      <line x1="156" y1="380" x2="197" y2="320"/>
      <line x1="98" y1="200" x2="165" y2="220"/>
    </svg>
  </div>

  <div class="hero-scroll" aria-hidden="true">Scroll</div>
</section>

<!-- ══════════ MANIFIESTO ══════════ -->
<section class="nn-manifesto fade" aria-label="Manifiesto">
  <div class="manifesto-inner">
    <div class="gold-bar" aria-hidden="true"></div>
    <blockquote><?php echo wp_kses( $mani_quote, ['strong'=>[],'br'=>[],'em'=>[]] ); ?></blockquote>
    <div class="gold-bar" aria-hidden="true"></div>
    <cite><?php echo esc_html( $mani_cite ); ?></cite>
  </div>
</section>

<!-- ══════════ DISCIPLINAS ══════════ -->
<section class="nn-section" id="disciplinas" aria-label="Las cinco disciplinas">
  <div class="section-inner">
    <div class="s-header">
      <div>
        <div class="s-eyebrow">Los territorios</div>
        <h2 class="s-title">Cinco disciplinas.<br>Un solo proyecto.</h2>
        <p class="s-desc">Cada disciplina tiene su propia ruta de estudio, sus lecciones y su bibliografía.</p>
      </div>
    </div>

    <div class="disciplines-grid">
      <?php foreach ( $disciplines as $d ) : ?>
      <a href="<?php echo esc_url( $d['url'] ); ?>" class="disc-card <?php echo esc_attr( $d['class'] ); ?> fade">
        <div class="disc-bg-num" aria-hidden="true"><?php echo esc_html( $d['numeral'] ); ?></div>
        <div class="disc-icon"><?php echo esc_html( $d['code_prefix'] ); ?> ·</div>
        <h3 class="disc-name"><?php echo esc_html( $d['name'] ); ?></h3>
        <p class="disc-tagline"><?php echo esc_html( $d['tagline'] ); ?></p>
        <div class="disc-meta">
          <span class="disc-count"><?php echo esc_html( $d['code'] ); ?></span>
          <span class="disc-arrow" aria-hidden="true">→</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════ ARTÍCULOS + LECCIONES (Tabs) ══════════ -->
<section class="nn-section fade" id="contenido" aria-label="Artículos y lecciones recientes">
  <div class="section-inner">
    <div class="s-header">
      <div>
        <div class="s-eyebrow">Lo más reciente</div>
        <h2 class="s-title">Artículos y <em>Lecciones</em></h2>
      </div>
      <a href="<?php echo esc_url( home_url('/blog') ); ?>" class="s-link">Ver todo el archivo</a>
    </div>

    <div class="tabs-header" role="tablist">
      <button class="tab-btn active" data-tab="articulos" role="tab" aria-selected="true">Artículos</button>
      <button class="tab-btn" data-tab="lecciones" role="tab" aria-selected="false">Lecciones</button>
    </div>

    <!-- Tab: Artículos -->
    <div class="tab-content active" id="tab-articulos" role="tabpanel">
      <div class="articles-row">
        <?php
        $art_query = new WP_Query([
          'posts_per_page' => 3,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
          'tax_query'      => [['taxonomy'=>'category','field'=>'slug','terms'=>'lecciones','operator'=>'NOT IN']],
        ]);
        $i = 0;
        if ( $art_query->have_posts() ) :
          while ( $art_query->have_posts() ) : $art_query->the_post();
            $num = get_post_meta(get_the_ID(),'_article_num',true) ?: str_pad($i,2,'0',STR_PAD_LEFT);
            $cat = get_the_category(); $cat_name = $cat ? esc_html($cat[0]->name) : 'El Viaje';
        ?>
        <article class="art-card">
          <div class="art-meta">
            <span class="art-num"><?php echo esc_html($num); ?></span>
            <span class="art-cat"><?php echo $cat_name; ?></span>
            <span class="art-date"><?php echo get_the_date('j M Y'); ?></span>
          </div>
          <a href="<?php the_permalink(); ?>" class="art-title"><?php the_title(); ?></a>
          <p class="art-excerpt"><?php echo wp_trim_words(get_the_excerpt(),22); ?></p>
          <a href="<?php the_permalink(); ?>" class="art-cta">Leer</a>
        </article>
        <?php $i++; endwhile; wp_reset_postdata();
        else: // Fallback ?>
        <article class="art-card">
          <div class="art-meta"><span class="art-num">00</span><span class="art-cat">El Viaje</span><span class="art-date">25 mayo 2026</span></div>
          <span class="art-title">25 de mayo de 2026. Hoy empieza.</span>
          <p class="art-excerpt">No el día en que llegué a algún destino. El día en que decidí hacer el camino en público.</p>
          <span class="art-cta">Pronto</span>
        </article>
        <article class="art-card">
          <div class="art-meta"><span class="art-num">01</span><span class="art-cat">Economía</span><span class="art-date">25 mayo 2026</span></div>
          <span class="art-title">Si te dijeron que la economía es la ciencia de la oferta y la demanda, te timaron.</span>
          <p class="art-excerpt">La economía no empezó con Adam Smith en 1776. Empezó con los griegos.</p>
          <span class="art-cta">Pronto</span>
        </article>
        <article class="art-card">
          <div class="art-meta"><span class="art-num">02</span><span class="art-cat" style="color:var(--ivory-3)">Próximamente</span><span class="art-date">Jun 2026</span></div>
          <span class="art-title" style="color:var(--ivory-3)">Las tres preguntas que toda economía tiene que responder.</span>
          <p class="art-excerpt">Qué producir. Cómo producirlo. Para quién.</p>
          <span class="art-cta" style="color:var(--ivory-3)">Próximamente</span>
        </article>
        <?php endif; ?>
      </div>
    </div>

    <!-- Tab: Lecciones -->
    <div class="tab-content" id="tab-lecciones" role="tabpanel">
      <div class="lessons-grid">
        <?php
        $lesson_query = new WP_Query([
          'posts_per_page' => 6,
          'post_status'    => 'publish',
          'category_name'  => 'lecciones',
        ]);
        if ( $lesson_query->have_posts() ) :
          while ( $lesson_query->have_posts() ) : $lesson_query->the_post();
            $code = get_post_meta(get_the_ID(),'_lesson_code',true) ?: 'NN-01';
            $time = get_post_meta(get_the_ID(),'_read_time',true) ?: '3 min';
        ?>
        <a href="<?php the_permalink(); ?>" class="lesson-card">
          <div class="lesson-top"><span class="lesson-code"><?php echo esc_html($code); ?></span><span class="lesson-time"><?php echo esc_html($time); ?></span></div>
          <span class="lesson-title"><?php the_title(); ?></span>
          <p class="lesson-summary"><?php echo wp_trim_words(get_the_excerpt(),18); ?></p>
          <span class="lesson-arrow">→</span>
        </a>
        <?php endwhile; wp_reset_postdata();
        else:
          $fl = [
            ['ECO-01','3 min','¿Qué estudia realmente la economía?','No es la ciencia del dinero. Es la ciencia de las decisiones bajo escasez.'],
            ['FIL-01','4 min','¿Qué es la filosofía y para qué sirve?','El único instrumento para cuestionar los supuestos que todas las demás disciplinas dan por sentados.'],
            ['MAT-01','3 min','Las matemáticas son un lenguaje, no un cálculo.','La mayoría aprende a calcular y cree que eso es matemáticas. No lo es.'],
            ['HIS-01','4 min','Cómo leer la historia sin que te manipulen.','Toda historia es una selección. Alguien decidió qué incluir y qué omitir.'],
            ['CIE-01','3 min','El método científico de verdad.','No es el diagrama del libro de secundaria. Es reducir el error sistemático del pensamiento.'],
          ];
          foreach($fl as $l): ?>
          <a href="#" class="lesson-card">
            <div class="lesson-top"><span class="lesson-code"><?php echo esc_html($l[0]); ?></span><span class="lesson-time"><?php echo esc_html($l[1]); ?></span></div>
            <span class="lesson-title"><?php echo esc_html($l[2]); ?></span>
            <p class="lesson-summary"><?php echo esc_html($l[3]); ?></p>
            <span class="lesson-arrow">→</span>
          </a>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ EL VIAJE ══════════ -->
<section class="nn-viaje fade" id="viaje" aria-label="El Viaje del Pensamiento">
  <div class="viaje-blob" aria-hidden="true"></div>
  <div class="viaje-inner">
    <div style="position:relative;">
      <div class="viaje-num" aria-hidden="true">∞</div>
      <div class="s-eyebrow">La serie principal</div>
      <h2>El Viaje del<br><em><?php echo esc_html( explode(' ', $viaje_title)[count(explode(' ', $viaje_title))-1] ); ?></em></h2>
      <p><?php echo esc_html( $viaje_text1 ); ?></p>
      <p><?php echo esc_html( $viaje_text2 ); ?></p>
      <a href="<?php echo esc_url( $viaje_cta_url ); ?>" class="btn btn-gold"><?php echo esc_html( $viaje_cta_text ); ?></a>
    </div>
    <div class="steps">
      <div class="step">
        <span class="step-badge">I</span>
        <div class="step-body">
          <strong>Lógica y Epistemología</strong>
          <p>Cálculo proposicional y de primer orden, falacias formales, la definición clásica de conocimiento, problemas de justificación (Gettier) y empirismo frente a racionalismo.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">II</span>
        <div class="step-body">
          <strong>Historia de las Ideas y Sistemas Sociales</strong>
          <p>Genealogía ética y política clásica, idealismo alemán (Hegel) y economía política basada en la teoría del valor-trabajo, plusvalía y acumulación de capital.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">III</span>
        <div class="step-body">
          <strong>El Lenguaje Matemático y Rigor Formal</strong>
          <p>Cálculo infinitesimal (diferencial e integral) multivariable, álgebra lineal (matrices y vectores), optimización estática y leyes de probabilidad aplicada.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">IV</span>
        <div class="step-body">
          <strong>Economía y Sistemas Materiales</strong>
          <p>Teoría microeconómica del consumidor y la empresa (utilidad y costos), equilibrio general macroeconómico (modelos IS-LM y Solow) e inferencia empírica por Mínimos Cuadrados Ordinarios.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">V</span>
        <div class="step-body">
          <strong>Epistemología de la Ciencia</strong>
          <p>El método de prueba científica, falsacionismo de Popper, paradigmas de Kuhn e investigación metodológica.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">VI</span>
        <div class="step-body">
          <strong>Computabilidad y Cómputo Formal</strong>
          <p>Límites de decidibilidad de sistemas lógicos, máquinas de Turing, teoremas de Gödel y teoría de la información.</p>
        </div>
      </div>
      <div class="step">
        <span class="step-badge">VII</span>
        <div class="step-body">
          <strong>Síntesis y Fronteras de la IA</strong>
          <p>Modelos de redes neuronales, el problema mente-cuerpo, implicaciones epistémicas del cómputo y la física contemporánea.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ ABOUT ══════════ -->
<section class="nn-section fade" id="sobre" aria-label="Sobre el autor">
  <div class="about-inner">
    <div>
      <div class="s-eyebrow">Quién escribe</div>
      <h2 class="about-name"><?php echo esc_html( $about_name_1 ); ?><br><em><?php echo esc_html( $about_name_2 ); ?></em></h2>
      <div class="fact-list">
        <?php foreach ( $about_facts as $f ) : ?>
        <div class="fact"><span class="fact-k"><?php echo esc_html( $f[0] ); ?></span><span class="fact-v"><?php echo esc_html( $f[1] ); ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div>
      <p class="about-text"><?php echo esc_html( $about_text_1 ); ?></p>
      <p class="about-text"><?php echo esc_html( $about_text_2 ); ?></p>
      <p class="about-text"><?php echo esc_html( $about_text_3 ); ?></p>
      <a href="<?php echo esc_url( $about_cta_url ); ?>" class="btn btn-outline"><?php echo esc_html( $about_cta_text ); ?></a>
    </div>
  </div>
</section>

<!-- ══════════ NEWSLETTER ══════════ -->
<section class="nn-newsletter fade" id="newsletter" aria-label="Suscripción">
  <div class="newsletter-inner">
    <div class="s-eyebrow" style="justify-content:center;margin-bottom:1.4rem;">El viaje, en tu correo</div>
    <h2><?php echo esc_html( $nl_title_1 ); ?><br><em><?php echo esc_html( $nl_title_2 ); ?></em></h2>
    <p><?php echo esc_html( $nl_body ); ?></p>
    <?php if ( function_exists('mc4wp_show_form') ) : mc4wp_show_form(); else : ?>
    <form class="form-row" method="post" novalidate>
      <input type="email" name="email" placeholder="tu@correo.com" required autocomplete="email" aria-label="Tu correo" />
      <button type="submit">Suscribirme</button>
    </form>
    <?php endif; ?>
    <p class="form-note"><?php echo esc_html( $nl_note ); ?></p>
  </div>
</section>

<?php get_footer(); ?>
