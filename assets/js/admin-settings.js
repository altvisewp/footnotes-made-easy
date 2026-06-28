/**
 * Footnotes Made Easy — Admin JS
 *
 * Handles tab switching (settings page), video modal (dashboard),
 * and notice auto-dismiss. All data is passed via wp_localize_script
 * as fmeSettings — no inline scripts needed.
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
(function () {
    'use strict';

    /* ── Auto-dismiss notices (runs on all plugin pages) ─── */
    document.querySelectorAll( '.fme-notice-autodismiss' ).forEach( function( notice ) {
        function dismissNotice() { notice.classList.add( 'fme-notice-hiding' ); }
        setTimeout( dismissNotice, 5000 );
        var closeBtn = notice.querySelector( '.fme-notice-close' );
        if ( closeBtn ) { closeBtn.addEventListener( 'click', dismissNotice ); }
    } );

    if ( typeof fmeSettings === 'undefined' ) { return; }

    /* ── Tab switching (Settings page) ─────────────────────── */
    var tabNav = document.getElementById('fme-tabs-nav');
    if ( tabNav ) {
        var tabs   = tabNav.querySelectorAll('.fme-inner-tab');
        var panels = document.querySelectorAll('.fme-tab-panel');

        var submitBtn = document.querySelector('.fme-settings-main .button-primary, .fme-settings-main .fme-save-btn');

        function switchTab( id ) {
            var validTabs = ['display', 'behaviour', 'suppress', 'advanced', 'citations'];
            if ( validTabs.indexOf( id ) === -1 ) { id = 'display'; }

            panels.forEach( function (p) { p.style.display = 'none'; } );
            tabs.forEach(   function (t) { t.classList.remove('fme-active'); } );

            var panel = document.getElementById( 'fme-panel-' + id );
            var tab   = tabNav.querySelector( '.fme-inner-tab[data-tab="' + id + '"]' );
            if ( panel ) { panel.style.display = ''; }
            if ( tab )   { tab.classList.add('fme-active'); }

            var inp = document.getElementById('fme-active-tab-input');
            if ( inp ) { inp.value = id; }

            // Disable Save button when on a locked Citations tab
            if ( submitBtn ) {
                var isLockedCitations = id === 'citations' && !! document.querySelector('#fme-panel-citations .fme-fullpage-lock');
                submitBtn.disabled = isLockedCitations;
                submitBtn.style.opacity       = isLockedCitations ? '0.4' : '';
                submitBtn.style.cursor        = isLockedCitations ? 'not-allowed' : '';
                submitBtn.style.pointerEvents = isLockedCitations ? 'none' : '';
                // Toggle sidebar visibility
                document.body.classList.toggle( 'fme-citations-locked', isLockedCitations );
            }

            if ( history.replaceState ) {
                history.replaceState( null, '', '#' + id );
            }
        }

        tabs.forEach( function (tab) {
            tab.addEventListener( 'click', function (e) {
                // Don't intercept external Pro landing page links
                if ( tab.classList.contains('fme-inner-tab--link') ) {
                    return;
                }
                e.preventDefault();
                switchTab( tab.getAttribute('data-tab') );
            } );
        } );

        // Restore active tab: posted value > URL hash > default
        var validTabs = ['display', 'behaviour', 'suppress', 'advanced', 'citations'];
        var posted  = ( fmeSettings.postedTab && validTabs.indexOf( fmeSettings.postedTab ) !== -1 ) ? fmeSettings.postedTab : '';
        var hash    = window.location.hash.replace( '#', '' );
        var initial = posted  ? posted
                    : ( hash && validTabs.indexOf( hash ) !== -1 ) ? hash
                    : 'display';
        switchTab( initial );
    }

    /* ── Video modal (Dashboard page) ──────────────────────── */
    var watchBtn = document.getElementById('fme-watch-video-btn');
    if ( watchBtn ) {
        var modal  = document.getElementById('fme-video-modal');
        var iframe = document.getElementById('fme-video-iframe');
        var closeBtn = document.getElementById('fme-video-close');
        var videoId = 'Bl9p2-lSZMU';

        function openModal() {
            if ( ! modal || ! iframe ) { return; }
            iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if ( ! modal || ! iframe ) { return; }
            modal.style.display = 'none';
            iframe.src = '';
            document.body.style.overflow = '';
        }

        watchBtn.addEventListener( 'click', openModal );
        if ( closeBtn ) { closeBtn.addEventListener( 'click', closeModal ); }
        if ( modal ) {
            modal.addEventListener( 'click', function (e) {
                if ( e.target === modal ) { closeModal(); }
            } );
        }
        document.addEventListener( 'keydown', function (e) {
            if ( e.key === 'Escape' && modal && modal.style.display === 'flex' ) { closeModal(); }
        } );
    }

    /* ── Reset settings modal (Tools page) ─────────────── */
    var resetTrigger = document.getElementById('fme-reset-trigger');
    var resetModal   = document.getElementById('fme-reset-modal');
    var modalCancel  = document.getElementById('fme-modal-cancel');
    var modalConfirm = document.getElementById('fme-modal-confirm');
    var resetForm    = document.getElementById('fme-reset-form');

    if ( resetTrigger && resetModal ) {
        function openResetModal() {
            resetModal.classList.add('fme-modal-open');
            if ( modalCancel ) { modalCancel.focus(); }
        }
        function closeResetModal() {
            resetModal.classList.remove('fme-modal-open');
        }

        resetTrigger.addEventListener( 'click', openResetModal );
        if ( modalCancel )  { modalCancel.addEventListener(  'click', closeResetModal ); }
        if ( modalConfirm ) { modalConfirm.addEventListener( 'click', function() { resetForm.submit(); } ); }

        // Close on overlay click
        resetModal.addEventListener( 'click', function(e) {
            if ( e.target === resetModal ) { closeResetModal(); }
        } );

        // Close on Escape
        document.addEventListener( 'keydown', function(e) {
            if ( e.key === 'Escape' && resetModal.classList.contains('fme-modal-open') ) {
                closeResetModal();
            }
        } );
    }

    /* ── Import settings modal (Tools page) ─────────────── */
    var importTrigger      = document.getElementById('fme-import-trigger');
    var importModal        = document.getElementById('fme-import-modal');
    var importModalCancel  = document.getElementById('fme-import-modal-cancel');
    var importModalConfirm = document.getElementById('fme-import-modal-confirm');
    var importForm         = document.getElementById('fme-import-form');
    var importFileInput    = document.getElementById('fme_import_file');

    if ( importTrigger && importModal ) {
        function openImportModal() {
            // Require a file to be selected first
            if ( ! importFileInput || ! importFileInput.value ) {
                importFileInput && importFileInput.focus();
                return;
            }
            importModal.classList.add('fme-modal-open');
            if ( importModalCancel ) { importModalCancel.focus(); }
        }
        function closeImportModal() {
            importModal.classList.remove('fme-modal-open');
        }

        importTrigger.addEventListener( 'click', openImportModal );
        if ( importModalCancel )  { importModalCancel.addEventListener(  'click', closeImportModal ); }
        if ( importModalConfirm ) { importModalConfirm.addEventListener( 'click', function() { importForm.submit(); } ); }

        importModal.addEventListener( 'click', function(e) {
            if ( e.target === importModal ) { closeImportModal(); }
        } );

        document.addEventListener( 'keydown', function(e) {
            if ( e.key === 'Escape' && importModal.classList.contains('fme-modal-open') ) {
                closeImportModal();
            }
        } );
    }


}());
