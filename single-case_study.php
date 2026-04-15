<?php
/**
 * Volkmann Express — Single Case Study
 */
get_header();

$client    = get_post_meta( get_the_ID(), 've_client',      true );
$industry  = get_post_meta( get_the_ID(), 've_industry',    true );
$challenge = get_post_meta( get_the_ID(), 've_challenge',   true );
$solution  = get_post_meta( get_the_ID(), 've_solution',    true );
$results   = get_post_meta( get_the_ID(), 've_results',     true ); // stored as JSON
$quote     = get_post_meta( get_the_ID(), 've_testimonial', true );
$author    = get_post_meta( get_the_ID(), 've_quote_author',true );

$results_arr = $results ? json_decode($results, true) : [];

get_template_part( 'template-parts/hero', null, [
    'badge'    => $industry ?: 'Case Study',
    'title'    => get_the_title(),
    'subtitle' => $client ? "Client: {$client}" : get_the_excerpt(),
    'size'     => 'sm',
] );
?>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">

        <!-- Results banner -->
        <?php if ( ! empty($results_arr) ) : ?>
        <div class="ve-proof-block__results mb-16 ve-reveal" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;">
            <?php foreach ( $results_arr as $r ) : ?>
            <div class="ve-result-stat">
                <span class="ve-result-stat__value"><?= esc_html($r['value'] ?? '') ?></span>
                <span class="ve-result-stat__label"><?= esc_html($r['label'] ?? '') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="ve-single-layout">
            <article class="ve-single-article">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="ve-single-thumb ve-reveal">
                    <?php the_post_thumbnail('ve-hero', ['class' => 've-single-thumb__img', 'loading' => 'eager']); ?>
                </div>
                <?php endif; ?>

                <?php if ( $challenge ) : ?>
                <div class="ve-cs-section ve-reveal">
                    <h2 class="ve-section-title">The <span class="ve-text-gradient">Challenge</span></h2>
                    <p class="ve-body-text"><?= wp_kses_post($challenge) ?></p>
                </div>
                <?php endif; ?>

                <?php if ( $solution ) : ?>
                <div class="ve-cs-section ve-reveal">
                    <h2 class="ve-section-title">Our <span class="ve-text-gradient">Approach</span></h2>
                    <p class="ve-body-text"><?= wp_kses_post($solution) ?></p>
                </div>
                <?php endif; ?>

                <div class="ve-prose ve-reveal">
                    <?php the_content(); ?>
                </div>

                <?php if ( $quote ) : ?>
                <blockquote class="ve-testimonial-block ve-reveal">
                    <p class="ve-testimonial-block__quote">"<?= esc_html($quote) ?>"</p>
                    <?php if ( $author ) : ?>
                    <cite class="ve-testimonial-block__author">— <?= esc_html($author) ?></cite>
                    <?php endif; ?>
                </blockquote>
                <?php endif; ?>
            </article>

            <aside class="ve-single-sidebar ve-reveal">
                <div class="ve-sidebar-card">
                    <h3 class="ve-sidebar-card__title">Achieve similar results</h3>
                    <p>Talk to our experts about your specific challenge.</p>
                    <a href="<?= esc_url(home_url('/contact')) ?>" class="ve-btn ve-btn--primary mt-4 w-full justify-center">
                        Get in Touch
                    </a>
                </div>
                <?php if ($industry || $client) : ?>
                <div class="ve-sidebar-card mt-4">
                    <h3 class="ve-sidebar-card__title">Project Details</h3>
                    <table style="width:100%;font-size:.875rem;border-collapse:collapse;">
                        <?php if ($client)   : ?><tr><td style="padding:.5rem 0;color:var(--ve-text-subtle);width:90px;">Client</td><td style="padding:.5rem 0;color:var(--ve-text);font-weight:500;"><?= esc_html($client) ?></td></tr><?php endif; ?>
                        <?php if ($industry) : ?><tr><td style="padding:.5rem 0;color:var(--ve-text-subtle);">Industry</td><td style="padding:.5rem 0;color:var(--ve-orange);font-weight:600;"><?= esc_html($industry) ?></td></tr><?php endif; ?>
                    </table>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta', null, [
    'btn_label'  => 'Start a Conversation',
    'btn_url'    => home_url('/contact'),
    'btn2_label' => 'View More Case Studies',
    'btn2_url'   => home_url('/case-studies'),
] );
get_footer();
