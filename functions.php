<?php
function theme_add_jquery() {
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.7.1.min.js', array(), '3.7.1', true);
}
add_action('wp_enqueue_scripts', 'theme_add_jquery');
function theme_setup() {
    //active les images mises en avant dans le thème
    add_theme_support( 'post-thumbnails' ) ; 
    
    // Définir la taille des images mises en avant
    set_post_thumbnail_size( 563, 0 , false );
    
    // Définir d'autres tailles d'images
    add_image_size( 'header-size', 1440, 962, true );
    add_image_size('desktop-home', 600, 520, true);
    }
function motaphoto_enqueue_styles() {
    wp_enqueue_style('motaphoto_style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('fonts', get_template_directory_uri() . '/css/fonts.css');
}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_styles');
function motaphoto_enqueue_scripts() {
    // Enqueue the main script for the theme
    wp_enqueue_script('motaphoto_script', get_template_directory_uri() . '/script.js', ['jquery'], '1.0.0', true);

    // Localize script to pass necessary data to the frontend
    wp_localize_script('motaphoto_script', 'ajax_motaphoto', ['ajax_url' => admin_url('admin-ajax.php')]);  
   
}

// Hook the function into WordPress
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


// chargement des photos
function load_photos($args) {
    // Parse arguments to ensure they are structured as expected
    $args = wp_parse_args($args, array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => 1,
        'category' => '',
        'format' => '',
        'dateFilter' => 'recent',
    ));

    // Modify WP_Query arguments based on the parsed data
    $query_args = array(        
        'post_type' => $args['post_type'],
        'posts_per_page' => $args['posts_per_page'],
        'paged' => $args['paged'],
    );

    // Add additional filtering for category, format, and date
    $tax_query = array();
    if ($args['category']) {
        $tax_query[] = array(
            'taxonomy' => 'categorie-photo',
            'field' => 'slug',
            'terms' => $args['category'],
        );
    }

    if ($args['format']) {
        $tax_query[] = array(
            'taxonomy' => 'format-photo',
            'field' => 'slug',
            'terms' => $args['format'],
        );
    }

    if (!empty($tax_query)) {
        $query_args['tax_query'] = $tax_query;
    }

    // Sort order
    if ($args['dateFilter'] === 'recent') {
        $query_args['orderby'] = 'date';
        $query_args['order'] = 'DESC';
    } else {
        $query_args['orderby'] = 'date';
        $query_args['order'] = 'ASC';
    }

    $query = new WP_Query($query_args);

    $html = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $reference = get_post_meta(get_the_ID(), 'Référence', true);
            $image_id = get_post_thumbnail_id();  // Or use another field for a custom image ID
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'desktop-home') : '';

            $categories = get_the_terms(get_the_ID(), 'categorie-photo');
            $category_name = $categories && !empty($categories) ? esc_html($categories[0]->name) : '';

            $html .= '<div class="photo-item">';
            $html .= '<a href="' . esc_url(get_permalink()) . '">';
            $html .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
            $html .= '<span class="icon-container">';
            $html .= '<span class="photo-reference">' . esc_html($reference) . '</span>';
            $html .= '<span class="photo-category">' . esc_html($category_name) . '</span>';
            $html .= '<span class="photo-info-icon"><i class="fas fa-eye"></i></span>';
            $html .= '<span class="fullscreen-icon"><i class="fas fa-expand fullscreen-icon"></i></span>';
            $html .= '</span></a></div>';
        }
        wp_reset_postdata();
    } else {
        $html = '<p>Photo introuvable.</p>';
    }

    return $html;
}

// AJAX callback function
function ajax_load_photos() {
    // Get and parse AJAX parameters
    $args = array(
        'paged' => isset($_POST['page']) ? intval($_POST['page']) : 1,
        'category' => isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '',
        'format' => isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '',
        'dateFilter' => isset($_POST['dateFilter']) ? sanitize_text_field($_POST['dateFilter']) : 'recent',
    );

    $html = load_photos($args);

    wp_send_json_success($html);
}

// Hook the AJAX function
add_action('wp_ajax_load_photos_by_category_and_format', 'ajax_load_photos');
add_action('wp_ajax_nopriv_load_photos_by_category_and_format', 'ajax_load_photos');

add_action('wp_ajax_load_more_photos', 'handle_load_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'handle_load_photos');

function cookinfamily_request_recettes() {
$response = $_POST["category"] ;
$response = $_POST["format"] ;
$response = $_POST["dateFilter"] ;
    wp_send_json($response);
    wp_die();
}
add_action( 'wp_ajax_world', 'ajax_load_photos' ); 
add_action( 'wp_ajax_nopriv_world', 'ajax_load_photos' );
