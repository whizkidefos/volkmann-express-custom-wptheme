<?php
/**
 * Volkmann Express — Privacy Policy Page
 * Template Name: Privacy Policy
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'    => 'Legal',
    'title'    => 'Privacy <span class="ve-text-gradient">Policy</span>',
    'subtitle' => 'How we collect, use, and protect your personal information.',
    'size'     => 'sm',
] );
?>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-legal-layout">
            <!-- Table of contents -->
            <aside class="ve-legal-toc ve-reveal">
                <p class="ve-legal-toc__heading">Contents</p>
                <ul>
                    <li><a href="#overview">1. Overview</a></li>
                    <li><a href="#information-collected">2. Information Collected</a></li>
                    <li><a href="#data-use">3. How We Use Your Data</a></li>
                    <li><a href="#sharing">4. Information Sharing</a></li>
                    <li><a href="#user-rights">5. Your Rights</a></li>
                    <li><a href="#cookies">6. Cookies</a></li>
                    <li><a href="#security">7. Security</a></li>
                    <li><a href="#retention">8. Data Retention</a></li>
                    <li><a href="#contact-legal">9. Contact Us</a></li>
                </ul>
                <p class="ve-legal-toc__date">Last updated: <?= date('F j, Y') ?></p>
            </aside>

            <!-- Body -->
            <div class="ve-prose ve-reveal">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php if ( get_the_content() ) : the_content(); else : ?>

                <section id="overview">
                    <h2>1. Overview</h2>
                    <p>Volkmann Express ("we", "our", or "us") is committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or engage our services.</p>
                    <p>Please read this policy carefully. If you disagree with its terms, please discontinue use of our site. We may update this policy from time to time; the date at the top of this page reflects the most recent revision.</p>
                </section>

                <section id="information-collected">
                    <h2>2. Information We Collect</h2>
                    <h3>Information You Provide</h3>
                    <p>We collect information you voluntarily provide when you contact us, request a consultation, or engage our services, including:</p>
                    <ul>
                        <li>Name, email address, and telephone number</li>
                        <li>Company name, job title, and industry</li>
                        <li>Project requirements and business challenges</li>
                        <li>Payment and billing information (processed securely by third-party providers)</li>
                    </ul>
                    <h3>Information Collected Automatically</h3>
                    <p>When you visit our website, we automatically collect certain technical information including IP address, browser type and version, pages visited, time spent on pages, referring URLs, and device identifiers. This data is collected via cookies and similar technologies (see Section 6).</p>
                </section>

                <section id="data-use">
                    <h2>3. How We Use Your Data</h2>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Respond to enquiries and provide the services you request</li>
                        <li>Send transactional communications related to your engagement</li>
                        <li>Improve and personalise your experience on our website</li>
                        <li>Send marketing communications (with your consent, where required)</li>
                        <li>Comply with legal obligations and enforce our agreements</li>
                        <li>Detect, prevent, and investigate fraud or misuse</li>
                    </ul>
                    <p>Our lawful bases for processing include performance of a contract, legitimate interests, compliance with legal obligations, and consent (where applicable).</p>
                </section>

                <section id="sharing">
                    <h2>4. Information Sharing</h2>
                    <p>We do not sell your personal data. We may share it with:</p>
                    <ul>
                        <li><strong>Service providers</strong> who assist us in website hosting, analytics, email delivery, and CRM (bound by data processing agreements)</li>
                        <li><strong>Professional advisers</strong> including lawyers and accountants, under obligations of confidentiality</li>
                        <li><strong>Law enforcement or regulatory authorities</strong> where required by applicable law</li>
                        <li><strong>Acquirers</strong> in the event of a merger, acquisition, or sale of all or part of our business</li>
                    </ul>
                </section>

                <section id="user-rights">
                    <h2>5. Your Rights</h2>
                    <p>Depending on your location, you may have the following rights regarding your personal data:</p>
                    <ul>
                        <li><strong>Access</strong> — request a copy of the personal data we hold about you</li>
                        <li><strong>Correction</strong> — request correction of inaccurate or incomplete data</li>
                        <li><strong>Deletion</strong> — request erasure of your personal data in certain circumstances</li>
                        <li><strong>Restriction</strong> — request that we restrict processing of your data</li>
                        <li><strong>Portability</strong> — receive your data in a portable format</li>
                        <li><strong>Objection</strong> — object to processing based on legitimate interests</li>
                        <li><strong>Withdraw consent</strong> — where processing is based on consent, withdraw it at any time</li>
                    </ul>
                    <p>To exercise any of these rights, contact us at <a href="mailto:privacy@volkmannexpress.com">privacy@volkmannexpress.com</a>. We will respond within 30 days.</p>
                </section>

                <section id="cookies">
                    <h2>6. Cookies</h2>
                    <p>We use essential, performance, and marketing cookies. Essential cookies are required for the website to function. Performance cookies (e.g., Google Analytics) help us understand how visitors use our site. Marketing cookies support targeted advertising.</p>
                    <p>You can manage cookies through your browser settings or our cookie consent tool. Disabling non-essential cookies will not affect your ability to use core site features.</p>
                </section>

                <section id="security">
                    <h2>7. Security</h2>
                    <p>We implement appropriate technical and organisational measures to protect your data against unauthorised access, alteration, disclosure, or destruction. These include TLS encryption in transit, access controls, and regular security assessments. However, no transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
                </section>

                <section id="retention">
                    <h2>8. Data Retention</h2>
                    <p>We retain personal data only as long as necessary for the purposes set out in this policy, or as required by law. Contact form submissions are retained for three years. Client engagement data is retained for seven years for legal and financial compliance purposes.</p>
                </section>

                <section id="contact-legal">
                    <h2>9. Contact Us</h2>
                    <p>For questions, requests, or complaints about this Privacy Policy or our data practices, please contact our Data Protection Officer:</p>
                    <address>
                        Volkmann Express — Data Protection Officer<br>
                        Email: <a href="mailto:privacy@volkmannexpress.com">privacy@volkmannexpress.com</a><br>
                        <?= esc_html( ve_opt('ve_address') ?: '123 Innovation Drive, Tech City, TX 75001' ) ?>
                    </address>
                    <p>If you are in the EEA or UK and believe we have infringed your rights, you have the right to lodge a complaint with your local supervisory authority (e.g., the ICO in the UK).</p>
                </section>

                <?php endif; endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
