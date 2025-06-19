<?php
/*
Plugin Name: Calculadora 
Plugin URI: https://dev-benjaminprueba01.pantheonsite.io/
Description: Calculadora de puntos ganados o perdidos tras una partida de tenis de mesa.
Version: 1.0
Author: Hazel Garro
Author URI: https://dev-benjaminprueba01.pantheonsite.io/ 
Text Domain: Calculadora
*/

if (!defined('ABSPATH')) {
    exit; 
}

function calculadora_shortcode() {
    ob_start();
    ?>

    <div class="contenedor">
        <div class="lado-izquierdo">
            <div>
                <label class="texto-azul"> Puntos del Ganador </label>
                <br>
                <input type="number" id="puntos_ganador" required>
                <span id="error_ganador" class="mensaje-error" style="display:none;"></span>
            </div>
            <br>
            <div>
                <label class="texto-azul"> Puntos del Perdedor </label>
                <br>
                <input type="number" id="puntos_perdedor" required>
                <span id="error_perdedor" class="mensaje-error" style="display:none;"></span>
            </div>
            <br>
            <div class="flex-center">
                <button id="calcular" class="margin-top-2">Calcular</button>
                <button id="limpiar" class="margin-top-2 margin-left-1">Limpiar</button>
            </div>
        </div>
        <div class="lado-derecho">
            <p class="texto-blanco titulo-seccion">Resultados</p>

            <div class="bloque-resultado">
                <p class="etiqueta texto-centrado">El ganador suma</p>
                <p class="valor texto-centrado" id="puntos_ganador_resultado">—</p>
            </div>

            <hr class="divisor">

            <div class="bloque-resultado">
                <p class="etiqueta texto-centrado">El perdedor pierde</p>
                <p class="valor texto-centrado" id="puntos_perdedor_resultado">—</p>
            </div>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('calculadora', 'calculadora_shortcode');

function calculadora_enqueue_scripts() {
    wp_enqueue_script('calculadora-script', plugin_dir_url(__FILE__) . 'assets/js/calculadora.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'calculadora_enqueue_scripts');

function calculadora_enqueue_styles() {
    wp_enqueue_style('calculadora-style', plugin_dir_url(__FILE__) . 'assets/css/calculadora.css');
}
add_action('wp_enqueue_scripts', 'calculadora_enqueue_styles');
?>
