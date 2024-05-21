<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Nathalie Mota</title>
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo get_stylesheet_uri(); ?>'>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/fonts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <?php wp_enqueue_style( 'motaphoto-style', get_stylesheet_uri() ); ?>
    <!-- <?php wp_head(); ?> -->
</head>

<!-- <body <?php body_class(); ?>> -->
    
<!-- <?php wp_body_open(); ?> -->
<header id="masthead" class="site-header">
    <div class="site-branding">
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?> logo" />
        </a>
    </div>
    <!-- Bouton menu burger -->
<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">&#9776;</button>
<button class="menu-close" aria-controls="primary-menu" aria-expanded="true" style="display: none;">&times;</button>
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
