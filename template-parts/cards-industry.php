<?php
/**
 * Template Part: Industry Cards Grid
 *
 * Usage:
 *   get_template_part('template-parts/cards-industry', null, [
 *       'count' => 12,
 *       'title' => 'Industries We Serve',
 *       'badge' => 'Industries',
 *   ]);
 */

$count = intval($args['count'] ?? 12);
$title = $args['title'] ?? '';
$badge = $args['badge'] ?? '';

$industry_posts = get_posts(['post_type' => 'industry', 'posts_per_page' => $count, 'post_status' => 'publish']);
$industries = [];
if (!empty($industry_posts)) {
    foreach ($industry_posts as $ip) {
        $sols = get_post_meta($ip->ID, 've_solutions', true);
        $industries[] = [
            'name'      => $ip->post_title,
            'solutions' => $sols ? array_map('trim', explode(',', $sols)) : [],
            'link'      => get_permalink($ip),
            'excerpt'   => wp_strip_all_tags(get_the_excerpt($ip)),
        ];
    }
} else {
    $industries = array_slice(ve_default_industries(), 0, $count);
}
?>

<?php if ($badge || $title) : ?>
<div class="ve-section-header ve-reveal">
    <?php if ($badge) : ?><div class="ve-badge"><?= esc_html($badge) ?></div><?php endif; ?>
    <?php if ($title) : ?><h2 class="ve-section-title"><?= wp_kses_post($title) ?></h2><?php endif; ?>
</div>
<?php endif; ?>

<div class="ve-industries-grid">
    <?php foreach ($industries as $i => $ind) :
        $link = $ind['link'] ?? '';
    ?>
    <article class="ve-industry-card ve-reveal" data-index="<?= $i ?>">
        <div class="ve-industry-card__icon">
            <?= ve_industry_icon($ind['name']) ?>
        </div>
        <h3 class="ve-industry-card__title"><?= esc_html($ind['name']) ?></h3>
        <?php if (!empty($ind['excerpt'])) : ?>
        <p class="ve-industry-card__desc"><?= esc_html($ind['excerpt']) ?></p>
        <?php endif; ?>
        <?php if (!empty($ind['solutions'])) : ?>
        <ul class="ve-industry-card__tags">
            <?php foreach (array_slice($ind['solutions'], 0, 3) as $sol) : ?>
            <li class="ve-tag"><?= esc_html(trim($sol)) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <?php if ($link) : ?>
        <a href="<?= esc_url($link) ?>" class="ve-industry-card__link">
            Explore <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
        </a>
        <?php endif; ?>
    </article>
    <?php endforeach; ?>
</div>
