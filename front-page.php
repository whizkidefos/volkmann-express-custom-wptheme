<?php
/**
 * Volkmann Express — Home Page (US Edition)
 */
get_header();
?>

<!-- ═══════════════════════════════════════════════════════ HERO -->
<?php
get_template_part( 'template-parts/hero', null, [
    'badge'      => 'Enterprise Technology Partner · Bowie, MD',
    'title'      => 'Accelerate Your Digital <span class="ve-text-gradient">Future</span>',
    'subtitle'   => 'From AI and cloud to cybersecurity and custom software — Volkmann Express delivers end-to-end technology solutions that drive measurable growth for America\'s most ambitious enterprises.',
    'cta_label'  => 'View All Solutions',
    'cta_url'    => home_url('/solutions'),
    'cta2_label' => 'Schedule a Consultation',
    'cta2_url'   => home_url('/contact'),
    'size'       => 'lg',
] );
?>

<!-- ═══════════════════════════════════════════════════════ 8 SERVICES -->
<section class="ve-section ve-reveal" id="ve-services-preview">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Our Capabilities</div>
            <h2 class="ve-section-title">Solutions Built for <span class="ve-text-gradient">Enterprise Scale</span></h2>
            <p class="ve-section-sub">Eight integrated practice areas — engineered to move your business faster and measure every dollar of impact.</p>
        </div>

        <div class="ve-services-grid ve-services-grid--8">
            <?php
            $services = ve_default_services();
            $service_posts = get_posts( [ 'post_type' => 'service', 'posts_per_page' => 8, 'post_status' => 'publish' ] );
            if ( ! empty( $service_posts ) ) {
                $services = [];
                foreach ( $service_posts as $sp ) {
                    $services[] = [
                        'title'   => $sp->post_title,
                        'slug'    => $sp->post_name,
                        'excerpt' => wp_strip_all_tags( get_the_excerpt( $sp ) ),
                        'icon'    => $sp->post_name,
                        'link'    => get_permalink( $sp ),
                    ];
                }
            }
            foreach ( $services as $i => $s ) :
                $url_map = ve_service_url_map();
                $url = $s['link'] ?? ($url_map[$s['slug']] ?? home_url('/solutions'));
            ?>
            <article class="ve-service-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-service-card__icon">
                    <?= ve_service_icon( $s['icon'] ?? $s['slug'] ) ?>
                </div>
                <h3 class="ve-service-card__title"><?= esc_html( $s['title'] ) ?></h3>
                <p class="ve-service-card__excerpt"><?= esc_html( $s['excerpt'] ) ?></p>
                <a href="<?= esc_url($url) ?>" class="ve-service-card__link" aria-label="Learn more about <?= esc_attr($s['title']) ?>">
                    Learn more
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-12 ve-reveal">
            <a href="<?= esc_url( home_url('/solutions') ) ?>" class="ve-btn ve-btn--secondary">
                View All Solutions
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ PROCESS (centred) -->
<section class="ve-section ve-section--alt ve-reveal" id="ve-process">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Our Approach</div>
            <h2 class="ve-section-title">How We <span class="ve-text-gradient">Deliver</span></h2>
            <p class="ve-section-sub">A proven four-phase model honed across 200+ enterprise engagements — transparent at every step.</p>
        </div>
        <div class="ve-process-track ve-process-track--centered">
            <?php
            $steps = [
                [
                    'num'   => '01',
                    'title' => 'Discover',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                    'desc'  => 'Immersive workshops with your team map the challenge landscape, quantify the opportunity, and define what success actually looks like — in business terms, not technology terms.',
                ],
                [
                    'num'   => '02',
                    'title' => 'Strategize',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
                    'desc'  => 'We architect a prioritized technology roadmap with clear milestones, effort estimates, and a business case your CFO can sign off on. You own the plan whether you proceed with us or not.',
                ],
                [
                    'num'   => '03',
                    'title' => 'Build',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/><line x1="12" y1="5" x2="12" y2="19"/></svg>',
                    'desc'  => 'Agile delivery with bi-weekly demos, a live risk register, and transparent communication at every level. No surprises — just consistent, quality execution tracked against agreed KPIs.',
                ],
                [
                    'num'   => '04',
                    'title' => 'Scale',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
                    'desc'  => 'Post-launch we remain your partner — monitoring performance, optimizing continuously, and expanding capability as your needs evolve. 98% of clients renew beyond initial delivery.',
                ],
            ];
            foreach ( $steps as $i => $step ) : ?>
            <div class="ve-process-step ve-process-step--centered ve-reveal" data-index="<?= $i ?>">
                <div class="ve-process-step__circle">
                    <div class="ve-process-step__circle-icon"><?= $step['icon'] ?></div>
                    <div class="ve-process-step__num-badge"><?= esc_html($step['num']) ?></div>
                </div>
                <?php if ($i < count($steps) - 1) : ?>
                <div class="ve-process-step__connector" aria-hidden="true"></div>
                <?php endif; ?>
                <h3 class="ve-process-step__title"><?= esc_html($step['title']) ?></h3>
                <p class="ve-process-step__desc"><?= esc_html($step['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ DIFFERENTIATORS + 3D -->
<section class="ve-section ve-reveal" id="ve-differentiators">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-diff-grid">
            <div class="ve-diff-copy">
                <div class="ve-badge">Why Volkmann Express</div>
                <h2 class="ve-section-title">Technology. Expertise. <span class="ve-text-gradient">Growth.</span></h2>
                <p class="ve-body-text">We combine deep technical capability with sector expertise and a relentless focus on measurable results. Headquartered in Bowie, MD — with clients across the US — every engagement is backed by dedicated specialists, transparent delivery, and long-term partnership.</p>
                <ul class="ve-diff-bullets">
                    <li><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><polyline points="2 8 6 12 14 4"/></svg> No offshore delivery surprises — US-based delivery leadership on every engagement</li>
                    <li><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><polyline points="2 8 6 12 14 4"/></svg> NIST, CMMC, HIPAA, SOX, FedRAMP compliance expertise built in</li>
                    <li><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><polyline points="2 8 6 12 14 4"/></svg> Fixed-price options available — no scope creep, no billing surprises</li>
                </ul>
                <a href="<?= esc_url( home_url('/about') ) ?>" class="ve-btn ve-btn--secondary mt-8">
                    About Us
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </div>
            <div class="ve-diff-right">
                <!-- 3D interactive canvas -->
                <div class="ve-globe-wrap" aria-hidden="true">
                    <canvas id="ve-globe-canvas"></canvas>
                </div>
                <!-- Stats overlay -->
                <div class="ve-diff-stats ve-diff-stats--overlay">
                    <?php
                    $stats = [
                        [ 'value' => '200+', 'label' => 'Enterprise Clients'   ],
                        [ 'value' => '98%',  'label' => 'Client Retention'     ],
                        [ 'value' => '12+',  'label' => 'Industries Served'    ],
                        [ 'value' => '$2B+', 'label' => 'Client Value Created' ],
                    ];
                    foreach ( $stats as $stat ) : ?>
                    <div class="ve-stat-card ve-reveal" data-count="<?= esc_attr($stat['value']) ?>">
                        <span class="ve-stat-card__value"><?= esc_html($stat['value']) ?></span>
                        <span class="ve-stat-card__label"><?= esc_html($stat['label']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ US CASE STUDY STRIP -->
<section class="ve-section ve-section--alt ve-reveal" id="ve-proof-strip">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">US Client Results</div>
            <h2 class="ve-section-title">Proof, Not <span class="ve-text-gradient">Promises</span></h2>
            <p class="ve-section-sub">Real outcomes from real American enterprises — across healthcare, finance, manufacturing, and beyond.</p>
        </div>
        <div class="ve-proof-strip">
            <?php
            $proof_items = [
                [ 'stat' => '34%', 'label' => 'Fewer Hospital Readmissions',     'client' => 'Houston Health System',     'service' => 'AI & Machine Learning'   ],
                [ 'stat' => '$4.2M','label'=> 'Annual Data Center Cost Cut',       'client' => 'Atlanta Logistics Corp',   'service' => 'Cloud Solutions'         ],
                [ 'stat' => '11wks','label'=> 'CMMC Level 2 Certification',        'client' => 'NoVA Defense Contractor',  'service' => 'Cybersecurity'           ],
                [ 'stat' => '29%', 'label' => 'Revenue Increase Year One',        'client' => 'Chicago Specialty Retailer','service' => 'Data Analytics'         ],
                [ 'stat' => '43%', 'label' => 'OEE Improvement',                  'client' => 'San Antonio Manufacturer', 'service' => 'Digital Transformation'  ],
                [ 'stat' => '89%', 'label' => 'Less Unplanned IT Downtime',       'client' => 'New York Consulting Firm', 'service' => 'Managed IT Services'     ],
            ];
            foreach ($proof_items as $p) : ?>
            <div class="ve-proof-strip-card ve-reveal">
                <div class="ve-proof-strip-card__stat"><?= esc_html($p['stat']) ?></div>
                <div class="ve-proof-strip-card__label"><?= esc_html($p['label']) ?></div>
                <div class="ve-proof-strip-card__client"><?= esc_html($p['client']) ?></div>
                <div class="ve-proof-strip-card__service"><?= esc_html($p['service']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-10 ve-reveal">
            <a href="<?= esc_url( home_url('/case-studies') ) ?>" class="ve-btn ve-btn--secondary">View All Case Studies</a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ INDUSTRIES STRIP -->
<section class="ve-section ve-reveal" id="ve-industries-strip">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Industries</div>
            <h2 class="ve-section-title">Built for Every <span class="ve-text-gradient">Sector</span></h2>
        </div>
        <div class="ve-industry-strip">
            <?php foreach ( ve_default_industries() as $industry ) : ?>
            <div class="ve-industry-chip ve-reveal">
                <span class="ve-industry-chip__icon"><?= ve_industry_icon( $industry['name'] ) ?></span>
                <span><?= esc_html( $industry['name'] ) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-10 ve-reveal">
            <a href="<?= esc_url( home_url('/industries') ) ?>" class="ve-btn ve-btn--secondary">Explore All Industries</a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════ CTA -->
<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Ready to transform your business?',
    'subtitle'   => 'Join 200+ US enterprises that trust Volkmann Express to power their digital future.',
    'btn_label'  => 'Schedule a Free Consultation',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View All Solutions',
    'btn2_url'   => home_url('/solutions'),
] );
get_footer();
