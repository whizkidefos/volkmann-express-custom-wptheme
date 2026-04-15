<?php
/**
 * Volkmann Express — FAQ Page
 * Template Name: FAQ
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'    => 'FAQ',
    'title'    => 'Frequently Asked <span class="ve-text-gradient">Questions</span>',
    'subtitle' => 'Everything you need to know about working with Volkmann Express — from first conversation to long-term partnership.',
    'cta_label'=> 'Still have questions? Contact us',
    'cta_url'  => home_url('/contact'),
    'size'     => 'sm',
] );

$faq_sections = [
    [
        'section' => 'Working With Us',
        'items'   => [
            ['q'=>'How does a typical engagement begin?','a'=>'Every engagement starts with a no-obligation discovery call — typically 45–60 minutes — where we listen to your challenge, ask the questions that matter, and share honest initial thoughts on approach. If there\'s a fit, we propose a structured discovery workshop (2–5 days) that produces a clear roadmap, effort estimate, and business case. You own everything from that workshop whether you proceed with us or not.'],
            ['q'=>'What engagement models do you offer?','a'=>'We work across three models. Fixed-scope projects suit defined problems with clear outputs — we agree on deliverables, timelines, and price upfront with no surprises. Time-and-materials retainers work for ongoing innovation where scope is uncertain. Managed services are available for clients who want us to own a capability long-term — SOC monitoring, data platform operations, or AI model management.'],
            ['q'=>'Do you work with startups or only large enterprises?','a'=>'Our primary market is mid-market and enterprise — typically £20M+ in revenue or significant institutional backing. We selectively work with well-funded scale-ups where the technical challenge is genuinely interesting and founders have the maturity to extract value from a partner like us. If you\'re unsure, just reach out — we\'ll tell you honestly.'],
            ['q'=>'How do you handle intellectual property?','a'=>'For bespoke work fully funded by a client, all IP vests in the client upon full payment. We retain rights to our proprietary frameworks, tooling, and reusable components — which we\'re transparent about up front. We\'re happy to discuss licensing for specific components where our pre-built accelerators form a substantial part of the solution.'],
            ['q'=>'What does your pricing look like?','a'=>'We don\'t publish standard rates because engagements vary so widely in scope and complexity. Discovery workshops typically run £15,000–£40,000. Multi-phase transformation programmes range from £150,000 to several million. We provide detailed commercial proposals after the discovery call. We price for value delivered — clients choose us because outcomes justify the investment.'],
        ],
    ],
    [
        'section' => 'Technical Questions',
        'items'   => [
            ['q'=>'We\'re heavily invested in a particular cloud provider. Can you work within our existing stack?','a'=>'Yes — we are cloud-agnostic and hold certifications across AWS, Azure, and GCP. We design for your constraints, not ours. In most cases we find significant optimisation opportunity within your existing provider before recommending architectural change. If multi-cloud would genuinely benefit you, we\'ll explain why with concrete numbers, not sales language.'],
            ['q'=>'How do you approach data security and privacy?','a'=>'Security is designed in from day one: threat modelling in the architecture phase, SAST/DAST in CI/CD, penetration testing before go-live, and ongoing vulnerability management post-launch. For regulated industries we have specific frameworks for GDPR, HIPAA, FCA, DSPT, and ISO 27001.'],
            ['q'=>'We have significant legacy technical debt. Where do you recommend starting?','a'=>'Our starting point is always a Technical Debt Audit that maps your architecture, quantifies the cost of inaction, and identifies the highest-ROI modernisation moves. We prioritise ruthlessly — most organisations achieve 80% of the benefit by addressing 20% of the debt. We rarely recommend big-bang rewrites.'],
            ['q'=>'How do you ensure AI models don\'t become outdated?','a'=>'Every AI system we deploy includes a monitoring layer that tracks model performance against real-world outcomes, flags drift automatically, and triggers retraining workflows. We build human-in-the-loop review checkpoints at appropriate intervals, and our handover documentation includes a model governance playbook your team can execute independently.'],
            ['q'=>'What does your quality assurance process look like?','a'=>'QA is continuous throughout delivery, not a phase at the end. We practise test-driven development with unit, integration, and end-to-end tests automated in CI. For AI products we run data quality validation, bias testing, and performance benchmarking. All user-facing products receive pre-launch security reviews, load testing, and accessibility audits.'],
        ],
    ],
    [
        'section' => 'Track Record & Reporting',
        'items'   => [
            ['q'=>'Can you share references from past clients?','a'=>'Yes. At proposal stage we offer to connect you directly with clients who have faced similar challenges. We ask that calls be scheduled through us to respect our clients\' time — but the conversations are entirely unmediated. You\'ll speak with their technology and business leaders, not our account team.'],
            ['q'=>'How do you measure and report on project success?','a'=>'At the start of every engagement we agree on a shared definition of success: specific, measurable business metrics — not technical outputs. This is reviewed at every monthly steering. We produce a plain-English monthly report showing progress against those metrics, flagging risks early, and highlighting decisions required. We don\'t hide problems; we surface them fast.'],
            ['q'=>'What happens if a project runs into difficulty?','a'=>'Our policy is to escalate issues to the steering committee within 24 hours of identification. We maintain a formal risk register reviewed weekly. If scope, timeline, or budget needs to change, we bring a clear options appraisal — not just bad news. Our 98% client retention rate reflects how we behave when things get hard.'],
        ],
    ],
];
?>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-faq-sections">
            <?php foreach ($faq_sections as $section) : ?>
            <div class="ve-faq-section ve-reveal">
                <h2 class="ve-faq-section__heading"><span class="ve-text-gradient"><?= esc_html($section['section']) ?></span></h2>
                <div class="ve-faq-list">
                    <?php foreach ($section['items'] as $item) : ?>
                    <div class="ve-faq-item">
                        <button class="ve-faq-btn" type="button" aria-expanded="false">
                            <span><?= esc_html($item['q']) ?></span>
                            <svg class="ve-faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="ve-faq-answer" hidden><p><?= esc_html($item['a']) ?></p></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'title'      => 'Still have a question we haven\'t answered?',
    'subtitle'   => 'Our team replies to every enquiry — usually within a few hours.',
    'btn_label'  => 'Get in Touch',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View All Solutions',
    'btn2_url'   => home_url('/solutions'),
] );
get_footer();
