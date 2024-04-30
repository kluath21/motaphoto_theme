<?php get_header(); ?>

<main>

    <!--  Header image aléatoire  -->
    <div class="hero-header">
        <img class="hero-header__title" src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-header.png" alt="Titre du header : PHOTOGRAPHE EVENT">
        <?php  // Configuration de la requête pour obtenir un tableau des images de tous les posts
        $query_images_args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
        );

        ?>
        <?php // Récupération des images
        $query_images = new WP_Query($query_images_args);
        $images = array();
        // Boucle pour obtenir les images
        foreach ($query_images->posts as $image) {
            // Ajoute les images dans le tableau
            $images[] = wp_get_attachment_image_src($image->ID, "header-size");
        }
        // Génère un nombre aléatoire entre 0 et le nombre d'images
        $aleatoire = rand(0,  count($images) - 1);
        // Récupére l'attribut alt de l'image actuelle
        $alt_text = get_post_meta($query_images->posts[$aleatoire]->ID, '_wp_attachment_image_alt', true);
        // Affiche l'image aléatoire avec la classe "hero-header__img", l'attribut et la source
        echo '<img class="hero-header__img" alt="' . esc_attr($alt_text) . '" src="' . $images[$aleatoire][0] . '">';

        ?>
    </div>

    <section class="filter-section">
        <!-- Category Filter -->
        <div class="filter-container category-filter-section">
            <label class="filter-label" for="category-filter">CATÉGORIES</label>
            <div class="custom-dropdown" id="category-dropdown">
                <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> CATÉGORIES</button>
                <ul class="dropdown-list">
                    <li data-value="" data-label="Toutes">Toutes</li>
                    <?php
                    // Récupération des catégories
                    $categories = get_terms(array(
                        'taxonomy' => 'categorie-photo',
                        'hide_empty' => false,
                    ));
                    // Boucle pour afficher les catégories
                    foreach ($categories as $category) {
                        $label = get_field('label', $category);
                        $label = $label ? $label : $category->name;
                        echo '<li data-value="' . esc_attr($category->slug) . '" data-label="' . esc_attr($label) . '">' . esc_html($label) . '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Format Filter -->
        <div class="filter-container format-filter-section">
            <label class="filter-label" for="format-filter">FORMATS</label>
            <div class="custom-dropdown" id="format-dropdown">
                <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> FORMATS</button>
                <ul class="dropdown-list">
                    <li data-value="" data-label="Tous">Tous</li>
                    <?php
                    // Récupération des formats
                    $formats = get_terms(array(
                        'taxonomy' => 'format-photo',
                        'hide_empty' => false,
                    ));
                    // Boucle pour afficher les formats
                    foreach ($formats as $format) {
                        $label = get_field('label', $format);
                        $label = $label ? $label : $format->name;
                        echo '<li data-value="' . esc_attr($format->slug) . '" data-label="' . esc_attr($label) . '">' . esc_html($label) . '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Trie par date -->
        <div class="filter-container date-filter-section">
            <label class="filter-label" for="date-filter">TRIER PAR</label>
            <div class="custom-dropdown" id="date-dropdown">
                <button class="dropdown-toggle"><i class="fas fa-chevron-down"></i> TRIER PAR</button>
                <ul class="dropdown-list">
                    <li data-value="recent" data-label="Plus récentes">Plus récentes</li>
                    <li data-value="old" data-label="Plus anciennes">Plus anciennes</li>
                </ul>
            </div>
        </div>
    </section>

    <?php
    // Configuration de la requête pour obtenir les premières photos
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
    ?>
        <!-- liste des Photos  -->
        <section class="photo-list-section">
            <div class="photo-list-container">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="photo-item">
                    <a href="<?php echo esc_url(get_permalink()); ?>">
    <?php
    // prend l'ID de l'image
    $image_id = get_post_meta(get_the_ID(), 'image', true);

    // vérifie si l'ID de l'image existe
    if ($image_id) {
        // prend l'URL de l'image
        $image_url = wp_get_attachment_image_url($image_id, 'desktop-home');

        if ($image_url) {
            // affiche l'image
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-image">';
        } else {
            echo '<p>No image available.</p>';
        }
    } else {
        echo '<p>No image available.</p>';
    }
    ?>
</a>
                        <span class="icon-container">
                            <span class="photo-reference">
                                <?php
                                $reference = get_post_meta(get_the_ID(), 'Référence', true);
                                if ($reference) {
                                    echo esc_html($reference);
                                }
                                ?>
                            </span>
                            <span class="photo-category">
                                <?php
                                $categories = get_the_terms(get_the_ID(), 'categorie-photo');
                                if ($categories) {
                                    echo esc_html($categories[0]->name);
                                }
                                ?>
                            </span>
                            <span class="photo-info-icon"><i class="fas fa-eye"></i></span>
                            <span class="fullscreen-icon"><i class="fas fa-expand fullscreen-icon"></i></span>
                        </span>
                        </a>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

            <!-- "Load More" Button for Infinite Scroll -->
            <div class="load-more-container">
                <button id="load-more-button">Charger plus</button>
            </div>
        </section>

    <?php else :
        echo 'Aucune photo trouvée.';
    endif;
    ?>
    <?php get_footer(); ?>