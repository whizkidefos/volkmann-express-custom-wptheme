<?php
/**
 * Volkmann Express — Industries Hub
 * Template Name: Industries Hub
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Industries',
    'title'     => 'Technology Solutions for <span class="ve-text-gradient">Every Sector</span>',
    'subtitle'  => 'We bring battle-tested technology expertise to 12 verticals — tailoring every engagement to the unique dynamics of your industry.',
    'cta_label' => 'Talk to an Expert',
    'cta_url'   => home_url('/contact'),
    'size'      => 'md',
] );
?>

<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-industries-grid">
            <?php
            $industries = ve_default_industries();
            $industry_posts = get_posts( [ 'post_type' => 'industry', 'posts_per_page' => -1, 'post_status' => 'publish' ] );
            if ( ! empty( $industry_posts ) ) {
                $industries = [];
                foreach ( $industry_posts as $ip ) {
                    $sols = get_post_meta( $ip->ID, 've_solutions', true );
                    $industries[] = [
                        'name'      => $ip->post_title,
                        'solutions' => $sols ? explode(',', $sols) : [],
                        'link'      => get_permalink($ip),
                        'excerpt'   => wp_strip_all_tags( get_the_excerpt($ip) ),
                    ];
                }
            }

            foreach ( $industries as $i => $ind ) : ?>
            <article class="ve-industry-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-industry-card__icon">
                    <?= ve_industry_icon($ind['name']) ?>
                </div>
                <h3 class="ve-industry-card__title"><?= esc_html($ind['name']) ?></h3>
                <?php if ( ! empty($ind['excerpt']) ) : ?>
                <p class="ve-industry-card__desc"><?= esc_html($ind['excerpt']) ?></p>
                <?php endif; ?>
                <?php if ( ! empty($ind['solutions']) ) : ?>
                <ul class="ve-industry-card__tags">
                    <?php foreach ( array_slice( $ind['solutions'], 0, 3 ) as $sol ) : ?>
                    <li class="ve-tag"><?= esc_html(trim($sol)) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <?php if ( ! empty($ind['link']) ) : ?>
                <a href="<?= esc_url($ind['link']) ?>" class="ve-industry-card__link">
                    Explore
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Proof metrics -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Proof Points</div>
            <h2 class="ve-section-title">Impact Across <span class="ve-text-gradient">Every Industry</span></h2>
        </div>
        <div class="ve-proof-metrics">
            <?php
            $metrics = [
                [ 'value' => '200+', 'label' => 'Enterprise Deployments',  'icon' => '🚀' ],
                [ 'value' => '12',   'label' => 'Industries Covered',       'icon' => '🌐' ],
                [ 'value' => '$2B+', 'label' => 'Client Value Created',     'icon' => '💰' ],
                [ 'value' => '98%',  'label' => 'Client Retention Rate',    'icon' => '🤝' ],
            ];
            foreach ( $metrics as $m ) : ?>
            <div class="ve-proof-metric ve-reveal">
                <span class="ve-proof-metric__emoji"><?= $m['icon'] ?></span>
                <span class="ve-proof-metric__value"><?= esc_html($m['value']) ?></span>
                <span class="ve-proof-metric__label"><?= esc_html($m['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Your industry, our expertise.',
    'subtitle'   => 'Let us show you what\'s possible for your sector.',
    'btn_label'  => 'Contact Us',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View Solutions',
    'btn2_url'   => home_url('/solutions'),
] );

get_footer();
