<?php
/**
 * Volkmann Express — functions.php
 */

defined( 'ABSPATH' ) || exit;

define( 'VE_VERSION', '1.0.0' );
define( 'VE_DIR',     get_template_directory() );
define( 'VE_URI',     get_template_directory_uri() );

// ─── Theme setup ────────────────────────────────────────────────────────────

function ve_setup() {
    load_theme_textdomain( 'volkmann-express', VE_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'custom-logo', [ 'height' => 60, 'width' => 200, 'flex-height' => true, 'flex-width' => true ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'volkmann-express' ),
        'footer'  => __( 'Footer Navigation',  'volkmann-express' ),
    ] );
}
add_action( 'after_setup_theme', 've_setup' );

// ─── Enqueue assets ─────────────────────────────────────────────────────────

function ve_enqueue_assets() {
    // Inter font
    wp_enqueue_style( 've-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap', [], null );
    // Main stylesheet
    wp_enqueue_style( 've-main', VE_URI . '/assets/css/main.css', [ 've-inter' ], VE_VERSION );
    // Tailwind CDN
    wp_enqueue_script( 've-tailwind', 'https://cdn.tailwindcss.com', [], null, false );
    // GSAP + ScrollTrigger
    wp_enqueue_script( 've-gsap',     'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',            [], '3.12.5', true );
    wp_enqueue_script( 've-gsap-st',  'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',   [], '3.12.5', true );
    wp_enqueue_script( 've-gsap-txt', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js',      [], '3.12.5', true );
    // THREE.js
    wp_enqueue_script( 've-three',    'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',         [], 'r128',   true );
    // Chart.js
    wp_enqueue_script( 've-chartjs',  'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js',   [], '4.4.1',  true );
    // Main JS
    wp_enqueue_script( 've-main',     VE_URI . '/assets/js/main.js', [ 've-gsap', 've-gsap-st', 've-three', 've-chartjs' ], VE_VERSION, true );

    wp_localize_script( 've-main', 'VE', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 've_contact_nonce' ),
        'themeUri'=> VE_URI,
    ] );
}
add_action( 'wp_enqueue_scripts', 've_enqueue_assets' );

function ve_enqueue_admin_assets() {
    wp_enqueue_style( 've-admin', VE_URI . '/assets/css/admin.css', [], VE_VERSION );
    wp_enqueue_script( 've-admin', VE_URI . '/assets/js/admin.js', [ 'jquery' ], VE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 've_enqueue_admin_assets' );

// Tailwind config inline
function ve_tailwind_config() { ?>
<script>
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: { sans: ['Inter','sans-serif'] },
            colors: {
                ve: {
                    orange:  '#F97316',
                    blue:    '#2563EB',
                    dark:    '#0A0A0F',
                    darker:  '#050508',
                    surface: '#111118',
                    card:    '#16161F',
                    light:   '#F8FAFF',
                    muted:   '#94A3B8',
                    border:  '#1E1E2E',
                }
            }
        }
    }
};
</script>
<?php }
add_action( 'wp_head', 've_tailwind_config', 5 );

// ─── Dark mode class on <html> ───────────────────────────────────────────────

function ve_html_class( $output ) {
    return str_replace( '<html ', '<html id="ve-html" ', $output );
}
// Applied via JS toggle instead — see main.js

// ─── Custom Post Types ───────────────────────────────────────────────────────

function ve_register_cpts() {
    $cpts = [
        'service' => [
            'singular' => 'Service',
            'plural'   => 'Services',
            'slug'     => 'solutions',
            'icon'     => 'dashicons-laptop',
            'supports' => [ 'title','editor','thumbnail','excerpt','custom-fields' ],
        ],
        'industry' => [
            'singular' => 'Industry',
            'plural'   => 'Industries',
            'slug'     => 'industries',
            'icon'     => 'dashicons-building',
            'supports' => [ 'title','editor','thumbnail','excerpt','custom-fields' ],
        ],
        'case_study' => [
            'singular' => 'Case Study',
            'plural'   => 'Case Studies',
            'slug'     => 'case-studies',
            'icon'     => 'dashicons-analytics',
            'supports' => [ 'title','editor','thumbnail','excerpt','custom-fields' ],
        ],
        'testimonial' => [
            'singular' => 'Testimonial',
            'plural'   => 'Testimonials',
            'slug'     => 'testimonials',
            'icon'     => 'dashicons-format-quote',
            'supports' => [ 'title','editor','custom-fields' ],
        ],
        'team_member' => [
            'singular' => 'Team Member',
            'plural'   => 'Team Members',
            'slug'     => 'team',
            'icon'     => 'dashicons-admin-users',
            'supports' => [ 'title','editor','thumbnail','custom-fields' ],
        ],
    ];

    foreach ( $cpts as $type => $args ) {
        register_post_type( $type, [
            'labels' => [
                'name'          => $args['plural'],
                'singular_name' => $args['singular'],
                'add_new_item'  => 'Add New ' . $args['singular'],
                'edit_item'     => 'Edit ' . $args['singular'],
            ],
            'public'            => true,
            'has_archive'       => true,
            'rewrite'           => [ 'slug' => $args['slug'] ],
            'menu_icon'         => $args['icon'],
            'supports'          => $args['supports'],
            'show_in_rest'      => true,
        ] );
    }
}
add_action( 'init', 've_register_cpts' );

// ─── Taxonomies ──────────────────────────────────────────────────────────────

function ve_register_taxonomies() {
    register_taxonomy( 'service_category', 'service', [
        'labels'       => [ 'name' => 'Service Categories', 'singular_name' => 'Service Category' ],
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'service-category' ],
        'show_in_rest' => true,
    ] );
    register_taxonomy( 'industry_category', 'industry', [
        'labels'       => [ 'name' => 'Industry Categories', 'singular_name' => 'Industry Category' ],
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'industry-category' ],
        'show_in_rest' => true,
    ] );
    register_taxonomy( 'tech_tag', [ 'service','case_study' ], [
        'labels'       => [ 'name' => 'Technology Tags', 'singular_name' => 'Technology Tag' ],
        'hierarchical' => false,
        'rewrite'      => [ 'slug' => 'tech' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 've_register_taxonomies' );

// ─── Contact Form AJAX ───────────────────────────────────────────────────────

function ve_handle_contact() {
    check_ajax_referer( 've_contact_nonce', 'nonce' );

    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $company = sanitize_text_field( $_POST['company'] ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
    }

    // Save lead as custom DB entry (wp_options list)
    $leads   = get_option( 've_leads', [] );
    $leads[] = [
        'id'      => uniqid(),
        'name'    => $name,
        'email'   => $email,
        'company' => $company,
        'subject' => $subject,
        'message' => $message,
        'date'    => current_time( 'mysql' ),
        'status'  => 'new',
    ];
    update_option( 've_leads', $leads );

    // Send notification email
    $to      = get_option( 'admin_email' );
    $headers = [ 'Content-Type: text/html; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];
    $body    = ve_contact_email_template( $name, $email, $company, $subject, $message );
    wp_mail( $to, "New Lead: {$subject}", $body, $headers );

    wp_send_json_success( [ 'message' => 'Thank you! We\'ll be in touch shortly.' ] );
}
add_action( 'wp_ajax_ve_contact',        've_handle_contact' );
add_action( 'wp_ajax_nopriv_ve_contact', 've_handle_contact' );

function ve_contact_email_template( $name, $email, $company, $subject, $message ) {
    ob_start(); ?>
    <div style="font-family:Inter,sans-serif;max-width:600px;margin:0 auto;background:#0A0A0F;color:#fff;padding:32px;border-radius:12px;">
        <h2 style="color:#F97316;margin-bottom:24px;">New Lead — Volkmann Express</h2>
        <table style="width:100%;border-collapse:collapse;">
            <?php foreach ( compact( 'name','email','company','subject' ) as $k => $v ) : ?>
            <tr><td style="padding:8px 0;color:#94A3B8;width:120px;"><?= ucfirst($k) ?></td><td style="padding:8px 0;"><?= esc_html($v) ?></td></tr>
            <?php endforeach; ?>
            <tr><td style="padding:8px 0;color:#94A3B8;vertical-align:top;">Message</td><td style="padding:8px 0;"><?= nl2br( esc_html($message) ) ?></td></tr>
        </table>
    </div>
    <?php return ob_get_clean();
}

// ─── Admin leads page ────────────────────────────────────────────────────────

function ve_admin_menu() {
    add_menu_page( 'VE Leads', 'VE Leads', 'manage_options', 've-leads', 've_leads_page', 'dashicons-email-alt', 30 );
}
add_action( 'admin_menu', 've_admin_menu' );

function ve_leads_page() {
    $leads = array_reverse( get_option( 've_leads', [] ) );
    echo '<div class="wrap"><h1>Volkmann Express — Leads</h1>';
    if ( empty( $leads ) ) { echo '<p>No leads yet.</p></div>'; return; }
    echo '<table class="widefat striped"><thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Company</th><th>Subject</th><th>Message</th></tr></thead><tbody>';
    foreach ( $leads as $l ) {
        printf(
            '<tr><td>%s</td><td>%s</td><td><a href="mailto:%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>',
            esc_html( $l['date'] ?? '' ),
            esc_html( $l['name'] ?? '' ),
            esc_attr( $l['email'] ?? '' ),
            esc_html( $l['email'] ?? '' ),
            esc_html( $l['company'] ?? '' ),
            esc_html( $l['subject'] ?? '' ),
            esc_html( substr( $l['message'] ?? '', 0, 80 ) ) . '…'
        );
    }
    echo '</tbody></table></div>';
}

// ─── Theme Options (Customizer) ───────────────────────────────────────────────

function ve_customizer( $wp_customize ) {
    $wp_customize->add_section( 've_options', [
        'title'    => 'Volkmann Express Settings',
        'priority' => 30,
    ] );

    $fields = [
        've_phone'   => [ 'label' => 'Primary Phone',   'default' => '+1 (555) 000-0000' ],
        've_email'   => [ 'label' => 'Primary Email',   'default' => 'info@volkmannexpress.com' ],
        've_address' => [ 'label' => 'Office Address',  'default' => '123 Innovation Drive, Tech City, TX 75001' ],
        've_hours'   => [ 'label' => 'Business Hours',  'default' => 'Monday – Friday, 9 AM – 6 PM EST' ],
        've_linkedin'=> [ 'label' => 'LinkedIn URL',    'default' => '' ],
        've_twitter' => [ 'label' => 'Twitter URL',     'default' => '' ],
    ];

    foreach ( $fields as $id => $args ) {
        $wp_customize->add_setting( $id, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id, [ 'label' => $args['label'], 'section' => 've_options', 'type' => 'text' ] );
    }
}
add_action( 'customize_register', 've_customizer' );

function ve_opt( $key ) {
    return get_theme_mod( $key ) ?: '';
}

// ─── Flush rewrite on activation ─────────────────────────────────────────────

function ve_flush_rewrite() { flush_rewrite_rules(); }
add_action( 'after_switch_theme', 've_flush_rewrite' );

// ─── Image sizes ─────────────────────────────────────────────────────────────

add_image_size( 've-hero',    1920, 900,  true );
add_image_size( 've-card',    800,  500,  true );
add_image_size( 've-thumb',   400,  300,  true );
add_image_size( 've-square',  400,  400,  true );

// ─── Excerpt length ──────────────────────────────────────────────────────────

add_filter( 'excerpt_length', fn() => 25 );
add_filter( 'excerpt_more',   fn() => '…' );

// ─── Load partials ───────────────────────────────────────────────────────────

require_once VE_DIR . '/inc/nav-walker.php';
require_once VE_DIR . '/inc/helpers.php';
require_once VE_DIR . '/inc/meta-boxes.php';
require_once VE_DIR . '/inc/seo.php';
require_once VE_DIR . '/inc/shortcodes.php';
require_once VE_DIR . '/inc/widgets.php';
require_once VE_DIR . '/inc/chatbot.php';

// ─── Register Insights as a custom archive ──────────────────
function ve_register_insights() {
    // Use default Posts for insights but override the archive slug
    add_rewrite_rule( '^insights/?$', 'index.php?post_type=post', 'top' );
    add_rewrite_rule( '^insights/page/([0-9]+)/?$', 'index.php?post_type=post&paged=$matches[1]', 'top' );
}
add_action( 'init', 've_register_insights' );

// Map /insights to the archive-insights.php template
function ve_insights_template( string $template ): string {
    if ( is_home() || is_front_page() ) return $template;
    if ( is_archive() && get_post_type() === 'post' ) {
        $t = locate_template( 'archive-insights.php' );
        if ( $t ) return $t;
    }
    // Also handle /insights page slug
    if ( is_page('insights') ) {
        $t = locate_template( 'archive-insights.php' );
        if ( $t ) return $t;
    }
    return $template;
}
add_filter( 'template_include', 've_insights_template' );

// ─── Admin notice: guide user to create service posts ────────

function ve_admin_setup_notice() {
    // Only show on dashboard and if no service posts exist
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, ['dashboard','edit-service','service','options-permalink'] ) ) return;

    $has_services = get_posts(['post_type'=>'service','posts_per_page'=>1,'post_status'=>'publish']);
    if ( ! empty($has_services) ) return; // all good

    $create_url    = admin_url('post-new.php?post_type=service');
    $permalink_url = admin_url('options-permalink.php');
    ?>
    <div class="notice notice-warning is-dismissible" style="border-left-color:#F97316;">
        <p>
            <strong>Volkmann Express Theme:</strong>
            Your service pages are not live yet.
            <strong>Step 1:</strong> <a href="<?= esc_url($permalink_url) ?>">Save your Permalink settings</a> to register the <code>/solutions/</code> URL pattern.
            <strong>Step 2:</strong> <a href="<?= esc_url($create_url) ?>">Create your first Service post</a> — use the exact slugs listed in the theme README.
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 've_admin_setup_notice' );

// ─── Auto-flush on theme switch & CPT registration ───────────

function ve_maybe_flush() {
    if ( get_option('ve_needs_flush') ) {
        flush_rewrite_rules();
        delete_option('ve_needs_flush');
    }
}
add_action( 'init', 've_maybe_flush', 999 );

function ve_schedule_flush() {
    update_option('ve_needs_flush', 1);
}
add_action( 'after_switch_theme',    've_schedule_flush' );
add_action( 'activated_plugin',      've_schedule_flush' );
// Also flush when a new service post is published for the first time
function ve_flush_on_service_publish( $new_status, $old_status, $post ) {
    if ( $post->post_type === 'service' && $new_status === 'publish' && $old_status !== 'publish' ) {
        flush_rewrite_rules();
    }
}
add_action( 'transition_post_status', 've_flush_on_service_publish', 10, 3 );

// ═══════════════════════════════════════════════════════════════════════════
// PHASE 5 — QA / LAUNCH OPTIMIZATIONS
// ═══════════════════════════════════════════════════════════════════════════

// ─── Performance: Resource hints (preconnect / dns-prefetch) ─────────────────

function ve_resource_hints( $urls, $relation_type ) {
    $hints = [
        'preconnect' => [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.tailwindcss.com',
        ],
        'dns-prefetch' => [
            '//www.google-analytics.com',
            '//www.googletagmanager.com',
        ],
    ];
    if ( isset( $hints[ $relation_type ] ) ) {
        foreach ( $hints[ $relation_type ] as $url ) {
            $urls[] = [ 'href' => $url, 'crossorigin' => 'anonymous' ];
        }
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 've_resource_hints', 10, 2 );

// ─── Performance: Add fetchpriority to hero images ───────────────────────────

function ve_add_fetchpriority( $attr, $attachment, $size ) {
    if ( is_singular() && has_post_thumbnail() && $size === 've-hero' ) {
        $attr['fetchpriority'] = 'high';
        $attr['decoding']      = 'async';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 've_add_fetchpriority', 10, 3 );

// ─── Performance: Defer non-critical JS ──────────────────────────────────────

function ve_defer_scripts( $tag, $handle ) {
    // Note: ve-three removed from defer list — required for 3D canvas on service pages
    $defer_handles = [ 've-chartjs' ];
    if ( in_array( $handle, $defer_handles, true ) && strpos( $tag, 'defer' ) === false ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 've_defer_scripts', 10, 2 );

// ─── Performance: Remove query strings from static resources ─────────────────

function ve_remove_query_strings( $src ) {
    if ( strpos( $src, '?ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src',  've_remove_query_strings', 15 );
add_filter( 'script_loader_src', 've_remove_query_strings', 15 );

// ─── Performance: Disable emojis ─────────────────────────────────────────────

function ve_disable_emojis() {
    remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles',     'print_emoji_styles' );
    remove_action( 'admin_print_styles',  'print_emoji_styles' );
    remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
    remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
}
add_action( 'init', 've_disable_emojis' );

// ─── Performance: Remove unnecessary head clutter ────────────────────────────

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// ─── SEO: Add theme-color meta ───────────────────────────────────────────────

function ve_theme_color_meta() {
    echo '<meta name="theme-color" content="#0A0A0F" media="(prefers-color-scheme: dark)">' . "\n";
    echo '<meta name="theme-color" content="#F8FAFF" media="(prefers-color-scheme: light)">' . "\n";
}
add_action( 'wp_head', 've_theme_color_meta', 2 );

// ═══════════════════════════════════════════════════════════════════════════
// CUSTOM LOGIN / AUTH SCREEN BRANDING
// ═══════════════════════════════════════════════════════════════════════════

function ve_login_enqueue_styles() {
    wp_enqueue_style( 've-login', VE_URI . '/assets/css/login.css', [], VE_VERSION );
}
add_action( 'login_enqueue_scripts', 've_login_enqueue_styles' );

function ve_login_logo_url() {
    return home_url( '/' );
}
add_filter( 'login_headerurl', 've_login_logo_url' );

function ve_login_logo_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 've_login_logo_title' );

function ve_admin_footer_branding() {
    echo '<span id="footer-thankyou">Volkmann Express Theme by <a href="https://yohanorg.com" target="_blank">Yohanorg Limited</a></span>';
}
add_filter( 'admin_footer_text', 've_admin_footer_branding' );
