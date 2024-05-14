<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Nathalie Mota</title>
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo get_stylesheet_uri(); ?>'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Ajouter Font Awesome à votre page -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php wp_enqueue_style( 'motaphoto-style', get_stylesheet_uri() ); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
<?php wp_body_open(); ?>
<header id="masthead" class="site-header">
    <div class="site-branding">
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?> logo" />
        </a>
    </div>
    <!-- Bouton menu burger sans texte "Menu" -->
    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">&#9776;</button>
    <nav id="site-navigation" class="main-navigation">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'header-main-menu',
                'menu_id' => 'primary-menu',
            )
        );
        ?>
    </nav>
</header>
