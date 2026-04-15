<?php
/**
 * Volkmann Express — Contact Page
 * Template Name: Contact
 */
get_header();
?>

<section class="ve-section ve-contact-hero relative overflow-hidden">
    <canvas id="ve-hero-canvas" class="ve-hero__canvas" aria-hidden="true"></canvas>
    <div class="ve-hero__overlay" aria-hidden="true"></div>
    <div class="container mx-auto px-4 lg:px-8 relative z-10 py-28 md:py-36">
        <div class="ve-badge ve-fade-up" data-delay="0">Get in Touch</div>
        <h1 class="ve-hero__title ve-fade-up" data-delay="100">
            Let's Build Something <span class="ve-text-gradient">Remarkable</span>
        </h1>
        <p class="ve-hero__subtitle ve-fade-up max-w-2xl" data-delay="200">
            Whether you have a specific challenge or just want to explore what's possible — our team is ready to help.
        </p>
    </div>
</section>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-contact-layout">

            <!-- Left: contact details -->
            <div class="ve-contact-info ve-reveal">
                <h2 class="ve-section-title mb-8">Contact <span class="ve-text-gradient">Details</span></h2>

                <ul class="ve-contact-detail-list">
                    <?php if ( $phone = ve_opt('ve_phone') ) : ?>
                    <li class="ve-contact-detail">
                        <div class="ve-contact-detail__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.88 12 19.79 19.79 0 01.81 3.41 2 2 0 012.82 1H5.82a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15.92z"/></svg>
                        </div>
                        <div>
                            <p class="ve-contact-detail__label">Phone</p>
                            <a href="tel:<?= esc_attr( preg_replace('/[^+\d]/', '', $phone) ) ?>" class="ve-contact-detail__value"><?= esc_html($phone) ?></a>
                        </div>
                    </li>
                    <?php else : ?>
                    <li class="ve-contact-detail">
                        <div class="ve-contact-detail__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.88 12 19.79 19.79 0 01.81 3.41 2 2 0 012.82 1H5.82a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.09a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 15.92z"/></svg>
                        </div>
                        <div>
                            <p class="ve-contact-detail__label">Phone</p>
                            <p class="ve-contact-detail__value">+1 (555) 000-0000</p>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if ( $email = ve_opt('ve_email') ) : ?>
                    <li class="ve-contact-detail">
                        <div class="ve-contact-detail__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <p class="ve-contact-detail__label">Email</p>
                            <a href="mailto:<?= esc_attr($email) ?>" class="ve-contact-detail__value"><?= esc_html($email) ?></a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if ( $address = ve_opt('ve_address') ) : ?>
                    <li class="ve-contact-detail">
                        <div class="ve-contact-detail__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <p class="ve-contact-detail__label">Address</p>
                            <p class="ve-contact-detail__value"><?= esc_html($address) ?></p>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if ( $hours = ve_opt('ve_hours') ) : ?>
                    <li class="ve-contact-detail">
                        <div class="ve-contact-detail__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <p class="ve-contact-detail__label">Hours</p>
                            <p class="ve-contact-detail__value"><?= esc_html($hours) ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Mini service links -->
                <div class="ve-contact-solutions mt-10">
                    <p class="ve-contact-solutions__label">Looking for a specific solution?</p>
                    <div class="ve-contact-solutions__grid">
                        <?php foreach ( ve_default_services() as $s ) : ?>
                        <a href="<?= esc_url( ve_get_service_url($s['slug']) ) ?>" class="ve-contact-solution-chip">
                            <?= ve_service_icon($s['icon']) ?>
                            <span><?= esc_html($s['title']) ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right: form -->
            <div class="ve-contact-form-col ve-reveal">
                <div class="ve-form-card">
                    <h2 class="ve-form-card__title">Send Us a Message</h2>
                    <p class="ve-form-card__sub">We typically respond within one business day.</p>
                    <?php get_template_part( 'template-parts/contact-form' ); ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
