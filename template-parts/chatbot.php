<?php
/**
 * Volkmann Express — Floating AI Chatbot Widget
 * Loaded in footer.php before wp_footer()
 */
?>

<!-- ══════════════════════════════════════════════════════════
     FLOATING CHATBOT
     ══════════════════════════════════════════════════════════ -->
<div id="ve-chat-widget" aria-live="polite" aria-label="Volkmann Express AI Assistant">

    <!-- Trigger button -->
    <button id="ve-chat-trigger" class="ve-chat-trigger" aria-label="Open AI Assistant" type="button">
        <!-- Chat icon (default) -->
        <svg class="ve-chat-trigger__icon ve-chat-trigger__icon--open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            <circle cx="12" cy="10" r="1" fill="currentColor" stroke="none"/>
            <circle cx="8"  cy="10" r="1" fill="currentColor" stroke="none"/>
            <circle cx="16" cy="10" r="1" fill="currentColor" stroke="none"/>
        </svg>
        <!-- Close icon -->
        <svg class="ve-chat-trigger__icon ve-chat-trigger__icon--close hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
        <!-- Unread badge -->
        <span class="ve-chat-badge" id="ve-chat-badge" aria-hidden="true">1</span>
    </button>

    <!-- Chat window -->
    <div id="ve-chat-window" class="ve-chat-window" role="dialog" aria-modal="false" aria-label="AI Assistant Chat" hidden>

        <!-- Header -->
        <div class="ve-chat-header">
            <div class="ve-chat-header__left">
                <div class="ve-chat-avatar" aria-hidden="true">
                    <img src="<?= esc_url(VE_URI . '/assets/images/logo.png') ?>" alt="" width="28" height="28">
                </div>
                <div>
                    <div class="ve-chat-header__name">Volkmann Express AI</div>
                    <div class="ve-chat-header__status">
                        <span class="ve-status-dot"></span> Online
                    </div>
                </div>
            </div>
            <button class="ve-chat-close" id="ve-chat-close" type="button" aria-label="Close chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div class="ve-chat-messages" id="ve-chat-messages" role="log" aria-label="Chat messages">
            <!-- Welcome message injected by JS -->
        </div>

        <!-- Suggested prompts (shown before first user message) -->
        <div class="ve-chat-suggestions" id="ve-chat-suggestions">
            <button class="ve-chat-suggestion" type="button" data-prompt="What services does Volkmann Express offer?">What services do you offer?</button>
            <button class="ve-chat-suggestion" type="button" data-prompt="How do I get started with a project?">How do I get started?</button>
            <button class="ve-chat-suggestion" type="button" data-prompt="Do you help with CMMC compliance?">CMMC compliance help?</button>
            <button class="ve-chat-suggestion" type="button" data-prompt="What industries do you serve?">Which industries do you serve?</button>
        </div>

        <!-- Input area -->
        <div class="ve-chat-input-area">
            <div class="ve-chat-input-wrap">
                <textarea
                    id="ve-chat-input"
                    class="ve-chat-input"
                    placeholder="Ask about our services, capabilities, or pricing…"
                    rows="1"
                    maxlength="500"
                    aria-label="Type your message"
                ></textarea>
                <button id="ve-chat-send" class="ve-chat-send" type="button" aria-label="Send message" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </div>
            <p class="ve-chat-disclaimer">AI assistant — may not reflect all details. For complex queries, <a href="<?= esc_url(home_url('/contact')) ?>">contact us directly</a>.</p>
        </div>

    </div><!-- .ve-chat-window -->
</div><!-- #ve-chat-widget -->
