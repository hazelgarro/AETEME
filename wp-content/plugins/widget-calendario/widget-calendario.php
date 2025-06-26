<?php
/**
 * Plugin Name: Widget Calendario
 * Description: Muestra un calendario de eventos personalizado.
 * Version: 1.0
 * Author: Diana
 */

defined('ABSPATH') || exit; //Seguridad: evita el acceso directo al archivo.

/**
 * Función que carga y registra el widget personalizado de calendario
 * dentro del constructor de widgets de Elementor.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager El administrador de widgets de Elementor.
 */
function mew_cargar_widgets($widgets_manager) {
     //Incluye el archivo PHP que contiene la clase del widget
    require_once __DIR__ . '/widgets/calendario-widget.php';

     // Registra una nueva instancia del widget con Elementor.
    $widgets_manager->register(new \Elemento_Widget_Calendario());
}

//Hook para registrar el widget al cargarse Elementor
add_action( 'elementor/widgets/widgets_registered', 'mew_cargar_widgets' );

//Registra los archivos CSS y JS del calendario para su uso en el sitio.
function wc_enqueue_assets() {
     //Registra el archivo CSS del calendario
    wp_register_style( 'wc-calendario-css', plugins_url( 'assets/css/calendario.css', __FILE__ ) );
   
   //Registra el archivo JS del calendario
    wp_register_script( 'wc-calendario-js', plugins_url( 'assets/js/calendario.js', __FILE__ ), [], null, true );
}

//Hook para registrar los recursos en el frontend
add_action( 'wp_enqueue_scripts', 'wc_enqueue_assets' );
