/**
 * Volkmann Express — Admin JS
 * Enhances the WP admin with theme-specific helpers.
 */

(function($) {
    'use strict';

    // ── Meta box field helpers ──────────────────────────────

    // JSON validator for results field on Case Studies
    const resultsField = document.getElementById('ve_results');
    if (resultsField) {
        const hint = document.createElement('p');
        hint.className = 'description';
        hint.style.color = '#888';
        hint.textContent = 'Format: [{"value":"32%","label":"Reduction in readmissions"},{"value":"$2.4M","label":"Annual savings"}]';
        resultsField.parentNode.insertBefore(hint, resultsField.nextSibling);

        resultsField.addEventListener('blur', function() {
            try {
                if (this.value.trim()) JSON.parse(this.value);
                this.style.borderColor = '';
                hint.style.color = '#888';
            } catch(e) {
                this.style.borderColor = '#d63638';
                hint.style.color = '#d63638';
                hint.textContent = '⚠ Invalid JSON. ' + e.message;
            }
        });
    }

    // ── Leads page: mark as read ────────────────────────────
    if (document.body.classList.contains('toplevel_page_ve-leads')) {
        document.querySelectorAll('tr[data-lead-id]').forEach(row => {
            row.style.cursor = 'pointer';
        });
    }

    // ── Service page: capability count indicator ────────────
    const capField = document.getElementById('ve_capabilities');
    if (capField) {
        const counter = document.createElement('span');
        counter.style.cssText = 'font-size:11px;color:#888;margin-left:8px;';
        capField.parentNode.insertBefore(counter, capField);

        function updateCounter() {
            try {
                const data = JSON.parse(capField.value || '[]');
                counter.textContent = `(${data.length} capabilities)`;
            } catch(e) {
                counter.textContent = '(invalid JSON)';
            }
        }
        capField.addEventListener('input', updateCounter);
        updateCounter();
    }

    // ── Color theme preview in Customizer ───────────────────
    if (typeof wp !== 'undefined' && wp.customize) {
        wp.customize('ve_phone', function(value) {
            value.bind(function(newVal) {
                // Could update live preview
            });
        });
    }

})(window.jQuery || { fn: {} });
