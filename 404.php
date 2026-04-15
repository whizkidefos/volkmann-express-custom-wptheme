<?php
/**
 * Volkmann Express — 404 Page
 */
get_header();
?>
<section class="ve-section" style="min-height: 60vh; display:flex; align-items:center;">
    <div class="container mx-auto px-4 lg:px-8 text-center">
        <div class="ve-badge" style="justify-content:center;">Error 404</div>
        <h1 class="ve-hero__title mt-4">Page Not <span class="ve-text-gradient">Found</span></h1>
        <p class="ve-hero__subtitle mx-auto" style="text-align:center;">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="flex gap-4 justify-center mt-8 flex-wrap">
            <a href="<?= esc_url(home_url('/')) ?>" class="ve-btn ve-btn--primary ve-btn--lg">
                Back to Home
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>
            <a href="<?= esc_url(home_url('/contact')) ?>" class="ve-btn ve-btn--ghost ve-btn--lg">Contact Us</a>
        </div>
    </div>
</section>
<?php get_footer(); ?>
