<?php

// Bloquear acesso direto
if (!defined('ABSPATH')) {
    exit;
}
    
// Instalar Bootstrap
function ep_carregar_bootstrap() {
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), null, true);
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
}
add_action('wp_enqueue_scripts', 'ep_carregar_bootstrap');

// Carregar o ficheiro style.css
function ep_carregar_estilos() {
    wp_enqueue_style('ep-style', plugin_dir_url(__FILE__) . '../css/style.css');
}
add_action('wp_enqueue_scripts', 'ep_carregar_estilos');

?>