<?php
/**
 * Template Part: Service Cards Grid
 *
 * Usage:
 *   get_template_part('template-parts/cards-service', null, [
 *       'count'  => 6,
 *       'style'  => 'compact|full',   // default: full
 *       'title'  => 'Our Solutions',
 *       'badge'  => 'Solutions',
 *       'cta'    => true,
 *   ]);
 */

$count = intval($args['count'] ?? 6);
$style = $args['style'] ?? 'full';
$title = $args['title'] ?? '';
$badge = $args['badge'] ?? '';
$show_cta = $args['cta'] ?? false;

// Fetch CPT posts or fall back to defaults
$service_posts = get_posts(['post_type' => 'service', 'posts_per_page' => $count, 'post_status' => 'publish']);
$services = [];
if (!empty($service_posts)) {
    foreach ($service_posts as $sp) {
        $services[] = [
            'title'   => $sp->post_title,
            'slug'    => $sp->post_name,
            'excerpt' => wp_strip_all_tags(get_the_excerpt($sp)),
            'icon'    => $sp->post_name,
            'link'    => get_permalink($sp),
        ];
    }
} else {
    $services = array_slice(ve_default_services(), 0, $count);
}
?>

<?php if ($badge || $title) : ?>
<div class="ve-section-header ve-reveal">
    <?php if ($badge) : ?><div class="ve-badge"><?= esc_html($badge) ?></div><?php endif; ?>
    <?php if ($title) : ?><h2 class="ve-section-title"><?= wp_kses_post($title) ?></h2><?php endif; ?>
</div>
<?php endif; ?>

<div class="ve-services-grid<?= $style === 'compact' ? ' ve-services-grid--compact' : '' ?>">
    <?php foreach ($services as $i => $s) :
        $url = $s['link'] ?? ve_get_service_url($s['slug']);
    ?>
    <article class="ve-service-card ve-reveal" data-index="<?= $i ?>">
        <div class="ve-service-card__icon">
            <?= ve_service_icon($s['icon'] ?? $s['slug']) ?>
        </div>
        <h3 class="ve-service-card__title"><?= esc_html($s['title']) ?></h3>
        <?php if ($style !== 'compact') : ?>
        <p class="ve-service-card__excerpt"><?= esc_html($s['excerpt']) ?></p>
        <?php endif; ?>
        <a href="<?= esc_url($url) ?>" class="ve-service-card__link" aria-label="Learn more about <?= esc_attr($s['title']) ?>">
            Learn more
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
        </a>
    </article>
    <?php endforeach; ?>
</div>

<?php if ($show_cta) : ?>
<div class="text-center mt-12 ve-reveal">
    <a href="<?= esc_url(home_url('/solutions')) ?>" class="ve-btn ve-btn--secondary">
        View All Solutions
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
    </a>
</div>
<?php endif; ?>
