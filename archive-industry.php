<?php
/**
 * Volkmann Express — Industries Archive (US Edition)
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Industries',
    'title'     => 'Technology Solutions for <span class="ve-text-gradient">Every Sector</span>',
    'subtitle'  => 'We bring battle-tested technology expertise to 12 verticals across the US — tailoring every engagement to the unique regulatory, competitive, and operational dynamics of your industry.',
    'cta_label' => 'Talk to a Specialist',
    'cta_url'   => home_url('/contact'),
    'size'      => 'md',
] );
?>

<section class="ve-section ve-section--alt">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-proof-metrics ve-reveal">
            <?php
            $metrics = [
                ['value'=>'12',   'label'=>'Industries Served',     'icon'=>'🌐'],
                ['value'=>'200+', 'label'=>'US Enterprise Clients',  'icon'=>'🏢'],
                ['value'=>'$2B+', 'label'=>'Client Value Created',   'icon'=>'💰'],
                ['value'=>'98%',  'label'=>'Client Retention Rate',  'icon'=>'🤝'],
            ];
            foreach ($metrics as $m) : ?>
            <div class="ve-proof-metric ve-reveal">
                <span class="ve-proof-metric__emoji"><?= $m['icon'] ?></span>
                <span class="ve-proof-metric__value"><?= esc_html($m['value']) ?></span>
                <span class="ve-proof-metric__label"><?= esc_html($m['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header ve-reveal">
            <div class="ve-badge">All Industries</div>
            <h2 class="ve-section-title">Where We <span class="ve-text-gradient">Operate</span></h2>
            <p class="ve-section-sub">Each industry engagement starts with deep listening and ends with measurable results.</p>
        </div>
        <div class="ve-industries-grid">
            <?php if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $solutions = get_post_meta(get_the_ID(),'ve_solutions',true);
                    $sol_arr   = $solutions ? array_map('trim',explode(',',$solutions)) : [];
            ?>
            <article <?php post_class('ve-industry-card ve-reveal'); ?>>
                <div class="ve-industry-card__icon"><?= ve_industry_icon(get_the_title()) ?></div>
                <h3 class="ve-industry-card__title"><?php the_title(); ?></h3>
                <?php if (get_the_excerpt()) : ?><p class="ve-industry-card__desc"><?php the_excerpt(); ?></p><?php endif; ?>
                <?php if (!empty($sol_arr)) : ?>
                <ul class="ve-industry-card__tags">
                    <?php foreach (array_slice($sol_arr,0,3) as $s) : ?><li class="ve-tag"><?= esc_html($s) ?></li><?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="ve-industry-card__link">Explore <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg></a>
            </article>
            <?php endwhile;
            else :
                foreach (ve_default_industries() as $i => $ind) : ?>
                <article class="ve-industry-card ve-reveal" data-index="<?= $i ?>">
                    <div class="ve-industry-card__icon"><?= ve_industry_icon($ind['name']) ?></div>
                    <h3 class="ve-industry-card__title"><?= esc_html($ind['name']) ?></h3>
                    <ul class="ve-industry-card__tags">
                        <?php foreach (array_slice($ind['solutions'],0,3) as $sol) : ?><li class="ve-tag"><?= esc_html($sol) ?></li><?php endforeach; ?>
                    </ul>
                </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- 6-pillar approach section -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Our Approach</div>
            <h2 class="ve-section-title">Industry Expertise × <span class="ve-text-gradient">Technical Depth</span></h2>
            <p class="ve-section-sub">We don't apply generic frameworks. Every engagement is rooted in the regulatory, operational, and competitive reality of your sector.</p>
        </div>
        <div class="ve-approach-grid ve-approach-grid--6 ve-reveal">
            <?php
            $pillars = [
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                    'title' => 'Sector-Specific Discovery',
                    'desc'  => 'Our engagement model begins with immersive workshops led by consultants who have operated inside your industry — not just advised it. We map regulatory constraints, competitive pressures, and operational bottlenecks before a single line of code is written.',
                ],
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
                    'title' => 'Pre-Built Accelerators',
                    'desc'  => 'For each of our 12 verticals, we maintain a library of pre-validated data models, compliance connectors, and UI components — cutting weeks from initial delivery and ensuring best-practice architecture from day one.',
                ],
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
                    'title' => 'Embedded US Specialists',
                    'desc'  => 'We embed sector specialists — former hospital CIOs, ex-bank CISOs, federal contracting experts — alongside our engineers. You get technical excellence with genuine domain credibility, not consultants learning on the job.',
                ],
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    'title' => 'Compliance-First Design',
                    'desc'  => 'HIPAA, CMMC, SOX, CCPA, FedRAMP — we design compliance controls into architecture from the start, not as an afterthought. Our clients routinely pass audits on first attempt.',
                ],
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
                    'title' => 'Outcome-Linked Delivery',
                    'desc'  => 'Every engagement is tracked against the business metrics we defined together — cost reduction, revenue uplift, risk reduction. Monthly steering reviews keep delivery aligned to what matters, not just what\'s on the backlog.',
                ],
                [
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                    'title' => 'Long-Term Partnership',
                    'desc'  => 'We don\'t disappear after go-live. Our post-launch partnership model covers continuous optimization, capacity planning, and evolving capability — which is why 98% of our clients renew year after year.',
                ],
            ];
            foreach ($pillars as $p) : ?>
            <div class="ve-approach-card ve-reveal">
                <div class="ve-approach-card__icon"><?= $p['icon'] ?></div>
                <h3><?= esc_html($p['title']) ?></h3>
                <p><?= esc_html($p['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/cta', null, [
    'title'      => 'Your industry. Our expertise.',
    'subtitle'   => 'Tell us about your sector and challenge — we\'ll show you what\'s possible.',
    'btn_label'  => 'Contact Us',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View Solutions',
    'btn2_url'   => home_url('/solutions'),
]);
get_footer();
