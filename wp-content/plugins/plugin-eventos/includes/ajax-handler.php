<?php

//Registrar acciones AJAX
add_action('wp_ajax_ct_cargar_torneos', 'ct_render_torneos_ajax');
add_action('wp_ajax_nopriv_ct_cargar_torneos', 'ct_render_torneos_ajax');

/**
 * Controlador AJAX que imprime el HTML de los torneos para el mes y año dados.
 * Se utiliza en la navegación entre trimestres.
 */
function ct_render_torneos_ajax()
{
    $mes_actual = isset($_POST['mes']) ? intval($_POST['mes']) : date('n');
    $anio_actual = isset($_POST['anio']) ? intval($_POST['anio']) : date('Y');

    echo ct_get_torneos_html($mes_actual, $anio_actual);
    wp_die(); // Finaliza correctamente la ejecución AJAX
}

/**
 * Genera el HTML de los torneos agrupados por mes, mostrando 3 meses consecutivos.
 * 
 * @param int $mes_actual Mes inicial
 * @param int $anio_actual Año inicial
 * @return string HTML renderizado
 */
function ct_get_torneos_html($mes_actual, $anio_actual)
{
    ob_start();

    //Consulta de torneos desde el mes actual en adelante
    $eventos = new WP_Query([
        'post_type' => 'torneo',
        'posts_per_page' => -1,
        'meta_key' => '_fecha_inicio',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => '_fecha_inicio',
                'value' => "$anio_actual-" . str_pad($mes_actual, 2, '0', STR_PAD_LEFT) . "-01",
                'compare' => '>=',
                'type' => 'DATE',
            ],
        ]
    ]);

    $eventos_por_mes = [];

    //Se agrupan eventos por mes/año
    if ($eventos->have_posts()) {
        while ($eventos->have_posts()) {
            $eventos->the_post();
            $fecha_inicio = get_post_meta(get_the_ID(), '_fecha_inicio', true);
            $fecha_fin = get_post_meta(get_the_ID(), '_fecha_fin', true);
            $timestamp = strtotime($fecha_inicio);
            $mes = date('n', $timestamp);
            $anio = date('Y', $timestamp);

            $clave = "$anio-$mes";
            if (!isset($eventos_por_mes[$clave])) $eventos_por_mes[$clave] = [];

            $eventos_por_mes[$clave][] = [
                'titulo' => get_the_title(),
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'lugar' => get_post_meta(get_the_ID(), '_lugar', true),
            ];
        }
        wp_reset_postdata();
    }

    //Mostrar bloques de 3 meses
    for ($i = 0; $i < 3; $i++) {
        $mes_i = $mes_actual + $i;
        $anio_i = $anio_actual;
        if ($mes_i > 12) {
            $mes_i -= 12;
            $anio_i += 1;
        }

        $clave = "$anio_i-$mes_i";

        $titulo_mes = ucfirst(date_i18n("F Y", mktime(0, 0, 0, $mes_i, 1, $anio_i)));
        echo "<div class='ct-mes-titulo'><span class='ct-mes-nombre'>$titulo_mes</span><span class='ct-linea'></span></div>";


        if (!empty($eventos_por_mes[$clave])) {
            foreach ($eventos_por_mes[$clave] as $evento) {
                $fecha_inicio = $evento['fecha_inicio'];
                $fecha_fin = $evento['fecha_fin'];
                $timestamp_inicio = strtotime($fecha_inicio);
                $timestamp_fin = $fecha_fin ? strtotime($fecha_fin) : false;

                if ($fecha_fin && $fecha_inicio !== $fecha_fin) {
                    $mismo_mes = date('n', $timestamp_inicio) === date('n', $timestamp_fin);
                    $texto_fecha = $mismo_mes
                        ? date_i18n("l j", $timestamp_inicio) . ' al ' . date_i18n("l j \\d\\e F", $timestamp_fin)
                        : date_i18n("l j \\d\\e F", $timestamp_inicio) . ' al ' . date_i18n("l j \\d\\e F", $timestamp_fin);
                } else {
                    $texto_fecha = date_i18n("l j \\d\\e F", $timestamp_inicio);
                }

                echo "<div class='ct-torneo'>";
                echo "<div class='ct-fecha'>$texto_fecha</div>";
                echo "<strong class='ct-titulo-evento'>{$evento['titulo']}</strong><br>";
                echo "<small class='ct-lugar'>Lugar: {$evento['lugar']}</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay eventos programados.</p>";
        }
    }

    //Navegación entre trimestres
    $hay_anterior = ct_tiene_eventos_en_rango($mes_actual, $anio_actual, 'anterior');
    $hay_siguiente = ct_tiene_eventos_en_rango($mes_actual, $anio_actual, 'siguiente');

    $mes_anterior = $mes_actual - 3;
    $anio_anterior = $anio_actual;
    if ($mes_anterior < 1) {
        $mes_anterior += 12;
        $anio_anterior -= 1;
    }

    $mes_siguiente = $mes_actual + 3;
    $anio_siguiente = $anio_actual;
    if ($mes_siguiente > 12) {
        $mes_siguiente -= 12;
        $anio_siguiente += 1;
    }


    echo "<div class='ct-controles'>";
    echo "<button class='ct-boton' data-mes='$mes_anterior' data-anio='$anio_anterior'" . ($hay_anterior ? '' : ' disabled') . ">Anteriores</button>";
    echo "<button class='ct-boton' data-mes='$mes_siguiente' data-anio='$anio_siguiente'" . ($hay_siguiente ? '' : ' disabled') . ">Siguientes</button>";
    echo "</div>";

    return ob_get_clean();
}

/**
 * Verifica si existen eventos en el trimestre anterior o siguiente.
 *
 * @param int $mes Mes actual
 * @param int $anio Año actual
 * @param string $direccion 'anterior' o 'siguiente'
 * @return bool
 */
function ct_tiene_eventos_en_rango($mes, $anio, $direccion = 'siguiente')
{
    if ($mes < 1) {
        $mes += 12;
        $anio -= 1;
    } elseif ($mes > 12) {
        $mes -= 12;
        $anio += 1;
    }

    // Si estamos yendo hacia el atrás, se buscan eventos antes de la fecha inicial
    if ($direccion === 'anterior') {
        $fecha_limite = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $anio));
        $compare = '<';
    } else { // Hacia adelante
        $fecha_limite = date("Y-m-t", mktime(0, 0, 0, $mes + 2, 1, $anio)); // fin del trimestre
        $compare = '>';
    }

    $query = new WP_Query([
        'post_type' => 'torneo',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_fecha_inicio',
                'value' => $fecha_limite,
                'compare' => $compare,
                'type' => 'DATE',
            ],
        ]
    ]);

    return $query->have_posts();
}
