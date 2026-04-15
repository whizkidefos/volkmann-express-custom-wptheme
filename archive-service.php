<?php
/**
 * Volkmann Express — Services Archive
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'    => 'All Solutions',
    'title'    => 'Enterprise Technology <span class="ve-text-gradient">Solutions</span>',
    'subtitle' => 'Our eight core practice areas — each engineered to deliver measurable business outcomes.',
    'cta_label'=> 'Schedule Consultation',
    'cta_url'  => home_url('/contact'),
    'size'     => 'md',
] );
?>

<section class="ve-section ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-solutions-full-grid">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="ve-solution-card ve-reveal">
                <div class="ve-solution-card__header">
                    <div class="ve-solution-card__icon"><?= ve_service_icon(get_post_field('post_name')) ?></div>
                    <h2 class="ve-solution-card__title"><?php the_title(); ?></h2>
                </div>
                <p class="ve-solution-card__excerpt"><?php the_excerpt(); ?></p>
                <a href="<?php the_permalink(); ?>" class="ve-btn ve-btn--ghost mt-auto">
                    Explore <?php the_title(); ?>
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </article>
            <?php endwhile; else: ?>
            <?php foreach ( ve_default_services() as $s ) :
                $url = ve_get_service_url($s['slug']);
            ?>
            <article class="ve-solution-card ve-reveal">
                <div class="ve-solution-card__header">
                    <div class="ve-solution-card__icon"><?= ve_service_icon($s['icon']) ?></div>
                    <h2 class="ve-solution-card__title"><?= esc_html($s['title']) ?></h2>
                </div>
                <p class="ve-solution-card__excerpt"><?= esc_html($s['excerpt']) ?></p>
                <a href="<?= esc_url($url) ?>" class="ve-btn ve-btn--ghost mt-auto">
                    Explore <?= esc_html($s['title']) ?>
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
