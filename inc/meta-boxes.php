<?php
/**
 * Volkmann Express — Meta Boxes
 * Registers native WP meta boxes for all CPTs.
 * If ACF is active, these are skipped (ACF takes priority).
 */

defined('ABSPATH') || exit;

// Skip if ACF is active — editors should use ACF field groups instead.
if ( function_exists('acf_add_local_field_group') ) return;

/* ─── Helper: render a text field ─────────────────────────── */
function ve_meta_field( string $post_type, string $key, string $label, string $type = 'text', string $desc = '', array $options = [] ): void {
    static $registered = [];
    $unique = "{$post_type}_{$key}";
    if ( in_array($unique, $registered, true) ) return;
    $registered[] = $unique;
}

/* ─── Service meta box ─────────────────────────────────────── */
function ve_register_service_meta_box() {
    add_meta_box(
        've-service-details',
        'Service Details',
        've_service_meta_box_html',
        'service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 've_register_service_meta_box');

function ve_service_meta_box_html( WP_Post $post ): void {
    wp_nonce_field('ve_service_meta', 've_service_nonce');
    $fields = [
        've_hero_headline'    => ['label' => 'Hero Headline',          'type' => 'text',     'desc' => 'Overrides the page title in the hero section.'],
        've_hero_sub'         => ['label' => 'Hero Subheadline',       'type' => 'textarea', 'desc' => ''],
        've_cta_label'        => ['label' => 'CTA Button Label',       'type' => 'text',     'desc' => 'e.g. "Schedule AI Consultation"'],
        've_cta_url'          => ['label' => 'CTA Button URL',         'type' => 'url',      'desc' => 'Defaults to /contact if left blank.'],
        've_result_1_value'   => ['label' => 'Result 1 Value',         'type' => 'text',     'desc' => 'e.g. "32%"'],
        've_result_1_label'   => ['label' => 'Result 1 Label',         'type' => 'text',     'desc' => 'e.g. "Reduction in readmissions"'],
        've_result_2_value'   => ['label' => 'Result 2 Value',         'type' => 'text',     'desc' => ''],
        've_result_2_label'   => ['label' => 'Result 2 Label',         'type' => 'text',     'desc' => ''],
        've_result_3_value'   => ['label' => 'Result 3 Value',         'type' => 'text',     'desc' => ''],
        've_result_3_label'   => ['label' => 'Result 3 Label',         'type' => 'text',     'desc' => ''],
        've_proof_title'      => ['label' => 'Case Study / Proof Title', 'type' => 'text',   'desc' => ''],
        've_proof_story'      => ['label' => 'Case Study / Proof Story', 'type' => 'textarea','desc' => ''],
    ];
    ve_render_meta_fields($post, $fields);
}

/* ─── Industry meta box ────────────────────────────────────── */
function ve_register_industry_meta_box() {
    add_meta_box('ve-industry-details', 'Industry Details', 've_industry_meta_box_html', 'industry', 'normal', 'high');
}
add_action('add_meta_boxes', 've_register_industry_meta_box');

function ve_industry_meta_box_html( WP_Post $post ): void {
    wp_nonce_field('ve_industry_meta', 've_industry_nonce');
    $fields = [
        've_solutions'   => ['label' => 'Solutions (comma-separated)',  'type' => 'text',     'desc' => 'e.g. "AI/ML, Cloud Migration, Cybersecurity"'],
        've_stat_1_value'=> ['label' => 'Stat 1 Value',                'type' => 'text',     'desc' => 'e.g. "45%"'],
        've_stat_1_label'=> ['label' => 'Stat 1 Label',                'type' => 'text',     'desc' => 'e.g. "Faster patient throughput"'],
        've_stat_2_value'=> ['label' => 'Stat 2 Value',                'type' => 'text',     'desc' => ''],
        've_stat_2_label'=> ['label' => 'Stat 2 Label',                'type' => 'text',     'desc' => ''],
        've_stat_3_value'=> ['label' => 'Stat 3 Value',                'type' => 'text',     'desc' => ''],
        've_stat_3_label'=> ['label' => 'Stat 3 Label',                'type' => 'text',     'desc' => ''],
    ];
    ve_render_meta_fields($post, $fields);
}

/* ─── Case Study meta box ──────────────────────────────────── */
function ve_register_case_study_meta_box() {
    add_meta_box('ve-cs-details', 'Case Study Details', 've_cs_meta_box_html', 'case_study', 'normal', 'high');
}
add_action('add_meta_boxes', 've_register_case_study_meta_box');

function ve_cs_meta_box_html( WP_Post $post ): void {
    wp_nonce_field('ve_cs_meta', 've_cs_nonce');
    $fields = [
        've_client'       => ['label' => 'Client Name (or anonymised)',   'type' => 'text',     'desc' => 'e.g. "Major NHS Trust" or "Global Logistics Provider"'],
        've_industry'     => ['label' => 'Industry',                      'type' => 'text',     'desc' => 'e.g. "Healthcare"'],
        've_challenge'    => ['label' => 'The Challenge',                 'type' => 'textarea', 'desc' => 'Describe the problem the client faced.'],
        've_solution'     => ['label' => 'Our Solution / Approach',       'type' => 'textarea', 'desc' => 'Describe what Volkmann Express delivered.'],
        've_result'       => ['label' => 'One-line Result (archive teaser)','type' => 'text',   'desc' => 'e.g. "32% fewer readmissions · £1.9M saved"'],
        've_results'      => ['label' => 'Results (JSON)',                 'type' => 'textarea', 'desc' => 'JSON array: [{"value":"32%","label":"Reduction in readmissions"},…]'],
        've_testimonial'  => ['label' => 'Testimonial Quote',             'type' => 'textarea', 'desc' => 'Client quote (without quotation marks).'],
        've_quote_author' => ['label' => 'Quote Author & Title',          'type' => 'text',     'desc' => 'e.g. "Dr. Sarah Jones, CIO, NHS Trust"'],
    ];
    ve_render_meta_fields($post, $fields);
}

/* ─── Team Member meta box ─────────────────────────────────── */
function ve_register_team_meta_box() {
    add_meta_box('ve-team-details', 'Team Member Details', 've_team_meta_box_html', 'team_member', 'side', 'high');
}
add_action('add_meta_boxes', 've_register_team_meta_box');

function ve_team_meta_box_html( WP_Post $post ): void {
    wp_nonce_field('ve_team_meta', 've_team_nonce');
    $fields = [
        've_role'     => ['label' => 'Job Title / Role',  'type' => 'text', 'desc' => 'e.g. "Chief AI Officer"'],
        've_linkedin' => ['label' => 'LinkedIn URL',      'type' => 'url',  'desc' => ''],
        've_email'    => ['label' => 'Email (optional)',  'type' => 'email','desc' => ''],
    ];
    ve_render_meta_fields($post, $fields);
}

/* ─── Shared render helper ─────────────────────────────────── */
function ve_render_meta_fields( WP_Post $post, array $fields ): void {
    echo '<table class="form-table" style="margin-top:0;">';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        $type  = $field['type'] ?? 'text';
        $label = esc_html($field['label']);
        $desc  = $field['desc'] ? '<p class="description">' . esc_html($field['desc']) . '</p>' : '';
        $id    = esc_attr($key);
        $name  = esc_attr($key);
        $val   = esc_attr($value);
        $tval  = esc_textarea($value);

        echo "<tr><th scope='row'><label for='{$id}'>{$label}</label></th><td>";
        if ($type === 'textarea') {
            echo "<textarea id='{$id}' name='{$name}' class='large-text' rows='4'>{$tval}</textarea>{$desc}";
        } else {
            echo "<input type='{$type}' id='{$id}' name='{$name}' value='{$val}' class='regular-text'>{$desc}";
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

/* ─── Save handlers ────────────────────────────────────────── */
function ve_save_post_meta( int $post_id, WP_Post $post ): void {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    $nonce_map = [
        'service'     => ['nonce' => 've_service_nonce',  'action' => 've_service_meta'],
        'industry'    => ['nonce' => 've_industry_nonce', 'action' => 've_industry_meta'],
        'case_study'  => ['nonce' => 've_cs_nonce',       'action' => 've_cs_meta'],
        'team_member' => ['nonce' => 've_team_nonce',     'action' => 've_team_meta'],
    ];

    $type = $post->post_type;
    if ( ! isset($nonce_map[$type]) ) return;

    $nonce_key    = $nonce_map[$type]['nonce'];
    $nonce_action = $nonce_map[$type]['action'];

    if ( ! isset($_POST[$nonce_key]) || ! wp_verify_nonce($_POST[$nonce_key], $nonce_action) ) return;

    $ve_keys = array_filter(array_keys($_POST), fn($k) => str_starts_with($k, 've_') && $k !== $nonce_key);

    foreach ($ve_keys as $key) {
        $value = $_POST[$key];
        if (is_array($value)) {
            update_post_meta($post_id, $key, array_map('sanitize_text_field', $value));
        } else {
            update_post_meta($post_id, $key, sanitize_textarea_field($value));
        }
    }
}
add_action('save_post', 've_save_post_meta', 10, 2);
