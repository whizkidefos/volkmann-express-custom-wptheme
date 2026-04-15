<?php
/**
 * Volkmann Express — Insights Archive
 * Template Name: Insights
 */
get_header();

get_template_part('template-parts/hero', null, [
    'badge'    => 'Insights',
    'title'    => 'Technology Intelligence for <span class="ve-text-gradient">Enterprise Leaders</span>',
    'subtitle' => 'Expert analysis, practical guidance, and forward-looking perspectives on AI, cloud, cybersecurity, data analytics, and digital transformation from the Volkmann Express team.',
    'size'     => 'sm',
]);
?>

<!-- Featured article -->
<?php
$featured = get_posts(['posts_per_page' => 1, 'post_status' => 'publish', 'meta_key' => 've_featured', 'meta_value' => '1']);
$featured = $featured ?: get_posts(['posts_per_page' => 1, 'post_status' => 'publish']);
if (!empty($featured)) :
    $f = $featured[0];
?>
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-badge" style="margin-bottom:1.5rem;">Featured Article</div>
        <a href="<?= get_permalink($f) ?>" class="ve-featured-article">
            <?php if (has_post_thumbnail($f)) : ?>
            <div class="ve-featured-article__thumb">
                <?php echo get_the_post_thumbnail($f, 've-hero', ['class'=>'ve-featured-article__img','loading'=>'eager']); ?>
            </div>
            <?php endif; ?>
            <div class="ve-featured-article__body">
                <div class="ve-blog-card__meta mb-3">
                    <time datetime="<?= get_the_date('c',$f) ?>"><?= get_the_date('',$f) ?></time>
                    <?php $cats = get_the_category($f->ID); if (!empty($cats)) : ?> &middot; <span style="color:var(--ve-orange);font-weight:600;"><?= esc_html($cats[0]->name) ?></span><?php endif; ?>
                </div>
                <h2 class="ve-featured-article__title"><?= esc_html($f->post_title) ?></h2>
                <p class="ve-featured-article__excerpt"><?= wp_trim_words($f->post_excerpt ?: $f->post_content, 35) ?></p>
                <span class="ve-service-card__link">
                    Read Article
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                </span>
            </div>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Article grid -->
<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">

        <!-- Filter bar -->
        <div class="ve-insights-filters ve-reveal">
            <a href="<?= esc_url(home_url('/insights')) ?>" class="ve-filter-chip <?= !is_category() ? 've-filter-chip--active' : '' ?>">All Topics</a>
            <?php
            $cats = get_categories(['hide_empty' => true]);
            foreach ($cats as $cat) : ?>
            <a href="<?= esc_url(get_category_link($cat)) ?>" class="ve-filter-chip <?= (is_category($cat->term_id) ? 've-filter-chip--active' : '') ?>">
                <?= esc_html($cat->name) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (have_posts()) : ?>
        <div class="ve-blog-grid">
            <?php
            // Skip the featured post to avoid duplication
            $featured_id = $featured[0]->ID ?? 0;
            while (have_posts()) : the_post();
                if (get_the_ID() === $featured_id && !is_category() && !is_search()) continue;
            ?>
            <article <?php post_class('ve-blog-card ve-reveal'); ?>>
                <?php if (has_post_thumbnail()) : ?>
                <div class="ve-blog-card__thumb">
                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                        <?php the_post_thumbnail('ve-card', ['class'=>'ve-blog-card__img','loading'=>'lazy']); ?>
                    </a>
                </div>
                <?php else : ?>
                <div class="ve-blog-card__thumb ve-blog-card__thumb--placeholder" aria-hidden="true">
                    <?php $cats_post = get_the_category(); ?>
                    <span class="ve-blog-card__cat-icon"><?= !empty($cats_post) ? esc_html($cats_post[0]->name[0]) : 'V' ?></span>
                </div>
                <?php endif; ?>
                <div class="ve-blog-card__body">
                    <div class="ve-blog-card__meta">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <?php
                        $post_cats = get_the_category();
                        if (!empty($post_cats)) : ?>
                        &middot; <a href="<?= esc_url(get_category_link($post_cats[0])) ?>" style="color:var(--ve-orange);font-weight:600;"><?= esc_html($post_cats[0]->name) ?></a>
                        <?php endif; ?>
                    </div>
                    <h2 class="ve-blog-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="ve-blog-card__excerpt"><?php the_excerpt(); ?></p>
                    <div class="ve-blog-card__footer">
                        <a href="<?php the_permalink(); ?>" class="ve-service-card__link">
                            Read Article
                            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                        </a>
                        <span class="ve-read-time"><?= ceil(str_word_count(strip_tags(get_the_content())) / 200) ?> min read</span>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <div class="ve-pagination">
            <?php the_posts_pagination(['prev_text'=>'← Previous','next_text'=>'Next →']); ?>
        </div>

        <?php else : ?>
        <!-- Placeholder articles when no posts published yet -->
        <div class="ve-blog-grid">
            <?php
            $placeholder_articles = [
                ['title'=>'Why US Enterprises Are Accelerating AI Adoption in 2025','cat'=>'AI & Machine Learning','date'=>'April 2, 2025','excerpt'=>'New Gartner data shows 67% of US enterprises plan to increase AI investment this year. We break down what\'s driving the acceleration — and what separates organizations that see ROI from those that don\'t.','read_time'=>7],
                ['title'=>'CMMC 2.0: What Every DoD Contractor Must Do Before Their Next Contract Renewal','cat'=>'Cybersecurity','date'=>'March 18, 2025','excerpt'=>'The final CMMC 2.0 rule took effect in December 2024. If you\'re a DoD contractor and haven\'t started your assessment, here\'s an honest timeline and what it actually costs.','read_time'=>9],
                ['title'=>'The Hidden Cost of Cloud Sprawl — and How to Fix It in 90 Days','cat'=>'Cloud Solutions','date'=>'March 5, 2025','excerpt'=>'The average US enterprise wastes 32% of its cloud spend. We walk through the FinOps framework we\'ve used to recover millions in wasted spend for clients across healthcare, finance, and manufacturing.','read_time'=>6],
                ['title'=>'Building a Data Lakehouse: Snowflake vs. Databricks vs. BigQuery in 2025','cat'=>'Data Analytics','date'=>'February 20, 2025','excerpt'=>'An honest, opinionated comparison of the three leading platforms for US enterprise analytics teams — including total cost of ownership, migration complexity, and which industries each is best suited for.','read_time'=>11],
                ['title'=>'Digital Transformation Failures: The 5 Patterns We See Most in US Mid-Market Companies','cat'=>'Digital Transformation','date'=>'February 7, 2025','excerpt'=>'After 200+ engagements, we\'ve seen every way a transformation program can go sideways. These five patterns account for over 80% of the failures — and all of them are preventable.','read_time'=>8],
                ['title'=>'IoT in American Manufacturing: From Buzzword to $4.8M in Annual Savings','cat'=>'IoT & Automation','date'=>'January 24, 2025','excerpt'=>'A behind-the-scenes look at our IoT deployment at a century-old Texas manufacturer — what the data actually told us, which changes leadership resisted, and how we got 43% OEE improvement in 18 months.','read_time'=>10],
            ];
            foreach ($placeholder_articles as $i => $article) : ?>
            <article class="ve-blog-card ve-reveal" data-index="<?= $i ?>">
                <div class="ve-blog-card__thumb ve-blog-card__thumb--placeholder" aria-hidden="true" style="background:linear-gradient(135deg,rgba(249,115,22,.06) 0%,rgba(37,99,235,.08) 100%);display:flex;align-items:center;justify-content:center;min-height:180px;">
                    <span style="font-size:2.5rem;font-weight:900;color:var(--ve-orange);opacity:.3;"><?= strtoupper(substr($article['cat'],0,1)) ?></span>
                </div>
                <div class="ve-blog-card__body">
                    <div class="ve-blog-card__meta">
                        <span><?= esc_html($article['date']) ?></span>
                        &middot; <span style="color:var(--ve-orange);font-weight:600;"><?= esc_html($article['cat']) ?></span>
                    </div>
                    <h2 class="ve-blog-card__title"><?= esc_html($article['title']) ?></h2>
                    <p class="ve-blog-card__excerpt"><?= esc_html($article['excerpt']) ?></p>
                    <div class="ve-blog-card__footer">
                        <span class="ve-service-card__link" style="cursor:default;opacity:.5;">Coming soon</span>
                        <span class="ve-read-time"><?= $article['read_time'] ?> min read</span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="ve-section ve-section--alt ve-reveal">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-newsletter-block">
            <div class="ve-newsletter-block__copy">
                <div class="ve-badge">Newsletter</div>
                <h2 class="ve-section-title">Technology Intelligence, <span class="ve-text-gradient">Delivered</span></h2>
                <p class="ve-body-text">Monthly insights for enterprise technology leaders — no fluff, no vendor marketing, just the analysis and perspective that helps you make better decisions.</p>
            </div>
            <form class="ve-newsletter-form" id="ve-newsletter-form">
                <div class="ve-newsletter-form__fields">
                    <input type="email" placeholder="your@company.com" class="ve-form-input" required aria-label="Email address">
                    <button type="submit" class="ve-btn ve-btn--primary">
                        Subscribe
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
                    </button>
                </div>
                <p class="ve-newsletter-form__legal">No spam. Unsubscribe any time. Read our <a href="<?= esc_url(home_url('/privacy')) ?>">Privacy Policy</a>.</p>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>
