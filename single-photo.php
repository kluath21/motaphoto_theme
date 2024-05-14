<?php
get_header(); ?>

<main id="main-container" class="main-site">
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>  
        <div class="post">
            <div class="post-top-container">

                <div class="post-left-container">
                    <h2 class="post-left-container__title"><?php the_title(); ?></h2>
                    <div class="post-left-container__fields">
                        <ul>
                            <li>Référence : <?php the_field('reference_'); ?></li>
                            <li>Catégorie : <?php echo strip_tags(get_the_term_list($post->slug, 'categorie-photo')); ?></li>
                            <li>Format : <?php echo strip_tags(get_the_term_list($post->slug, 'format-photo')); ?></li>
                            <li>Type : <?php the_field('type'); ?></li>
                            <li>Année : <?php echo get_the_date('Y'); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="post-right-container">
                    <div class="post__image photo-item">
                    <?php 
                        $image_id = get_post_meta(get_the_ID(), 'image', true);
                        $image_url = wp_get_attachment_image_url($image_id, 'desktop-home');
                        if ($image_id) {
                            echo '<img src="' . esc_url($image_url) . '" class="photo-image">';
                                        }
                    ?>
                    </div>
                    <div class="icon-full-screen">
                        <!-- <button
                        type="button"
                        class="icon-full-screen__button"
                        id="fullscreen-button<?= get_the_ID(); ?>"
                        data-image-url="<?= get_the_post_thumbnail_url(null,'full') ?>"
                        data-image-ref="<?= get_field('reference_') ?>"
                        data-image-categorie="<?= get_the_terms(get_the_ID(), 'categorie-photo')[0]->name; ?>"
                        >
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-fullscreen.svg" alt="icône plein écran" />
                        </button> -->
                    </div>
                </div>
            </div>

            <div class="post-center-container">
    <div class="post__contact">
        <div class="post-contact-content">
            <p> Cette photo vous intéresse ? </p>
            <button class="open-contact-modal" data-reference="<?php the_field('reference_'); ?>">Contact</button>
        </div>
    </div>

    <div class="post__navigation">
        <div class="post-navigation__previous-thumbnail">
            <?php
            $prev_post = get_previous_post();
            if ($prev_post) {
                $prev_image_id = get_post_meta($prev_post->ID, 'image', true);
                if ($prev_image_id) {
                    $prev_image_url = wp_get_attachment_image_url($prev_image_id, 'single-photo-thumbnail-size');
                    if ($prev_image_url) {
                        echo '<img src="' . esc_url($prev_image_url) . '" class="photo-thumbnail">';
                    }
                }
            }
            ?>
        </div>
        <div class="post-navigation__next-thumbnail">
            <?php
            $next_post = get_next_post();
            if ($next_post) {
                $next_image_id = get_post_meta($next_post->ID, 'image', true);
                if ($next_image_id) {
                    $next_image_url = wp_get_attachment_image_url($next_image_id, 'single-photo-thumbnail-size');
                    if ($next_image_url) {
                        echo '<img src="' . esc_url($next_image_url) . '" class="photo-thumbnail">';
                    }
                }
            }
            ?>
        </div>
        <div class="post-navigation__arrows">
            <div class="post-navigation__previous-arrow">
                <?php previous_post_link('%link', '&#10229;'); ?>
            </div>
            <div class="post-navigation__next-arrow">
                <?php next_post_link('%link', '&#10230;'); ?>
            </div>
        </div>
    </div>
</div>

<div class="bottom-container">
    <h3>VOUS AIMEREZ AUSSI</h3>
    <div class="related-photos-section">
        <!-- Liste des cards -->
        <div class="related-photos-container">
            <?php
            // Récupérer les termes de la taxonomie pour le post actuel
            $categories = get_the_terms(get_the_ID(), 'categorie-photo');
            if (!empty($categories) && !is_wp_error($categories)) {
                $current_category = $categories[0];
                $related_args = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 2,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie-photo',
                            'field' => 'term_id',
                            'terms' => $current_category->term_id,
                        ),
                    ),
                    'post__not_in' => array(get_the_ID()), // Exclure la photo actuelle
                );

                // Exécuter la requête pour récupérer les photos apparentées
                $related_query = new WP_Query($related_args);

                // Afficher les miniatures des photos apparentées
                if ($related_query->have_posts()) {
                    while ($related_query->have_posts()) {
                        $related_query->the_post();
                        $image_id = get_post_meta(get_the_ID(), 'image', true); // Assumons que 'image' est la clé utilisée
                        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'desktop-home') : '';
                        ?>
                        <div class="related-photo-thumbnail photo-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if ($image_url) {
                                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="photo-thumbnail photo-image" />';
                                } else {
                                    // Afficher une image de remplacement si aucune miniature n'est disponible
                                    echo '<img src="' . esc_url(get_template_directory_uri()) . '" class="photo-thumbnail photo-image" />';
                                }
                                ?>
                                <span class="icon-container">
                                    <span class="photo-reference"><?php echo esc_html(get_post_meta(get_the_ID(), 'reference', true)); ?></span>
                                    <span class="photo-category">
                                        <?php
                                        $category_list = get_the_terms(get_the_ID(), 'categorie-photo');
                                        echo $category_list ? esc_html($category_list[0]->name) : 'Uncategorized';
                                        ?>
                                    </span>
                                    <span class="photo-info-icon"><i class="fas fa-eye"></i></span>
                                    <span class="fullscreen-icon"><i class="fas fa-expand fullscreen-icon"></i></span>
                                </span>
                            </a>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>Aucune photo apparentée trouvée.</p>';
                }
            } else {
                echo '<p>Erreur de catégorie ou catégorie non trouvée.</p>';
            }
            ?>
        </div>
    </div>
</div>



    <?php endwhile; 
        endif; ?>
</main>

<?php get_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/script.js"></script>