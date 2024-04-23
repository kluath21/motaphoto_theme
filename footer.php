<?php include 'template/modal-contact.php'; ?>
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
</footer>

</html>