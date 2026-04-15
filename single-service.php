<?php
/**
 * Volkmann Express — Single Service Page (US Edition)
 * All 8 services with US-based case studies and 3D canvas.
 */
get_header();

$service_data = [
    'ai-machine-learning' => [
        'badge' => 'Artificial Intelligence',
        'title' => 'AI & Machine <span class="ve-text-gradient">Learning</span>',
        'subtitle' => 'Deploy intelligent systems that learn, predict, and automate — delivering measurable ROI from day one, engineered to meet US federal and industry compliance standards.',
        'capabilities' => [
            ['title'=>'Deep Learning','desc'=>'Neural network architectures trained on your proprietary data for best-in-class accuracy across image, text, and time-series tasks.'],
            ['title'=>'Machine Learning','desc'=>'Supervised and unsupervised models that continuously improve with new data — from fraud detection to demand forecasting.'],
            ['title'=>'Natural Language Processing','desc'=>'Intelligent document processing, contract review, chatbots, and sentiment analysis at enterprise scale.'],
            ['title'=>'Predictive Analytics','desc'=>'Forward-looking models that surface risk, churn, and revenue opportunity before they appear in your P&L.'],
            ['title'=>'Computer Vision','desc'=>'Real-time image and video analysis for quality control, safety monitoring, and process automation.'],
            ['title'=>'Responsible AI','desc'=>'Explainability layers, bias auditing, and governance frameworks that meet NIST AI RMF and emerging federal requirements.'],
        ],
        'process' => ['Data Audit','Model Design','Training','Validation','Deployment','Monitoring'],
        'cta' => 'Schedule AI Consultation',
    ],
    'cloud' => [
        'badge' => 'Cloud Solutions',
        'title' => 'Cloud <span class="ve-text-gradient">Solutions</span>',
        'subtitle' => 'Migrate, modernize, and scale with secure, high-performance cloud architecture across AWS, Azure, and GCP — FedRAMP and HIPAA ready.',
        'capabilities' => [
            ['title'=>'Cloud Migration','desc'=>'Lift-and-shift or re-architect migrations to AWS, Azure, or GCP with zero downtime and a proven cutover playbook.'],
            ['title'=>'Infrastructure as Code','desc'=>'Terraform and Pulumi-powered environments that are reproducible, auditable, and deployable in minutes.'],
            ['title'=>'Cloud Security & Compliance','desc'=>'FedRAMP, HIPAA, and SOC 2 posture management — zero-trust IAM hardening, continuous compliance monitoring.'],
            ['title'=>'FinOps & Cost Governance','desc'=>'Right-sizing, reserved capacity, and automated waste elimination — typically 30–50% cost reduction within 90 days.'],
            ['title'=>'Cloud Native Development','desc'=>'Kubernetes, microservices, and serverless architectures built to scale elastically and deploy continuously.'],
            ['title'=>'Multi-Cloud Strategy','desc'=>'Vendor-neutral architecture that reduces lock-in, maximizes resilience, and leverages best-of-breed services.'],
        ],
        'process' => ['Assessment','Architecture','Migration','Optimize','Monitor','Govern'],
        'cta' => 'Get Started with Cloud',
    ],
    'cybersecurity' => [
        'badge' => 'Cybersecurity',
        'title' => 'Enterprise <span class="ve-text-gradient">Cybersecurity</span>',
        'subtitle' => '24/7 SOC-backed protection, zero-trust architecture, and proactive threat hunting — NIST, CMMC, HIPAA, and FedRAMP expertise built in.',
        'capabilities' => [
            ['title'=>'Threat Detection & Response','desc'=>'AI-powered SIEM with sub-minute detection and automated SOAR playbooks — stopping threats before they become incidents.'],
            ['title'=>'CMMC & NIST Compliance','desc'=>'End-to-end CMMC Level 1/2/3 preparation, assessment support, and continuous compliance evidence generation for DoD contractors.'],
            ['title'=>'Zero Trust Architecture','desc'=>'NIST SP 800-207-aligned zero trust implementation — identity-centric access controls, micro-segmentation, and continuous verification.'],
            ['title'=>'Penetration Testing','desc'=>'Red team exercises, network and application pen tests, and social engineering assessments that find gaps before adversaries do.'],
            ['title'=>'Cloud Security Posture','desc'=>'CSPM, CWPP, and CIEM controls across AWS, Azure, and GCP — automated drift detection and policy enforcement.'],
            ['title'=>'Incident Response','desc'=>'24/7 IR retainer with guaranteed 1-hour response SLA, forensic investigation capability, and breach notification support.'],
        ],
        'process' => ['Assess','Design','Deploy','Monitor','Respond','Report'],
        'cta' => 'Get Security Assessment',
    ],
    'data-analytics' => [
        'badge' => 'Data Analytics',
        'title' => 'Data <span class="ve-text-gradient">Analytics</span>',
        'subtitle' => 'Turn raw data into competitive intelligence with real-time dashboards, predictive models, and a modern data lakehouse built on Snowflake, Databricks, or BigQuery.',
        'capabilities' => [
            ['title'=>'Business Intelligence','desc'=>'Executive dashboards and operational reports built on Power BI, Looker, or Tableau — surfacing the KPIs that drive decisions.'],
            ['title'=>'Predictive Analytics','desc'=>'Demand forecasting, churn prediction, and risk scoring models that give you a measurable lead time on market changes.'],
            ['title'=>'Customer Analytics','desc'=>'360-degree customer profiles, segmentation models, CLV analysis, and next-best-action engines.'],
            ['title'=>'Data Engineering','desc'=>'Modern data lakehouse on Snowflake, Databricks, or BigQuery — reliable, scalable, and documented pipelines your team can maintain.'],
            ['title'=>'Data Governance','desc'=>'CCPA and HIPAA-aligned data cataloging, lineage tracking, and access control frameworks that satisfy auditors and accelerate analytics.'],
            ['title'=>'Embedded Analytics','desc'=>'White-label analytics capabilities embedded directly in your product — turning your data into a revenue stream.'],
        ],
        'process' => ['Collect','Warehouse','Model','Visualize','Act','Iterate'],
        'cta' => 'Start Your Analytics Journey',
    ],
    'digital-transformation' => [
        'badge' => 'Digital Transformation',
        'title' => 'Digital <span class="ve-text-gradient">Transformation</span>',
        'subtitle' => 'End-to-end reinvention — process automation, culture change, and digital workplace modernization for mid-market and enterprise organizations across the US.',
        'capabilities' => [
            ['title'=>'Process Automation','desc'=>'RPA, intelligent document processing, and workflow automation that eliminates manual work and redirects staff to high-value tasks.'],
            ['title'=>'Digital Workplace','desc'=>'Microsoft 365, Teams, and SharePoint modernization — collaboration platforms deployed and actually adopted at scale.'],
            ['title'=>'ERP & Legacy Modernization','desc'=>'Phased modernization of SAP, Oracle, and custom legacy systems — new capabilities without big-bang risk.'],
            ['title'=>'Customer Experience','desc'=>'Omnichannel digital journeys, self-service portals, and CX automation that reduce friction and increase lifetime value.'],
            ['title'=>'Change Management','desc'=>'Prosci-certified change programs that ensure technology investments are actually adopted — not just installed.'],
            ['title'=>'Innovation Programs','desc'=>'Structured hackathons, POC pipelines, and innovation governance that surface ideas and accelerate from concept to production.'],
        ],
        'process' => ['Assess','Strategize','Prioritize','Implement','Adopt','Scale'],
        'cta' => 'Start Digital Transformation',
    ],
    'custom-software' => [
        'badge' => 'Custom Software',
        'title' => 'Custom <span class="ve-text-gradient">Software</span>',
        'subtitle' => 'Bespoke web, mobile, and enterprise applications built to your exact specifications — secure, scalable, and engineered for long-term maintainability.',
        'capabilities' => [
            ['title'=>'Web Applications','desc'=>'React, Next.js, and Vue frontends backed by robust Python, Node.js, or .NET APIs — built for performance and accessibility.'],
            ['title'=>'Mobile Applications','desc'=>'Cross-platform iOS/Android apps with native performance using React Native or Flutter — App Store to production in weeks.'],
            ['title'=>'Enterprise Software','desc'=>'ERP extensions, workflow engines, and line-of-business tools that integrate cleanly with your existing stack.'],
            ['title'=>'API Development','desc'=>'RESTful and GraphQL APIs with authentication, rate limiting, versioning, and developer portals that teams actually use.'],
            ['title'=>'Secure by Design','desc'=>'OWASP Top 10 mitigations, SAST/DAST in CI/CD, pen testing pre-launch, and ongoing vulnerability management.'],
            ['title'=>'Accessibility & Compliance','desc'=>'WCAG 2.1 AA compliance built in from the start — not bolted on afterward — for federal and commercial clients alike.'],
        ],
        'process' => ['Discovery','Planning','Development','Testing','Deployment','Support'],
        'cta' => 'Start Your Project',
    ],
    'iot-automation' => [
        'badge' => 'IoT & Automation',
        'title' => 'IoT & <span class="ve-text-gradient">Automation</span>',
        'subtitle' => 'Connected device ecosystems and intelligent automation that streamline manufacturing, facilities, and field operations — from sensor to insight to action.',
        'capabilities' => [
            ['title'=>'Industrial IoT Platform','desc'=>'End-to-end IIoT architecture — edge devices, secure connectivity, time-series data pipelines, and real-time dashboards for operations.'],
            ['title'=>'Predictive Maintenance','desc'=>'ML models trained on your equipment\'s failure history that predict remaining useful life and schedule maintenance only when needed.'],
            ['title'=>'Smart Building & Facilities','desc'=>'BMS integration, energy optimization, and occupancy-driven HVAC and lighting automation for commercial real estate.'],
            ['title'=>'Robotic Process Automation','desc'=>'UiPath and Automation Anywhere implementations that automate high-volume, rule-based tasks across finance, HR, and ops.'],
            ['title'=>'Quality Inspection AI','desc'=>'Computer vision systems that inspect 100% of production output at line speed — replacing sampling-based QC with real-time defect detection.'],
            ['title'=>'Digital Twin','desc'=>'Physics-based and data-driven digital twins of your facilities, equipment, or supply chain for simulation and scenario planning.'],
        ],
        'process' => ['Assess','Design','Pilot','Deploy','Integrate','Optimize'],
        'cta' => 'Explore IoT Solutions',
    ],
    'managed-it-services' => [
        'badge' => 'Managed IT Services',
        'title' => 'Managed IT <span class="ve-text-gradient">Services</span>',
        'subtitle' => 'Proactive monitoring, helpdesk, and infrastructure management that keeps your technology running — so your team can focus entirely on growth.',
        'capabilities' => [
            ['title'=>'24/7 NOC & Monitoring','desc'=>'Around-the-clock network operations center monitoring with automated alerting and human escalation — issues resolved before users notice.'],
            ['title'=>'IT Helpdesk','desc'=>'Tier 1–3 helpdesk support with guaranteed SLAs, a self-service portal, and average first-call resolution above 82%.'],
            ['title'=>'Endpoint Management','desc'=>'Patch management, antivirus, and device lifecycle across Windows, Mac, and mobile — automated and auditable.'],
            ['title'=>'Backup & Disaster Recovery','desc'=>'3-2-1 backup strategy with tested recovery runbooks — RTO and RPO commitments we actually guarantee in writing.'],
            ['title'=>'Vendor Management','desc'=>'Single point of contact for all technology vendors — we own the relationships, manage escalations, and protect your time.'],
            ['title'=>'vCIO Services','desc'=>'Fractional CIO advisory — technology roadmapping, budget planning, and board-level reporting without the full-time cost.'],
        ],
        'process' => ['Onboard','Baseline','Monitor','Maintain','Report','Improve'],
        'cta' => 'Get a Managed IT Quote',
    ],
];

global $post;
$slug = is_singular('service') ? $post->post_name : '';
// Also try URL-based matching for static page routes
if ( ! $slug ) {
    $uri = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    $parts = explode('/', $uri);
    $slug = end($parts);
}
$data = $service_data[$slug] ?? null;

if ( ! $data && is_singular('service') ) {
    $proof = ve_us_proof($slug);
    $data = [
        'badge'        => 'Solution',
        'title'        => get_the_title(),
        'subtitle'     => get_the_excerpt(),
        'capabilities' => [],
        'process'      => [],
        'cta'          => 'Schedule Consultation',
    ];
}
if ( ! $data ) {
    // Fallback to first service
    $data = array_values($service_data)[0];
}
$proof = ve_us_proof($slug);
?>

<?php
get_template_part( 'template-parts/hero', null, [
    'badge'     => $data['badge'],
    'title'     => $data['title'],
    'subtitle'  => $data['subtitle'],
    'cta_label' => $data['cta'],
    'cta_url'   => home_url('/contact'),
    'size'      => 'md',
] );
?>

<!-- Capabilities -->
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-section-header">
            <div class="ve-badge">What We Deliver</div>
            <h2 class="ve-section-title">Core <span class="ve-text-gradient">Capabilities</span></h2>
        </div>
        <div class="ve-capabilities-grid">
            <?php foreach ( $data['capabilities'] as $i => $cap ) : ?>
            <div class="ve-cap-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-cap-card__num"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
                <h3><?= esc_html($cap['title']) ?></h3>
                <p><?= esc_html($cap['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Process + 3D visual -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-solution-process-layout">
            <div class="ve-solution-process-left">
                <div class="ve-section-header" style="text-align:left;">
                    <div class="ve-badge">Delivery Approach</div>
                    <h2 class="ve-section-title" style="max-width:none;">How We <span class="ve-text-gradient">Work</span></h2>
                </div>
                <div class="ve-process-steps-inline ve-process-steps-inline--vertical">
                    <?php foreach ( $data['process'] as $i => $step ) : ?>
                    <div class="ve-process-pill ve-process-pill--vertical ve-reveal" style="--delay:<?= $i*80 ?>ms">
                        <span class="ve-process-pill__num"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></span>
                        <span><?= esc_html($step) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="ve-solution-3d-wrap" aria-hidden="true">
                <canvas id="ve-solution-canvas" data-service="<?= esc_attr($slug) ?>"></canvas>
            </div>
        </div>
    </div>
</section>

<!-- US Proof -->
<?php if ( ! empty($proof) ) : ?>
<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-proof-block">
            <div class="ve-proof-block__copy">
                <div class="ve-badge">US Case Study</div>
                <h2 class="ve-section-title"><?= esc_html($proof['title']) ?></h2>
                <p class="ve-body-text"><?= esc_html($proof['story']) ?></p>
                <a href="<?= esc_url(home_url('/case-studies')) ?>" class="ve-btn ve-btn--secondary mt-6">View More Case Studies</a>
            </div>
            <div class="ve-proof-block__results">
                <?php foreach ( $proof['results'] as $r ) : ?>
                <div class="ve-result-stat ve-reveal">
                    <span class="ve-result-stat__value"><?= esc_html($r['value']) ?></span>
                    <span class="ve-result-stat__label"><?= esc_html($r['label']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta', null, [
    'btn_label'  => esc_html($data['cta']),
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View All Solutions',
    'btn2_url'   => home_url('/solutions'),
] );
get_footer();
