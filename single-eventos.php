<?php

// Bloquear acesso direto
if (!defined('ABSPATH')) {
    exit;
}

get_header();

?>

<div class="container my-5" id="single-evento">

    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();

            $data = DateTime::createFromFormat('Y-m-d', get_field('data_evento'));
            $data_form = $data->format('d.m.Y');
            $local = esc_html(get_field('local_evento'));
            $organizador = esc_html(get_field('organizador_evento'));
            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ( !$thumb_url ) {
                $thumb_url = includes_url('images/media/default.png');
            }
    ?>

    <div class="row mb-4 start-evento">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="img-evento" style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
        </div>

        <div class="col-md-8 d-flex flex-column justify-content-between">
            <h1><?php the_title(); ?></h1>
            
            <div>
                <p class="mb-1"> <i class="bi bi-calendar-check me-1"></i>Data: <?php echo $data_form; ?></p>
                <p class="mb-1"><i class="bi bi-geo-alt-fill me-1"></i>Local: <?php echo $local; ?></p>
                <p class="mb-0"><i class="bi bi-person-fill me-1"></i>Organizador: <?php echo $organizador; ?></p>
            </div>  
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-4" style="font-family: Montserrat, sans-serif;">
            <?php the_content(); ?>
        </div>
    </div>

    <?php
        endwhile;
    endif;
    ?>

</div>

<div class="d-flex justify-content-between my-4 mx-4" id="nav-eventos">
    <?php
    $prev_event = get_previous_post();
    if ($prev_event) :
        $prev_title = get_the_title($prev_event->ID);
        $prev_link  = get_permalink($prev_event->ID);
    ?>
        <a href="<?php echo esc_url($prev_link); ?>" class="text-decoration-none">
            <i class="bi bi-chevron-left"></i> <?php echo esc_html($prev_title); ?>
        </a>
    <?php else: ?>
        <div></div> 
    <?php endif; ?>

    <?php
    $next_event = get_next_post();
    if ($next_event) :
        $next_title = get_the_title($next_event->ID);
        $next_link  = get_permalink($next_event->ID);
    ?>
        <a href="<?php echo esc_url($next_link); ?>" class="text-decoration-none"><?php echo esc_html($next_title); ?> <i class="bi bi-chevron-right"></i>
        </a>
    <?php else: ?>
        <div></div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
