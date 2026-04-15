<!DOCTYPE html>
<html <?php language_attributes(); ?> class="dark">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class('ve-body font-sans antialiased transition-colors duration-300'); ?>>
<?php wp_body_open(); ?>

<!-- Skip link -->
<a href="#main-content" class="ve-skip-link">Skip to content</a>

<!-- ============================================================
     HEADER
     ============================================================ -->
<header id="ve-header" class="ve-header" role="banner">
    <div class="ve-header__inner container mx-auto px-4 lg:px-8">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="ve-logo" aria-label="Volkmann Express Home">
            <img
                src="<?php echo esc_url( VE_URI . '/assets/images/logo.png' ); ?>"
                alt="Volkmann Express"
                width="48"
                height="48"
                class="ve-logo__img"
                loading="eager"
            >
            <span class="ve-logo__text">
                <span class="ve-logo__primary">Volkmann</span>
                <span class="ve-logo__accent">Express</span>
            </span>
        </a>

        <!-- Primary navigation -->
        <nav id="ve-nav" class="ve-nav" aria-label="Primary Navigation">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 've-nav__list',
                'container'      => false,
                'walker'         => new VE_Nav_Walker(),
                'fallback_cb'    => 've_fallback_nav',
            ] );
            ?>
        </nav>

        <!-- Header actions -->
        <div class="ve-header__actions">
            <!-- Theme toggle -->
            <button
                id="ve-theme-toggle"
                class="ve-theme-toggle"
                aria-label="Toggle colour scheme"
                type="button"
            >
                <svg class="ve-icon--sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg class="ve-icon--moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                </svg>
            </button>

            <!-- CTA button -->
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="ve-btn ve-btn--primary hidden md:inline-flex">
                <span>Get Started</span>
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>

            <!-- Mobile hamburger -->
            <button
                id="ve-hamburger"
                class="ve-hamburger md:hidden"
                aria-label="Open navigation"
                aria-expanded="false"
                type="button"
            >
                <span class="ve-hamburger__line"></span>
                <span class="ve-hamburger__line"></span>
                <span class="ve-hamburger__line"></span>
            </button>
        </div>

    </div>
</header>

<!-- Mobile nav overlay -->
<div id="ve-mobile-nav" class="ve-mobile-nav" aria-hidden="true">
    <div class="ve-mobile-nav__inner">
        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'menu_class'     => 've-mobile-nav__list',
            'container'      => false,
            'fallback_cb'    => 've_fallback_nav',
        ] );
        ?>
        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="ve-btn ve-btn--primary mt-8 w-full justify-center">Get Started</a>
    </div>
</div>

<main id="main-content" class="ve-main">
<?php

function ve_fallback_nav( $args ) {
    $pages = [
        'Home'          => home_url('/'),
        'About'         => home_url('/about'),
        'Solutions'     => home_url('/solutions'),
        'Industries'    => home_url('/industries'),
        'Contact'       => home_url('/contact'),
    ];
    $class = $args['menu_class'] ?? 've-nav__list';
    echo "<ul class=\"{$class}\">";
    foreach ( $pages as $label => $url ) {
        $active = ( rtrim( home_url( $_SERVER['REQUEST_URI'] ), '/' ) === rtrim( $url, '/' ) ) ? ' ve-nav__item--active' : '';
        echo "<li class=\"ve-nav__item{$active}\"><a href=\"{$url}\" class=\"ve-nav__link\"><span>{$label}</span></a></li>";
    }
    echo '</ul>';
}
