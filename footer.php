<!-- Overlay de la lightbox -->
<div id="lightbox-overlay" style="display: none;" aria-hidden="true">
    <!-- Contenu de la lightbox -->
    <div id="lightbox-container" role="dialog" aria-modal="true" aria-labelledby="lightbox-image" aria-describedby="lightbox-info">
        <!-- Boutons de navigation de la lightbox -->
        <button id="lightbox-prev" class="lightbox-navigation" aria-label="Image précédente">&lt;</button>
        <button id="lightbox-next" class="lightbox-navigation" aria-label="Image suivante">&gt;</button>
        <button id="lightbox-close" class="lightbox-navigation" aria-label="Fermer la lightbox">X</button>
        
        <!-- Image de la lightbox -->
        <img id="lightbox-image" src="" alt="Photo en plein écran">
        
        <!-- Informations supplémentaires de la lightbox -->
        <div id="lightbox-info">
            <!-- Référence de la photo -->
            <div id="lightbox-reference" aria-live="polite"></div>
            <!-- Catégorie de la photo -->
            <div id="lightbox-category" aria-live="polite"></div>
        </div>
    </div>
</div>

</body>
<footer id="site-footer" class="site-footer">
    <nav id="footer-navigation" class="footer-navigation">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'menu_id'        => 'footer-menu',
        ));
        ?>
    </nav>
    <?php get_template_part( 'template/modal-contact' ); ?>
</footer>
<?php wp_footer(); ?>
</html>