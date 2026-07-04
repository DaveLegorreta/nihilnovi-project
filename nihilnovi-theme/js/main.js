/**
 * Nihil Novi — main.js
 * Theme JavaScript: all interactive behaviour in one place.
 * Vanilla JS only · No jQuery · IIFE encapsulation
 *
 * Sections:
 *  1. Scroll Progress Bar
 *  2. Compact Navigation
 *  3. Fade-in on Scroll (IntersectionObserver)
 *  4. Tab Switching
 *  5. Language Switcher
 *  6. Mobile Menu
 *  7. Smooth Scroll
 */

(function () {
    'use strict';

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouch              = window.matchMedia('(pointer: coarse)').matches;
    const enableEffects        = !prefersReducedMotion && !isTouch;

    /* =========================================================
     * 1. SCROLL PROGRESS BAR
     * Updates the width of #nn-progress as the user scrolls.
     * ========================================================= */
    var progressBar = document.getElementById('nn-progress');

        // ─── SCROLL PROGRESS BAR ─────────────────────────────────
    // Calculates the scroll percentage and updates the width of #nn-progress.
    function updateProgress() {
        if (!progressBar) return;
        var scrollTop  = window.scrollY || document.documentElement.scrollTop;
        var docHeight  = document.documentElement.scrollHeight - window.innerHeight;
        var pct        = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        progressBar.style.width = pct.toFixed(2) + '%';
    }


    /* =========================================================
     * 2. COMPACT NAVIGATION
     * Adds / removes class 'compact' on #nn-nav when the page
     * is scrolled beyond 80 px from the top.
     * ========================================================= */
    var nav = document.getElementById('nn-nav');

        // ─── COMPACT NAVIGATION ──────────────────────────────────
    // Adds the 'compact' class to the navigation bar upon scrolling down.
    function updateNav() {
        if (!nav) return;
        if (window.scrollY > 80) {
            nav.classList.add('compact');
        } else {
            nav.classList.remove('compact');
        }
    }

    /* Los gráficos del hero ahora usan su propio loop rAF con lerp (ver abajo) */
        // ─── HERO BLUR EFFECT ────────────────────────────────────
    // Applies dynamic blur filters to the Hero graphic elements based on scroll position.
    function updateHeroParallax() {
        if ( ! enableEffects ) return;
        /* solo actualiza el blur inmediato al hacer scroll — no la posición */
        var scrollTop = window.scrollY || 0;
        var heroGraphic  = document.querySelector('.hero-graphic');
        var heroGraphic2 = document.querySelector('.hero-graphic-2');
        if (scrollTop < window.innerHeight + 100) {
            if (heroGraphic) {
                var bv = Math.min(8, scrollTop * 0.008);
                heroGraphic.style.filter = bv > 0.4 ? 'blur(' + bv + 'px)' : 'none';
            }
            if (heroGraphic2) {
                var bv2 = Math.min(8, scrollTop * 0.008);
                heroGraphic2.style.filter = bv2 > 0.4 ? 'blur(' + bv2 + 'px)' : 'none';
            }
        }
    }


    /* Attach scroll handlers in a single listener */
    window.addEventListener('scroll', function () {
        updateProgress();
        updateNav();
        updateHeroParallax();
    }, { passive: true });

    /* Run once on load to reflect initial scroll position */
    updateProgress();
    updateNav();
    updateHeroParallax();


    /* =========================================================
     * DOT MATRIX — PARALLAX ORGÁNICO CON LERP
     * Dos capas (nítida arriba, borrosa abajo) se desplazan a
     * velocidades distintas con interpolación suave (easing)
     * para que el movimiento sea fluido e inercial, no mecánico.
     * ========================================================= */
    /* =========================================================
     * DOT MATRIX — PARALLAX ORGÁNICO CON LERP (más sutil)
     * Dos capas se desplazan a velocidades distintas con
     * interpolación suave para movimiento fluido e inercial.
     * ========================================================= */
    if ( enableEffects ) {
        (function initDotParallax() {
            var dotSharp   = document.querySelector('.nn-bg-dot-matrix');
            var dotBlurred = document.querySelector('.nn-bg-dot-matrix-blurred');
            var dotHeavy   = document.querySelector('.nn-bg-dot-matrix-heavy');
            if (!dotSharp && !dotBlurred && !dotHeavy) return;

            var scrollY    = 0;
            var posSharp   = 0;
            var posBlurred = 0;
            var posHeavy   = 0;

            window.addEventListener('scroll', function () {
                scrollY = window.scrollY || 0;
            }, { passive: true });

            function tick() {
                /* Tres capas, tres profundidades, tres velocidades distintas.
                   Sharp (capa 1): más rápida — siente como el fondo lejano.
                   Blurred (capa 2): media — zona de transición de profundidad.
                   Heavy (capa 3): la más lenta — siente como niebla en primer plano. */
                posSharp   += (scrollY * -0.12 - posSharp)   * 0.030;
                posBlurred += (scrollY * -0.06 - posBlurred) * 0.020;
                posHeavy   += (scrollY * -0.02 - posHeavy)   * 0.008;

                if (dotSharp)   dotSharp.style.backgroundPositionY   = posSharp.toFixed(2)   + 'px';
                if (dotBlurred) dotBlurred.style.backgroundPositionY = posBlurred.toFixed(2) + 'px';
                if (dotHeavy)   dotHeavy.style.backgroundPositionY   = posHeavy.toFixed(2)   + 'px';

                requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }());
    }


    /* =========================================================
     * HERO GRÁFICOS — PARALLAX ORGÁNICO CON LERP
     * Círculo e icosaedro se mueven a distinta velocidad con
     * suavizado de resorte, igual que los puntos.
     * En pantallas < 1100px el posicionamiento lo controla CSS.
     * ========================================================= */
    if ( enableEffects ) {
        (function initHeroParallax() {
            var graphic1 = document.querySelector('.hero-graphic');   /* círculo  */
            var graphic2 = document.querySelector('.hero-graphic-2'); /* icosaedro */
            if (!graphic1 && !graphic2) return;

            var scrollY = 0;
            var pos1    = 0;
            var pos2    = 0;

            window.addEventListener('scroll', function () {
                scrollY = window.scrollY || 0;
            }, { passive: true });

            function tickHero() {
                var isDesktop = window.innerWidth > 1100;

                pos1 += (scrollY * 0.08 - pos1) * 0.03;
                pos2 += (scrollY * 0.06 - pos2) * 0.02;

                if (isDesktop) {
                    if (graphic1) {
                        graphic1.style.transform = 'translateY(calc(-50% + ' + pos1.toFixed(2) + 'px))';
                    }
                    if (graphic2) {
                        graphic2.style.transform = 'translateY(calc(-50% + ' + pos2.toFixed(2) + 'px))';
                    }
                } else {
                    // Let CSS handle coordinates and animations on mobile/tablet
                    if (graphic1) graphic1.style.transform = 'translate(-50%, calc(-50% + ' + pos1.toFixed(2) + 'px))';
                    if (graphic2) graphic2.style.transform = 'translate(-50%, calc(-50% + ' + pos2.toFixed(2) + 'px))';
                }

                requestAnimationFrame(tickHero);
            }
            requestAnimationFrame(tickHero);
        }());
    }





    /* =========================================================
     * 3. FADE-IN ON SCROLL (IntersectionObserver)
     * Watches every element with class 'fade' and toggles 'in'
     * as they enter or leave the viewport.
     * ========================================================= */
    (function initFadeIn() {
        var fadeEls = document.querySelectorAll('.fade');
        if (!fadeEls.length) return;

        // Force elements visible if observer fails, is delayed or fails initialization
        function revealAll() {
            fadeEls.forEach(function (el) { el.classList.add('in'); });
        }

        if (!('IntersectionObserver' in window)) {
            revealAll();
            return;
        }

        // Safety timeout: if observer hasn't triggered visible states within 1.5 seconds, force-reveal them
        var safetyTimeout = setTimeout(revealAll, 1500);

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        // Once shown, we can clear the safety timer if any element behaves correctly
                        clearTimeout(safetyTimeout);
                    } else {
                        // Keep visible state if already scrolled past to avoid flashing content
                        // entry.target.classList.remove('in');
                    }
                });
            },
            { threshold: 0.05, rootMargin: '0px 0px -50px 0px' }
        );

        fadeEls.forEach(function (el) { observer.observe(el); });
    }());



    /* =========================================================
     * 4. TAB SWITCHING
     * Buttons:  .tab-btn  with  data-tab="name"
     * Panels:   .tab-content with  id="tab-{name}"
     *
     * Clicking a button:
     *  - removes 'active' from all buttons and panels
     *  - adds    'active' to the clicked button and matching panel
     * ========================================================= */
    (function initTabs() {
        var tabBtns = document.querySelectorAll('.tab-btn');
        if (!tabBtns.length) return;

        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-tab');
                if (!target) return;

                /* Deactivate all buttons */
                document.querySelectorAll('.tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });

                /* Hide all panels */
                document.querySelectorAll('.tab-content').forEach(function (panel) {
                    panel.classList.remove('active');
                    panel.setAttribute('hidden', '');
                });

                /* Activate selected button */
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');

                /* Show matching panel */
                var panel = document.getElementById('tab-' + target);
                if (panel) {
                    panel.classList.add('active');
                    panel.removeAttribute('hidden');
                }
            });
        });
    }());


    /* =========================================================
     * 5. MOBILE MENU
     * #nav-toggle  — hamburger / close button
     * #mobile-menu — the slide-in menu panel
     *
     * Toggling adds / removes class 'open' on both elements,
     * updates aria-expanded on the toggle button, and locks /
     * unlocks body scroll.
     * ========================================================= */
    (function initMobileMenu() {
        var toggle = document.getElementById('nav-toggle');
        var menu   = document.getElementById('mobile-menu');
        if (!toggle || !menu) return;

        function openMenu() {
            menu.classList.add('open');
            toggle.classList.add('open');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            menu.classList.remove('open');
            toggle.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        toggle.addEventListener('click', function () {
            if (menu.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        /* Close menu when a link inside it is clicked */
        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        /* Close menu on Escape key */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && menu.classList.contains('open')) {
                closeMenu();
                toggle.focus();
            }
        });
    }());


    /* =========================================================
     * 6. SMOOTH SCROLL
     * Intercepts clicks on any anchor whose href contains '#'
     * and smoothly scrolls to the target element.
     * Respects prefers-reduced-motion.
     * ========================================================= */
    (function initSmoothScroll() {
        var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        document.querySelectorAll('a[href*="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                var href = anchor.getAttribute('href');
                /* Only handle pure hash links or same-page hash links */
                var hashIndex = href.indexOf('#');
                if (hashIndex === -1) return;

                var hash = href.slice(hashIndex);
                /* Ignore lone '#' */
                if (hash === '#') return;

                var target = document.querySelector(hash);
                if (!target) return;

                e.preventDefault();

                if (prefersReduced) {
                    target.scrollIntoView();
                } else {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                /* Update URL hash without triggering a jump */
                if (history.pushState) {
                    history.pushState(null, null, hash);
                }
            });
        });
    }());


    /* =========================================================
     * GSAP SCROLLTRIGGERS AND ACTIONS
     * ========================================================= */
    if ( enableEffects && typeof gsap !== 'undefined' ) {
        gsap.registerPlugin(ScrollTrigger);

        // Hero entrance timeline
        var heroTl = gsap.timeline({ delay: 0.2 });
        heroTl
            .to('#hero-eyebrow', { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' })
            .to('#hero-h1',      { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.4')
            .to('#hero-sub',     { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '-=0.5')
            .to('#hero-pills',   { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.4')
            .to('#hero-ctas',    { opacity: 1, y: 0, duration: 0.6, ease: 'back.out(1.7)' }, '-=0.35');

        // Mouse interactions for platonic solid
        document.addEventListener('mousemove', function (e) {
            var cx = (e.clientX - window.innerWidth / 2) / (window.innerWidth / 2);
            var cy = (e.clientY - window.innerHeight / 2) / (window.innerHeight / 2);
            gsap.to('.hero-platonic', {
                x: cx * 50,
                y: cy * 50,
                rotateX: -cy * 30,
                rotateY: cx * 30,
                duration: 1.4,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });

        // GSAP animations for interactive elements
        gsap.to('.hero-graphic', { 
            y: -60, 
            rotation: 8, 
            filter: 'blur(12px)',
            opacity: 0.1,
            scrollTrigger: { trigger: '.nn-hero', start: 'top top', end: 'bottom top', scrub: 1.5 } 
        });

        gsap.to('.hero-graphic-2', { 
            filter: 'drop-shadow(0 0 16px rgba(196,151,58,0.25)) blur(15px)',
            opacity: 0.05,
            scrollTrigger: { trigger: '.nn-hero', start: 'top top', end: 'bottom top', scrub: 1.5 } 
        });

        // Scroll revealing for disciplines cards
        gsap.fromTo('.disc-card', 
            { opacity: 0, y: 35, filter: 'blur(10px)' }, 
            { 
                opacity: 1, 
                y: 0, 
                filter: 'blur(0px)', 
                duration: 0.8, 
                ease: 'power2.out', 
                stagger: 0.08, 
                scrollTrigger: { 
                    trigger: '.disciplines-grid', 
                    start: 'top 85%'
                } 
            }
        );

        gsap.utils.toArray('.step').forEach(function (step, i) {
            gsap.fromTo(step, { opacity: 0, x: 30 }, { 
                opacity: 1, 
                x: 0, 
                duration: 0.6, 
                ease: 'power3.out', 
                delay: i * 0.1, 
                scrollTrigger: { trigger: step, start: 'top 90%' } 
            });
        });

        // Card mouse movement depth effect
        document.querySelectorAll('.disc-card').forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                var r = card.getBoundingClientRect();
                var x = (e.clientX - r.left - r.width / 2) / (r.width / 2);
                var y = (e.clientY - r.top - r.height / 2) / (r.height / 2);
                gsap.to(card, { rotateY: x * 6, rotateX: -y * 6, transformPerspective: 600, ease: 'power1.out', duration: 0.3 });
            });
            card.addEventListener('mouseleave', function () {
                gsap.to(card, { rotateY: 0, rotateX: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
            });
        });
    }
}());
