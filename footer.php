
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