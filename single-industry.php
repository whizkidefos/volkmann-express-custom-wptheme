<?php
/**
 * Volkmann Express — Single Industry Page
 */
get_header();

$solutions_meta = get_post_meta( get_the_ID(), 've_solutions',     true );
$hero_stat_1    = get_post_meta( get_the_ID(), 've_stat_1_value',  true );
$hero_stat_1_l  = get_post_meta( get_the_ID(), 've_stat_1_label',  true );
$hero_stat_2    = get_post_meta( get_the_ID(), 've_stat_2_value',  true );
$hero_stat_2_l  = get_post_meta( get_the_ID(), 've_stat_2_label',  true );
$hero_stat_3    = get_post_meta( get_the_ID(), 've_stat_3_value',  true );
$hero_stat_3_l  = get_post_meta( get_the_ID(), 've_stat_3_label',  true );
$solutions_arr  = $solutions_meta ? array_map('trim', explode(',', $solutions_meta)) : [];
$industry_name  = get_the_title();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Industry',
    'title'     => esc_html($industry_name) . ' <span class="ve-text-gradient">Solutions</span>',
    'subtitle'  => get_the_excerpt() ?: "Tailored technology solutions for the {$industry_name} sector — built on deep domain knowledge and proven delivery.",
    'cta_label' => 'Discuss Your Challenge',
    'cta_url'   => home_url('/contact'),
    'size'      => 'md',
] );
?>

<!-- Overview + stats -->
<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-two-col">
            <div class="ve-reveal">
                <div class="ve-badge"><?= esc_html($industry_name) ?></div>
                <h2 class="ve-section-title">Deep Expertise in <span class="ve-text-gradient"><?= esc_html($industry_name) ?></span></h2>
                <div class="ve-prose">
                    <?php the_content(); ?>
                    <?php if ( ! get_the_content() ) : ?>
                    <p class="ve-body-text">The <?= esc_html($industry_name) ?> sector faces a unique convergence of pressures: rising operational costs, intensifying regulatory scrutiny, rapidly shifting customer expectations, and the constant demand to do more with less. Technology is no longer optional — it is the primary lever for competitive differentiation.</p>
                    <p class="ve-body-text">Volkmann Express brings a combination of technical depth and genuine sector expertise to every <?= esc_html($industry_name) ?> engagement. Our team includes practitioners who have held operational roles inside <?= esc_html($industry_name) ?> organisations — not just advised them — giving us a ground-level understanding of the constraints that generic technology vendors miss.</p>
                    <p class="ve-body-text">From initial discovery through to post-launch optimisation, we work as an embedded partner rather than an external supplier. Our goal is always the same: deliver measurable outcomes against the metrics that matter to your board.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( $hero_stat_1 || $hero_stat_2 || $hero_stat_3 ) : ?>
            <div class="ve-diff-stats ve-reveal">
                <?php
                $stats = array_filter([
                    ['value' => $hero_stat_1, 'label' => $hero_stat_1_l],
                    ['value' => $hero_stat_2, 'label' => $hero_stat_2_l],
                    ['value' => $hero_stat_3, 'label' => $hero_stat_3_l],
                ], fn($s) => !empty($s['value']));
                foreach ($stats as $s) : ?>
                <div class="ve-stat-card">
                    <span class="ve-stat-card__value"><?= esc_html($s['value']) ?></span>
                    <span class="ve-stat-card__label"><?= esc_html($s['label']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <!-- Default visual when no stats set -->
            <div class="ve-industry-visual ve-reveal" aria-hidden="true">
                <div class="ve-industry-visual__icon">
                    <?= ve_industry_icon($industry_name) ?>
                </div>
                <div class="ve-industry-visual__ring ve-industry-visual__ring--1"></div>
                <div class="ve-industry-visual__ring ve-industry-visual__ring--2"></div>
                <div class="ve-industry-visual__ring ve-industry-visual__ring--3"></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Solutions for this industry -->
<?php if ( ! empty($solutions_arr) ) : ?>
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Our Solutions</div>
            <h2 class="ve-section-title">What We Deliver for <span class="ve-text-gradient"><?= esc_html($industry_name) ?></span></h2>
            <p class="ve-section-sub">Each solution is configured and calibrated for the specific dynamics of the <?= esc_html($industry_name) ?> sector.</p>
        </div>
        <div class="ve-capabilities-grid">
            <?php
            $service_icons = ve_default_services();
            $icon_map = [];
            foreach ($service_icons as $svc) {
                $icon_map[strtolower($svc['title'])] = ['icon' => $svc['icon'], 'excerpt' => $svc['excerpt']];
            }

            foreach ($solutions_arr as $i => $sol) :
                $key  = strtolower($sol);
                $icon = 'default';
                foreach ($icon_map as $ikey => $idata) {
                    if (str_contains($key, explode(' ', $ikey)[0])) {
                        $icon = $idata['icon'];
                        break;
                    }
                }
            ?>
            <div class="ve-cap-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-cap-card__num"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
                <div style="width:36px;height:36px;color:var(--ve-orange);margin-bottom:.75rem;">
                    <?= ve_service_icon($icon) ?>
                </div>
                <h3><?= esc_html($sol) ?></h3>
                <p>Proven <?= esc_html($sol) ?> capabilities adapted to the <?= esc_html($industry_name) ?> sector's compliance requirements, data structures, and operational constraints.</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Challenges we solve -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Challenges We Solve</div>
            <h2 class="ve-section-title">The Problems <span class="ve-text-gradient">We Hear Most</span></h2>
            <p class="ve-section-sub">Every <?= esc_html($industry_name) ?> client comes to us with a version of these challenges. We've solved them before.</p>
        </div>
        <?php
        // Industry-specific challenges — keyed off post slug
        $slug = get_post_field('post_name');
        $challenges_by_industry = [
            'healthcare' => [
                ['q' => 'How do we reduce clinical variation without constraining clinicians?',    'a' => 'We build decision-support tools that surface evidence at the point of care, nudging without mandating — achieving measurable pathway adherence while preserving clinical autonomy.'],
                ['q' => 'Our patient data is siloed across 12 systems. How do we join it up?',    'a' => 'Our healthcare integration layer connects EPRs, lab systems, imaging platforms, and community health records into a unified longitudinal patient record — in weeks, not years.'],
                ['q' => 'How do we demonstrate DSPT compliance without consuming our entire IT team?', 'a' => 'We deploy automated compliance monitoring that maps controls to DSPT requirements, generates evidence packs, and flags gaps — freeing your team from manual spreadsheet exercises.'],
                ['q' => 'We want to use AI but our board is worried about explainability.',          'a' => 'Every model we deploy for clinical use includes a full explainability layer — plain-English reasoning for every prediction, built for both clinical and regulator audiences.'],
            ],
            'financial-services' => [
                ['q' => 'Our fraud detection triggers too many false positives. Customers are frustrated.', 'a' => 'We rebuild fraud models using behavioural biometrics and graph-based network analysis — reducing false positives by 60–80% while maintaining or improving detection rates.'],
                ['q' => 'How do we accelerate KYC without increasing compliance risk?',             'a' => 'Our AI-powered document verification and adverse media screening cuts average KYC time from days to minutes, with a full audit trail that satisfies FCA and PRA examiners.'],
                ['q' => 'Our legacy core banking system is slowing down every digital initiative.', 'a' => 'We build an API abstraction layer that lets your digital channels innovate at speed while the core is modernised in parallel — no big-bang cutover, no service disruption.'],
                ['q' => 'How do we meet Consumer Duty obligations without rebuilding our products?', 'a' => 'We deliver a Consumer Duty intelligence platform that monitors outcomes across your book, flags vulnerable customers, and generates board-level MI — all from your existing data.'],
            ],
            'manufacturing' => [
                ['q' => 'Our OEE is stuck at 68%. How do we get to 80%+?',                       'a' => 'We deploy IoT-based predictive maintenance and real-time production analytics that identify the specific micro-stops, quality rejects, and changeover inefficiencies dragging down your OEE.'],
                ['q' => 'We have data from 40 machines but no one can make sense of it.',         'a' => 'Our manufacturing data platform unifies PLC, SCADA, and MES data into a single real-time view — with operator dashboards that surface actionable insight, not raw telemetry.'],
                ['q' => 'How do we reduce unplanned downtime without over-maintaining equipment?', 'a' => 'Condition-based monitoring models trained on your equipment\'s failure history predict the remaining useful life of components — scheduling maintenance only when genuinely needed.'],
                ['q' => 'Our supply chain visibility is essentially zero beyond tier 1.',           'a' => 'We build multi-tier supply chain visibility platforms that combine supplier data, logistics APIs, and external risk signals to give you real early-warning capability.'],
            ],
        ];

        $default_challenges = [
            ['q' => 'Our legacy systems are blocking digital progress. Where do we start?',         'a' => 'We begin with a structured technology audit that maps legacy constraints to business impact — then design a pragmatic modernisation roadmap that prioritises highest-ROI changes first, with no disruptive big-bang migrations.'],
            ['q' => 'We have lots of data but no one is using it to make decisions.',               'a' => 'Our analytics programmes move clients from data collection to data culture — combining a modern lakehouse architecture with embedded data literacy programmes that make insight genuinely usable at every level.'],
            ['q' => 'How do we build AI capability without hiring a 20-person data science team?',  'a' => 'We provide a fully managed AI service — from model development and deployment to monitoring and retraining — that gives you the output of a large AI team at a fraction of the fixed cost.'],
            ['q' => 'Our cybersecurity posture is patchy. We had an incident last year.',           'a' => 'We conduct a zero-trust readiness assessment, implement priority controls within 30 days, and deploy 24/7 SOC monitoring — providing immediate risk reduction while a longer-term security transformation is under way.'],
            ['q' => 'We\'ve invested in cloud but our costs keep rising. What are we doing wrong?', 'a' => 'Cloud FinOps is rarely configured correctly at migration. We run a cloud cost optimisation sprint that typically identifies 30–50% savings through right-sizing, reserved capacity, and architecture refactoring.'],
            ['q' => 'How do we evaluate AI vendors without being misled by demos?',                 'a' => 'We run structured AI vendor assessments — including technical due diligence, reference checks, and proof-of-concept design — that let you evaluate real-world performance against your data, not a curated dataset.'],
        ];

        $challenges = $challenges_by_industry[$slug] ?? $default_challenges;
        ?>
        <div class="ve-faq-list ve-reveal">
            <?php foreach ($challenges as $i => $ch) : ?>
            <div class="ve-faq-item" data-index="<?= $i ?>">
                <button class="ve-faq-btn" type="button" aria-expanded="false">
                    <span><?= esc_html($ch['q']) ?></span>
                    <svg class="ve-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="ve-faq-answer" hidden>
                    <p><?= esc_html($ch['a']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Related case studies -->
<?php
$related = get_posts([
    'post_type'      => 'case_study',
    'posts_per_page' => 3,
    'meta_query'     => [['key' => 've_industry', 'value' => $industry_name, 'compare' => 'LIKE']],
]);
if ( ! empty($related) ) : ?>
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Proof Points</div>
            <h2 class="ve-section-title"><?= esc_html($industry_name) ?> <span class="ve-text-gradient">Case Studies</span></h2>
        </div>
        <div class="ve-blog-grid">
            <?php foreach ($related as $cs) : ?>
            <article class="ve-blog-card ve-reveal">
                <div class="ve-blog-card__body">
                    <div class="ve-blog-card__meta"><span style="color:var(--ve-orange);font-weight:600;"><?= esc_html($industry_name) ?></span></div>
                    <h2 class="ve-blog-card__title"><a href="<?= get_permalink($cs) ?>"><?= esc_html($cs->post_title) ?></a></h2>
                    <p class="ve-blog-card__excerpt"><?= wp_trim_words($cs->post_excerpt ?: $cs->post_content, 25) ?></p>
                    <a href="<?= get_permalink($cs) ?>" class="ve-service-card__link">Read Case Study <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Ready to solve your ' . esc_html($industry_name) . ' challenge?',
    'subtitle'   => 'Talk to a specialist who has solved it before.',
    'btn_label'  => 'Discuss Your Challenge',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View All Solutions',
    'btn2_url'   => home_url('/solutions'),
] );
get_footer();
