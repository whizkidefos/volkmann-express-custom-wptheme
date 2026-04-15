<?php
/**
 * Volkmann Express — Terms of Service
 * Template Name: Terms of Service
 */
get_header();

get_template_part( 'template-parts/hero', null, [
    'badge'    => 'Legal',
    'title'    => 'Terms of <span class="ve-text-gradient">Service</span>',
    'subtitle' => 'Please read these terms carefully before using our website or engaging our services.',
    'size'     => 'sm',
] );
?>

<section class="ve-section">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="ve-legal-layout">
            <aside class="ve-legal-toc ve-reveal">
                <p class="ve-legal-toc__heading">Contents</p>
                <ul>
                    <li><a href="#agreement">1. Agreement to Terms</a></li>
                    <li><a href="#use-license">2. Use License</a></li>
                    <li><a href="#services">3. Services</a></li>
                    <li><a href="#ip">4. Intellectual Property</a></li>
                    <li><a href="#disclaimer">5. Disclaimer</a></li>
                    <li><a href="#limitations">6. Limitations</a></li>
                    <li><a href="#indemnification">7. Indemnification</a></li>
                    <li><a href="#governing-law">8. Governing Law</a></li>
                    <li><a href="#changes">9. Changes to Terms</a></li>
                    <li><a href="#contact-terms">10. Contact Us</a></li>
                </ul>
                <p class="ve-legal-toc__date">Last updated: <?= date('F j, Y') ?></p>
            </aside>

            <div class="ve-prose ve-reveal">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php if ( get_the_content() ) : the_content(); else : ?>

                <section id="agreement">
                    <h2>1. Agreement to Terms</h2>
                    <p>By accessing or using the Volkmann Express website (the "Site") or engaging our services, you confirm that you are at least 18 years of age, have read and understood these Terms of Service ("Terms"), and agree to be bound by them. If you do not agree, you must not use our Site or services.</p>
                    <p>These Terms apply to all visitors, users, clients, and others who access or use the Site. They form a legally binding agreement between you and Volkmann Express.</p>
                </section>

                <section id="use-license">
                    <h2>2. Use License</h2>
                    <p>We grant you a limited, non-exclusive, non-transferable, revocable licence to access and use the Site strictly in accordance with these Terms. You may not:</p>
                    <ul>
                        <li>Reproduce, distribute, modify, or create derivative works of any Site content without our prior written consent</li>
                        <li>Use the Site or its content for any commercial purpose not expressly authorised by us</li>
                        <li>Attempt to gain unauthorised access to any portion of the Site or its related systems</li>
                        <li>Use any data mining, scraping, or automated data collection tools without express written permission</li>
                        <li>Transmit any unsolicited or unauthorised advertising or promotional material</li>
                        <li>Engage in any conduct that restricts or inhibits any other user's use or enjoyment of the Site</li>
                    </ul>
                    <p>We reserve the right to terminate or restrict access to the Site for any breach of these Terms, at our sole discretion and without notice.</p>
                </section>

                <section id="services">
                    <h2>3. Services</h2>
                    <p>Volkmann Express provides technology consulting, software development, cloud infrastructure, cybersecurity, data analytics, and related professional services (collectively, the "Services"). The specific terms governing any engagement for Services will be set out in a separate Statement of Work or Services Agreement, which will take precedence over these Terms to the extent of any conflict.</p>
                    <p>We reserve the right to modify, suspend, or discontinue any aspect of our Services at any time. We will endeavour to provide reasonable notice of any material changes.</p>
                </section>

                <section id="ip">
                    <h2>4. Intellectual Property</h2>
                    <p>The Site and all its content, features, and functionality — including but not limited to text, graphics, logos, icons, images, code, and software — are owned by Volkmann Express or our licensors and are protected by applicable intellectual property laws.</p>
                    <p>For client engagements, intellectual property ownership will be governed by the applicable services agreement. In the absence of such agreement, all work product developed by Volkmann Express remains our property until full payment is received, at which point ownership transfers to the client as agreed in the relevant contract.</p>
                </section>

                <section id="disclaimer">
                    <h2>5. Disclaimer</h2>
                    <p>The Site and its content are provided on an "as is" and "as available" basis without any warranties of any kind, either express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>
                    <p>We do not warrant that the Site will be uninterrupted, error-free, or free of viruses or other harmful components. Any content on the Site is for informational purposes only and should not be construed as professional advice specific to your situation.</p>
                </section>

                <section id="limitations">
                    <h2>6. Limitations of Liability</h2>
                    <p>To the fullest extent permitted by applicable law, Volkmann Express and its directors, employees, partners, agents, suppliers, and affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, goodwill, or other intangible losses, resulting from:</p>
                    <ul>
                        <li>Your access to or use of (or inability to access or use) the Site</li>
                        <li>Any content obtained from the Site</li>
                        <li>Unauthorised access to or alteration of your transmissions or data</li>
                        <li>Any third-party conduct or content on the Site</li>
                    </ul>
                    <p>Our total liability for any claim arising out of or relating to these Terms or your use of the Site shall not exceed £100 (or the equivalent in your local currency).</p>
                </section>

                <section id="indemnification">
                    <h2>7. Indemnification</h2>
                    <p>You agree to indemnify, defend, and hold harmless Volkmann Express and its officers, directors, employees, and agents from and against any claims, liabilities, damages, judgements, awards, losses, costs, expenses, or fees (including reasonable legal fees) arising out of or relating to your violation of these Terms or your use of the Site.</p>
                </section>

                <section id="governing-law">
                    <h2>8. Governing Law</h2>
                    <p>These Terms shall be governed by and construed in accordance with the laws of England and Wales, without regard to conflict of law provisions. Any disputes arising under or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>
                </section>

                <section id="changes">
                    <h2>9. Changes to Terms</h2>
                    <p>We reserve the right to revise these Terms at any time. When we do, we will update the "last updated" date at the top of this page. Continued use of the Site following any changes constitutes your acceptance of the revised Terms. If changes are material, we will endeavour to provide prominent notice on the Site.</p>
                </section>

                <section id="contact-terms">
                    <h2>10. Contact Us</h2>
                    <p>If you have any questions about these Terms, please contact us:</p>
                    <address>
                        Volkmann Express — Legal<br>
                        Email: <a href="mailto:legal@volkmannexpress.com">legal@volkmannexpress.com</a><br>
                        <?= esc_html( ve_opt('ve_address') ?: '123 Innovation Drive, Tech City, TX 75001' ) ?>
                    </address>
                </section>

                <?php endif; endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
