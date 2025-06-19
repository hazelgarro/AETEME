<?php
/*
Plugin Name: Tenis Equipo Stats
Description: Muestra estadísticas del equipo AETEME
Version: 1.0
Author: Hazel Garro
*/

add_shortcode('tenis_stats', 'mostrar_stats_equipo');

function mostrar_stats_equipo($atts) {
    $atts = shortcode_atts([
        'anios' => '0',
        'miembros' => '0',
        'torneos' => '0',
        'medallas' => '0',
    ], $atts);

    ob_start();
    ?>
    <div class="stats">
        <div class="tenis-stats">
        <div class="stat"><?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/icons/anios.svg'); ?><p><?php echo $atts['anios']; ?> años</p></div>
        <div class="stat"><?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/icons/miembros.svg'); ?><p><?php echo $atts['miembros']; ?> miembros</p></div>
        <div class="stat"><?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/icons/torneos.svg'); ?><p><?php echo $atts['torneos']; ?> torneos</p></div>
        <div class="stat"><?php echo file_get_contents(plugin_dir_path(__FILE__) . 'assets/icons/medallas.svg'); ?><p><?php echo $atts['medallas']; ?> medallas</p></div>
    </div>
    <?php
    return ob_get_clean();
}

add_action('wp_enqueue_scripts', 'tenis_stats_estilos');
function tenis_stats_estilos() {
    wp_enqueue_style(
        'tenis-stats-css',
        plugins_url('assets/css/styles.css', __FILE__),
        [],
        '1.0'
    );
}
?>

