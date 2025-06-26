<?php
/**
 * Template Name: Galería Personalizada
 */

// Evita el acceso directo al archivo a través de la URL, y si no está definida la constante ABSPATH se termina la ejecución del script.
if ( ! defined( 'ABSPATH' ) ) {
	exit;  
}

//Para mostrar el header en la plantilla personalizada
get_header(); ?>

<!-- Swiper CSS, carga una hoja de estilos externa para la biblioteca Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>

<main>
    <section class="contenedor-carrusel">
        <h3><?php the_title(); ?></h3>

        <div class="swiper swiper-custom">
            <div class="swiper-wrapper">

                <?php
                // Incluye y muestra imágenes dentro del contenido
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $content = apply_filters('the_content', get_the_content());
                        preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);
                        if (!empty($matches[1])) {
                            foreach ($matches[1] as $src) {
                                echo '<div class="swiper-slide swiper-slide-custom"><img src="'.esc_url($src).'" alt="" /></div>';
                            }
                        }
                    endwhile;
                endif;

                // Incluye y muestra imágenes adjuntas a la página (no insertadas)
                $attached_images = get_attached_media('image', get_the_ID());
                foreach ($attached_images as $img) {
                    $url = wp_get_attachment_url($img->ID);
                    if (!in_array($url, $matches[1] ?? [])) {
                        echo '<div class="swiper-slide swiper-slide-custom"><img src="'.esc_url($url).'" alt="" /></div>';
                    }
                }
                ?>

            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
</main>

<!-- Carga el archivo JavaScript de la biblioteca Swiper desde el CDN de jsDelivr. Este archivo permite crear sliders/carouseles interactivos en la página. -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
    // Inicializa una instancia de Swiper para el contenedor con la clase 'swiper'.
    // Se habilita el bucle infinito, la paginación clickeable y los botones de navegación.
    const swiper = new Swiper('.swiper', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        }
    });
</script>

<!-- Para mostrar el footer en la plantilla personalizada -->
<?php get_footer(); ?>