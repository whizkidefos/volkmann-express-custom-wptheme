<?php
/**
 * Template Part: Contact Form (AJAX)
 */
?>
<div class="ve-contact-form-wrap">
    <form id="ve-contact-form" class="ve-contact-form" novalidate>
        <?php wp_nonce_field( 've_contact_nonce', 've_nonce_field' ); ?>

        <div class="ve-form-row">
            <div class="ve-form-group">
                <label for="ve-name" class="ve-form-label">Full Name <span class="ve-required">*</span></label>
                <input
                    type="text"
                    id="ve-name"
                    name="name"
                    class="ve-form-input"
                    placeholder="Jane Smith"
                    required
                    autocomplete="name"
                >
                <span class="ve-form-error" id="ve-name-error"></span>
            </div>
            <div class="ve-form-group">
                <label for="ve-email" class="ve-form-label">Email Address <span class="ve-required">*</span></label>
                <input
                    type="email"
                    id="ve-email"
                    name="email"
                    class="ve-form-input"
                    placeholder="jane@company.com"
                    required
                    autocomplete="email"
                >
                <span class="ve-form-error" id="ve-email-error"></span>
            </div>
        </div>

        <div class="ve-form-row">
            <div class="ve-form-group">
                <label for="ve-company" class="ve-form-label">Company</label>
                <input
                    type="text"
                    id="ve-company"
                    name="company"
                    class="ve-form-input"
                    placeholder="Acme Corp"
                    autocomplete="organization"
                >
            </div>
            <div class="ve-form-group">
                <label for="ve-subject" class="ve-form-label">Subject <span class="ve-required">*</span></label>
                <select id="ve-subject" name="subject" class="ve-form-select" required>
                    <option value="">Select a topic…</option>
                    <option value="AI & Machine Learning">AI & Machine Learning</option>
                    <option value="Cloud Solutions">Cloud Solutions</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                    <option value="Data Analytics">Data Analytics</option>
                    <option value="Digital Transformation">Digital Transformation</option>
                    <option value="Custom Software">Custom Software</option>
                    <option value="General Enquiry">General Enquiry</option>
                    <option value="Partnership">Partnership</option>
                </select>
                <span class="ve-form-error" id="ve-subject-error"></span>
            </div>
        </div>

        <div class="ve-form-group">
            <label for="ve-message" class="ve-form-label">Message <span class="ve-required">*</span></label>
            <textarea
                id="ve-message"
                name="message"
                class="ve-form-textarea"
                placeholder="Tell us about your project or challenge…"
                rows="5"
                required
            ></textarea>
            <span class="ve-form-error" id="ve-message-error"></span>
        </div>

        <!-- Honeypot -->
        <div class="ve-hp" aria-hidden="true">
            <input type="text" name="ve_pot" value="" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit" class="ve-btn ve-btn--primary ve-btn--lg w-full justify-center" id="ve-contact-submit">
            <span class="ve-btn__text">Send Message</span>
            <svg class="ve-btn__spinner hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><circle cx="12" cy="12" r="10" stroke-opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 ve-btn__arrow"><line x1="3" y1="8" x2="13" y2="8"/><polyline points="9 4 13 8 9 12"/></svg>
        </button>

        <div id="ve-form-success" class="ve-form-success hidden">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8"><polyline points="20 6 9 17 4 12"/></svg>
            <div>
                <strong>Message sent!</strong>
                <p>Thank you. We'll be in touch within one business day.</p>
            </div>
        </div>
        <div id="ve-form-error-global" class="ve-form-error-global hidden"></div>

    </form>
</div>
