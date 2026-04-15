<?php
/**
 * Volkmann Express — Template Helpers (US Edition)
 */
defined( 'ABSPATH' ) || exit;

function ve_service_icon( string $slug ): string {
    $icons = [
        'ai'              => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M8 12h8M12 8v8"/><circle cx="12" cy="12" r="3"/></svg>',
        'cloud'           => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 18a4 4 0 0 0 0-8h-.5A6 6 0 1 0 5.5 18H17z"/></svg>',
        'cybersecurity'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 3l7 4v5c0 4.418-2.99 8.552-7 10-4.01-1.448-7-5.582-7-10V7l7-4z"/></svg>',
        'data'            => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v4c0 1.657 4.03 3 9 3s9-1.343 9-3V5"/><path d="M3 9v4c0 1.657 4.03 3 9 3s9-1.343 9-3V9"/><path d="M3 13v4c0 1.657 4.03 3 9 3s9-1.343 9-3v-4"/></svg>',
        'digital'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
        'custom-software' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/><line x1="12" y1="5" x2="12" y2="19"/></svg>',
        'iot'             => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M6.3 6.3a8 8 0 0 0 0 11.4"/><path d="M17.7 6.3a8 8 0 0 1 0 11.4"/><path d="M3.5 3.5a14 14 0 0 0 0 17"/><path d="M20.5 3.5a14 14 0 0 1 0 17"/></svg>',
        'managed'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><path d="M7 8h10M7 12h5"/></svg>',
        'default'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>',
    ];
    foreach ( $icons as $key => $svg ) {
        if ( str_contains( $slug, $key ) ) return $svg;
    }
    return $icons['default'];
}

function ve_industry_icon( string $name ): string {
    $map = [
        'healthcare'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg>',
        'financial'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
        'manufacturing'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 20h20M4 20V10l6-6 6 6v10"/><rect x="9" y="14" width="6" height="6"/></svg>',
        'retail'             => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',
        'transport'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        'energy'             => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
        'agriculture'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 8C8 10 5.9 16.17 3.82 20.6"/><path d="M17 8c0 5.33-2 9-2 9"/><path d="M6 16c2-5 8-7 13-8"/></svg>',
        'construction'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 20h20M4 20V8l8-6 8 6v12"/><rect x="9" y="12" width="6" height="8"/></svg>',
        'telecommunications' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><circle cx="12" cy="20" r="1"/></svg>',
        'media'              => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>',
        'government'         => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 22h18M4 22V8l8-6 8 6v14"/><path d="M9 22V12h6v10"/><path d="M12 2v2"/></svg>',
        'default'            => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>',
    ];
    $name_lower = strtolower( $name );
    foreach ( $map as $key => $svg ) {
        if ( str_contains( $name_lower, $key ) ) return $svg;
    }
    return $map['default'];
}

function ve_stat( string $value, string $label, string $class = '' ): void { ?>
    <div class="ve-stat <?= esc_attr($class) ?>">
        <span class="ve-stat__value"><?= esc_html($value) ?></span>
        <span class="ve-stat__label"><?= esc_html($label) ?></span>
    </div>
<?php }

/**
 * 8 default services — US-focused
 */
function ve_default_services(): array {
    return [
        [
            'title'   => 'AI & Machine Learning',
            'slug'    => 'ai-machine-learning',
            'excerpt' => 'Intelligent systems that predict, learn, and automate — delivering measurable ROI from day one.',
            'icon'    => 'ai',
        ],
        [
            'title'   => 'Cloud Solutions',
            'slug'    => 'cloud',
            'excerpt' => 'Secure, scalable AWS, Azure, and GCP infrastructure engineered for performance and cost efficiency.',
            'icon'    => 'cloud',
        ],
        [
            'title'   => 'Cybersecurity',
            'slug'    => 'cybersecurity',
            'excerpt' => '24/7 SOC-backed threat detection, zero-trust architecture, and NIST/CMMC compliance frameworks.',
            'icon'    => 'cybersecurity',
        ],
        [
            'title'   => 'Data Analytics',
            'slug'    => 'data-analytics',
            'excerpt' => 'Turn siloed data into competitive intelligence with real-time dashboards and predictive models.',
            'icon'    => 'data',
        ],
        [
            'title'   => 'Digital Transformation',
            'slug'    => 'digital-transformation',
            'excerpt' => 'End-to-end reinvention — process automation, culture change, and digital workplace modernization.',
            'icon'    => 'digital',
        ],
        [
            'title'   => 'Custom Software',
            'slug'    => 'custom-software',
            'excerpt' => 'Bespoke web, mobile, and enterprise applications engineered to your exact specifications.',
            'icon'    => 'custom-software',
        ],
        [
            'title'   => 'IoT & Automation',
            'slug'    => 'iot-automation',
            'excerpt' => 'Connected device ecosystems and intelligent automation that streamline operations and reduce waste.',
            'icon'    => 'iot',
        ],
        [
            'title'   => 'Managed IT Services',
            'slug'    => 'managed-it-services',
            'excerpt' => 'Proactive monitoring, helpdesk, and infrastructure management so your team can focus on growth.',
            'icon'    => 'managed',
        ],
    ];
}

/**
 * Default industries — US-market framing
 */
function ve_default_industries(): array {
    return [
        [ 'name' => 'Enterprise & Technology',    'solutions' => ['AI/ML','Cloud','Cybersecurity','Custom Software'] ],
        [ 'name' => 'Healthcare & Life Sciences',  'solutions' => ['AI Diagnostics','Data Analytics','HIPAA Compliance'] ],
        [ 'name' => 'Manufacturing',               'solutions' => ['Digital Transformation','IoT','Automation'] ],
        [ 'name' => 'Retail & E-Commerce',         'solutions' => ['Data Analytics','Cloud','Customer AI'] ],
        [ 'name' => 'Financial Services',          'solutions' => ['Cybersecurity','Fraud Detection','SOX Compliance'] ],
        [ 'name' => 'Transportation & Logistics',  'solutions' => ['Route Optimization','Digital Twin','Fleet AI'] ],
        [ 'name' => 'Energy & Utilities',          'solutions' => ['Predictive Maintenance','Smart Grid AI','Analytics'] ],
        [ 'name' => 'Agriculture',                 'solutions' => ['Precision Farming AI','IoT Sensors','Analytics'] ],
        [ 'name' => 'Construction',                'solutions' => ['Project Intelligence','Digital Transformation','BIM AI'] ],
        [ 'name' => 'Professional Services',       'solutions' => ['Process Automation','Custom Software','Analytics'] ],
        [ 'name' => 'Telecommunications',          'solutions' => ['Network AI','Churn Prediction','Cloud Migration'] ],
        [ 'name' => 'Government & Defense',        'solutions' => ['Cybersecurity','CMMC Compliance','Data Analytics'] ],
    ];
}

/**
 * US-based case study proof stories per service slug
 */
function ve_us_proof( string $slug ): array {
    $stories = [
        'ai-machine-learning' => [
            'title'   => 'Reducing Patient Readmissions at a Major Houston Health System',
            'story'   => 'A Houston-based health system engaged us to build a readmission risk model across 280,000 patient records. Our ML platform flagged high-risk patients at discharge, enabling targeted interventions that cut 30-day readmissions by 34% and generated $3.1M in annual avoided penalties under CMS value-based care programs.',
            'results' => [['value'=>'34%','label'=>'Reduction in 30-day readmissions'],['value'=>'$3.1M','label'=>'Annual CMS penalties avoided'],['value'=>'92%','label'=>'Model prediction accuracy']],
        ],
        'cloud' => [
            'title'   => 'Zero-Downtime Multi-Cloud Migration for a National Logistics Provider',
            'story'   => 'A Fortune 500 logistics company headquartered in Atlanta needed to exit two aging data centers without disrupting 24/7 freight operations. We executed a phased migration to AWS and Azure over 14 weeks, eliminating $4.2M in annual data center costs and enabling same-day deployment of new routing software.',
            'results' => [['value'=>'$4.2M','label'=>'Annual data center cost eliminated'],['value'=>'99.99%','label'=>'Uptime maintained throughout migration'],['value'=>'14 wks','label'=>'Full migration timeline']],
        ],
        'cybersecurity' => [
            'title'   => 'Achieving CMMC Level 2 for a Defense Contractor in Virginia',
            'story'   => 'A Tier 2 DoD contractor in Northern Virginia faced a hard deadline to achieve CMMC Level 2 certification or lose a $40M contract. We deployed a zero-trust architecture, 24/7 SOC monitoring, and automated compliance evidence collection — achieving certification in 11 weeks and eliminating 3 audit findings that would have disqualified the contract.',
            'results' => [['value'=>'11 wks','label'=>'CMMC Level 2 achieved'],['value'=>'$40M','label'=>'Federal contract secured'],['value'=>'Zero','label'=>'Security incidents post-deployment']],
        ],
        'data-analytics' => [
            'title'   => 'Real-Time Analytics Drives $31M Revenue Uplift for Midwest Retailer',
            'story'   => 'A national specialty retailer based in Chicago consolidated 18 legacy data silos into a unified Snowflake lakehouse. Real-time pricing and inventory analytics enabled same-day markdown decisions that drove a 29% revenue uplift in the first year, while reducing overstock write-offs by $8.4M.',
            'results' => [['value'=>'29%','label'=>'First-year revenue increase'],['value'=>'$8.4M','label'=>'Overstock write-offs eliminated'],['value'=>'18','label'=>'Data silos unified']],
        ],
        'digital-transformation' => [
            'title'   => 'Modernizing Operations at a 100-Year-Old Texas Manufacturer',
            'story'   => 'A century-old industrial manufacturer in San Antonio was losing ground to leaner competitors. We deployed IoT sensing across 9 production lines, built an operations intelligence platform, and ran a 6-month change management program. The result: 43% OEE improvement, $4.8M in annual labor and waste savings, and a 28% reduction in unplanned downtime.',
            'results' => [['value'=>'43%','label'=>'OEE improvement'],['value'=>'$4.8M','label'=>'Annual savings achieved'],['value'=>'28%','label'=>'Less unplanned downtime']],
        ],
        'custom-software' => [
            'title'   => 'Replacing a $2M Legacy EHR Integration Backlog at a Florida Health Network',
            'story'   => 'A 12-hospital network in South Florida had accumulated a $2M backlog of custom EHR integrations that were breaking with every vendor update. We replaced the point-to-point spaghetti with a modern FHIR-compliant integration platform, reducing integration maintenance costs by 71% and enabling the network to onboard new facilities in days instead of months.',
            'results' => [['value'=>'71%','label'=>'Integration maintenance cost reduction'],['value'=>'Days','label'=>'Time to onboard new facilities (was months)'],['value'=>'$2M','label'=>'Backlog cleared in 16 weeks']],
        ],
        'iot-automation' => [
            'title'   => 'Smart Factory Deployment Cuts Waste by 38% at Ohio Auto Parts Maker',
            'story'   => 'An automotive parts manufacturer in Columbus, OH deployed our IoT platform across 14 CNC machines and 3 assembly lines. Real-time telemetry, predictive maintenance alerts, and automated quality inspection reduced scrap rates by 38%, eliminated 2,400 hours of unplanned downtime annually, and improved OEE from 61% to 79%.',
            'results' => [['value'=>'38%','label'=>'Scrap rate reduction'],['value'=>'2,400','label'=>'Unplanned downtime hours eliminated'],['value'=>'79%','label'=>'OEE (up from 61%)']],
        ],
        'managed-it-services' => [
            'title'   => 'Always-On IT for a 600-Person Professional Services Firm in New York',
            'story'   => 'A New York-based consulting firm was spending 22% of its IT budget on reactive break-fix work and averaging 4.2 hours of downtime per incident. Under our Managed IT program, mean time to resolution dropped to 18 minutes, unplanned downtime fell by 89%, and the firm redirected $1.1M in IT spend toward digital growth initiatives.',
            'results' => [['value'=>'18 min','label'=>'Mean time to resolution'],['value'=>'89%','label'=>'Reduction in unplanned downtime'],['value'=>'$1.1M','label'=>'IT spend redirected to growth']],
        ],
    ];
    return $stories[$slug] ?? [];
}

/**
 * Get the correct URL for a service by slug.
 *
 * Priority:
 *   1. Real CPT post with matching slug → use its permalink
 *   2. No CPT post → fall back to Solutions hub page
 *
 * Usage: ve_get_service_url('ai-machine-learning')
 */
function ve_get_service_url( string $slug ): string {
    static $cache = [];
    if ( isset( $cache[$slug] ) ) return $cache[$slug];

    $post = get_page_by_path( $slug, OBJECT, 'service' );
    if ( $post ) {
        $url = get_permalink( $post );
    } else {
        // Fallback: solutions hub
        $url = home_url('/solutions');
    }

    $cache[$slug] = $url;
    return $url;
}

/**
 * Build a lookup map of service slug → permalink for all published services.
 * Used to avoid N+1 queries when rendering multiple service links at once.
 *
 * @return array  ['slug' => 'https://...']
 */
function ve_service_url_map(): array {
    static $map = null;
    if ( $map !== null ) return $map;

    $map = [];
    $posts = get_posts( [
        'post_type'      => 'service',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ] );

    foreach ( $posts as $p ) {
        $map[ $p->post_name ] = get_permalink( $p );
    }

    return $map;
}
