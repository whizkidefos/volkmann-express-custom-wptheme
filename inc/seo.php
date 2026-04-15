<?php
/**
 * Volkmann Express — SEO Module
 * Handles meta description, Open Graph, Twitter Card, canonical, Schema.org.
 * If Yoast or RankMath is active these are skipped.
 */

defined('ABSPATH') || exit;

// Skip if a premium SEO plugin is active
if ( defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION') ) return;

/* ─── Title tag ────────────────────────────────────────────── */
function ve_document_title_parts( array $title ): array {
    $title['site'] = get_bloginfo('name');
    return $title;
}
add_filter('document_title_parts', 've_document_title_parts');
add_filter('document_title_separator', fn() => '|');

/* ─── Meta description ─────────────────────────────────────── */
function ve_meta_description(): string {
    global $post;

    if ( is_front_page() ) {
        return 'Volkmann Express — Enterprise technology partner delivering AI, cloud, cybersecurity, data analytics, digital transformation, and custom software at scale.';
    }
    if ( is_singular() && $post ) {
        $excerpt = get_the_excerpt($post);
        if ($excerpt) return wp_trim_words($excerpt, 30, '');
    }
    if ( is_category() ) {
        return 'Browse ' . single_cat_title('', false) . ' articles and insights from Volkmann Express.';
    }
    if ( is_search() ) {
        return 'Search results for "' . get_search_query() . '" on Volkmann Express.';
    }
    return get_bloginfo('description') ?: 'Enterprise technology solutions from Volkmann Express.';
}

/* ─── Canonical URL ────────────────────────────────────────── */
function ve_canonical_url(): string {
    global $post;
    if ( is_singular() && $post ) return get_permalink($post);
    if ( is_front_page() )        return home_url('/');
    if ( is_home() )              return get_permalink(get_option('page_for_posts'));
    return home_url(add_query_arg(null, null));
}

/* ─── OG image ─────────────────────────────────────────────── */
function ve_og_image(): string {
    global $post;
    if ( is_singular() && $post && has_post_thumbnail($post) ) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($post), 've-hero');
        if ($img) return $img[0];
    }
    return VE_URI . '/assets/images/og-default.png';
}

/* ─── Inject all head tags ─────────────────────────────────── */
function ve_seo_head(): void {
    $desc      = ve_meta_description();
    $canonical = ve_canonical_url();
    $og_image  = ve_og_image();
    $title     = wp_get_document_title();
    $site_name = get_bloginfo('name');
    $type      = is_singular() ? 'article' : 'website';

    echo "\n<!-- Volkmann Express SEO -->\n";
    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    // Robots
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    // Open Graph
    echo '<meta property="og:type"        content="' . esc_attr($type) . '">'      . "\n";
    echo '<meta property="og:title"       content="' . esc_attr($title) . '">'     . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '">'      . "\n";
    echo '<meta property="og:url"         content="' . esc_url($canonical) . '">'  . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:image"       content="' . esc_url($og_image) . '">'   . "\n";
    echo '<meta property="og:image:width" content="1200">'                          . "\n";
    echo '<meta property="og:image:height" content="630">'                          . "\n";
    // Twitter Card
    echo '<meta name="twitter:card"        content="summary_large_image">'          . "\n";
    echo '<meta name="twitter:title"       content="' . esc_attr($title) . '">'    . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">'     . "\n";
    echo '<meta name="twitter:image"       content="' . esc_url($og_image) . '">'  . "\n";

    ve_schema_json_ld();
    echo "\n";
}
add_action('wp_head', 've_seo_head', 1);

/* ─── Schema.org JSON-LD ───────────────────────────────────── */
function ve_schema_json_ld(): void {
    global $post;
    $schemas = [];

    // Organisation (always)
    $schemas[] = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => 'Volkmann Express',
        'url'         => home_url('/'),
        'logo'        => VE_URI . '/assets/images/logo.png',
        'description' => 'Enterprise technology partner delivering AI, cloud, cybersecurity, data analytics, digital transformation, and custom software.',
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => ve_opt('ve_address') ?: '123 Innovation Drive',
            'addressLocality' => 'Tech City',
        ],
        'contactPoint' => [
            '@type'       => 'ContactPoint',
            'telephone'   => ve_opt('ve_phone') ?: '',
            'email'       => ve_opt('ve_email') ?: '',
            'contactType' => 'customer service',
        ],
        'sameAs' => array_filter([
            ve_opt('ve_linkedin'),
            ve_opt('ve_twitter'),
        ]),
    ];

    // WebSite with SearchAction
    $schemas[] = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => 'Volkmann Express',
        'url'             => home_url('/'),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => home_url('/?s={search_term_string}')],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // BreadcrumbList for inner pages
    if ( is_singular() && $post && ! is_front_page() ) {
        $breadcrumbs = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url('/')],
        ];
        $pos = 2;

        if ( in_array($post->post_type, ['service','industry','case_study'], true) ) {
            $archive_map = [
                'service'    => ['Solutions',    home_url('/solutions')],
                'industry'   => ['Industries',   home_url('/industries')],
                'case_study' => ['Case Studies', home_url('/case-studies')],
            ];
            [$archive_label, $archive_url] = $archive_map[$post->post_type];
            $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $archive_label, 'item' => $archive_url];
        }

        $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title($post), 'item' => get_permalink($post)];

        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ];

        // Service schema for service CPT
        if ( $post->post_type === 'service' ) {
            $schemas[] = [
                '@context'    => 'https://schema.org',
                '@type'       => 'Service',
                'name'        => get_the_title($post),
                'description' => get_the_excerpt($post),
                'url'         => get_permalink($post),
                'provider'    => ['@type' => 'Organization', 'name' => 'Volkmann Express', 'url' => home_url('/')],
            ];
        }

        // Article schema for posts
        if ( $post->post_type === 'post' ) {
            $schemas[] = [
                '@context'         => 'https://schema.org',
                '@type'            => 'Article',
                'headline'         => get_the_title($post),
                'description'      => get_the_excerpt($post),
                'url'              => get_permalink($post),
                'datePublished'    => get_the_date('c', $post),
                'dateModified'     => get_the_modified_date('c', $post),
                'author'           => ['@type' => 'Organization', 'name' => 'Volkmann Express'],
                'publisher'        => ['@type' => 'Organization', 'name' => 'Volkmann Express', 'logo' => VE_URI . '/assets/images/logo.png'],
            ];
        }
    }

    // FAQ schema for industry pages (from their challenge blocks)
    if ( is_singular('industry') && $post ) {
        // No static FAQ injected here — editors can add via post content.
    }

    foreach ($schemas as $schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}

/* ─── XML sitemap ping on publish ─────────────────────────── */
function ve_ping_search_engines( int $post_id ): void {
    if ( get_post_status($post_id) !== 'publish' ) return;
    $sitemap = home_url('/sitemap.xml');
    @wp_remote_get("https://www.google.com/ping?sitemap=" . urlencode($sitemap), ['blocking' => false]);
}
add_action('publish_post',     've_ping_search_engines');
add_action('publish_service',  've_ping_search_engines');
add_action('publish_industry', 've_ping_search_engines');
