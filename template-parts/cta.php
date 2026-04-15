<?php
/**
 * Template Part: CTA Banner
 * $args['title'], $args['subtitle'], $args['btn_label'], $args['btn_url'], $args['btn2_label'], $args['btn2_url']
 */
$title      = $args['title']      ?? 'Ready to transform your business?';
$subtitle   = $args['subtitle']   ?? 'Join the enterprises that trust Volkmann Express to power their digital future.';
$btn_label  = $args['btn_label']  ?? 'Schedule a Consultation';
$btn_url    = $args['btn_url']    ?? home_url('/contact');
$btn2_label = $args['btn2_label'] ?? 'View All Solutions';
$btn2_url   = $args['btn2_url']   ?? home_url('/solutions');
?>
<section class="ve-cta-section ve-reveal">
    <div class="ve-cta-section__bg" aria-hidden="true"></div>
    <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center">
        <h2 class="ve-cta-section__title"><?= wp_kses_post($title) ?></h2>
        <p class="ve-cta-section__sub"><?= wp_kses_post($subtitle) ?></p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
            <a href="<?= esc_url($btn_url) ?>" class="ve-btn ve-btn--primary ve-btn--lg">
                <span><?= esc_html($btn_label) ?></span>
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>
            <a href="<?= esc_url($btn2_url) ?>" class="ve-btn ve-btn--ghost ve-btn--lg">
                <?= esc_html($btn2_label) ?>
            </a>
        </div>
    </div>
</section>
