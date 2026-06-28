/**
 * Welcome modal — Footnotes Made Easy
 * Shown once after fresh install or update from an older version.
 */
( function () {
    'use strict';

    var config = window.fmeWelcome || {};
    if ( ! config.show ) return;

    // ── Build modal HTML ──────────────────────────────────────

    var features = [
        {
            icon: '<svg viewBox="0 0 20 20" fill="currentColor"><path d="M3 4h14v2H3zm0 5h9v2H3zm0 5h11v2H3z"/></svg>',
            title: 'Brand new UI',
            desc:  'Redesigned admin with tabbed settings, a dashboard, tools, and help page.',
        },
        {
            icon: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>',
            title: 'Suppress controls',
            desc:  'Hide footnotes on homepages, archives, search, feeds, and custom URLs.',
        },
        {
            icon: '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>',
            title: 'Custom delimiters',
            desc:  'Change (( )) to any opening and closing tags you prefer.',
        },
        {
            icon: '<svg viewBox="0 0 20 20" fill="currentColor"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4zM3 8a1 1 0 000 2v3a2 2 0 002 2h10a2 2 0 002-2v-3a1 1 0 100-2H3z"/></svg>',
            title: 'Export & Import',
            desc:  'Back up your settings and reuse them across multiple sites instantly.',
        },
    ];

    function buildModal() {
        var featuresHtml = features.map( function ( f ) {
            return '<div class="fme-welcome-feature">' +
                '<div class="fme-welcome-feature__icon">' + f.icon + '</div>' +
                '<div>' +
                '<p class="fme-welcome-feature__title">' + f.title + '</p>' +
                '<p class="fme-welcome-feature__desc">' + f.desc + '</p>' +
                '</div>' +
                '</div>';
        } ).join( '' );

        // ── Pro block + CTA vary by license state ──────────────────
        var proState = config.proState || 'none';
        var proBlock = '';
        var ctaHtml  = '';

        if ( proState === 'active' ) {
            // Paying customer — thank them, never upsell.
            proBlock =
                '<div class="fme-welcome-pro fme-welcome-pro--active">' +
                '<div class="fme-welcome-pro__icon">★</div>' +
                '<div class="fme-welcome-pro__text">' +
                '<p class="fme-welcome-pro__title">You\'re on Footnotes Made Easy Pro</p>' +
                '<p class="fme-welcome-pro__desc">Thanks for your support. Citations, the Citation Library, and all Pro tools are ready to use.</p>' +
                '</div>' +
                '</div>';
            ctaHtml =
                '<button type="button" class="fme-welcome-modal__cta" id="fme-welcome-cta" data-action="dismiss">Explore your Pro features →</button>';

        } else if ( proState === 'inactive' ) {
            // Pro installed but not licensed — open the same Freemius license
            // activation dialog the other "Activate license" buttons use.
            proBlock =
                '<div class="fme-welcome-pro">' +
                '<div class="fme-welcome-pro__icon">🔑</div>' +
                '<div class="fme-welcome-pro__text">' +
                '<p class="fme-welcome-pro__title">Activate your Pro license</p>' +
                '<p class="fme-welcome-pro__desc">Enter your license key to unlock citations, the Citation Library, and Classic Editor &amp; Gutenberg support.</p>' +
                '</div>' +
                '</div>';
            ctaHtml =
                '<button type="button" class="fme-welcome-modal__cta" id="fme-welcome-cta" data-action="activate-license">Activate license →</button>' +
                '<button type="button" class="fme-welcome-modal__cta-secondary" id="fme-welcome-dismiss" data-action="dismiss">Maybe later</button>';

        } else {
            // Free user — the main conversion moment.
            proBlock =
                '<div class="fme-welcome-pro">' +
                '<div class="fme-welcome-pro__icon">🚀</div>' +
                '<div class="fme-welcome-pro__text">' +
                '<p class="fme-welcome-pro__title">Footnotes Made Easy Pro is here</p>' +
                '<p class="fme-welcome-pro__desc">Academic citations in APA, MLA, Chicago &amp; Harvard · Reusable Citation Library · Classic Editor &amp; Gutenberg support</p>' +
                '</div>' +
                '</div>';
            ctaHtml =
                '<a href="' + ( config.proUrl || '#' ) + '" target="_blank" rel="noopener noreferrer" class="fme-welcome-modal__cta" id="fme-welcome-cta" data-action="link">See Pro features →</a>' +
                '<button type="button" class="fme-welcome-modal__cta-secondary" id="fme-welcome-dismiss" data-action="dismiss">Explore the free version</button>';
        }

        var html =
            // Loader
            '<div class="fme-page-loader" id="fme-page-loader">' +
            '<div class="fme-page-loader__spinner"></div>' +
            '<p class="fme-page-loader__text">Loading...</p>' +
            '</div>' +

            // Overlay
            '<div class="fme-welcome-overlay fme-welcome-open" id="fme-welcome-overlay">' +
            '<div class="fme-welcome-modal" role="dialog" aria-modal="true" aria-labelledby="fme-welcome-title">' +

            // Close button
            '<button type="button" class="fme-welcome-modal__close" id="fme-welcome-close" aria-label="Close">' +
            '<svg viewBox="0 0 12 12" fill="none" width="12" height="12"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>' +
            '</button>' +

            // Hero
            '<div class="fme-welcome-modal__hero">' +
            '<div class="fme-welcome-modal__badge">✦ ' + ( config.isUpdate ? 'Updated to ' : 'Welcome to ' ) + 'v' + ( config.version || '3.2' ) + '</div>' +
            '<h2 class="fme-welcome-modal__title" id="fme-welcome-title">Footnotes Made Easy,<br>completely redesigned</h2>' +
            '<p class="fme-welcome-modal__subtitle">A brand new interface, more controls, and now — Footnotes Made Easy Pro.</p>' +
            '</div>' +

            // Body
            '<div class="fme-welcome-modal__body">' +
            '<p class="fme-welcome-modal__section-label">What\'s new</p>' +
            '<div class="fme-welcome-features">' + featuresHtml + '</div>' +

            // Pro block (state-aware)
            proBlock +

            // CTA (state-aware)
            '<div class="fme-welcome-modal__actions">' + ctaHtml + '</div>' +

            '</div>' + // body
            '</div>' + // modal
            '</div>'; // overlay

        var div = document.createElement( 'div' );
        div.innerHTML = html;
        while ( div.firstChild ) {
            document.body.appendChild( div.firstChild );
        }
    }

    // ── Blur the page content behind the modal ────────────────

    function unblurPage() {
        // Remove the server-injected preblur style.
        // wp_add_inline_style outputs the CSS in a style tag with the handle suffixed by "-inline-css".
        var preblur = document.getElementById( 'fme-welcome-preblur-inline-css' );
        if ( preblur ) preblur.remove();
        // Fallback for older markup
        var legacyPreblur = document.getElementById( 'fme-welcome-preblur' );
        if ( legacyPreblur ) legacyPreblur.remove();
        // Also remove any JS-added blur classes
        document.querySelectorAll( '.fme-welcome-blur' ).forEach( function ( el ) {
            el.classList.remove( 'fme-welcome-blur' );
        } );
    }

    // ── Open the Freemius license-activation dialog ───────────
    // Dismisses the welcome modal, then forwards to the same trigger the
    // plugin's other "Activate license" buttons use. Freemius binds a click
    // handler to `.activate-license-trigger.{slug}` that opens its dialog.

    function openLicenseActivation() {
        dismiss();

        // Defer so the welcome overlay is gone before the Freemius dialog opens.
        setTimeout( function () {
            var trigger = document.querySelector( '.activate-license-trigger.footnotes-made-easy' );
            if ( trigger ) {
                trigger.click();
                return;
            }
            // Fallback: Freemius exposes a global helper on some versions.
            if ( window.FS && typeof window.FS.showActivation === 'function' ) {
                window.FS.showActivation();
                return;
            }
            // Last resort: send them to their account so the button is never a dead end.
            if ( config.accountUrl ) {
                window.open( config.accountUrl, '_blank', 'noopener' );
            }
        }, 50 );
    }

    // ── Mark the welcome modal as shown (so it doesn't reappear) ──

    function markShown() {
        if ( config.ajaxUrl && config.nonce ) {
            fetch( config.ajaxUrl, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'action=fme_dismiss_welcome&nonce=' + config.nonce,
                keepalive: true,
            } );
        }
    }

    // ── Dismiss and show loader ───────────────────────────────

    function dismiss() {
        var overlay = document.getElementById( 'fme-welcome-overlay' );
        var loader  = document.getElementById( 'fme-page-loader' );

        if ( overlay ) overlay.style.display = 'none';
        unblurPage();

        // Show loader for 2 seconds then hide
        if ( loader ) {
            loader.classList.add( 'fme-loader-active' );
            setTimeout( function () {
                loader.classList.remove( 'fme-loader-active' );
            }, 2000 );
        }

        markShown();
    }

    // ── Init ─────────────────────────────────────────────────

    document.addEventListener( 'DOMContentLoaded', function () {
        buildModal();

        var closeBtn   = document.getElementById( 'fme-welcome-close' );
        var ctaBtn     = document.getElementById( 'fme-welcome-cta' );
        var dismissBtn = document.getElementById( 'fme-welcome-dismiss' );
        var overlay    = document.getElementById( 'fme-welcome-overlay' );

        if ( closeBtn ) closeBtn.addEventListener( 'click', dismiss );

        // The primary CTA may be a dismiss button (active state), an external
        // link (free state), or the license-activation trigger (inactive state).
        if ( ctaBtn ) {
            ctaBtn.addEventListener( 'click', function ( e ) {
                var action = ctaBtn.getAttribute( 'data-action' );
                if ( action === 'link' ) {
                    // External link opening in a new tab — current page stays put,
                    // so the async markShown() completes fine.
                    markShown();
                } else if ( action === 'activate-license' ) {
                    openLicenseActivation();
                } else {
                    dismiss();
                }
            } );
        }

        // Secondary "maybe later" / "explore free" button always dismisses.
        if ( dismissBtn ) dismissBtn.addEventListener( 'click', dismiss );

        // Escape key
        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' ) dismiss();
        } );
    } );

} )();
