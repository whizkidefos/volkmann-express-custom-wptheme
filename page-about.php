<?php
/**
 * Volkmann Express — About Page
 * Template Name: About
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Who We Are',
    'title'     => 'Technology Partner for the <span class="ve-text-gradient">Modern Enterprise</span>',
    'subtitle'  => 'Volkmann Express was founded on a single belief: technology should accelerate human ambition, not complicate it.',
    'cta_label' => 'Contact Sales',
    'cta_url'   => home_url('/contact'),
    'cta2_label'=> 'Explore Solutions',
    'cta2_url'  => home_url('/solutions'),
    'size'      => 'md',
] );
?>

<!-- ============================================================ STORY -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-two-col">
            <div class="ve-reveal">
                <div class="ve-badge">Our Story</div>
                <h2 class="ve-section-title">Built from the <span class="ve-text-gradient">Ground Up</span></h2>
                <p class="ve-body-text">Volkmann Express began as a small team of engineers and strategists who believed enterprise technology was overcomplicated, overpriced, and underdelivered. We set out to change that.</p>
                <p class="ve-body-text mt-4">Today we operate across 12 industries, partnering with organisations from fast-growth startups to global enterprises — all united by the desire to build something extraordinary.</p>
                <p class="ve-body-text mt-4">Our model is simple: understand your business deeply, apply the right technology precisely, and measure everything.</p>
            </div>
            <div class="ve-reveal">
                <div class="ve-about-visual" aria-hidden="true">
                    <canvas id="ve-about-canvas" class="w-full h-full rounded-2xl"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ MISSION + VISION -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-mv-grid">
            <div class="ve-mv-card ve-reveal">
                <div class="ve-mv-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3>Our Mission</h3>
                <p>To deliver enterprise technology solutions that create lasting competitive advantage — on time, on budget, and beyond expectations.</p>
            </div>
            <div class="ve-mv-card ve-reveal" style="--delay:150ms">
                <div class="ve-mv-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h3>Our Vision</h3>
                <p>A world where every enterprise — regardless of size — has access to world-class AI, cloud, and software capabilities that were once reserved for the largest organisations.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ VALUES -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Core Values</div>
            <h2 class="ve-section-title">What Drives <span class="ve-text-gradient">Everything</span></h2>
        </div>
        <div class="ve-values-grid">
            <?php
            $values = [
                [ 'title' => 'Outcomes First',    'desc' => 'We measure success by business results, not deliverables. Technology is the means, not the end.',        'icon' => '🎯' ],
                [ 'title' => 'Radical Transparency', 'desc' => 'No surprises. No hidden complexity. We communicate early, clearly, and honestly.',                   'icon' => '🔍' ],
                [ 'title' => 'Technical Excellence', 'desc' => 'We hold ourselves to the highest engineering standards — maintainable, secure, and performant.',      'icon' => '⚡' ],
                [ 'title' => 'Partnership',        'desc' => 'We treat client teams as colleagues, not recipients. Your success is our success, long after go-live.', 'icon' => '🤝' ],
                [ 'title' => 'Continuous Learning','desc' => 'Technology evolves. So do we. Every team member invests in staying ahead of the curve.',                'icon' => '📚' ],
                [ 'title' => 'Inclusive Impact',   'desc' => 'We build solutions that are accessible, ethical, and considerate of all stakeholders.',                 'icon' => '🌍' ],
            ];
            foreach ( $values as $v ) : ?>
            <div class="ve-value-card ve-reveal">
                <span class="ve-value-card__emoji"><?= $v['icon'] ?></span>
                <h3><?= esc_html($v['title']) ?></h3>
                <p><?= esc_html($v['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ ACHIEVEMENTS -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Track Record</div>
            <h2 class="ve-section-title">Numbers That <span class="ve-text-gradient">Speak</span></h2>
        </div>
        <div class="ve-achievements-grid ve-achievements-grid--centered">
            <?php
            $achievements = [
                [ 'value' => '200+',  'label' => 'Enterprise Clients',         'suffix' => '' ],
                [ 'value' => '98',    'label' => 'Client Retention Rate',       'suffix' => '%' ],
                [ 'value' => '12',    'label' => 'Industries Served',           'suffix' => '+' ],
                [ 'value' => '2',     'label' => 'Billion in Client Value',     'suffix' => 'B+' ],
                [ 'value' => '150',   'label' => 'Technology Specialists',      'suffix' => '+' ],
                [ 'value' => '500',   'label' => 'Successful Deployments',      'suffix' => '+' ],
            ];
            foreach ( $achievements as $a ) : ?>
            <div class="ve-achievement ve-reveal" data-countup="<?= esc_attr($a['value']) ?>" data-suffix="<?= esc_attr($a['suffix']) ?>">
                <span class="ve-achievement__value">
                    <span class="ve-countup-num"><?= esc_html($a['value']) ?></span><?= esc_html($a['suffix']) ?>
                </span>
                <span class="ve-achievement__label"><?= esc_html($a['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ TEAM -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Leadership</div>
            <h2 class="ve-section-title">The Team <span class="ve-text-gradient">Behind It</span></h2>
        </div>
        <?php
        $team = get_posts( [ 'post_type' => 'team_member', 'posts_per_page' => 8, 'post_status' => 'publish' ] );
        if ( ! empty( $team ) ) : ?>
        <div class="ve-team-grid">
            <?php foreach ( $team as $member ) :
                setup_postdata( $member );
                $role  = get_post_meta( $member->ID, 've_role',    true );
                $email = get_post_meta( $member->ID, 've_email',   true );
                $li    = get_post_meta( $member->ID, 've_linkedin', true );
            ?>
            <div class="ve-team-card ve-reveal">
                <?php if ( has_post_thumbnail( $member ) ) : ?>
                <div class="ve-team-card__img-wrap">
                    <?php echo get_the_post_thumbnail( $member, 've-square', [ 'class' => 've-team-card__img', 'loading' => 'lazy' ] ); ?>
                </div>
                <?php else : ?>
                <div class="ve-team-card__img-wrap ve-team-card__img-wrap--placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <?php endif; ?>
                <div class="ve-team-card__body">
                    <h3 class="ve-team-card__name"><?= esc_html($member->post_title) ?></h3>
                    <?php if ( $role ) : ?><p class="ve-team-card__role"><?= esc_html($role) ?></p><?php endif; ?>
                    <?php if ( $li ) : ?>
                    <a href="<?= esc_url($li) ?>" class="ve-team-card__li" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p class="text-center ve-muted">Team profiles coming soon.</p>
        <?php endif; ?>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'btn_label'  => 'Contact Sales',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'Explore Solutions',
    'btn2_url'   => home_url('/solutions'),
] );

get_footer();
