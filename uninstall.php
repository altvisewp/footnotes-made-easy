<?php
/**
 * Uninstaller — Footnotes Made Easy
 *
 * Runs when the plugin is deleted from the WordPress admin.
 * Respects the "Preserve settings on uninstall" option from the Tools page.
 * If preserve is enabled, all settings are kept.
 * If disabled (default), all plugin data is removed and options reset to defaults.
 *
 * @package footnotes-made-easy
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// If the user chose to preserve settings, do nothing.
if ( get_option( 'fme_preserve_settings_on_uninstall' ) === '1' ) {
    return;
}

// Remove all plugin options.
delete_option( 'swas_footnote_options' );
delete_option( 'fme_preserve_settings_on_uninstall' );
delete_option( 'fme_welcome_shown_version' );
delete_option( 'fme_orphan_cleanup_version' );
