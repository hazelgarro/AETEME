<?php
/**
 * Plugin Name: Hero Destacado
 * Description: Muestra el post más reciente en grande y los tres siguientes a la par.
 * Version: 1.0
 * Author: Dana
 */

// Evita el acceso directo al archivo
if (!defined('ABSPATH')) exit;

/**
 * Función para cargar la hoja de estilos del plugin
 */
function hero_destacado_enqueue_styles() {
    wp_enqueue_style('hero-destacado-style'// identificador único del estilo
    , plugin_dir_url(__FILE__) . 'css/style.css'); // ruta del archivo CSS
}
// Hook para cargar los estilos en el frontend
add_action('wp_enqueue_scripts', 'hero_destacado_enqueue_styles');
// Carga nuevamente el estilo con un parámetro de versión basado en el tiempo actual (evita caché)
wp_enqueue_style('hero-destacado-style', plugin_dir_url(__FILE__) . 'css/style.css', array(), time());

// Genera el HTML para el componente de hero destacado
 function mostrar_hero_destacado() {
    // Argumentos para la consulta de WP_Query
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4
    );
    // Realiza la consulta
    $query = new WP_Query($args);
    // Verifica si hay publicaciones
    if ($query->have_posts()) {
        $output = '<div class="hero-grid">';  // contenedor principal
        $count = 0;

        // Recorre cada publicación
        while ($query->have_posts()) {
            $query->the_post();
            
            // Extrae información de cada post
            $title = get_the_title();
            $link = get_permalink();
            $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $time = get_the_time('F j, Y');
            $category = get_the_category();
            $category_name = $category ? $category[0]->name : '';
            $category_slug = $category ? $category[0]->slug : '';

            // El primer post va en la izquierda del hero
            if ($count == 0) {
                $output .= '
                <div class="hero-left">
                    <a  href="' . $link . '">
                        <div class="hero-img" style=" background-image: url(' . $image . ')">
                            <span class="hero-category">' . esc_html($category_name) . '</span>
                            <h2>' . $title . '</h2>
                            <div class="hero-meta">
                                <span>' . get_the_author() . '</span>
                       <svg width="10" height="10">
                        <circle cx="4" cy="4" r="3" fill="#3498db" />
                        </svg> 
                        <svg class="bloghash-icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M400 64h-48V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H160V12c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v52H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zm-6 400H54c-3.3 0-6-2.7-6-6V160h352v298c0 3.3-2.7 6-6 6z"></path>                
                        </svg>
                        <span>' . $time . '</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="hero-right">
                ';
            } else {
                 // Los demás posts van en el lado derecho en forma de cuadrícula
                $output .= '
                    <a class="hero-grid-2" href="' . $link . '">
                        <img src="' . $image . '" alt="' . esc_attr($title) . '">
                        <div class="mini-info">
                            <span class="mini-category">' . esc_html($category_name) . '</span>
                            <h4>' . $title . '</h4>
                            <span class="mini-date">' . $time . '</span>
                        </div>
                    </a>
                ';
            }
            $count++;
        }

        $output .= '</div></div>'; // cerrar hero-right y hero-grid
        
        // Restaura la consulta global de WordPress
        wp_reset_postdata();
        return $output;
    } else {
        // Si no hay publicaciones disponibles
        return '<p>No hay noticias disponibles.</p>';
    }
}
add_shortcode('hero_destacado', 'mostrar_hero_destacado');
