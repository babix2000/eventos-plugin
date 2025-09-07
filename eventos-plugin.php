<?php
/**
 * Plugin Name: Eventos Plugin
 * Description: Plugin simples para gerir e apresentar eventos (CPT + ACF + shortcode).
 * Version: 1.0.0
 * Author: Bárbara Barbosa
 */

// Bloquear acesso direto
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'inc/scripts.php';

// Registar Custom Post Type "Eventos"
function ep_registar_cpt_eventos() {

    $labels = array(
        'name'               => 'Eventos',
        'singular_name'      => 'Evento',
        'menu_name'          => 'Eventos',
        'name_admin_bar'     => 'Evento',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Evento',
        'new_item'           => 'Novo Evento',
        'edit_item'          => 'Editar Evento',
        'view_item'          => 'Ver Evento',
        'all_items'          => 'Todos os Eventos',
        'search_items'       => 'Procurar Eventos',
        'not_found'          => 'Nenhum evento encontrado',
        'not_found_in_trash' => 'Nenhum evento na lixeira',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_rest'       => true, 
        'menu_icon'          => 'dashicons-calendar-alt',
    );

    register_post_type('eventos', $args);
}
add_action('init', 'ep_registar_cpt_eventos');

// Verifica se o ACF está ativo
function ep_verificar_acf_ativo() {
    if (!class_exists('ACF')) {
        echo '<div class="notice notice-error is-dismissible">
            <p><strong>Eventos Plugin:</strong> É necessário que o plugin <strong>Advanced Custom Fields (ACF)</strong> esteja ativo para que o Eventos Plugin funcione corretamente.</p>
        </div>';
    }
}
add_action('admin_notices', 'ep_verificar_acf_ativo');

// Criação dos custom fields
if(function_exists('acf_add_local_field_group')){
    acf_add_local_field_group(array(
        'key' => 'group_eventos',
        'title' => 'Detalhes do Evento',
        'fields' => array(
            array(
                'key' => 'field_evento_data',
                'label' => 'Data do Evento',
                'name' => 'data_evento',
                'type' => 'date_picker',
                'required' => 1,
                'display_format' => 'd/m/Y',
                'return_format' => 'Y-m-d',
            ),
            array(
                'key' => 'field_evento_local',
                'label' => 'Local',
                'name' => 'local_evento',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_evento_organizador',
                'label' => 'Organizador',
                'name' => 'organizador_evento',
                'type' => 'text',
                'required' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'eventos',
                ),
            ),
        ),
    ));
}

// Shortcode para listar eventos futuros (Bootstrap)
function ep_shortcode_eventos_futuros($atts) {

    $atts = shortcode_atts(array(
        'limite' => -1,
    ), $atts, 'eventos_futuros');

    $hoje = date('Y-m-d');

    $query = new WP_Query(array(
        'post_type'      => 'eventos',
        'posts_per_page' => intval($atts['limite']),
        'meta_key'       => 'data_evento',
        'meta_value'     => $hoje,
        'meta_compare'   => '>=',
        'meta_type'      => 'DATE', 
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
    ));

    if ($query->have_posts()) {

        $output = '<h2 id="ep-eventos-title">--- EVENTOS FUTUROS ---</h2>';
        $output .= '<div class="row" id="ep-eventos-row">';

        while ($query->have_posts()) {
            $query->the_post();

            $data       = DateTime::createFromFormat('Y-m-d', get_field('data_evento'));
            $data_form = $data->format('d.m.Y');
            $local      = esc_html(get_field('local_evento'));
            $organizador= esc_html(get_field('organizador_evento'));
            $titulo     = get_the_title();
            $link       = get_permalink();
            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            if (!$thumb_url) {
                $thumb_url = includes_url('images/media/default.png'); 
            }

            $output .= '<div class="col-12 col-sm-6 col-md-4 mb-4">';
            $output .= '<a href="'.esc_url($link).'" class="text-decoration-none">'; 
            $output .= '<div class="d-flex flex-column card-evento">';

            $output .= '<span class="mb-1">'.$data_form.'</span>';

            if ($thumb_url) {
                $output .= '<div class="img-evento" style="background-image: url('.esc_url($thumb_url).');"></div>';
            }

            $output .= '<h5>'.$titulo.'</h5>';
            $output .= '<p class="mb-0"><i class="bi bi-geo-alt-fill me-1"></i>Local: '.$local.'</p>';
            $output .= '<p class="mb-0"><i class="bi bi-person-fill me-1"></i>Organizador: '.$organizador.'</p>';

            $output .= '</div>'; 
            $output .= '</a>';
            $output .= '</div>'; 
        }

        $output .= '</div>'; 

        wp_reset_postdata();
        return $output;

    } else {
        return '<p>Nenhum evento futuro encontrado.</p>';
    }
}
add_shortcode('eventos_futuros', 'ep_shortcode_eventos_futuros');

// Template Single Evento
function ep_single_eventos_template($template) {
    if ( is_singular('eventos') ) {
        $plugin_template = plugin_dir_path(__FILE__) . 'single-eventos.php';
        if ( file_exists($plugin_template) ) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'ep_single_eventos_template');
