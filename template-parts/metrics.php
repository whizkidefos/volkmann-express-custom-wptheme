<?php
/**
 * Template Part: Metrics Strip
 *
 * Usage:
 *   get_template_part('template-parts/metrics', null, [
 *       'metrics' => [
 *           ['value' => '200+', 'label' => 'Enterprise Clients', 'icon' => '🏢'],
 *           ['value' => '98%',  'label' => 'Retention Rate',     'icon' => '🤝'],
 *       ],
 *       'style' => 'bar|grid',   // default: bar
 *   ]);
 */

$metrics = $args['metrics'] ?? [
    ['value' => '200+', 'label' => 'Enterprise Clients',    'icon' => '🏢'],
    ['value' => '98%',  'label' => 'Client Retention Rate', 'icon' => '🤝'],
    ['value' => '12+',  'label' => 'Industries Served',     'icon' => '🌐'],
    ['value' => '$2B+', 'label' => 'Value Created',         'icon' => '💰'],
];
$style = $args['style'] ?? 'bar';
?>

<?php if ($style === 'grid') : ?>
<div class="ve-proof-metrics">
    <?php foreach ($metrics as $m) : ?>
    <div class="ve-proof-metric ve-reveal">
        <?php if (!empty($m['icon'])) : ?>
        <span class="ve-proof-metric__emoji"><?= $m['icon'] ?></span>
        <?php endif; ?>
        <span class="ve-proof-metric__value"><?= esc_html($m['value']) ?></span>
        <span class="ve-proof-metric__label"><?= esc_html($m['label']) ?></span>
    </div>
    <?php endforeach; ?>
</div>

<?php else : ?>
<!-- Bar style: full-width horizontal strip -->
<div class="ve-metrics-bar ve-reveal">
    <?php foreach ($metrics as $i => $m) : ?>
    <div class="ve-metrics-bar__item">
        <span class="ve-metrics-bar__value"><?= esc_html($m['value']) ?></span>
        <span class="ve-metrics-bar__label"><?= esc_html($m['label']) ?></span>
    </div>
    <?php if ($i < count($metrics) - 1) : ?>
    <div class="ve-metrics-bar__divider" aria-hidden="true"></div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
