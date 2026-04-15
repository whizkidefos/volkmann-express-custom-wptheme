<?php
/**
 * Template Part: Hero
 * Args: $args['title'], $args['subtitle'], $args['cta_label'], $args['cta_url'],
 *       $args['cta2_label'], $args['cta2_url'], $args['badge'], $args['size'] (sm|md|lg)
 */

$title      = $args['title']      ?? get_the_title();
$subtitle   = $args['subtitle']   ?? '';
$cta_label  = $args['cta_label']  ?? '';
$cta_url    = $args['cta_url']    ?? '';
$cta2_label = $args['cta2_label'] ?? '';
$cta2_url   = $args['cta2_url']   ?? '';
$badge      = $args['badge']      ?? '';
$size       = $args['size']       ?? 'lg';

$size_classes = [
    'sm' => 'py-20 md:py-28',
    'md' => 'py-28 md:py-36',
    'lg' => 'py-32 md:py-48',
];
$padding = $size_classes[ $size ] ?? $size_classes['lg'];
?>

<section class="ve-hero <?= esc_attr($padding) ?> relative overflow-hidden">
    <!-- THREE.js canvas background -->
    <canvas id="ve-hero-canvas" class="ve-hero__canvas" aria-hidden="true"></canvas>
    <!-- Gradient overlay -->
    <div class="ve-hero__overlay" aria-hidden="true"></div>
    <!-- Noise texture -->
    <div class="ve-hero__noise" aria-hidden="true"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="ve-hero__content max-w-4xl">

            <?php if ( $badge ) : ?>
            <div class="ve-badge ve-badge--hero ve-fade-up" data-delay="0">
                <span class="ve-badge__dot"></span>
                <?= esc_html($badge) ?>
            </div>
            <?php endif; ?>

            <h1 class="ve-hero__title ve-fade-up" data-delay="100"><?= wp_kses_post($title) ?></h1>

            <?php if ( $subtitle ) : ?>
            <p class="ve-hero__subtitle ve-fade-up" data-delay="200"><?= wp_kses_post($subtitle) ?></p>
            <?php endif; ?>

            <?php if ( $cta_label || $cta2_label ) : ?>
            <div class="ve-hero__ctas ve-fade-up" data-delay="300">
                <?php if ( $cta_label && $cta_url ) : ?>
                <a href="<?= esc_url($cta_url) ?>" class="ve-btn ve-btn--primary ve-btn--lg">
                    <span><?= esc_html($cta_label) ?></span>
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
                <?php endif; ?>
                <?php if ( $cta2_label && $cta2_url ) : ?>
                <a href="<?= esc_url($cta2_url) ?>" class="ve-btn ve-btn--ghost ve-btn--lg">
                    <?= esc_html($cta2_label) ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>

        <!-- Scroll indicator (large hero only) -->
        <?php if ( $size === 'lg' ) : ?>
        <div class="ve-scroll-indicator ve-fade-up" data-delay="500" aria-hidden="true">
            <div class="ve-scroll-indicator__mouse">
                <div class="ve-scroll-indicator__wheel"></div>
            </div>
            <span>Scroll</span>
        </div>
        <?php endif; ?>

    </div>
</section>
