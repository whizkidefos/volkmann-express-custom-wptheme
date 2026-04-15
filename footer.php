<?php
/**
 * Volkmann Express — footer.php
 */
?>
</main><!-- #main-content -->

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="ve-footer" role="contentinfo">

    <!-- Footer top grid -->
    <div class="ve-footer__top container mx-auto px-4 lg:px-8">

        <!-- Brand column -->
        <div class="ve-footer__brand">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="ve-logo" aria-label="Volkmann Express Home">
                <img
                    src="<?php echo esc_url( VE_URI . '/assets/images/logo.png' ); ?>"
                    alt="Volkmann Express"
                    width="44"
                    height="44"
                    class="ve-logo__img"
                    loading="lazy"
                >
                <span class="ve-logo__text">
                    <span class="ve-logo__primary">Volkmann</span>
                    <span class="ve-logo__accent">Express</span>
                </span>
            </a>
            <p class="ve-footer__tagline">
                Enterprise technology partner delivering AI, cloud, and digital transformation at scale. Headquartered in Bowie, MD.
            </p>

            <!-- Social links -->
            <div class="ve-footer__social">
                <?php if ( $linkedin = ve_opt('ve_linkedin') ) : ?>
                <a href="<?= esc_url($linkedin) ?>" class="ve-social-link" aria-label="LinkedIn" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <?php endif; ?>
                <?php if ( $twitter = ve_opt('ve_twitter') ) : ?>
                <a href="<?= esc_url($twitter) ?>" class="ve-social-link" aria-label="Twitter / X" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Solutions column — uses real CPT permalinks when posts exist -->
        <div class="ve-footer__col">
            <h3 class="ve-footer__heading">Solutions</h3>
            <ul class="ve-footer__links">
                <?php
                $service_posts = get_posts( [
                    'post_type'      => 'service',
                    'posts_per_page' => 8,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ] );

                if ( ! empty( $service_posts ) ) {
                    // Real CPT posts exist — use their proper permalinks
                    foreach ( $service_posts as $sp ) : ?>
                    <li><a href="<?= esc_url( get_permalink( $sp ) ) ?>" class="ve-footer__link"><?= esc_html( $sp->post_title ) ?></a></li>
                    <?php endforeach;
                } else {
                    // No CPT posts yet — list services but all point to the Solutions hub
                    foreach ( ve_default_services() as $s ) : ?>
                    <li><a href="<?= esc_url( home_url('/solutions') ) ?>" class="ve-footer__link"><?= esc_html( $s['title'] ) ?></a></li>
                    <?php endforeach;
                } ?>
            </ul>
        </div>

        <!-- Company column -->
        <div class="ve-footer__col">
            <h3 class="ve-footer__heading">Company</h3>
            <ul class="ve-footer__links">
                <?php
                $company = [
                    'About'        => '/about',
                    'Industries'   => '/industries',
                    'Case Studies' => '/case-studies',
                    'Insights'     => '/insights',
                    'Careers'      => '/careers',
                    'Contact'      => '/contact',
                ];
                foreach ( $company as $label => $path ) : ?>
                <li><a href="<?= esc_url( home_url($path) ) ?>" class="ve-footer__link"><?= esc_html($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Contact column -->
        <div class="ve-footer__col">
            <h3 class="ve-footer__heading">Contact</h3>
            <ul class="ve-footer__contact-list">
                <?php if ( $phone = ve_opt('ve_phone') ) : ?>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4 shrink-0"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.88 12 19.79 19.79 0 01.81 3.41 2 2 0 012.82 1H5.82a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15.92z"/></svg>
                    <a href="tel:<?= esc_attr( preg_replace('/[^+\d]/', '', $phone) ) ?>" class="ve-footer__link"><?= esc_html($phone) ?></a>
                </li>
                <?php endif; ?>
                <?php if ( $email = ve_opt('ve_email') ) : ?>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4 shrink-0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <a href="mailto:<?= esc_attr($email) ?>" class="ve-footer__link"><?= esc_html($email) ?></a>
                </li>
                <?php endif; ?>
                <?php if ( $address = ve_opt('ve_address') ) : ?>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4 shrink-0 mt-0.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span><?= esc_html($address) ?></span>
                </li>
                <?php endif; ?>
                <?php if ( $hours = ve_opt('ve_hours') ) : ?>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-4 h-4 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span><?= esc_html($hours) ?></span>
                </li>
                <?php endif; ?>
            </ul>
        </div>

    </div><!-- .ve-footer__top -->

    <!-- Footer bottom bar -->
    <div class="ve-footer__bottom container mx-auto px-4 lg:px-8">
        <p class="ve-footer__copy">
            &copy; <?= date('Y') ?> Volkmann Express Inc. All rights reserved.
        </p>
        <nav class="ve-footer__legal" aria-label="Legal">
            <a href="<?= esc_url( home_url('/privacy') ) ?>" class="ve-footer__link">Privacy Policy</a>
            <span aria-hidden="true">·</span>
            <a href="<?= esc_url( home_url('/terms') ) ?>" class="ve-footer__link">Terms of Service</a>
        </nav>
    </div>

</footer>

<!-- Back to top -->
<button id="ve-back-top" aria-label="Back to top" type="button">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<!-- Cookie consent banner -->
<div id="ve-cookie-banner" role="region" aria-label="Cookie consent">
    <div class="ve-cookie-inner">
        <p>We use cookies to improve your experience and analyze site traffic. By continuing to use this site you accept our <a href="<?= esc_url(home_url('/privacy')) ?>">Privacy Policy</a>.</p>
        <div class="ve-cookie-btns">
            <button id="ve-cookie-decline" class="ve-btn ve-btn--ghost" type="button">Decline</button>
            <button id="ve-cookie-accept"  class="ve-btn ve-btn--primary" type="button">Accept All</button>
        </div>
    </div>
</div>

<!-- AI Chatbot Widget -->
<?php get_template_part('template-parts/chatbot'); ?>

<?php wp_footer(); ?>
</body>
</html>
