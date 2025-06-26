<?php

// Evita el acceso directo al archivo a través de la URL, y si no está definida la constante ABSPATH se termina la ejecución del script.
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

// Esta funcion se encarga de incluir los archivos de hojas de estilo necesarios del tema hijo
function bloghash_hijo_enqueue_styles() {
    $parent_style = 'parent-style';

    // Estilos del tema padre (en assets/css/style.css)
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/assets/css/style.css');

    // Estilos del tema hijo (en assets/css/styles.css)
    wp_enqueue_style('child-style',
        get_stylesheet_directory_uri() . '/assets/css/styles.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'bloghash_hijo_enqueue_styles');

// Esta funcion se encarga de eliminar el sidebar en la página con ID 34
function bloghash_remover_sidebar_en_pagina_34() {
    if ( is_page(34) ) {
        remove_all_actions( 'bloghash_sidebar' );
    }
}
add_action( 'template_redirect', 'bloghash_remover_sidebar_en_pagina_34' );

// Esta funcion se encarga de ocultar los widgets del sidebar en la página con ID 34
function ocultar_widgets_en_pagina_34( $sidebars_widgets ) {
    if ( is_page(34) ) {
        $sidebars_widgets['sidebar-1'] = array(); // Vacía el sidebar
    }
    return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'ocultar_widgets_en_pagina_34' );


function includes() {
    require get_stylesheet_directory() . '/inc/template-parts.php';
    require get_template_directory() . '/inc/customizer/dynamic-styles.php';
    require get_template_directory() . '/inc/customizer/default.php';
    require get_template_directory() . '/inc/customizer/customizer.php';
    require get_template_directory() . '/inc/common.php';
    require get_template_directory() . '/inc/template-parts.php';
}