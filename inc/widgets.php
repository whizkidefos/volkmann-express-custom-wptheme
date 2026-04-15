<?php
/**
 * Volkmann Express — Widgets
 */

defined('ABSPATH') || exit;

/* ─── Register sidebars ────────────────────────────────────── */
function ve_register_sidebars() {
    register_sidebar([
        'name'          => 'Blog Sidebar',
        'id'            => 've-blog-sidebar',
        'before_widget' => '<div class="ve-sidebar-card mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="ve-sidebar-card__title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => 'Footer Widget Area',
        'id'            => 've-footer-widgets',
        'before_widget' => '<div class="ve-footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="ve-footer__heading">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 've_register_sidebars');

/* ─── CTA Widget ───────────────────────────────────────────── */
class VE_CTA_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct('ve_cta_widget', 'VE: CTA Box', ['description' => 'Displays a call-to-action box with heading, text, and button.']);
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        ?>
        <div class="ve-cta-widget">
            <?php if (!empty($instance['title'])) : ?>
            <h3 class="ve-cta-widget__title"><?= esc_html($instance['title']) ?></h3>
            <?php endif; ?>
            <?php if (!empty($instance['text'])) : ?>
            <p><?= esc_html($instance['text']) ?></p>
            <?php endif; ?>
            <?php if (!empty($instance['btn_label']) && !empty($instance['btn_url'])) : ?>
            <a href="<?= esc_url($instance['btn_url']) ?>" class="ve-btn ve-btn--primary mt-4 w-full justify-center">
                <?= esc_html($instance['btn_label']) ?>
            </a>
            <?php endif; ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $fields = ['title' => 'Title', 'text' => 'Body Text', 'btn_label' => 'Button Label', 'btn_url' => 'Button URL'];
        foreach ($fields as $key => $label) {
            $val = $instance[$key] ?? '';
            printf(
                '<p><label for="%s">%s</label><input class="widefat" id="%s" name="%s" type="text" value="%s"></p>',
                $this->get_field_id($key), esc_html($label),
                $this->get_field_id($key), $this->get_field_name($key),
                esc_attr($val)
            );
        }
    }

    public function update($new, $old) {
        return array_map('sanitize_text_field', $new);
    }
}

function ve_register_widgets() {
    register_widget('VE_CTA_Widget');
}
add_action('widgets_init', 've_register_widgets');
