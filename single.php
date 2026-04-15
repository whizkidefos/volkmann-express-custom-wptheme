<?php
/**
 * Volkmann Express — Single Post / Insight
 */
get_header();
?>
<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-single-layout">
            <article class="ve-single-article">
                <?php while ( have_posts() ) : the_post(); ?>

                <header class="ve-single-header ve-fade-up" data-delay="0">
                    <div class="ve-blog-card__meta mb-4">
                        <time datetime="<?php echo get_the_date('c'); ?>" class="ve-blog-card__date"><?php echo get_the_date(); ?></time>
                        <?php the_category(' &middot; '); ?>
                    </div>
                    <h1 class="ve-section-title"><?php the_title(); ?></h1>
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="ve-single-thumb">
                        <?php the_post_thumbnail('ve-hero', [ 'class' => 've-single-thumb__img', 'loading' => 'eager' ]); ?>
                    </div>
                    <?php endif; ?>
                </header>

                <div class="ve-prose ve-fade-up" data-delay="100">
                    <?php the_content(); ?>
                </div>

                <footer class="ve-single-footer">
                    <?php the_tags('<div class="ve-single-tags"><strong>Topics:</strong> ', ', ', '</div>'); ?>
                </footer>

                <?php endwhile; ?>
            </article>

            <aside class="ve-single-sidebar ve-reveal">
                <div class="ve-sidebar-card">
                    <h3 class="ve-sidebar-card__title">Ready to take action?</h3>
                    <p>Talk to our experts about your specific challenge.</p>
                    <a href="<?= esc_url(home_url('/contact')) ?>" class="ve-btn ve-btn--primary mt-4 w-full justify-center">
                        Get in Touch
                    </a>
                </div>

                <div class="ve-sidebar-card mt-4">
                    <h3 class="ve-sidebar-card__title">Explore Solutions</h3>
                    <ul class="ve-sidebar-links">
                        <?php foreach ( ve_default_services() as $s ) : ?>
                        <li>
                            <a href="<?= esc_url(home_url('/solutions/' . $s['slug'])) ?>" class="ve-footer__link">
                                <?= esc_html($s['title']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php
get_template_part( 'template-parts/cta' );
get_footer();
?>
