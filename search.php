<?php
/**
 * Volkmann Express — Search Results
 */
get_header();

$query   = get_search_query();
$count   = $GLOBALS['wp_query']->found_posts;
?>

<section class="ve-section" style="padding-top: 2rem;">
    <div class="container mx-auto px-4 lg:px-8">

        <!-- Search header -->
        <div class="ve-search-header ve-fade-up" data-delay="0">
            <div class="ve-badge">Search Results</div>
            <h1 class="ve-hero__title">
                <?php if ( $query ) : ?>
                    Results for <span class="ve-text-gradient">"<?= esc_html($query) ?>"</span>
                <?php else : ?>
                    Search <span class="ve-text-gradient">Volkmann Express</span>
                <?php endif; ?>
            </h1>
            <?php if ( $count ) : ?>
            <p class="ve-hero__subtitle"><?= $count ?> result<?= $count !== 1 ? 's' : '' ?> found.</p>
            <?php endif; ?>

            <!-- Search form -->
            <form role="search" method="get" action="<?= esc_url(home_url('/')) ?>" class="ve-search-form ve-fade-up" data-delay="100">
                <input
                    type="search"
                    name="s"
                    value="<?= esc_attr($query) ?>"
                    placeholder="Search services, insights, industries…"
                    class="ve-form-input ve-search-form__input"
                    aria-label="Search"
                >
                <button type="submit" class="ve-btn ve-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Search
                </button>
            </form>
        </div>

        <?php if ( have_posts() ) : ?>

        <div class="ve-search-results">
            <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class('ve-search-result ve-reveal'); ?>>
                <div class="ve-search-result__type">
                    <?php echo esc_html( ucwords( str_replace('_', ' ', get_post_type()) ) ); ?>
                </div>
                <h2 class="ve-search-result__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <p class="ve-search-result__excerpt"><?php the_excerpt(); ?></p>
                <a href="<?php the_permalink(); ?>" class="ve-service-card__link">
                    View
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </a>
            </article>
            <?php endwhile; ?>
        </div>

        <div class="ve-pagination">
            <?php the_posts_pagination(['prev_text' => '← Previous', 'next_text' => 'Next →']); ?>
        </div>

        <?php else : ?>

        <div class="ve-search-empty ve-reveal">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5" class="w-16 h-16 mx-auto text-ve-muted mb-6" style="color:var(--ve-text-subtle)"><circle cx="21" cy="21" r="15"/><line x1="36" y1="36" x2="47" y2="47"/></svg>
            <h2 class="ve-section-title">No results found</h2>
            <p class="ve-section-sub mx-auto">Try different keywords, or browse our solutions and insights below.</p>
            <div class="flex gap-4 justify-center mt-8 flex-wrap">
                <a href="<?= esc_url(home_url('/solutions')) ?>" class="ve-btn ve-btn--primary">View All Solutions</a>
                <a href="<?= esc_url(home_url('/contact')) ?>"  class="ve-btn ve-btn--ghost">Contact Us</a>
            </div>

            <div class="ve-search-suggestions mt-16">
                <h3 class="ve-search-suggestions__heading">Popular topics</h3>
                <div class="ve-industry-strip" style="justify-content:center;">
                    <?php foreach ( ve_default_services() as $s ) : ?>
                    <a href="<?= esc_url(home_url('/solutions/' . $s['slug'])) ?>" class="ve-industry-chip">
                        <span class="ve-industry-chip__icon"><?= ve_service_icon($s['icon']) ?></span>
                        <span><?= esc_html($s['title']) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
