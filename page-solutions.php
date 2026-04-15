<?php
/**
 * Volkmann Express — Solutions Hub
 * Template Name: Solutions Hub
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Our Solutions',
    'title'     => 'Enterprise Technology <span class="ve-text-gradient">That Performs</span>',
    'subtitle'  => 'Six integrated practice areas, purpose-built for enterprise scale and measurable outcomes.',
    'cta_label' => 'Schedule Consultation',
    'cta_url'   => home_url('/contact'),
    'size'      => 'md',
] );
?>

<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">

        <div class="ve-solutions-full-grid">
            <?php
            $services = ve_default_services();
            $service_posts = get_posts( [ 'post_type' => 'service', 'posts_per_page' => -1, 'post_status' => 'publish' ] );
            if ( ! empty( $service_posts ) ) {
                $services = [];
                foreach ( $service_posts as $sp ) {
                    $services[] = [
                        'title'   => $sp->post_title,
                        'slug'    => $sp->post_name,
                        'excerpt' => wp_strip_all_tags( get_the_excerpt($sp) ),
                        'icon'    => $sp->post_name,
                        'link'    => get_permalink($sp),
                        'meta'    => get_post_meta( $sp->ID ),
                    ];
                }
            }

            foreach ( $services as $i => $s ) :
                $url = $s['link'] ?? ve_get_service_url($s['slug']);
                $capabilities = $s['meta']['ve_capabilities'][0] ?? '';
            ?>
            <article class="ve-solution-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-solution-card__header">
                    <div class="ve-solution-card__icon"><?= ve_service_icon($s['icon'] ?? $s['slug']) ?></div>
                    <div>
                        <h2 class="ve-solution-card__title"><?= esc_html($s['title']) ?></h2>
                    </div>
                </div>
                <p class="ve-solution-card__excerpt"><?= esc_html($s['excerpt']) ?></p>
                <a href="<?= esc_url($url) ?>" class="ve-btn ve-btn--ghost mt-auto">
                    Explore <?= esc_html($s['title']) ?>
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Not sure which solution fits?',
    'subtitle'   => 'Our experts will map your challenges to the right capabilities — at no cost.',
    'btn_label'  => 'Schedule Consultation',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'Explore Industries',
    'btn2_url'   => home_url('/industries'),
] );

get_footer();
