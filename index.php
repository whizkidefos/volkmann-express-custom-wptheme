<?php
/**
 * Volkmann Express — Default Blog/Archive Template (index.php)
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'   => is_category() ? 'Category' : ( is_tag() ? 'Tag' : 'Insights' ),
    'title'   => is_category() ? single_cat_title('', false) : ( is_tag() ? single_tag_title('', false) : 'Insights &amp; <span class="ve-text-gradient">Perspectives</span>' ),
    'subtitle'=> 'Expert thinking on AI, cloud, cybersecurity, data analytics, and digital transformation.',
    'size'    => 'sm',
] );
?>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <?php if ( have_posts() ) : ?>
        <div class="ve-blog-grid">
            <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class('ve-blog-card ve-reveal'); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="ve-blog-card__thumb">
                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail('ve-card', [ 'class' => 've-blog-card__img', 'loading' => 'lazy' ]); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="ve-blog-card__body px-16">
                    <div class="ve-blog-card__meta">
                        <time datetime="<?php echo get_the_date('c'); ?>" class="ve-blog-card__date"><?php echo get_the_date(); ?></time>
                        <?php the_category(' &middot; '); ?>
                    </div>
                    <h2 class="ve-blog-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="ve-blog-card__excerpt"><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="ve-service-card__link">
                        Read Article
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                    </a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <div class="ve-pagination">
            <?php the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ]); ?>
        </div>

        <?php else : ?>
        <p class="text-center ve-muted py-16">No posts found.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
