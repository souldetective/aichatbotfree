<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="container nav">
        <div class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">AIChatbotFree</a></div>
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'primary-menu',
            'fallback_cb'    => false,
        ] );
        ?>
    </div>
</header>
<main class="site-main">
