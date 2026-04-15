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

            <!-- CTA button (hidden on mobile via CSS) -->
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="ve-btn ve-btn--primary">
                <span>Get Started</span>
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>

            <!-- Mobile hamburger (visible on mobile via CSS) -->
            <button
                id="ve-hamburger"
                class="ve-hamburger"
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
    <!-- Backdrop -->
    <div class="ve-mobile-nav__backdrop"></div>
    
    <!-- Drawer panel -->
    <div class="ve-mobile-nav__panel">
        <!-- Panel header -->
        <div class="ve-mobile-nav__header">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="ve-logo ve-logo--small">
                <img src="<?php echo esc_url( VE_URI . '/assets/images/logo.png' ); ?>" alt="Volkmann Express" width="36" height="36" class="ve-logo__img">
                <span class="ve-logo__text">
                    <span class="ve-logo__primary">Volkmann</span>
                    <span class="ve-logo__accent">Express</span>
                </span>
            </a>
            <button id="ve-mobile-close" class="ve-mobile-nav__close" type="button" aria-label="Close navigation">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Navigation links -->
        <nav class="ve-mobile-nav__body" aria-label="Mobile Navigation">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 've-mobile-nav__list',
                'container'      => false,
                'fallback_cb'    => 've_fallback_nav',
            ] );
            ?>
        </nav>

        <!-- Panel footer -->
        <div class="ve-mobile-nav__footer">
            <!-- Theme toggle -->
            <div class="ve-mobile-nav__theme">
                <span class="ve-mobile-nav__theme-label">Appearance</span>
                <div class="ve-mobile-nav__theme-toggle">
                    <button type="button" class="ve-mobile-theme-btn" data-theme="light" aria-label="Light mode">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                        <span>Light</span>
                    </button>
                    <button type="button" class="ve-mobile-theme-btn" data-theme="dark" aria-label="Dark mode">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                        </svg>
                        <span>Dark</span>
                    </button>
                </div>
            </div>

            <!-- CTA button -->
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="ve-btn ve-btn--primary ve-btn--block">
                Get Started
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>

            <!-- Contact info -->
            <?php if ( $phone = ve_opt('ve_phone') ) : ?>
            <a href="tel:<?= esc_attr( preg_replace('/[^+\d]/', '', $phone) ) ?>" class="ve-mobile-nav__contact">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.88 12 19.79 19.79 0 01.81 3.41 2 2 0 012.82 1H5.82a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15.92z"/></svg>
                <?= esc_html($phone) ?>
            </a>
            <?php endif; ?>
        </div>
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
