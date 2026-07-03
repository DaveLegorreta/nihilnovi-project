> «Puesto que vemos que toda ciudad es una cierta comunidad y que toda comunidad está constituida con miras a algún bien... es evidente que todas tienden a algún bien, pero sobre todo tiende al bien soberano la que es suprema y contiene a todas las demás. Ésta es la llamada ciudad o comunidad política.»
> — Aristóteles, *Política* I, 1, 1252a1-7 (trad. M. García Valdés, Gredos, 1988)

---

## Génesis y Conexión Histórica

La *Política* (Πολιτικά) de Aristóteles representa un giro copernicano en el pensamiento político de la Antigüedad clásica, marcando el tránsito del idealismo normativo y geométrico de la *República* platónica hacia un análisis empírico, descriptivo y comparativo de las formas de organización humana. Mientras que Platón (FIL-03) concibe la ciudad ideal como una estructura tripartita deducida *a priori* desde la justicia del alma individual, Aristóteles enfoca la política como una **ciencia práctica** (*praktikē epistēmē* / πρακτικὴ ἐπιστήμη) indisolublemente ligada a la ética.

El contexto de redacción de la *Política* se sitúa en el último tercio del siglo IV a. C., durante el segundo período de residencia de Aristóteles en Atenas tras fundar el Liceo (ca. 335 a. C.). Para sostener las tesis de su tratado, Aristóteles y sus discípulos llevaron a cabo un monumental trabajo de investigación histórica y empírica preliminar: la recopilación e inspección de las **Constituciones de 158 poleis** (*politeiai*) de todo el mundo griego y bárbaro (de las cuales solo conservamos la *Constitución de los atenienses*). 

Este método biológico-inductivo aplicado a la sociedad humana asume que la pólis es una entidad natural, un organismo compuesto que se desarrolla desde formas más simples (familia, aldea) hasta alcanzar su fin biológico y teleológico (*telos* / τέλος): la autarquía y la vida buena (*eu zen* / εὖ ζῆn). Si en la *Ética a Nicómaco* Aristóteles investiga en qué consiste la felicidad individual, en la *Política* indaga qué estructura institucional y qué orden de leyes (*nomoi*) posibilitan que los ciudadanos alcancen colectivamente dicha excelencia (*aretē* / ἀρετή).

---

## Estructura General y Mapeo de la Obra

La *Política* está compuesta por **ocho libros**, cuya ordenación sistemática ha sido objeto de debate filológico desde la edición clásica de Andrónico de Rodas, pero que responden a un plan intelectual coherente:

1.  **Libro I: La Pólis como Comunidad Natural y la Oikonomía.** Definición teleológica de la ciudad, génesis social, justificación de la esclavitud natural y análisis de la adquisición de bienes (*crematística*).
2.  **Libro II: Crítica de las Teorías Políticas y Constituciones Reales.** Examen crítico de los modelos ideales (incluyendo la *República* y las *Leyes* de Platón) y de las mejores constituciones históricas (Esparta, Creta, Cartago).
3.  **Libro III: El Ciudadano, el Estado y la Tipología de las Constituciones.** Definición esencial del ciudadano y de la *politeia*. Clasificación de los regímenes en seis formas (tres rectas y tres desviadas).
4.  **Libro IV: Análisis Empírico de los Regímenes Reales.** Estudio de las variedades concretas de oligarquía y democracia, y de la *politeia* mixta (gobierno de la clase media).
5.  **Libro V: Las Revoluciones y el Cambio Constitucional.** Teoría de la inestabilidad política. Causas de la sedición (*stasis* / στάσις) y métodos de preservación de cada régimen.
6.  **Libro VI: Organización de Democracias y Oligarquías.** Directrices técnicas de diseño constitucional para hacer viables y estables los regímenes ordinarios.
7.  **Libro VII: El Estado Ideal y la Educación.** Filosofía de la ciudad feliz. Planificación física, demográfica y pedagógica de la *pólis* perfecta.
8.  **Libro VIII: La Educación de la Juventud en la Pólis Ideal.** La función cívica de la gimnasia, las letras y la música (*mousikē* / μουσική) en la formación del ciudadano.

---

<!-- wp:html -->
<style>
    :root {
        --pol-bg: #0b0c10;
        --pol-container-bg: #1f2833;
        --pol-accent: #c5a059;
        --pol-text: #c5c6c7;
        --pol-light: #ffffff;
        --pol-box-bg: #151b24;
        --pol-border: rgba(197, 160, 89, 0.2);
    }

    .pol-map-container-outer {
        padding: 20px 0;
        width: 100%;
        box-sizing: border-box;
    }

    .pol-map-container {
        width: 100%;
        max-width: 850px;
        background-color: var(--pol-container-bg);
        border: 1px solid var(--pol-border);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        color: var(--pol-text);
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .pol-map-container h3 {
        color: var(--pol-light) !important;
        margin-top: 0 !important;
        margin-bottom: 12px !important;
        border-left: 4px solid var(--pol-accent) !important;
        padding-left: 12px !important;
        font-size: 1.4rem !important;
    }

    .pol-books-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .pol-books-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .pol-books-grid {
            grid-template-columns: 1fr;
        }
    }

    .pol-book-btn {
        background-color: var(--pol-box-bg);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: bold;
        font-size: 0.8rem;
        color: var(--pol-light);
    }

    .pol-book-btn:hover {
        border-color: var(--pol-accent);
        transform: translateY(-2px);
    }

    .pol-book-btn.active {
        background-color: rgba(197, 160, 89, 0.1);
        border-color: var(--pol-accent);
        box-shadow: 0 0 10px rgba(197, 160, 89, 0.2);
    }

    .pol-details-box {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        border-left: 4px solid var(--pol-accent);
        padding: 16px;
        min-height: 160px;
    }

    .pol-details-box h4 {
        margin: 0 0 8px 0 !important;
        color: var(--pol-light) !important;
        font-size: 1.1rem !important;
    }

    .pol-details-box p {
        font-size: 0.85rem !important;
        line-height: 1.5 !important;
        margin: 0 !important;
        color: var(--pol-text) !important;
    }
</style>

<div class="pol-map-container-outer">
<div class="pol-map-container">
    <h3>Organigrama Didáctico de la «Política»</h3>
    <p style="font-size: 0.9rem; line-height: 1.5; margin-bottom: 16px;">
        Selecciona un libro para inspeccionar su núcleo conceptual y su papel en el arco general de la filosofía práctica de Aristóteles.
    </p>

    <div class="pol-books-grid">
        <div class="pol-book-btn active" id="btn-L1" onclick="showPolBook('L1')">Libro I</div>
        <div class="pol-book-btn" id="btn-L2" onclick="showPolBook('L2')">Libro II</div>
        <div class="pol-book-btn" id="btn-L3" onclick="showPolBook('L3')">Libro III</div>
        <div class="pol-book-btn" id="btn-L4" onclick="showPolBook('L4')">Libro IV</div>
        <div class="pol-book-btn" id="btn-L5" onclick="showPolBook('L5')">Libro V</div>
        <div class="pol-book-btn" id="btn-L6" onclick="showPolBook('L6')">Libro VI</div>
        <div class="pol-book-btn" id="btn-L7" onclick="showPolBook('L7')">Libro VII</div>
        <div class="pol-book-btn" id="btn-L8" onclick="showPolBook('L8')">Libro VIII</div>
    </div>

    <div class="pol-details-box">
        <h4 id="pol-det-title">Libro I: La Comunidad Natural y la Oikonomía</h4>
        <p id="pol-det-text">
            Aristóteles define a la pólis como la comunidad soberana nacida para permitir la vida buena. Introduce el concept de que el ser humano es un "animal político" (zoon politikon) por naturaleza debido a su posesión exclusiva del lenguaje (logos). Analiza la administración del hogar (oikonomía) y la crematística (acumulación de dinero).
        </p>
    </div>
</div>
</div>

<script>
    (function() {
        const polBookData = {
            L1: {
                title: "Libro I: La Comunidad Natural y la Oikonomía",
                text: "Aristóteles define a la pólis como la comunidad soberana nacida para permitir la vida buena. Introduce el concepto de que el ser humano es un 'animal político' (zoon politikon) por naturaleza debido a su posesión exclusiva del lenguaje (logos). Analiza la administración del hogar (oikonomía) y la crematística (acumulación de dinero)."
            },
            L2: {
                title: "Libro II: Examen Crítico de Constituciones",
                text: "Estudio comparativo y refutación de los modelos políticos ideales precedentes. Aristóteles lanza críticas devastadoras contra el comunismo de bienes y mujeres propuesto por Platón en la República, y evalúa críticamente los regímenes reales de Esparta, Creta y Cartago."
            },
            L3: {
                title: "Libro III: El Ciudadano y los Regímenes Políticos",
                text: "Estructura ontológica del Estado. Define al ciudadano por su derecho a participar en las funciones deliberativas y judiciales. Presenta la célebre clasificación de las seis formas de gobierno, distinguiendo si gobiernan con miras al bien común o al interés particular de la clase gobernante."
            },
            L4: {
                title: "Libro IV: Tipología Real de las Politeiai",
                text: "Física social aristotélica. Analiza el funcionamiento práctico de las subdivisiones reales de la oligarquía y la democracia. Propone que el régimen más equilibrado y realizable en la práctica es la politeia mixta, donde la clase media actúa como moderadora."
            },
            L5: {
                title: "Libro V: Las Revoluciones y la Estabilidad",
                text: "Manual científico de la patología política. Aristóteles rastrea las causas psicológicas y materiales que provocan las revoluciones (stasis) y destruyen los regímenes, ofreciendo recetas técnicas concretas para que demócratas, oligarcas e incluso tiranos preserven su poder."
            },
            L6: {
                title: "Libro VI: El Diseño de las Instituciones",
                text: "Análisis procedimental sobre cómo deben organizarse y estructurarse los tribunales, asambleas y magistraturas en democracias y oligarquías concretas para que éstas sean funcionales, estables y de larga duración."
            },
            L7: {
                title: "Libro VII: El Estado Ideal y la Geografía Humana",
                text: "Bosquejo del mejor régimen posible bajo condiciones ideales. Aristóteles detalla la ubicación geográfica óptima de la ciudad, los límites de población para mantener la cohesión deliberativa, y el inicio del programa pedagógico cívico."
            },
            L8: {
                title: "Libro VIII: La Pedagogía y la Música en la Pólis",
                text: "Culminación teleológica del Estado. Analiza la educación pública de la juventud como la principal garantía de estabilidad constitucional. Estudia en profundidad el valor moral y purgativo de la música (mousikē) en el alma de los futuros gobernantes."
            }
        };

        window.showPolBook = function(id) {
            document.querySelectorAll('.pol-book-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`btn-${id}`).classList.add('active');

            const data = polBookData[id];
            document.getElementById('pol-det-title').innerText = data.title;
            document.getElementById('pol-det-text').innerText = data.text;
        };
    })();
</script>
<!-- /wp:html -->

---

## Bibliografía Recomendada

- Aristóteles. (1988). *Política* (M. García Valdés, Trad.). Editorial Gredos. https://amzn.to/4gQHI26
- Jaeger, W. (1996). *Aristóteles: Bases para la historia de su desarrollo intelectual*. Fondo de Cultura Económica.
- Copleston, F. (2011). *Historia de la Filosofía (Vol. 1: Grecia y Roma)*. Editorial Ariel.
