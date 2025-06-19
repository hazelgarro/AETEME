<?php

use \Elementor\Widget_Base;

class Elemento_Widget_Calendario extends Widget_Base
{
    public function get_name()
    {
        return 'pwc_calendario';
    }

    public function get_title()
    {
        return 'Calendario';
    }

    public function get_icon()
    {
        return 'eicon-calendar';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_style_depends()
    {
        return ['wc-calendario-css'];
    }

    public function get_script_depends()
    {
        return ['wc-calendario-js'];
    }

    protected function render()
    {
        $mes = intval(date('n'));
        $anio = date('Y');
        $primer_dia_mes = mktime(0, 0, 0, $mes, 1, $anio);
        $dia_semana = date('N', $primer_dia_mes);
        $dias_mes = date('t', $primer_dia_mes);
        $mes_nombre = date_i18n('F', $primer_dia_mes);

        $fecha_inicio_mes = date('Y-m-01', $primer_dia_mes);
        $fecha_fin_mes = date('Y-m-t', $primer_dia_mes);

        $args = [
            'post_type' => 'torneo',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_fecha_inicio',
                    'value' => [$fecha_inicio_mes, $fecha_fin_mes],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
                [
                    'key' => '_fecha_fin',
                    'value' => [$fecha_inicio_mes, $fecha_fin_mes],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
            ],
        ];

        $query = new WP_Query($args);
        $eventos_por_dia = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $nombre = get_the_title();
                $fecha_inicio = get_post_meta(get_the_ID(), '_fecha_inicio', true);
                $fecha_fin = get_post_meta(get_the_ID(), '_fecha_fin', true);
                $lugar = get_post_meta(get_the_ID(), '_lugar', true);

                if (!$fecha_inicio || !$fecha_fin) continue;

                $inicio_ts = strtotime($fecha_inicio);
                $fin_ts = strtotime($fecha_fin);

                for ($ts = $inicio_ts; $ts <= $fin_ts; $ts += 86400) {
                    $dia = intval(date('j', $ts));
                    $mes_actual = intval(date('n', $ts));
                    $anio_actual = intval(date('Y', $ts));

                    if ($mes_actual == $mes && $anio_actual == $anio) {
                        $eventos_por_dia[$dia][] = [
                            'nombre' => sanitize_text_field($nombre),
                            'fecha_inicio' => date_i18n('j \d\e F', $inicio_ts),
                            'fecha_fin' => date_i18n('j \d\e F', $fin_ts),
                            'lugar' => sanitize_text_field($lugar),
                            'timestamp_inicio' => $inicio_ts,
                        ];
                    }
                }
            }
            wp_reset_postdata();
        }

        echo '<h2 class="calendario-titulo">Eventos</h2>';
        // Botones de visualización
        echo '<div class="contenedor-botones">';
        echo '<button id="btn-ver-calendario" class="boton-switch-custom  activo">Calendario</button>';
        echo '<button id="btn-ver-shortcode" class="boton-switch-custom ">Lista de eventos</button>';
        echo '</div>';

        // Contenedor CALENDARIO
        echo '<div id="contenedor-calendario" class="calendario-eventos">';
        echo '<p class="calendario-mes">' . $mes_nombre . ' ' . $anio . '</p>';

        $dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        echo '<div class="calendario-cabecera">';
        foreach ($dias as $d) {
            echo '<div class="calendario-dia-nombre">' . $d . '</div>';
        }
        echo '</div>';

        echo '<div class="calendario-cuerpo">';
        for ($i = 1; $i < $dia_semana; $i++) {
            echo '<div></div>';
        }

        $evento_id = 0;

        for ($dia = 1; $dia <= $dias_mes; $dia++) {
            echo '<div class="dia-calendario" data-dia="' . $dia . '">';
            echo '<strong>' . $dia . '</strong>';

            if (isset($eventos_por_dia[$dia])) {
                echo '<div class="mobile-dot"></div>';
                foreach ($eventos_por_dia[$dia] as $evento) {
                    $nombre = esc_html($evento['nombre']);
                    $truncado = mb_strlen($nombre) > 5 ? mb_substr($nombre, 0, 5) . '...' : $nombre;
                    $es_pasado = $evento['timestamp_inicio'] < strtotime(date('Y-m-d'));
                    $clase_chip = 'evento-chip' . ($es_pasado ? ' evento-pasado' : '');

                    $evento_id++;
                    echo '<div class="' . esc_attr($clase_chip) . '" data-evento-id="' . $evento_id . '" data-nombre="' . esc_attr($nombre) . '" data-fecha-inicio="' . esc_attr($evento['fecha_inicio']) . '" data-fecha-fin="' . esc_attr($evento['fecha_fin']) . '" data-lugar="' . esc_attr($evento['lugar']) . '" tabindex="0" role="button" aria-pressed="false" aria-label="Mostrar información del evento ' . esc_attr($nombre) . '">' . esc_html($truncado) . '</div>';
                }
            }

            echo '</div>';
        }

        echo '</div>'; // calendario-cuerpo
        echo '</div>'; // contenedor-calendario

        // Contenedor SHORTCODE (oculto al inicio)
        echo '<div id="contenedor-shortcode" style="display: none;">';
        echo do_shortcode('[eventos_torneos]');
        echo '</div>';

        // Modal para mobile
        echo '<div id="modal-eventos" class="modal-eventos" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-contenido">
            <button class="cerrar-modal" aria-label="Cerrar">&times;</button>
            <div id="modal-contenido-evento"></div>
        </div>
    </div>';

        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnCalendario = document.getElementById("btn-ver-calendario");
            const btnShortcode = document.getElementById("btn-ver-shortcode");
            const contCalendario = document.getElementById("contenedor-calendario");
            const contShortcode = document.getElementById("contenedor-shortcode");

            btnCalendario.addEventListener("click", function() {
                contCalendario.style.display = "block";
                contShortcode.style.display = "none";
                btnCalendario.classList.add("activo");
                btnShortcode.classList.remove("activo");
            });

            btnShortcode.addEventListener("click", function() {
                contCalendario.style.display = "none";
                contShortcode.style.display = "block";
                btnCalendario.classList.remove("activo");
                btnShortcode.classList.add("activo");
            });
        });
        </script>';

        wp_localize_script(
            'wc-calendario-js', 
            'pwc_eventos',     
            ['datos' => $eventos_por_dia] 
        );
    }
}
