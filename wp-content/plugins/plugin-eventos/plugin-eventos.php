<?php
/*
Plugin Name: Eventos Torneos por Mes
Description: Muestra los torneos del CPT por mes en bloques de 3 meses con navegación AJAX.
Version: 1.1
Author: Diana
*/

if (!defined('ABSPATH')) exit; //Seguridad: evita el acceso directo al archivo

define('CT_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Estilos y scripts necesarios para el plugin.
 * El archivo JS se localiza con la URL para llamadas AJAX.
 */
function ct_enqueue_assets() {
    wp_enqueue_style('ct-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('ct-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], null, true);
    
    //Pasar ajaxurl al script JS
    wp_localize_script('ct-script', 'ct_ajax', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'ct_enqueue_assets');

/**
 * Shortcode [eventos_torneos]
 * Renderiza los torneos del mes actual al cargar la página
 * Luego mediante AJAX se puede navegar entre trimestres
 *
 * @return string HTML con el contenedor inicial de torneos.
 */
function ct_render_eventos() {
    $mes = date('n');
    $anio = date('Y');

    $contenido = ct_get_torneos_html($mes, $anio);
    return "<div id='ct-eventos'>$contenido</div>";
}

add_shortcode('eventos_torneos', 'ct_render_eventos');

//Carga del manejador AJAX desde la carpeta includes
require_once CT_PLUGIN_PATH . 'includes/ajax-handler.php';

