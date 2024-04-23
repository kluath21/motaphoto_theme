<div id="overlay" class="hidden">
    <div id="contact-modal" class="modal">
        <div class="modal-content">
            <!-- Bouton de fermeture de la modal -->
            <span class="close-modal">&times;</span>
            
            <!-- Image d'en-tête de la modal -->
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contact.png" alt="Contact" />
             <!-- Formulaire de contact -->
             <?php echo do_shortcode('[contact-form-7 id="fb94585" title="CONTACT"]'); ?>
        </div>
    </div>
</div>