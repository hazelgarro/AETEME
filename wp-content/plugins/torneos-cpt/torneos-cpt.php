<?php
/*
Plugin Name: Torneos
Description: Crea un Custom Post Type para torneos deportivos.
Version: 1.0
Author: Diana
*/

defined('ABSPATH') || exit; //Seguridad: evita el acceso directo al archivo.

/**
 * Este plugin registra un Custom Post Type llamado "torneo" 
 * para gestionar eventos deportivos desde el panel de WordPress.
 *
 * La funcionalidad principal se encuentra en el archivo:
 *   includes/cpt-torneos.php
 * 
 * Este archivo principal solo se encarga de cargar dicho archivo.
 */

 //Incluye el archivo que define y registra el CPT "torneo"
require_once plugin_dir_path(__FILE__) . 'includes/cpt-torneos.php';
