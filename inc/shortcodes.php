<?php
/**
 * Volkmann Express — Shortcodes
 *
 * Available shortcodes:
 *   [ve_cta label="…" url="…"]
 *   [ve_badge text="…"]
 *   [ve_stat value="…" label="…"]
 *   [ve_services count="3"]
 *   [ve_contact_form]
 */

defined('ABSPATH') || exit;

/* ─── CTA Button ───────────────────────────────────────────── */
add_shortcode('ve_cta', function($atts) {
    $a = shortcode_atts([
        'label'  => 'Get Started',
        'url'    => home_url('/contact'),
        'style'  => 'primary', // primary | ghost | secondary
    ], $atts, 've_cta');

    $class = 've-btn ve-btn--' . esc_attr($a['style']);
    return sprintf(
        '<a href="%s" class="%s">%s <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg></a>',
        esc_url($a['url']),
        $class,
        esc_html($a['label'])
    );
});

/* ─── Badge ────────────────────────────────────────────────── */
add_shortcode('ve_badge', function($atts) {
    $a = shortcode_atts(['text' => ''], $atts, 've_badge');
    return '<span class="ve-badge">' . esc_html($a['text']) . '</span>';
});

/* ─── Stat ─────────────────────────────────────────────────── */
add_shortcode('ve_stat', function($atts) {
    $a = shortcode_atts(['value' => '', 'label' => ''], $atts, 've_stat');
    return sprintf(
        '<div class="ve-stat-card" style="display:inline-flex;"><span class="ve-stat-card__value">%s</span><span class="ve-stat-card__label">%s</span></div>',
        esc_html($a['value']),
        esc_html($a['label'])
    );
});

/* ─── Services grid ────────────────────────────────────────── */
add_shortcode('ve_services', function($atts) {
    $a = shortcode_atts(['count' => 6], $atts, 've_services');

    $services  = ve_default_services();
    $posts     = get_posts(['post_type' => 'service', 'posts_per_page' => (int)$a['count'], 'post_status' => 'publish']);

    if (!empty($posts)) {
        $services = [];
        foreach ($posts as $p) {
            $services[] = ['title' => $p->post_title, 'slug' => $p->post_name, 'excerpt' => wp_strip_all_tags(get_the_excerpt($p)), 'icon' => $p->post_name, 'link' => get_permalink($p)];
        }
    }

    ob_start();
    echo '<div class="ve-services-grid">';
    foreach (array_slice($services, 0, (int)$a['count']) as $s) {
        $url = $s['link'] ?? home_url('/solutions/' . $s['slug']);
        printf(
            '<article class="ve-service-card"><div class="ve-service-card__icon">%s</div><h3 class="ve-service-card__title">%s</h3><p class="ve-service-card__excerpt">%s</p><a href="%s" class="ve-service-card__link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg></a></article>',
            ve_service_icon($s['icon'] ?? $s['slug']),
            esc_html($s['title']),
            esc_html($s['excerpt']),
            esc_url($url)
        );
    }
    echo '</div>';
    return ob_get_clean();
});

/* ─── Contact form ─────────────────────────────────────────── */
add_shortcode('ve_contact_form', function() {
    ob_start();
    get_template_part('template-parts/contact-form');
    return ob_get_clean();
});

/* ─── Industry grid ────────────────────────────────────────── */
add_shortcode('ve_industries', function($atts) {
    $a = shortcode_atts(['count' => 12], $atts, 've_industries');
    $industries = ve_default_industries();

    ob_start();
    echo '<div class="ve-industry-strip">';
    foreach (array_slice($industries, 0, (int)$a['count']) as $ind) {
        printf(
            '<div class="ve-industry-chip"><span class="ve-industry-chip__icon">%s</span><span>%s</span></div>',
            ve_industry_icon($ind['name']),
            esc_html($ind['name'])
        );
    }
    echo '</div>';
    return ob_get_clean();
});

/* ─── Proof metrics ────────────────────────────────────────── */
add_shortcode('ve_metrics', function($atts) {
    $a = shortcode_atts([
        'metrics' => '200+ Enterprise Clients, 98% Retention Rate, 12+ Industries, $2B+ Value Created',
    ], $atts, 've_metrics');

    $items = array_map('trim', explode(',', $a['metrics']));
    ob_start();
    echo '<div class="ve-proof-metrics">';
    foreach ($items as $item) {
        preg_match('/^([\d\$\+\%\.]+[^\s]*)\s+(.+)$/', $item, $m);
        if (count($m) >= 3) {
            echo '<div class="ve-proof-metric"><span class="ve-proof-metric__value">' . esc_html($m[1]) . '</span><span class="ve-proof-metric__label">' . esc_html($m[2]) . '</span></div>';
        }
    }
    echo '</div>';
    return ob_get_clean();
});
