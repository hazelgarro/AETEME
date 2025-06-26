<?php
defined('ABSPATH') || exit; //Seguridad: evita el acceso directo al archivo.

//Registra el Custom Post Type "torneo" para eventos deportivos
function registrar_cpt_torneos() {
    $labels = array(
        'name'               => 'Torneos',
        'singular_name'      => 'Torneo',
        'add_new'            => 'Añadir nuevo',
        'add_new_item'       => 'Añadir nuevo Torneo',
        'edit_item'          => 'Editar Torneo',
        'new_item'           => 'Nuevo Torneo',
        'all_items'          => 'Todos los Torneos',
        'view_item'          => 'Ver Torneo',
        'search_items'       => 'Buscar Torneos',
        'not_found'          => 'No se encontraron torneos',
        'not_found_in_trash' => 'No hay torneos en la papelera',
        'menu_name'          => 'Torneos'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
    );

    register_post_type('torneo', $args);
}
add_action('init', 'registrar_cpt_torneos');

//Agrega un metabox personalizado con campos extra para los torneos.
function torneos_agregar_metaboxes() {
    add_meta_box(
        'torneo_datos',
        'Datos del Torneo',
        'torneo_formulario_campos',
        'torneo',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'torneos_agregar_metaboxes' );

//Formulario HTML del metabox para capturar datos personalizados.
function torneo_formulario_campos( $post ) {
    //Seguridad: nonce para validación
    wp_nonce_field( 'torneo_guardar_campos', 'torneo_nonce' );

    //Obtener valores actuales
    $fecha_inicio = get_post_meta( $post->ID, '_fecha_inicio', true );
    $fecha_fin    = get_post_meta( $post->ID, '_fecha_fin', true );
    $lugar        = get_post_meta( $post->ID, '_lugar', true );

    //Campos del formulario
    //Fecha inicio
    echo '<p><label><strong>Fecha de inicio:</strong><br>';
    echo '<input type="date" name="fecha_inicio" value="' . esc_attr( $fecha_inicio ) . '" style="width:200px;"></label></p>';

    //Fecha fin
    echo '<p><label><strong>Fecha de finalización:</strong><br>';
    echo '<input type="date" name="fecha_fin" value="' . esc_attr( $fecha_fin ) . '" style="width:200px;"></label></p>';

    //Lugar
    echo '<p><label><strong>Lugar:</strong><br>';
    echo '<input type="text" name="lugar" value="' . esc_attr( $lugar ) . '" style="width:100%;"></label></p>';

    echo '<p style="color:#555;">La descripción larga del torneo se edita en el cuadro grande de contenido de WordPress (editor).</p>';
}

//Guarda los campos personalizados al guardar un torneo.
function torneo_guardar_campos( $post_id ) {
    
    // Verificaciones de seguridad: nonce, autosave y permisos
    if ( ! isset( $_POST['torneo_nonce'] ) || ! wp_verify_nonce( $_POST['torneo_nonce'], 'torneo_guardar_campos' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    //Guardar campos personalizados
    if ( isset( $_POST['fecha_inicio'] ) ) {
        update_post_meta( $post_id, '_fecha_inicio', sanitize_text_field( $_POST['fecha_inicio'] ) );
    }

    if ( isset( $_POST['fecha_fin'] ) ) {
        update_post_meta( $post_id, '_fecha_fin', sanitize_text_field( $_POST['fecha_fin'] ) );
    }

    if ( isset( $_POST['lugar'] ) ) {
        update_post_meta( $post_id, '_lugar', sanitize_text_field( $_POST['lugar'] ) );
    }
}
add_action( 'save_post_torneo', 'torneo_guardar_campos' );

//Agrega nuevas columnas personalizadas en la vista de lista del admin.
function torneo_columnas_admin( $columns ) {
    $columns['fecha_inicio'] = 'Inicio';
    $columns['fecha_fin']    = 'Fin';
    $columns['lugar']        = 'Lugar';
    return $columns;
}
add_filter( 'manage_torneo_posts_columns', 'torneo_columnas_admin' );

//Muestra el contenido de las columnas personalizadas en la vista admin.
function torneo_contenido_columnas_admin( $column, $post_id ) {
    if ( $column === 'fecha_inicio' ) {
        echo esc_html( get_post_meta( $post_id, '_fecha_inicio', true ) );
    }
    if ( $column === 'fecha_fin' ) {
        echo esc_html( get_post_meta( $post_id, '_fecha_fin', true ) );
    }
    if ( $column === 'lugar' ) {
        echo esc_html( get_post_meta( $post_id, '_lugar', true ) );
    }
}
add_action( 'manage_torneo_posts_custom_column', 'torneo_contenido_columnas_admin', 10, 2 );