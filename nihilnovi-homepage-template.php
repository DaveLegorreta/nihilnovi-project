<?php
/**
 * Template Name: Nihil Novi — Homepage
 * Template Post Type: page
 * Description: Custom full-page template for nihilnovi.xyz homepage.
 *              Bypasses WordPress header/footer. Standalone.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Economía, pensamiento y verdades que nadie quiere decir en voz alta. El registro público de alguien que estudia economía en serio. Sin respuestas simples." />
  <meta property="og:title" content="Nihil Novi — David Legorreta" />
  <meta property="og:description" content="Economía, pensamiento y verdades incómodas. nihilnovi.xyz" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>" />
  <title>Nihil Novi — El camino, no el destino.</title>

  <!-- WordPress head hooks (SEO plugins, analytics, etc.) -->
  <?php wp_head(); ?>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;1,8..60,300&family=Inter:wght@300;400;500&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet" />

  <style>
    /* ─── TOKENS ─────────────────────────────────────────────── */
    :root {
      --black:        #0A0A0A;
      --black-blue:   #0D0D1A;
      --dark-card:    #101018;
      --dark-card-2:  #13131E;
      --ivory:        #EDE8DF;
      --ivory-dim:    #C8C3BB;
      --gray:         #6B6460;
      --gray-light:   #9A9490;
      --gold:         #C4973A;
      --gold-glow:    rgba(196,151,58,0.08);
      --gold-border:  rgba(196,151,58,0.2);
      --blue:         #2D5A8E;
      --border:       #1A1A24;
      --border-light: #242430;
    }

    /* ─── RESET ──────────────────────────────────────────────── */
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior: smooth; font-size: 16px; }
    body {
      background: var(--black);
      color: var(--ivory);
      font-family: 'Source Serif 4', serif;
      line-height: 1.8;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }
    a { color: inherit; text-decoration: none; }
    img { display: block; max-width: 100%; }

    /* ─── SCROLL PROGRESS ────────────────────────────────────── */
    #progress-bar {
      position: fixed; top: 0; left: 0;
      width: 0%; height: 2px;
      background: linear-gradient(90deg, var(--gold), #E8B84B);
      z-index: 9999;
      transition: width 0.1s linear;
    }

    /* ─── NAVIGATION ─────────────────────────────────────────── */
    nav {
      position: fixed; top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 1.4rem 4rem;
      background: rgba(10,10,10,0.88);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border);
      transition: padding 0.3s ease;
    }
    nav.scrolled { padding: 1rem 4rem; }
    .nav-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--gold);
      font-weight: 400;
    }
    .nav-links { display: flex; gap: 2.5rem; list-style: none; }
    .nav-links a {
      font-family: 'Inter', sans-serif;
      font-size: 0.72rem; font-weight: 400;
      letter-spacing: 0.12em; text-transform: uppercase;
      color: var(--gray-light);
      transition: color 0.3s ease;
      position: relative;
    }
    .nav-links a::after {
      content: ''; position: absolute; bottom: -3px; left: 0;
      width: 0; height: 1px; background: var(--gold);
      transition: width 0.3s ease;
    }
    .nav-links a:hover { color: var(--ivory); }
    .nav-links a:hover::after { width: 100%; }
    .nav-cta {
      font-family: 'Inter', sans-serif;
      font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase;
      color: var(--black); background: var(--gold);
      padding: 0.55rem 1.4rem;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .nav-cta:hover { background: #D4A840; transform: translateY(-1px); }

    /* ─── HERO ───────────────────────────────────────────────── */
    .hero {
      position: relative; min-height: 100vh;
      display: flex; align-items: center;
      padding: 9rem 4rem 7rem; overflow: hidden;
      background: linear-gradient(145deg, var(--black) 0%, var(--black-blue) 55%, #0C0C14 100%);
    }
    .hero-blob-1 {
      position: absolute; top: 15%; left: -8%;
      width: 55vw; height: 55vw; max-width: 700px; max-height: 700px;
      background: radial-gradient(ellipse, rgba(44,62,120,0.14) 0%, transparent 68%);
      pointer-events: none; animation: floatBlob 12s ease-in-out infinite;
    }
    .hero-blob-2 {
      position: absolute; bottom: 5%; right: -5%;
      width: 45vw; height: 45vw; max-width: 580px; max-height: 580px;
      background: radial-gradient(ellipse, rgba(196,151,58,0.07) 0%, transparent 65%);
      pointer-events: none; animation: floatBlob 15s ease-in-out infinite reverse;
    }
    .hero-blob-3 {
      position: absolute; top: 60%; left: 40%;
      width: 30vw; height: 30vw; max-width: 400px; max-height: 400px;
      background: radial-gradient(ellipse, rgba(30,50,100,0.1) 0%, transparent 70%);
      pointer-events: none; animation: floatBlob 18s ease-in-out infinite 3s;
    }
    @keyframes floatBlob {
      0%, 100% { transform: translate(0,0) scale(1); }
      33%  { transform: translate(2%,-2%) scale(1.03); }
      66%  { transform: translate(-1%,2%) scale(0.97); }
    }
    .hero-grid {
      position: absolute; top: 0; right: 0; width: 40%; height: 100%;
      background-image: radial-gradient(circle, rgba(196,151,58,0.12) 1px, transparent 1px);
      background-size: 32px 32px;
      mask-image: linear-gradient(to left, rgba(0,0,0,0.4) 0%, transparent 80%);
      -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0.4) 0%, transparent 80%);
      pointer-events: none;
    }
    .hero-content { position: relative; z-index: 1; max-width: 820px; }
    .hero-eyebrow {
      display: flex; align-items: center; gap: 1rem;
      font-family: 'Inter', sans-serif; font-size: 0.68rem;
      letter-spacing: 0.22em; text-transform: uppercase;
      color: var(--gold); margin-bottom: 2.2rem;
    }
    .hero-eyebrow::before {
      content: ''; display: block; width: 36px; height: 1px; background: var(--gold);
    }
    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(3rem, 6.5vw, 6rem);
      font-weight: 400; line-height: 1.12;
      letter-spacing: -0.015em; color: var(--ivory); margin-bottom: 1.6rem;
    }
    .hero h1 em { font-style: italic; color: var(--gold); }
    .hero-sub {
      font-family: 'Source Serif 4', serif;
      font-size: clamp(1rem, 1.8vw, 1.2rem);
      color: var(--gray); line-height: 1.75; max-width: 540px; margin-bottom: 3rem;
    }
    .hero-ctas { display: flex; gap: 1rem; flex-wrap: wrap; }
    .btn-primary {
      font-family: 'Inter', sans-serif; font-size: 0.76rem;
      letter-spacing: 0.14em; text-transform: uppercase;
      color: var(--black); background: var(--gold); padding: 0.95rem 2.2rem;
      display: inline-block; transition: background 0.3s ease, transform 0.25s ease;
    }
    .btn-primary:hover { background: #D4A840; transform: translateY(-2px); }
    .btn-ghost {
      font-family: 'Inter', sans-serif; font-size: 0.76rem;
      letter-spacing: 0.14em; text-transform: uppercase;
      color: var(--ivory-dim); border: 1px solid var(--border-light);
      padding: 0.95rem 2.2rem; display: inline-block;
      transition: border-color 0.3s ease, color 0.3s ease, transform 0.25s ease;
    }
    .btn-ghost:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-2px); }
    .hero-scroll {
      position: absolute; bottom: 2.5rem; left: 50%; transform: translateX(-50%);
      display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
      font-family: 'Inter', sans-serif; font-size: 0.62rem;
      letter-spacing: 0.18em; text-transform: uppercase; color: var(--gray);
      animation: bounceScroll 2.5s ease-in-out infinite;
    }
    .hero-scroll::after {
      content: ''; display: block; width: 1px; height: 36px;
      background: linear-gradient(to bottom, var(--gray), transparent);
    }
    @keyframes bounceScroll {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50%       { transform: translateX(-50%) translateY(6px); }
    }

    /* ─── SECTION BASE ───────────────────────────────────────── */
    .section { padding: 7rem 4rem; border-top: 1px solid var(--border); }
    .section-inner { max-width: 1200px; margin: 0 auto; }
    .section-eyebrow {
      font-family: 'Inter', sans-serif; font-size: 0.68rem;
      letter-spacing: 0.22em; text-transform: uppercase; color: var(--gold);
      margin-bottom: 0.9rem; display: flex; align-items: center; gap: 0.9rem;
    }
    .section-eyebrow::before {
      content: ''; display: block; width: 28px; height: 1px; background: var(--gold);
    }
    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.9rem, 3.5vw, 2.8rem); font-weight: 400;
      line-height: 1.25; color: var(--ivory); margin-bottom: 0.5rem;
    }
    .section-title em { font-style: italic; color: var(--gold); }
    .section-desc {
      font-family: 'Source Serif 4', serif; font-size: 1rem;
      color: var(--gray); max-width: 560px; line-height: 1.8; margin-top: 0.8rem;
    }

    /* ─── MANIFESTO ──────────────────────────────────────────── */
    .manifesto {
      padding: 7rem 4rem; border-top: 1px solid var(--border);
      background: linear-gradient(180deg, var(--black) 0%, var(--black-blue) 100%);
    }
    .manifesto-inner { max-width: 760px; margin: 0 auto; text-align: center; }
    .manifesto blockquote {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.1rem, 2.2vw, 1.45rem); font-style: italic;
      line-height: 2; color: var(--ivory-dim); margin-bottom: 2rem;
    }
    .manifesto blockquote strong { font-style: normal; color: var(--ivory); }
    .manifesto .gold-rule { width: 48px; height: 1px; background: var(--gold); margin: 0 auto 1.5rem; }
    .manifesto cite {
      font-family: 'Inter', sans-serif; font-size: 0.72rem;
      letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold);
    }

    /* ─── PILLARS ────────────────────────────────────────────── */
    .pillars-header {
      display: flex; justify-content: space-between; align-items: flex-end;
      margin-bottom: 3.5rem; flex-wrap: wrap; gap: 1.5rem;
    }
    .pillars-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1px; background: var(--border); border: 1px solid var(--border);
    }
    .pillar-card {
      background: var(--dark-card); padding: 2.8rem 2.4rem;
      transition: background 0.4s ease; position: relative; overflow: hidden;
    }
    .pillar-card::after {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), transparent);
      opacity: 0; transition: opacity 0.4s ease;
    }
    .pillar-card:hover { background: var(--dark-card-2); }
    .pillar-card:hover::after { opacity: 1; }
    .pillar-num {
      font-family: 'Playfair Display', serif; font-size: 5rem; font-weight: 400;
      color: var(--gold); opacity: 0.18; line-height: 1; margin-bottom: 1.5rem;
    }
    .pillar-title {
      font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 400;
      color: var(--ivory); margin-bottom: 0.75rem; line-height: 1.4;
    }
    .pillar-desc { font-family: 'Source Serif 4', serif; font-size: 0.9rem; color: var(--gray); line-height: 1.75; margin-bottom: 1.5rem; }
    .pillar-link {
      font-family: 'Inter', sans-serif; font-size: 0.7rem; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--gold);
      display: inline-flex; align-items: center; gap: 0.5rem;
      transition: gap 0.3s ease;
    }
    .pillar-link:hover { gap: 0.8rem; }
    .pillar-link::after { content: '→'; }

    /* ─── LATEST ARTICLES ────────────────────────────────────── */
    .articles-header {
      display: flex; justify-content: space-between; align-items: flex-end;
      margin-bottom: 3.5rem; flex-wrap: wrap; gap: 1.5rem;
    }
    .view-all {
      font-family: 'Inter', sans-serif; font-size: 0.72rem; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--gold);
      border-bottom: 1px solid var(--gold-border); padding-bottom: 2px;
      transition: border-color 0.3s ease;
    }
    .view-all:hover { border-color: var(--gold); }
    .articles-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      border-top: 1px solid var(--border);
    }
    .article-card {
      padding: 2.5rem 0; border-bottom: 1px solid var(--border);
      padding-right: 3rem; transition: all 0.3s ease;
    }
    .article-card:not(:last-child) { border-right: 1px solid var(--border); padding-right: 3rem; }
    .article-card:not(:first-child) { padding-left: 3rem; }
    .article-meta { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .article-num { font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; color: var(--gold); opacity: 0.7; }
    .article-tag { font-family: 'Inter', sans-serif; font-size: 0.65rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--blue); }
    .article-date { font-family: 'Inter', sans-serif; font-size: 0.65rem; color: var(--gray); margin-left: auto; }
    .article-title {
      font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 400;
      line-height: 1.4; color: var(--ivory); margin-bottom: 0.8rem;
      display: block; transition: color 0.3s ease;
    }
    .article-card:hover .article-title { color: var(--gold); }
    .article-excerpt { font-family: 'Source Serif 4', serif; font-size: 0.88rem; color: var(--gray); line-height: 1.75; margin-bottom: 1.5rem; }
    .article-read {
      font-family: 'Inter', sans-serif; font-size: 0.7rem; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--gold);
      display: inline-flex; align-items: center; gap: 0.4rem; transition: gap 0.3s ease;
    }
    .article-read:hover { gap: 0.7rem; }
    .article-read::after { content: '→'; }

    /* ─── JOURNEY ────────────────────────────────────────────── */
    .journey {
      padding: 7rem 4rem; border-top: 1px solid var(--border);
      background: linear-gradient(135deg, var(--black-blue) 0%, #0A0A12 100%);
      position: relative; overflow: hidden;
    }
    .journey-blob {
      position: absolute; top: -20%; right: -10%;
      width: 60vw; height: 60vw; max-width: 700px; max-height: 700px;
      background: radial-gradient(ellipse, rgba(44,70,130,0.1) 0%, transparent 70%);
      pointer-events: none;
    }
    .journey-inner {
      max-width: 1200px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 6rem; align-items: center; position: relative; z-index: 1;
    }
    .journey-num-bg {
      position: absolute; font-family: 'Playfair Display', serif;
      font-size: 22rem; color: var(--gold); opacity: 0.025; line-height: 1;
      top: -4rem; left: -3rem; pointer-events: none; user-select: none;
    }
    .journey h2 {
      font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 3vw, 2.6rem);
      font-weight: 400; line-height: 1.3; color: var(--ivory); margin-bottom: 1.5rem;
    }
    .journey h2 em { font-style: italic; color: var(--gold); }
    .journey p { font-family: 'Source Serif 4', serif; font-size: 1rem; color: var(--gray); line-height: 1.85; margin-bottom: 2rem; }
    .journey-steps { display: flex; flex-direction: column; gap: 1.5rem; }
    .journey-step { display: flex; gap: 1.5rem; align-items: flex-start; }
    .step-num {
      font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--gold);
      background: var(--gold-glow); border: 1px solid var(--gold-border);
      padding: 0.3rem 0.7rem; white-space: nowrap; margin-top: 0.2rem;
    }
    .step-text { font-family: 'Source Serif 4', serif; font-size: 0.9rem; color: var(--ivory-dim); line-height: 1.7; }
    .step-text strong { display: block; font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--ivory); font-weight: 400; margin-bottom: 0.2rem; }

    /* ─── ABOUT ──────────────────────────────────────────────── */
    .about-inner {
      max-width: 1200px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 2fr; gap: 5rem; align-items: start;
    }
    .about-name { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 400; color: var(--ivory); line-height: 1.25; margin-top: 0.8rem; }
    .about-name em { font-style: italic; color: var(--gold); }
    .about-text { font-family: 'Source Serif 4', serif; font-size: 1rem; color: var(--gray); line-height: 1.9; margin-bottom: 1.5rem; }
    .about-facts { display: flex; flex-direction: column; gap: 0.6rem; margin-top: 2rem; }
    .about-fact { display: flex; gap: 1rem; align-items: baseline; font-family: 'Inter', sans-serif; font-size: 0.75rem; }
    .fact-label { color: var(--gold); letter-spacing: 0.1em; text-transform: uppercase; white-space: nowrap; min-width: 100px; }
    .fact-value { color: var(--gray-light); }
    .about-cta { margin-top: 2.5rem; }

    /* ─── NEWSLETTER ─────────────────────────────────────────── */
    .newsletter { padding: 7rem 4rem; border-top: 1px solid var(--border); background: linear-gradient(180deg, var(--black) 0%, var(--black-blue) 100%); }
    .newsletter-inner { max-width: 600px; margin: 0 auto; text-align: center; }
    .newsletter h2 { font-family: 'Playfair Display', serif; font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 400; color: var(--ivory); line-height: 1.35; margin-bottom: 1rem; }
    .newsletter h2 em { font-style: italic; color: var(--gold); }
    .newsletter p { font-family: 'Source Serif 4', serif; font-size: 0.95rem; color: var(--gray); line-height: 1.8; margin-bottom: 2.5rem; }
    .newsletter-form { display: flex; border: 1px solid var(--border-light); transition: border-color 0.3s ease; }
    .newsletter-form:focus-within { border-color: var(--gold); }
    .newsletter-form input { flex: 1; background: transparent; border: none; outline: none; padding: 1rem 1.5rem; font-family: 'Inter', sans-serif; font-size: 0.85rem; color: var(--ivory); min-width: 200px; }
    .newsletter-form input::placeholder { color: var(--gray); }
    .newsletter-form button { background: var(--gold); border: none; padding: 1rem 1.8rem; font-family: 'Inter', sans-serif; font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--black); cursor: pointer; transition: background 0.3s ease; white-space: nowrap; }
    .newsletter-form button:hover { background: #D4A840; }
    .newsletter-note { margin-top: 1.2rem; font-family: 'Inter', sans-serif; font-size: 0.68rem; color: var(--gray); }

    /* ─── FOOTER ─────────────────────────────────────────────── */
    footer { padding: 2.5rem 4rem; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; }
    .footer-brand { font-family: 'Playfair Display', serif; font-size: 0.85rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); }
    .footer-links { display: flex; gap: 2rem; list-style: none; }
    .footer-links a { font-family: 'Inter', sans-serif; font-size: 0.68rem; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gray); transition: color 0.3s ease; }
    .footer-links a:hover { color: var(--gold); }
    .footer-copy { font-family: 'Inter', sans-serif; font-size: 0.68rem; color: var(--gray); }

    /* ─── ANIMATIONS ─────────────────────────────────────────── */
    .fade-up { opacity: 0; transform: translateY(28px); transition: opacity 0.8s ease, transform 0.8s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
    .fade-up:nth-child(2) { transition-delay: 0.1s; }
    .fade-up:nth-child(3) { transition-delay: 0.2s; }
    .fade-up:nth-child(4) { transition-delay: 0.3s; }

    /* ─── RESPONSIVE ─────────────────────────────────────────── */
    @media (max-width: 900px) {
      nav { padding: 1.2rem 1.8rem; }
      .nav-links, .nav-cta { display: none; }
      .hero, .section, .manifesto, .journey, .newsletter { padding: 5rem 1.8rem; }
      .journey-inner { grid-template-columns: 1fr; gap: 3rem; }
      .journey-num-bg { display: none; }
      .about-inner { grid-template-columns: 1fr; gap: 3rem; }
      .article-card:not(:first-child) { padding-left: 0; }
      .article-card:not(:last-child) { border-right: none; padding-right: 0; }
      footer { padding: 2rem 1.8rem; flex-direction: column; text-align: center; }
      .footer-links { justify-content: center; }
    }
  </style>
</head>

<body>
  <div id="progress-bar"></div>

  <!-- NAVIGATION -->
  <nav id="navbar">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo">Nihil Novi</a>
    <ul class="nav-links">
      <li><a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>">El Viaje</a></li>
      <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
      <li><a href="<?php echo esc_url( home_url( '/biblioteca' ) ); ?>">Biblioteca</a></li>
      <li><a href="<?php echo esc_url( home_url( '/conceptos' ) ); ?>">Conceptos</a></li>
      <li><a href="<?php echo esc_url( home_url( '/ahora' ) ); ?>">Ahora</a></li>
    </ul>
    <a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>" class="nav-cta">Empezar</a>
  </nav>

  <!-- HERO -->
  <section class="hero" id="inicio">
    <div class="hero-blob-1"></div>
    <div class="hero-blob-2"></div>
    <div class="hero-blob-3"></div>
    <div class="hero-grid"></div>
    <div class="hero-content">
      <div class="hero-eyebrow">David Legorreta</div>
      <h1>El camino,<br>no el <em>destino.</em></h1>
      <p class="hero-sub">Economía. Pensamiento. Verdades que nadie quiere decir en voz alta. El registro público de alguien que decidió entender cómo funciona el mundo — con todo el material, las dudas y la bibliografía incluidos.</p>
      <div class="hero-ctas">
        <a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>" class="btn-primary">Empezar el viaje</a>
        <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn-ghost">Explorar el blog</a>
      </div>
    </div>
    <div class="hero-scroll">Scroll</div>
  </section>

  <!-- MANIFESTO -->
  <section class="manifesto">
    <div class="manifesto-inner">
      <div class="gold-rule"></div>
      <blockquote>"Este no es un blog de economía.<br>Es un registro. <strong>Un cuaderno de trabajo en público.</strong><br>El mapa de alguien que decidió entender<br>cómo funciona el mundo — de verdad,<br>no en versión de infografía."</blockquote>
      <div class="gold-rule"></div>
      <cite>— David Legorreta, nihilnovi.xyz</cite>
    </div>
  </section>

  <!-- PILLARS -->
  <section class="section">
    <div class="section-inner">
      <div class="pillars-header">
        <div>
          <div class="section-eyebrow">Territorios</div>
          <h2 class="section-title">Lo que se explora aquí</h2>
          <p class="section-desc">Cuatro pilares de contenido. Ninguno tiene respuestas simples.</p>
        </div>
      </div>
      <div class="pillars-grid">
        <div class="pillar-card fade-up">
          <div class="pillar-num">I</div>
          <h3 class="pillar-title">El Viaje del Economista</h3>
          <p class="pillar-desc">Una serie semanal que recorre los fundamentos del pensamiento económico — desde Aristóteles hasta la econometría moderna.</p>
          <a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>" class="pillar-link">Ver la serie</a>
        </div>
        <div class="pillar-card fade-up">
          <div class="pillar-num">II</div>
          <h3 class="pillar-title">Estructural vs. Individual</h3>
          <p class="pillar-desc">Por qué los equipos fallan y casi nunca es culpa de las personas. Cómo distinguir un problema de sistema de un problema de conducta.</p>
          <a href="<?php echo esc_url( home_url( '/blog/equipos' ) ); ?>" class="pillar-link">Leer más</a>
        </div>
        <div class="pillar-card fade-up">
          <div class="pillar-num">III</div>
          <h3 class="pillar-title">Conversaciones Incómodas</h3>
          <p class="pillar-desc">Lo que nadie dice en las juntas pero todos piensan. Las verdades económicas y organizacionales que políticamente nadie quiere nombrar.</p>
          <a href="<?php echo esc_url( home_url( '/blog/conversaciones' ) ); ?>" class="pillar-link">Leer más</a>
        </div>
        <div class="pillar-card fade-up">
          <div class="pillar-num">IV</div>
          <h3 class="pillar-title">Economía para el Mundo Real</h3>
          <p class="pillar-desc">La diferencia entre lo que dice la teoría y lo que pasa en América Latina. Marcos de análisis que sí sirven fuera de Cambridge.</p>
          <a href="<?php echo esc_url( home_url( '/blog/economia' ) ); ?>" class="pillar-link">Leer más</a>
        </div>
      </div>
    </div>
  </section>

  <!-- LATEST ARTICLES -->
  <section class="section">
    <div class="section-inner">
      <div class="articles-header">
        <div>
          <div class="section-eyebrow">Últimas entregas</div>
          <h2 class="section-title">Lo más reciente</h2>
        </div>
        <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="view-all">Ver todo el archivo</a>
      </div>
      <div class="articles-grid">
        <div class="article-card">
          <div class="article-meta">
            <span class="article-num">00</span>
            <span class="article-tag">El Viaje</span>
            <span class="article-date">25 mayo 2026</span>
          </div>
          <a href="<?php echo esc_url( home_url( '/viaje/00-el-inicio' ) ); ?>" class="article-title">25 de mayo de 2026. Hoy empieza.</a>
          <p class="article-excerpt">No el día en que llegué a algún destino. El día en que decidí hacer el camino en público — con los libros, las dudas y los 7 elementos que nadie te dice que necesitas.</p>
          <a href="<?php echo esc_url( home_url( '/viaje/00-el-inicio' ) ); ?>" class="article-read">Leer</a>
        </div>
        <div class="article-card">
          <div class="article-meta">
            <span class="article-num">01</span>
            <span class="article-tag">Economía</span>
            <span class="article-date">25 mayo 2026</span>
          </div>
          <a href="<?php echo esc_url( home_url( '/viaje/01-que-es-la-economia' ) ); ?>" class="article-title">Si te dijeron que la economía es la ciencia de la oferta y la demanda, te timaron.</a>
          <p class="article-excerpt">La economía no empezó con Adam Smith en 1776. Empezó con los griegos. Y alguien, en algún momento, decidió olvidar eso.</p>
          <a href="<?php echo esc_url( home_url( '/viaje/01-que-es-la-economia' ) ); ?>" class="article-read">Leer</a>
        </div>
        <div class="article-card">
          <div class="article-meta">
            <span class="article-num">02</span>
            <span class="article-tag">Próximamente</span>
            <span class="article-date">Jun 2026</span>
          </div>
          <a href="#" class="article-title" style="color: var(--gray); pointer-events:none;">Las tres preguntas que toda economía tiene que responder.</a>
          <p class="article-excerpt">Antes de aprender cualquier modelo, hay tres preguntas de fondo. Qué producir. Cómo producirlo. Para quién. La forma en que una sociedad las responde lo dice todo.</p>
          <span class="article-read" style="color: var(--gray); pointer-events:none;">Próximamente</span>
        </div>
      </div>
    </div>
  </section>

  <!-- JOURNEY -->
  <section class="journey">
    <div class="journey-blob"></div>
    <div class="journey-inner">
      <div>
        <span class="journey-num-bg">I</span>
        <div class="section-eyebrow">La serie principal</div>
        <h2>El Viaje del<br><em>Economista</em></h2>
        <p>Una ruta de estudio construida en público. Semana a semana, materia a materia. Con el material real, la bibliografía completa y las preguntas que cada tema genera.</p>
        <p>No es un curso. No hay certificado. Es el camino que estoy recorriendo — y que cualquiera puede seguir con los mismos libros.</p>
        <a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>" class="btn-primary">Ver el mapa completo</a>
      </div>
      <div class="journey-steps">
        <div class="journey-step"><span class="step-num">I</span><div class="step-text"><strong>Historia del Pensamiento Económico</strong>Antes de los modelos, la genealogía. Quién construyó las herramientas y por qué.</div></div>
        <div class="journey-step"><span class="step-num">II</span><div class="step-text"><strong>Microeconomía</strong>La lógica de las decisiones individuales. El lenguaje base de la disciplina.</div></div>
        <div class="journey-step"><span class="step-num">III</span><div class="step-text"><strong>Macroeconomía</strong>Lo que pasa cuando las decisiones individuales se suman y crean algo diferente.</div></div>
        <div class="journey-step"><span class="step-num">IV</span><div class="step-text"><strong>Estadística y Econometría</strong>Sin esto, la economía es filosofía con gráficas. El lenguaje de la evidencia.</div></div>
        <div class="journey-step"><span class="step-num">V–VII</span><div class="step-text"><strong>Matemáticas · Economía Política · Filosofía de la Ciencia</strong>Los pilares que los programas no enseñan bien. Los que más hacen falta.</div></div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section">
    <div class="about-inner">
      <div>
        <div class="section-eyebrow">Quién escribe</div>
        <h2 class="about-name">David<br><em>Legorreta</em></h2>
        <div class="about-facts">
          <div class="about-fact"><span class="fact-label">Base</span><span class="fact-value">México / LATAM</span></div>
          <div class="about-fact"><span class="fact-label">Formación</span><span class="fact-value">Filosofía · Economía · Operaciones</span></div>
          <div class="about-fact"><span class="fact-label">Experiencia</span><span class="fact-value">BPO · Retail · Tech · Finanzas</span></div>
        </div>
      </div>
      <div>
        <p class="about-text">Estudié filosofía. Después trabajé catorce años en operaciones — BPO, retail, logística, tecnología. Cash App, TikTok, Walmart, Avis. Gestión de equipos, entrega de resultados, problemas estructurales disfrazados de problemas personales.</p>
        <p class="about-text">En algún momento me di cuenta de que las preguntas que me importaban no eran preguntas filosóficas. Eran preguntas económicas. La filosofía me enseñó a preguntar. La economía, si se estudia en serio, enseña a responder con evidencia.</p>
        <p class="about-text">Nihil Novi es donde hago eso en público. Sin pretender que ya llegué. Sin vender certezas que no tengo.</p>
        <div class="about-cta"><a href="<?php echo esc_url( home_url( '/sobre' ) ); ?>" class="btn-ghost">Leer más</a></div>
      </div>
    </div>
  </section>

  <!-- NEWSLETTER -->
  <section class="newsletter">
    <div class="newsletter-inner">
      <div class="section-eyebrow" style="justify-content:center;margin-bottom:1.5rem;">El viaje, en tu correo</div>
      <h2>Una entrega por semana.<br><em>Sin algoritmos.</em></h2>
      <p>Cada artículo, el material de estudio correspondiente y las preguntas que ese tema me generó. Directo. Sin curation de plataforma.</p>
      <form class="newsletter-form" onsubmit="return false;">
        <input type="email" placeholder="tu@correo.com" id="email-input" name="email" />
        <button type="submit">Suscribirme</button>
      </form>
      <p class="newsletter-note">Sin spam. Sin venta de datos. Puedes darte de baja cuando quieras.</p>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <span class="footer-brand">Nihil Novi</span>
    <ul class="footer-links">
      <li><a href="<?php echo esc_url( home_url( '/viaje' ) ); ?>">El Viaje</a></li>
      <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
      <li><a href="<?php echo esc_url( home_url( '/biblioteca' ) ); ?>">Biblioteca</a></li>
      <li><a href="<?php echo esc_url( home_url( '/sobre' ) ); ?>">Sobre</a></li>
    </ul>
    <span class="footer-copy">© <?php echo date('Y'); ?> David Legorreta · nihilnovi.xyz</span>
  </footer>

  <?php wp_footer(); ?>

  <script>
    // Scroll progress
    const bar = document.getElementById('progress-bar');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      const total = document.documentElement.scrollHeight - window.innerHeight;
      bar.style.width = Math.min((scrolled / total) * 100, 100) + '%';
    });
    // Navbar compact
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
    // Fade-in
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
      });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
  </script>
</body>
</html>
