# Guía de Estilos y Plantilla de Citado Filosófico

Este documento establece las directrices académicas de citado y la plantilla estructural en Markdown para todas las publicaciones del proyecto **Nihil Novi**.

---

## 1. Estándares de Citación Filosófica

Para mantener el máximo rigor académico, los textos deben respetar las citaciones estandarizadas clásicas en lugar de limitarse a referencias por página física del libro moderno.

### A. Citado Clásico (Paginación Especial)

#### 1. Platón (Paginación Stephanus)
Se basa en la edición de tres volúmenes de Henricus Stephanus (1578).
*   **Formato:** `[Nombre del Diálogo] [Página] [Sección de la A a la E]`
*   **Ejemplo:** *República* 473d, o simplemente *Rep.* 473d.
*   **Nota en Markdown:** Debe indicarse en el margen izquierdo o en texto inline de forma visible para el lector crítico:
    > "A menos que los filósofos reinen en las ciudades..." (*Rep.* 473d).

#### 2. Aristóteles (Paginación Bekker)
Se basa en la edición de la Real Academia Prusiana por Immanuel Bekker (1831).
*   **Formato:** `[Nombre del Tratado] [Página] [Columna a o b] [Número de línea]`
*   **Ejemplo:** *Metafísica* 980a21, o *Met.* 980a21.
*   **Nota en Markdown:**
    > "Todos los hombres por naturaleza desean saber" (*Met.* 980a21).

#### 3. Immanuel Kant (Edición de la Academia - Akademie-Ausgabe)
Se cita según la edición crítica de las obras de Kant de la Real Academia Prusiana (*Kant's gesammelte Schriften*, abreviado **AA** o **Ak**).
*   **Formato:** `AA [Volumen]: [Página]`
*   **Ejemplo:** AA IV: 421 (para la *Fundamentación de la metafísica de las costumbres*).
*   **Excepción - Crítica de la razón pura:** Se utiliza la paginación de la primera (**A**, 1781) y segunda (**B**, 1787) edición original.
    *   *Ejemplo:* KrV A 51 / B 75.

#### 4. G.W.F. Hegel (Citación por Párrafos)
Se utiliza para obras estructuradas en forma sistemática, como la *Enciclopedia de las ciencias filosóficas* o la *Filosofía del Derecho*.
*   **Formato:** `§ [Número de párrafo] [Observación/Adición]`
*   **Ejemplo:** *Filosofía del Derecho* § 258, o § 258 Anmerkung (Observación), o § 258 Zusatz (Adición).

---

### B. Citado APA 7 (Traducciones y Obras con Copyright)

Para estudios introductorios, ensayos críticos contemporáneos o citas de traducciones protegidas por derechos de autor, se utiliza el formato de autor-fecha.

*   **Cita Directa Corta (< 40 palabras):** Integrada en el párrafo entre comillas.
    *   *Ejemplo:* Según Gadamer (1993), "comprender es siempre interpretar" (p. 23).
*   **Cita Directa Larga (>= 40 palabras):** En bloque aparte, sin comillas, sangrado y en una tipografía ligeramente diferenciada.
    *   *Ejemplo en Markdown:*
        > Para comprender un texto histórico no se requiere anular el propio horizonte cultural, sino más bien ponerlo en juego para lograr una fusión con el horizonte del texto. En palabras de Gadamer (1993):
        >
        > La fusión de horizontes que se lleva a cabo en la comprensión es la obra propiamente dicha del lenguaje histórico. El lenguaje no es una mera herramienta que uno tiene a su disposición... (p. 312)

---

## 2. Plantilla Markdown de Capítulo (.md)

Todos los artículos deben comenzar con el siguiente bloque frontmatter en YAML y seguir la jerarquía estructural:

```markdown
---
title: "Título del Capítulo o Ensayo Académico"
author: "Nombre del Autor Original (ej. Immanuel Kant)"
translator: "Nombre del Traductor / Editor (si aplica)"
source_work: "Nombre de la Obra Original (ej. Grundlegung zur Metaphysik der Sitten)"
original_year: 1785
citation_style: "Akademie / APA 7"
category: "Historia de la Filosofía / Kantianismo"
tags: ["Ética", "Metafísica", "Deontología"]
---

# Título del Capítulo o Ensayo Académico

> **Nota del Editor / Traductor:** 
> Breve contextualización histórica del texto que sigue, justificación de la traducción y aclaraciones metodológicas de citado. (Tipografía en cursiva/bloque).

## I. Primer Subtítulo del Capítulo o Texto

Texto principal del capítulo. Las notas al pie de página deben usarse para aclaraciones del traductor o referencias adicionales. Se insertan usando la sintaxis estándar de Markdown[^1].

Cuando se introduce un concept técnico crucial en el idioma original, se coloca entre corchetes: *Dasein* [ser-ahí/existencia].

### Subsección de Análisis

Si el texto incluye una cita textual larga de otra obra del autor o de un comentario, debe presentarse en bloque destacado:

> Pues el deber debe ser una necesidad práctica incondicionada de la acción; tiene que valer, por tanto, para todos los seres racionales... y solo por ello ser ley también para todas las voluntades humanas. (Kant, 1785/2012, p. 73 [AA IV: 425])

## II. Segundo Subtítulo del Capítulo o Texto

Continuación del desarrollo de las ideas. 

[^1]: Nota aclaratoria o referencia bibliográfica que aparecerá al final del documento de forma automatizada por el motor de Markdown de WordPress.

---

## Referencias Bibliográficas

*   **Libro Clásico Traducido (APA 7):**
    Kant, I. (2012). *Fundamentación de la metafísica de las costumbres* (R. R. Aramayo, Trad.). Alianza Editorial. (Obra original publicada en 1785).
*   **Artículo de Revista Académica (APA 7):**
    Heidegger, M. (1927). Sein und Zeit. *Jahrbuch für Philosophie und phänomenologische Forschung*, 8, 1-438.
```
