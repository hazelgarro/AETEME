<?php
/**
 * Plugin Name: Widget Calendario
 * Description: Muestra un calendario de eventos personalizado.
 * Version: 1.0
 * Author: Diana
 */

defined('ABSPATH') || exit;


function mew_cargar_widgets($widgets_manager) {
    require_once __DIR__ . '/widgets/calendario-widget.php';

    $widgets_manager->register(new \Elemento_Widget_Calendario());
}
add_action( 'elementor/widgets/widgets_registered', 'mew_cargar_widgets' );

// Cargar CSS y JS
function wc_enqueue_assets() {
    wp_register_style( 'wc-calendario-css', plugins_url( 'assets/css/calendario.css', __FILE__ ) );
    wp_register_script( 'wc-calendario-js', plugins_url( 'assets/js/calendario.js', __FILE__ ), [], null, true );
}
add_action( 'wp_enqueue_scripts', 'wc_enqueue_assets' );
