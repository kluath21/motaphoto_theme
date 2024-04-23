<?php
function motaphoto_enqueue_styles() {
    wp_enqueue_style('motaphoto_style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_styles');
function motaphoto_enqueue_scripts() {
    wp_enqueue_script('motaphoto_script', get_template_directory_uri() . '/script.js', [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_scripts');
function motaphoto_register_menus() {
    register_nav_menus(array(
        'header-main-menu' => __('Header Main Menu', 'motaphoto'),
        'footer-menu' => __('Footer Menu', 'motaphoto')
    ));
}
add_action('after_setup_theme', 'motaphoto_register_menus');

add_action( 'wp', 'var_dump_current_post_taxonomies_and_meta' );
function var_dump_current_post_taxonomies_and_meta() {
    global $post;
    if (is_single() || is_page()) {
        var_dump($post);
        
        // Obtenez les métadonnées du post
        $post_meta = get_post_meta($post->ID);
        var_dump($post_meta);
        
        // Obtenez les termes pour chaque taxonomie associée au post
        $taxonomies = get_post_taxonomies($post->ID);
        $taxonomy_terms = array();
        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms($post->ID, $taxonomy);
            if ($terms) {
                $taxonomy_terms[$taxonomy] = $terms;
            }
        }
        
        // Affichez les termes de la taxonomie
        var_dump($taxonomy_terms);
    }
}