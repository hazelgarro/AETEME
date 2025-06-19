<?php
/*
Plugin Name: Eventos Torneos por Mes
Description: Muestra los torneos del CPT por mes en bloques de 3 meses con navegación AJAX.
Version: 1.1
Author: Diana
*/

if (!defined('ABSPATH')) exit;

define('CT_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Estilos y JS
function ct_enqueue_assets() {
    wp_enqueue_style('ct-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('ct-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], null, true);
    wp_localize_script('ct-script', 'ct_ajax', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'ct_enqueue_assets');

//shortcode
function ct_render_eventos() {
    $mes = date('n');
    $anio = date('Y');

    $contenido = ct_get_torneos_html($mes, $anio);
    return "<div id='ct-eventos'>$contenido</div>";
}

add_shortcode('eventos_torneos', 'ct_render_eventos');

require_once CT_PLUGIN_PATH . 'includes/ajax-handler.php';

