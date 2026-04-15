<?php
/**
 * Volkmann Express — Default Page Template
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'title' => get_the_title(),
    'size'  => 'sm',
] );
?>
<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-prose ve-reveal">
            <?php
            while ( have_posts() ) {
                the_post();
                the_content();
            }
            ?>
        </div>
    </div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
