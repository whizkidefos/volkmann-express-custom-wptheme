<?php
/**
 * Volkmann Express — Careers Page
 * Template Name: Careers
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'     => 'Careers',
    'title'     => 'Build the Future of <span class="ve-text-gradient">Enterprise Tech</span>',
    'subtitle'  => 'We hire curious people who care about craft, want to grow fast, and believe technology should make the world measurably better.',
    'cta_label' => 'View Open Roles',
    'cta_url'   => '#ve-open-roles',
    'cta2_label'=> 'About Us',
    'cta2_url'  => home_url('/about'),
    'size'      => 'md',
] );
?>

<!-- Why Volkmann Express -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Why Work Here</div>
            <h2 class="ve-section-title">What Makes Us <span class="ve-text-gradient">Different</span></h2>
            <p class="ve-section-sub">We don't recite perks. These are the things our team actually say when asked why they joined — and why they stayed.</p>
        </div>
        <div class="ve-values-grid">
            <?php
            $reasons = [
                ['emoji' => '🧠', 'title' => 'Work on Hard Problems', 'desc' => 'Our clients bring us their toughest challenges — the ones that have stumped internal teams and generic vendors. Every project is a chance to think deeply and build something that genuinely matters.'],
                ['emoji' => '🚀', 'title' => 'Ship at Speed', 'desc' => 'We operate in small, empowered teams with short feedback loops. No endless approval chains. No death-by-committee. You\'ll see your work in production within weeks.'],
                ['emoji' => '📈', 'title' => 'Grow Rapidly', 'desc' => 'We invest seriously in development — a dedicated learning budget, internal knowledge programmes, conference attendance, and mentorship from practitioners who have built at scale.'],
                ['emoji' => '🌍', 'title' => 'Sector Variety', 'desc' => 'One quarter you might be deploying AI in healthcare, the next you\'re hardening financial infrastructure. Breadth of exposure accelerates learning in ways deep specialisation rarely does.'],
                ['emoji' => '🤝', 'title' => 'Flat & Transparent', 'desc' => 'Everyone sees the financials, the pipeline, and the strategy. We make decisions together where we can, and explain our reasoning when we can\'t. No politics.'],
                ['emoji' => '⚖️', 'title' => 'Sustainable Pace', 'desc' => 'We don\'t burn people out to hit short-term revenue targets. Engagements are scoped and staffed realistically, and we say no to work that would require the team to operate unsustainably.'],
            ];
            foreach ($reasons as $r) : ?>
            <div class="ve-value-card ve-reveal">
                <span class="ve-value-card__emoji"><?= $r['emoji'] ?></span>
                <h3><?= esc_html($r['title']) ?></h3>
                <p><?= esc_html($r['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Benefits</div>
            <h2 class="ve-section-title">A Package Built for <span class="ve-text-gradient">Long-Term Growth</span></h2>
        </div>
        <div class="ve-benefits-grid">
            <?php
            $benefits = [
                ['icon' => '💰', 'title' => 'Competitive Salary',    'desc' => 'Market-leading compensation reviewed annually, with transparent pay bands.'],
                ['icon' => '📚', 'title' => 'Learning Budget',       'desc' => '£2,000/year for courses, certifications, conferences, and books — yours to use.'],
                ['icon' => '🏠', 'title' => 'Flexible Working',      'desc' => 'Hybrid-first culture. We trust you to know where you do your best work.'],
                ['icon' => '🌴', 'title' => '33 Days Holiday',       'desc' => '25 days annual leave plus bank holidays, plus your birthday off.'],
                ['icon' => '❤️', 'title' => 'Private Healthcare',    'desc' => 'Full private health and dental insurance for you and your family.'],
                ['icon' => '💼', 'title' => 'Pension',               'desc' => '6% employer matched pension contribution from day one.'],
                ['icon' => '🏋️', 'title' => 'Wellbeing Allowance',  'desc' => '£50/month for gym, sport, meditation — whatever keeps you performing.'],
                ['icon' => '🛫', 'title' => 'Team Offsites',         'desc' => 'Quarterly all-hands with team building, strategy, and genuine fun.'],
            ];
            foreach ($benefits as $b) : ?>
            <div class="ve-benefit-card ve-reveal">
                <span class="ve-benefit-card__emoji"><?= $b['icon'] ?></span>
                <h3><?= esc_html($b['title']) ?></h3>
                <p><?= esc_html($b['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Open roles -->
<section class="ve-section" id="ve-open-roles">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header ve-reveal">
            <div class="ve-badge">Open Roles</div>
            <h2 class="ve-section-title">Current <span class="ve-text-gradient">Opportunities</span></h2>
        </div>

        <?php
        // Check for roles via CPT or custom post type "jobs" — fall back to hardcoded
        $job_posts = get_posts(['post_type' => 'job', 'posts_per_page' => -1, 'post_status' => 'publish']);
        if (!empty($job_posts)) : ?>

        <div class="ve-roles-list">
            <?php foreach ($job_posts as $job) :
                $dept     = get_post_meta($job->ID, 've_dept',     true);
                $location = get_post_meta($job->ID, 've_location', true);
                $type     = get_post_meta($job->ID, 've_type',     true);
            ?>
            <a href="<?= get_permalink($job) ?>" class="ve-role-card ve-reveal">
                <div class="ve-role-card__info">
                    <span class="ve-role-card__dept"><?= esc_html($dept ?: 'General') ?></span>
                    <h3 class="ve-role-card__title"><?= esc_html($job->post_title) ?></h3>
                    <div class="ve-role-card__meta">
                        <?php if ($location) : ?><span><?= esc_html($location) ?></span><?php endif; ?>
                        <?php if ($type)     : ?><span><?= esc_html($type) ?></span><?php endif; ?>
                    </div>
                </div>
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 flex-shrink-0 text-orange-400"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>
            <?php endforeach; ?>
        </div>

        <?php else : ?>

        <div class="ve-roles-list ve-reveal">
            <?php
            $roles = [
                ['dept' => 'Engineering',    'title' => 'Senior Software Engineer (Python / FastAPI)',  'location' => 'London / Remote',  'type' => 'Full-time'],
                ['dept' => 'Engineering',    'title' => 'Lead Frontend Engineer (React / Next.js)',     'location' => 'London / Remote',  'type' => 'Full-time'],
                ['dept' => 'Data & AI',      'title' => 'Senior Machine Learning Engineer',             'location' => 'Remote (UK)',      'type' => 'Full-time'],
                ['dept' => 'Data & AI',      'title' => 'Data Platform Architect (Snowflake/Databricks)','location' => 'London / Remote', 'type' => 'Full-time'],
                ['dept' => 'Cybersecurity',  'title' => 'SOC Analyst — Tier 2',                        'location' => 'London',           'type' => 'Full-time'],
                ['dept' => 'Cybersecurity',  'title' => 'Penetration Tester (Web / Cloud)',             'location' => 'Remote (UK)',      'type' => 'Full-time'],
                ['dept' => 'Cloud',          'title' => 'Senior Cloud Infrastructure Engineer (AWS)',   'location' => 'London / Remote',  'type' => 'Full-time'],
                ['dept' => 'Consulting',     'title' => 'Digital Transformation Consultant',            'location' => 'London',           'type' => 'Full-time'],
                ['dept' => 'Consulting',     'title' => 'AI Strategy Consultant',                       'location' => 'London / Remote',  'type' => 'Full-time'],
                ['dept' => 'Product',        'title' => 'Senior Product Manager',                       'location' => 'London',           'type' => 'Full-time'],
            ];
            foreach ($roles as $role) : ?>
            <a href="<?= esc_url(home_url('/contact')) ?>" class="ve-role-card ve-reveal">
                <div class="ve-role-card__info">
                    <span class="ve-role-card__dept"><?= esc_html($role['dept']) ?></span>
                    <h3 class="ve-role-card__title"><?= esc_html($role['title']) ?></h3>
                    <div class="ve-role-card__meta">
                        <span>
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="w-3.5 h-3.5"><path d="M8 9.5a3 3 0 100-6 3 3 0 000 6z"/><path d="M8 1C5.24 1 3 3.24 3 6c0 3.94 5 9 5 9s5-5.06 5-9c0-2.76-2.24-5-5-5z"/></svg>
                            <?= esc_html($role['location']) ?>
                        </span>
                        <span>
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="w-3.5 h-3.5"><circle cx="8" cy="8" r="7"/><path d="M8 4.5v3.5l2 2"/></svg>
                            <?= esc_html($role['type']) ?>
                        </span>
                    </div>
                </div>
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="ve-role-card__arrow"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
            </a>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>

        <!-- Speculative -->
        <div class="ve-speculative-card ve-reveal">
            <div>
                <h3>Don't see your role listed?</h3>
                <p>We're always interested in exceptional people. Send us your CV and a brief note on what you're looking for — we'll be in touch if there's a fit.</p>
            </div>
            <a href="<?= esc_url(home_url('/contact')) ?>" class="ve-btn ve-btn--secondary flex-shrink-0">
                Send Speculative Application
            </a>
        </div>

    </div>
</section>

<!-- Hiring process -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">Our Process</div>
            <h2 class="ve-section-title">How We <span class="ve-text-gradient">Hire</span></h2>
            <p class="ve-section-sub">Transparent, respectful, and focused on finding the right mutual fit — not putting you through pointless hoops.</p>
        </div>
        <div class="ve-process-track">
            <?php
            $steps = [
                ['num' => '01', 'title' => 'Application',   'desc' => 'Submit your CV and a short cover note. We read every application personally and respond within 5 business days.'],
                ['num' => '02', 'title' => 'Intro Call',    'desc' => '30-minute conversation with a member of our talent team. We\'ll cover your background, motivations, and answer your questions.'],
                ['num' => '03', 'title' => 'Technical Stage','desc' => 'A practical, take-home assessment or live problem-solving session. Scoped to be completable in under 3 hours — we respect your time.'],
                ['num' => '04', 'title' => 'Final Interview','desc' => 'A structured conversation with the hiring manager and a future peer. We discuss real scenarios, not brain teasers.'],
            ];
            foreach ($steps as $step) : ?>
            <div class="ve-process-step ve-reveal">
                <div class="ve-process-step__num"><?= esc_html($step['num']) ?></div>
                <div class="ve-process-step__line"></div>
                <h3 class="ve-process-step__title"><?= esc_html($step['title']) ?></h3>
                <p class="ve-process-step__desc"><?= esc_html($step['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Ready to join us?',
    'subtitle'   => 'Browse our open roles or send us a speculative application.',
    'btn_label'  => 'View Open Roles',
    'btn_url'    => '#ve-open-roles',
    'btn2_label' => 'Contact Us',
    'btn2_url'   => home_url('/contact'),
] );
get_footer();
