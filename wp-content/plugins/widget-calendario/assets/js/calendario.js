/**
 * calendario.js
 * ---------------------------------------------------------------------------
 * Gestiona la interacción del widget Calendario:
 *   • Tooltip accesible en escritorio.
 *   • Modal de eventos en dispositivos móviles.
 *   • Alterna entre vistas calendario/lista (controlado en PHP).
 *
 * Flujo general:
 * 1. Obtiene o crea nodos auxiliares (tooltip, modal).
 * 2. Helper functions: showTooltip / hideTooltip.
 * 3. Detecta si el viewport es mobile o desktop.
 * 4. Desktop → tooltip sobre chips.
 * 5. Mobile → modal con eventos del día.
 *
 * Dependencia: pwc_eventos (inyectada vía wp_localize_script en PHP).
 *---------------------------------------------------------------------------*/
document.addEventListener("DOMContentLoaded", function () {

    //Tooltip único: si no existe en el DOM, se crea dinámicamente.
    const tooltip = document.getElementById("evento-tooltip") || (() => {
        const t = document.createElement("div");
        t.id = "evento-tooltip";
        t.className = "evento-tooltip";
        document.body.appendChild(t);
        return t;
    })();

    //Modal (solo se usa en móviles)
    const modal = document.getElementById("modal-eventos");
    const contenidoModal = document.getElementById("modal-contenido-evento");
    const cerrarModalBtn = modal.querySelector(".cerrar-modal");

    // Datos de eventos agrupados por día
    const eventos = window.pwc_eventos?.datos || {};


    //Muestra el tooltip con la información del evento.
    function showTooltip(el) {
        const nombre = el.getAttribute("data-nombre");
        const fechaInicio = el.getAttribute("data-fecha-inicio");
        const fechaFin = el.getAttribute("data-fecha-fin");
        const lugar = el.getAttribute("data-lugar");

        tooltip.innerHTML = `
                <strong>${nombre}</strong><br>
                <small><strong>Fecha:</strong> ${fechaInicio} al ${fechaFin}</small><br>
                <small><strong>Lugar:</strong> ${lugar}</small>
            `;

        tooltip.style.display = "block";

        //Posiciona tooltip evitando desbordes
        const rect = el.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();

        let top = rect.bottom + window.scrollY + 8; // 8 px de margen inferior
        let left = rect.left + window.scrollX;

        //Ajuste horizontal (no salir de viewport)
        if (left + tooltipRect.width > window.innerWidth) {
            left = window.innerWidth - tooltipRect.width - 10;
        }

        //Ajuste vertical (si no cabe abajo,se muestra arriba)
        if (top + tooltipRect.height > window.innerHeight + window.scrollY) {
            top = rect.top + window.scrollY - tooltipRect.height - 8;
        }

        tooltip.style.top = top + "px";
        tooltip.style.left = left + "px";
    }

    //Oculta el tooltip.
    function hideTooltip() {
        tooltip.style.display = "none";
    }

    // Detectar si es mobile
    const esMobile = window.matchMedia("(max-width: 768px)").matches;

    if (!esMobile) {
        // Desktop: tooltip en chips
        document.querySelectorAll(".evento-chip").forEach(el => {
            el.addEventListener("mouseenter", () => showTooltip(el));
            el.addEventListener("mouseleave", hideTooltip);
            el.addEventListener("focus", () => showTooltip(el)); // accesibilidad
            el.addEventListener("blur", hideTooltip);
        });
    } else {
        // Mobile: abrir modal al tocar día con eventos.
        document.querySelectorAll(".dia-calendario").forEach(diaEl => {
            diaEl.addEventListener("click", () => {
                const dia = diaEl.getAttribute("data-dia");
                if (eventos[dia]) {
                    //Construye el HTML de la lista de eventos
                    let html = `<h3 id="modal-title" style="margin-bottom:1rem">Eventos del día ${dia}</h3>`;
                    eventos[dia].forEach(evento => {
                        html += `
                            <div style="margin-bottom:1rem; padding:1rem; background:#f8fafc; border-radius:8px;">
                                <strong>${evento.nombre}</strong>
                                <p style="margin:4px 0; font-size:0.9em;">
                                    ${evento.fecha_inicio} - ${evento.fecha_fin}<br>
                                    ${evento.lugar}
                                </p>
                            </div>
                        `;
                    });
                    contenidoModal.innerHTML = html;
                    modal.style.display = "block";
                }
            });
        });

        // Cerrar modal
        cerrarModalBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Cerrar modal al hacer click fuera del contenido
        modal.addEventListener("click", e => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    }
});