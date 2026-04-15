<?php
/**
 * Volkmann Express — Case Studies Archive (US Edition)
 */
get_header();

get_template_part('template-parts/hero', null, [
    'badge'    => 'Client Success Stories',
    'title'    => 'US Enterprise <span class="ve-text-gradient">Case Studies</span>',
    'subtitle' => 'Real challenges. Real technology. Real outcomes — from healthcare organizations in Houston to defense contractors in Northern Virginia.',
    'size'     => 'md',
]);
?>

<section class="ve-section ve-section--alt">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-proof-metrics ve-reveal">
            <?php
            $metrics = [
                ['value'=>'200+','label'=>'US Projects Delivered',  'icon'=>'🚀'],
                ['value'=>'$2B+','label'=>'Client Value Created',   'icon'=>'💰'],
                ['value'=>'12',  'label'=>'Industries Served',      'icon'=>'🌐'],
                ['value'=>'98%', 'label'=>'Client Satisfaction',    'icon'=>'⭐'],
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
        <div class="ve-blog-grid">
            <?php if (have_posts()) : while (have_posts()) : the_post();
                $client   = get_post_meta(get_the_ID(),'ve_client',  true);
                $industry = get_post_meta(get_the_ID(),'ve_industry',true);
                $result   = get_post_meta(get_the_ID(),'ve_result',  true);
            ?>
            <article <?php post_class('ve-blog-card ve-reveal'); ?>>
                <?php if (has_post_thumbnail()) : ?>
                <div class="ve-blog-card__thumb"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('ve-card',['class'=>'ve-blog-card__img','loading'=>'lazy']); ?></a></div>
                <?php else : ?>
                <div class="ve-blog-card__thumb" style="background:linear-gradient(135deg,rgba(249,115,22,.1),rgba(37,99,235,.1));display:flex;align-items:center;justify-content:center;min-height:200px;">
                    <div style="width:60px;height:60px;color:var(--ve-orange);"><?= ve_industry_icon($industry ?: 'default') ?></div>
                </div>
                <?php endif; ?>
                <div class="ve-blog-card__body">
                    <div class="ve-blog-card__meta">
                        <?php if ($industry) : ?><span style="color:var(--ve-orange);font-weight:600;"><?= esc_html($industry) ?></span><?php endif; ?>
                        <?php if ($client) : ?><span>&middot; <?= esc_html($client) ?></span><?php endif; ?>
                    </div>
                    <h2 class="ve-blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="ve-blog-card__excerpt"><?php the_excerpt(); ?></p>
                    <?php if ($result) : ?>
                    <div style="background:rgba(249,115,22,.08);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:.75rem 1rem;font-size:.875rem;font-weight:600;color:var(--ve-orange);">✓ <?= esc_html($result) ?></div>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="ve-service-card__link">Read Case Study <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg></a>
                </div>
            </article>
            <?php endwhile;
            else :
                $us_cases = [
                    ['title'=>'AI Cuts Hospital Readmissions 34% at Houston Health System','industry'=>'Healthcare','location'=>'Houston, TX','result'=>'34% fewer readmissions · $3.1M in avoided CMS penalties','excerpt'=>'We built an ML readmission risk model across 280,000 patient records that flagged high-risk patients at discharge — enabling targeted interventions that transformed value-based care performance.'],
                    ['title'=>'Zero-Downtime Multi-Cloud Migration for Atlanta Logistics Leader','industry'=>'Transportation','location'=>'Atlanta, GA','result'=>'$4.2M annual cost eliminated · 99.99% uptime maintained','excerpt'=>'A Fortune 500 freight company exited two aging data centers in 14 weeks without disrupting 24/7 operations — enabling same-day deployment of new routing software.'],
                    ['title'=>'CMMC Level 2 Certification in 11 Weeks for Northern Virginia Defense Contractor','industry'=>'Government & Defense','location'=>'Northern Virginia, VA','result'=>'$40M federal contract secured · Zero security incidents','excerpt'=>'Facing a hard certification deadline, we deployed a zero-trust architecture and 24/7 SOC monitoring — achieving CMMC Level 2 and protecting a critical DoD contract.'],
                    ['title'=>'Analytics Platform Drives 29% Revenue Uplift for Chicago Specialty Retailer','industry'=>'Retail & E-Commerce','location'=>'Chicago, IL','result'=>'29% revenue increase · $8.4M overstock write-offs eliminated','excerpt'=>'We unified 18 data silos into a real-time Snowflake lakehouse, enabling same-day pricing decisions that transformed profitability in year one.'],
                    ['title'=>'43% OEE Improvement at 100-Year-Old San Antonio Manufacturer','industry'=>'Manufacturing','location'=>'San Antonio, TX','result'=>'43% OEE improvement · $4.8M annual savings','excerpt'=>'IoT sensing across 9 production lines combined with an operations intelligence platform and change management program reversed a decade of efficiency decline.'],
                    ['title'=>'Smart Factory IoT Cuts Scrap 38% at Columbus Auto Parts Maker','industry'=>'Manufacturing','location'=>'Columbus, OH','result'=>'38% scrap reduction · 2,400 downtime hours eliminated','excerpt'=>'Real-time telemetry and AI quality inspection across 14 CNC machines drove dramatic OEE gains and material savings at a Tier 1 automotive supplier.'],
                    ['title'=>'EHR Integration Modernization Saves Florida Health Network $2M Backlog','industry'=>'Healthcare','location'=>'South Florida, FL','result'=>'71% maintenance cost reduction · Days to onboard (was months)','excerpt'=>'A FHIR-compliant integration platform replaced 12 years of point-to-point EHR spaghetti — eliminating the $2M backlog and cutting ongoing integration maintenance by 71%.'],
                    ['title'=>'Managed IT Drives 89% Downtime Reduction at New York Consulting Firm','industry'=>'Professional Services','location'=>'New York, NY','result'=>'89% downtime reduction · $1.1M redirected to growth','excerpt'=>'24/7 proactive monitoring and an 18-minute mean time to resolution transformed IT from a cost center to a growth enabler at a 600-person consulting firm.'],
                ];
                foreach ($us_cases as $case) : ?>
                <article class="ve-blog-card ve-reveal">
                    <div class="ve-blog-card__thumb" style="background:linear-gradient(135deg,rgba(249,115,22,.08),rgba(37,99,235,.08));display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.5rem;min-height:160px;padding:1.5rem;">
                        <div style="width:48px;height:48px;color:var(--ve-orange);"><?= ve_industry_icon($case['industry']) ?></div>
                        <span style="font-size:.75rem;font-weight:600;color:var(--ve-text-subtle);letter-spacing:.06em;text-transform:uppercase;"><?= esc_html($case['location']) ?></span>
                    </div>
                    <div class="ve-blog-card__body">
                        <div class="ve-blog-card__meta"><span style="color:var(--ve-orange);font-weight:600;"><?= esc_html($case['industry']) ?></span></div>
                        <h2 class="ve-blog-card__title"><?= esc_html($case['title']) ?></h2>
                        <p class="ve-blog-card__excerpt"><?= esc_html($case['excerpt']) ?></p>
                        <div style="background:rgba(249,115,22,.08);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:.75rem 1rem;font-size:.875rem;font-weight:600;color:var(--ve-orange);">✓ <?= esc_html($case['result']) ?></div>
                    </div>
                </article>
                <?php endforeach;
            endif; ?>
        </div>
        <?php the_posts_pagination(['prev_text'=>'← Previous','next_text'=>'Next →']); ?>
    </div>
</section>

<?php
get_template_part('template-parts/cta', null, [
    'title'      => 'Could your organization be our next success story?',
    'subtitle'   => 'Let\'s explore what\'s possible for your business.',
    'btn_label'  => 'Start a Conversation',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View Our Solutions',
    'btn2_url'   => home_url('/solutions'),
]);
get_footer();
