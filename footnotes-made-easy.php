<?php
/*
 * Plugin Name:       Footnotes Made Easy
 * Plugin URI:        https://lumumbas.blog/plugins/footnotes-made-easy/
 * Description:       Allows post authors to easily add and manage footnotes in posts.
 * Version:           3.2.2-beta.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Patrick Lumumba
 * Author URI:        https://lumumbas.blog
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       footnotes-made-easy
*/

/**
* Footnotes Made Easy
*
* Easily add footnotes to a post
*
* @package	footnotes-made-easy
* @since	1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// Plugin version — kept in sync with the header above. Used for cache-busting
// and for the welcome-modal version comparison (more reliable than reading the
// header at runtime via get_plugin_data(), which isn't always loaded on admin_init).
if ( ! defined( 'FME_VERSION' ) ) {
    define( 'FME_VERSION', '3.2.2-beta.1' );
}

// External Pro upgrade / pricing page. All in-admin "Upgrade to Pro" CTAs and
// the Pro menu item point here (opening in a new tab) instead of an in-plugin page.
if ( ! defined( 'FME_PRO_URL' ) ) {
    define( 'FME_PRO_URL', 'https://altvisewp.com/plugins/footnotes-made-easy/' );
}

/**
 * Enqueue plugin admin styles and scripts — only on our plugin pages.
 *
 * We match on the $_GET['page'] query var — the only value that is
 * guaranteed to be correct on every WP version and host, because it is
 * the raw slug we registered with add_menu_page / add_submenu_page.
 * Screen-ID and $hook string derivation has proven unreliable for
 * custom top-level menus across environments.
 */
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
function fme_enqueue_styles( $hook ) {
    $fme_pages = array(
        'footnotes-made-easy',
        'footnotes-settings',
        'footnotes-help',
        'footnotes-tools',
        'footnotes-pro',
    );

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- $_GET['page'] is a routing parameter, not form data.
    $current_page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

    if ( ! in_array( $current_page, $fme_pages, true ) ) {
        return;
    }

    $css_path = plugin_dir_path( __FILE__ ) . 'assets/css/admin-settings.css';
    $js_path  = plugin_dir_path( __FILE__ ) . 'assets/js/admin-settings.js';

    wp_enqueue_style(
        'fme-admin-styles',
        plugin_dir_url( __FILE__ ) . 'assets/css/admin-settings.css',
        array(),
        file_exists( $css_path ) ? filemtime( $css_path ) : '1.0'
    );

    wp_enqueue_script(
        'fme-admin-settings',
        plugin_dir_url( __FILE__ ) . 'assets/js/admin-settings.js',
        array(),
        file_exists( $js_path ) ? filemtime( $js_path ) : '1.0',
        true // load in footer
    );

    wp_localize_script( 'fme-admin-settings', 'fmeSettings', array(
        'tabs' => array(
            'display'   => array(
                'title' => esc_html__( 'Display settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Control how footnote identifiers, links, and back-links appear on the front end.', 'footnotes-made-easy' ),
            ),
            'behaviour' => array(
                'title' => esc_html__( 'Behaviour settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Configure how footnotes are processed and rendered by WordPress.', 'footnotes-made-easy' ),
            ),
            'suppress'  => array(
                'title' => esc_html__( 'Suppress settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Choose where on your site footnotes should not appear.', 'footnotes-made-easy' ),
            ),
            'advanced'  => array(
                'title' => esc_html__( 'Advanced settings', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Modify footnote delimiter tags — changes require updating all existing posts.', 'footnotes-made-easy' ),
            ),
            'about'     => array(
                'title' => esc_html__( 'About', 'footnotes-made-easy' ),
                'sub'   => esc_html__( 'Plugin stats, version status, tutorials, and resources.', 'footnotes-made-easy' ),
            ),
        ),
        'postedTab'  => isset( $_POST['fme_active_tab'] ) ? sanitize_key( wp_unslash( $_POST['fme_active_tab'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Tab state only; nonce is verified in save_options().
    ) );
}
add_action( 'admin_enqueue_scripts', 'fme_enqueue_styles' );

/**
 * Enqueue the deactivation survey on the plugins page.
 */
function fme_enqueue_deactivation_survey( string $hook ): void { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, renaming would break existing installations.
    if ( $hook !== 'plugins.php' ) return;

    $plugin_version = FME_VERSION;
    $css_ver        = filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/admin-settings.css' ) ?: $plugin_version;
    $js_ver         = filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/deactivation-survey.js' ) ?: $plugin_version;

    wp_enqueue_style(
        'fme-admin-settings',
        plugin_dir_url( __FILE__ ) . 'assets/css/admin-settings.css',
        [],
        $css_ver
    );

    wp_enqueue_script(
        'fme-deactivation-survey',
        plugin_dir_url( __FILE__ ) . 'assets/js/deactivation-survey.js',
        [],
        $js_ver,
        true
    );

    wp_localize_script( 'fme-deactivation-survey', 'fmeDeactivation', [
        'pluginSlug'    => 'footnotes-made-easy',
        'pluginFile'    => 'footnotes-made-easy/footnotes-made-easy.php',
        'pluginVersion' => $plugin_version,
        'wpVersion'     => get_bloginfo( 'version' ),
        'phpVersion'    => PHP_VERSION,
        'endpoint'      => 'https://analytics.altvisewp.com/wp-json/altvisewp/v1/deactivation-feedback',
    ] );
}
add_action( 'admin_enqueue_scripts', 'fme_enqueue_deactivation_survey' );

/**
 * Welcome modal — shown once after fresh install or update from an older version.
 */
function fme_check_welcome_modal(): void { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, renaming would break existing installations.
    $current_ver    = FME_VERSION;
    $stored_ver     = get_option( 'fme_welcome_shown_version', '' );

    // Show if never shown, or if version has changed
    if ( $stored_ver !== $current_ver ) {
        set_transient( 'fme_show_welcome', $current_ver, DAY_IN_SECONDS );
    }
}
add_action( 'admin_init', 'fme_check_welcome_modal' );

/**
 * One-time cleanup of files removed from the plugin package.
 *
 * The WordPress.org updater overwrites files but does not reliably delete files
 * that were removed in a new version. Sites updating from an older release may
 * therefore keep orphaned files on disk (e.g. the retired Pro "coming soon"
 * page and its script). This routine deletes those known orphans once per
 * version, so updated installs end up identical to a fresh install without the
 * user needing to delete-and-reinstall.
 *
 * Add new paths to $fme_orphans (relative to the plugin root) whenever files are
 * removed from the package in future releases.
 */
function fme_cleanup_orphan_files(): void { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function naming convention for this plugin.
    // Run at most once per version.
    if ( get_option( 'fme_orphan_cleanup_version', '' ) === FME_VERSION ) {
        return;
    }

    $fme_orphans = array(
        'includes/coming-soon-pro.php',
        'assets/js/coming-soon.js',
    );

    foreach ( $fme_orphans as $fme_orphan ) {
        // Normalise and confine strictly to within the plugin directory.
        $fme_base = wp_normalize_path( plugin_dir_path( __FILE__ ) );
        $fme_path = wp_normalize_path( $fme_base . $fme_orphan );

        if ( 0 !== strpos( $fme_path, $fme_base ) ) {
            continue; // Path escaped the plugin dir — skip defensively.
        }
        if ( file_exists( $fme_path ) ) {
            wp_delete_file( $fme_path );
        }
    }

    update_option( 'fme_orphan_cleanup_version', FME_VERSION );
}
add_action( 'admin_init', 'fme_cleanup_orphan_files' );

function fme_enqueue_welcome_modal( string $hook ): void { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, renaming would break existing installations.
    // Only show on our own plugin pages
    $fme_pages = [
        'toplevel_page_footnotes-made-easy',
        'footnotes_page_footnotes-settings',
        'footnotes_page_footnotes-tools',
        'footnotes_page_footnotes-help',
        'footnotes_page_footnotes-pro',
        'footnotes_page_fme-pro-library',
    ];
    if ( ! in_array( $hook, $fme_pages, true ) ) return;

    $show_version = get_transient( 'fme_show_welcome' );
    if ( ! $show_version ) return;

    $stored_ver  = get_option( 'fme_welcome_shown_version', '' );
    $is_update   = ! empty( $stored_ver );

    // Determine the Pro state so the modal can address each audience correctly:
    //   'active'   — Pro installed and license is paying
    //   'inactive' — Pro installed but no active license
    //   'none'     — free user, Pro not installed
    $fme_pro_installed = defined( 'FME_PRO_VERSION' );
    $fme_pro_active    = $fme_pro_installed
        && function_exists( 'fmep_fs' )
        && fmep_fs()
        && fmep_fs()->is_paying();
    if ( $fme_pro_active ) {
        $fme_pro_state = 'active';
    } elseif ( $fme_pro_installed ) {
        $fme_pro_state = 'inactive';
    } else {
        $fme_pro_state = 'none';
    }

    $js_path = plugin_dir_path( __FILE__ ) . 'assets/js/welcome-modal.js';
    wp_enqueue_script(
        'fme-welcome-modal',
        plugin_dir_url( __FILE__ ) . 'assets/js/welcome-modal.js',
        [],
        file_exists( $js_path ) ? filemtime( $js_path ) : '1.0',
        true
    );

    wp_localize_script( 'fme-welcome-modal', 'fmeWelcome', [
        'show'      => true,
        'version'   => $show_version,
        'isUpdate'  => $is_update,
        'proState'  => $fme_pro_state,
        'proUrl'    => FME_PRO_URL,
        'accountUrl'=> 'https://altvisewp.com/account/',
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'fme_welcome_nonce' ),
    ] );

    // Preblur the page until the welcome modal appears (added via inline style on a registered handle)
    wp_register_style( 'fme-welcome-preblur', false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    wp_enqueue_style( 'fme-welcome-preblur' );
    wp_add_inline_style(
        'fme-welcome-preblur',
        '#wpcontent,#wpbody-content,#adminmenu{filter:blur(4px) brightness(0.92);pointer-events:none;user-select:none;transition:filter 0.3s ease;}body.wp-admin::before{content:"";position:fixed;inset:0;background:rgba(20,18,40,0.28);z-index:9998;pointer-events:none;}'
    );
}
add_action( 'admin_enqueue_scripts', 'fme_enqueue_welcome_modal' );

// AJAX handler — mark welcome as shown
add_action( 'wp_ajax_fme_dismiss_welcome', function (): void {
    check_ajax_referer( 'fme_welcome_nonce', 'nonce' );
    $version = get_transient( 'fme_show_welcome' );
    if ( $version ) {
        update_option( 'fme_welcome_shown_version', $version );
        delete_transient( 'fme_show_welcome' );
    }
    wp_send_json_success();
} );



// Inject admin CSS for full-width purple Upgrade to Pro menu item
function fme_pro_menu_css() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy function name, renaming would break existing installations.
    if ( defined( 'FME_PRO_VERSION' ) || ! swas_wp_footnotes::show_upsell() ) return;

    // Style the "Upgrade to Pro" menu item. We match BOTH the internal href
    // (as WordPress renders it) and the external URL (after the JS below swaps
    // it), so the styling never flashes unstyled regardless of timing.
    $css = '
#adminmenu a[href="admin.php?page=footnotes-pro"],
#adminmenu a[href="' . esc_url( FME_PRO_URL ) . '"] {
    background: #534AB7 !important;
    color: #fff !important;
    font-weight: 600 !important;
    margin: 4px 0 2px !important;
    border-radius: 0 !important;
}
#adminmenu a[href="admin.php?page=footnotes-pro"]:hover,
#adminmenu a[href="admin.php?page=footnotes-pro"].current,
#adminmenu li.current a[href="admin.php?page=footnotes-pro"],
#adminmenu a[href="' . esc_url( FME_PRO_URL ) . '"]:hover {
    background: #433aa0 !important;
    color: #fff !important;
}';

    // Register a lightweight handle to attach inline styles to (menu appears on all admin pages)
    wp_register_style( 'fme-admin-menu', false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    wp_enqueue_style( 'fme-admin-menu' );
    wp_add_inline_style( 'fme-admin-menu', $css );

    // Point the "Upgrade to Pro" menu item directly at the external pricing page
    // and open it in a new tab. We rewrite the href (so it links straight out,
    // no internal hop) and add target/rel. The CSS above matches both hrefs, so
    // there's no unstyled flash. If JS is disabled, the original href still works
    // (the page redirects to the same external URL server-side).
    $menu_js = sprintf(
        'document.addEventListener("DOMContentLoaded",function(){' .
            'var a=document.querySelector(\'#adminmenu a[href$="page=footnotes-pro"]\');' .
            'if(a){a.setAttribute("href",%s);a.setAttribute("target","_blank");a.setAttribute("rel","noopener noreferrer");}' .
        '});',
        wp_json_encode( FME_PRO_URL )
    );
    wp_register_script( 'fme-admin-menu-js', false, array(), FME_VERSION, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Inline-only handle (src is false); version passed for consistency.
    wp_enqueue_script( 'fme-admin-menu-js' );
    wp_add_inline_script( 'fme-admin-menu-js', $menu_js );
}
add_action( 'admin_enqueue_scripts', 'fme_pro_menu_css' );
add_action( 'network_admin_menu', function () {
    add_action( 'admin_enqueue_scripts', 'fme_pro_menu_css' );
} );

$swas_wp_footnotes = new swas_wp_footnotes(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// Encapsulate in a class
class swas_wp_footnotes {

    // Declare the $styles property
    private $styles;

    private $current_options;
    private $default_options;

    const OPTIONS_VERSION = "5"; // Incremented when the options array changes

    // Constructor
    function __construct() {

        // Define the implemented option styles
        $this->styles = array(
            'decimal' => '1,2...10',
            'decimal-leading-zero' => '01, 02...10',
            'lower-alpha' => 'a,b...j',
            'upper-alpha' => 'A,B...J',
            'lower-roman' => 'i,ii...x',
            'upper-roman' => 'I,II...X',
            'symbol' => 'Symbol'
        );

        // Define default options
        $this->default_options = array(
            'superscript' => true,
            'pre_backlink' => ' [',
            'backlink' => '&#8617;',
            'post_backlink' => ']',
            'backlink_position' => 'end',
            'pre_identifier' => '',
            'inner_pre_identifier' => '',
            'list_style_type' => 'decimal',
            'list_style_symbol' => '&dagger;',
            'inner_post_identifier' => '',
            'post_identifier' => '',
            'pre_footnotes' => '',
            'post_footnotes' => '',
            'no_display_home' => false,
            'no_display_preview' => false,
            'no_display_archive' => false,
            'no_display_date' => false,
            'no_display_category' => false,
            'no_display_search' => false,
            'no_display_feed' => false,
            'combine_identical_notes' => true,
            'priority' => 11,
            'footnotes_open' => ' ((',
            'footnotes_close' => '))',
            'pretty_tooltips' => false,
            'exclude_urls' => '',
            'exclude_categories' => '',
            'version' => self::OPTIONS_VERSION
        );

        // Get the current settings or setup some defaults if needed
        $this->current_options = get_option( 'swas_footnote_options' );
        if ( ! $this->current_options ) {		
            $this->current_options = $this->default_options;
            update_option( 'swas_footnote_options', $this->current_options );
        } else {
            // Set any unset options
            if ( !isset( $this->current_options[ 'version' ] ) || $this->current_options[ 'version' ] !== self::OPTIONS_VERSION) {
                foreach ( $this->default_options as $key => $value ) {
                    if ( !isset( $this->current_options[ $key ] ) ) {
                        $this->current_options[ $key ] = $value;
                    }
                }
                $this->current_options[ 'version' ] = self::OPTIONS_VERSION;
                update_option( 'swas_footnote_options', $this->current_options );
            }
        }

        // SECURITY FIX: Move options processing to admin_init hook instead of constructor
        // This ensures it only runs in admin context with proper authentication
        add_action( 'admin_init', array( $this, 'save_options' ) );
        add_action( 'admin_post_fme_reset_settings',         array( $this, 'handle_reset_settings' ) );
        add_action( 'admin_post_fme_save_preserve_settings', array( $this, 'handle_preserve_settings' ) );
        add_action( 'admin_post_fme_export_settings',        array( $this, 'handle_export_settings' ) );
        add_action( 'admin_post_fme_import_settings',        array( $this, 'handle_import_settings' ) );

        // Hook me up
        add_action( 'the_content', array( $this, 'process' ), $this->current_options[ 'priority' ] );
        add_action( 'admin_menu',         array( $this, 'add_options_page' ) );      // Insert the Admin panel.
        add_action( 'network_admin_menu',  array( $this, 'add_network_menu' ) );         // Network admin menu.
        add_action( 'wp_enqueue_scripts', array( $this, 'insert_styles' ) );
        if ( $this->current_options[ 'pretty_tooltips' ] ) add_action( 'wp_enqueue_scripts', array( $this, 'tooltip_scripts' ) );

        add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );
        add_filter( 'network_admin_plugin_action_links', array( $this, 'add_network_settings_link' ), 10, 2 );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), 10, 2 );

        add_filter( 'admin_footer_text', array( $this, 'remove_footer_text' ) );
        add_filter( 'update_footer', array( $this, 'remove_footer_version' ), 11 );
        add_action( 'admin_notices', array( $this, 'suppress_other_notices' ), 1 );


    }

    /**
     * Save Options - SECURITY FIX
     * 
     * Process and save plugin options with proper security checks
     * 
     * @since 3.0.8
     */
    function save_options() {
        // SECURITY FIX: Only process if all security requirements are met:
        // 1. User must be in admin area
        // 2. User must have manage_options capability
        // 3. Request must be POST with our specific save flags
        // 4. Nonce must be valid (CSRF protection)
        if ( ! is_admin() ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // SECURITY FIX: Check for save flags and verify nonce BEFORE accessing $_POST
        if ( empty( $_POST[ 'save_options' ] ) || empty( $_POST[ 'save_footnotes_made_easy_options' ] ) ) {
            return;
        }

        if ( ! check_admin_referer( 'footnotes-nonce', 'footnotes_nonce' ) ) {
            return;
        }

        // Now it's safe to access POST data
        $post_array = $_POST;

        // Now it's safe to process the options
        $footnotes_options = array();

        $footnotes_options[ 'superscript' ] = ( array_key_exists( 'superscript', $post_array ) ) ? true : false;
        $footnotes_options[ 'pre_backlink' ] = sanitize_text_field( $post_array[ 'pre_backlink' ] );
        $footnotes_options[ 'backlink' ] = sanitize_text_field( $post_array[ 'backlink' ] );
        $footnotes_options[ 'post_backlink' ] = sanitize_text_field( $post_array[ 'post_backlink' ] );
        $footnotes_options[ 'backlink_position' ] = ( isset( $post_array[ 'backlink_position' ] ) && 'start' === $post_array[ 'backlink_position' ] ) ? 'start' : 'end';
        $footnotes_options[ 'pre_identifier' ] = sanitize_text_field( $post_array[ 'pre_identifier' ] );
        $footnotes_options[ 'inner_pre_identifier' ] = sanitize_text_field( $post_array[ 'inner_pre_identifier' ] );
        $footnotes_options[ 'list_style_type' ] = sanitize_text_field( $post_array[ 'list_style_type' ] );
        $footnotes_options[ 'inner_post_identifier' ] = sanitize_text_field( $post_array[ 'inner_post_identifier' ] );
        $footnotes_options[ 'post_identifier' ] = sanitize_text_field( $post_array[ 'post_identifier' ] );
        $footnotes_options[ 'list_style_symbol' ] = sanitize_text_field( $post_array[ 'list_style_symbol' ] );
        
        // SECURITY FIX: Sanitize HTML content fields to prevent XSS.
        // html_entity_decode() is applied before wp_kses_post() to prevent double-encoding on
        // repeated saves. esc_textarea() encodes < and > for display in the textarea; without
        // decoding first, each save would re-encode the already-encoded entities
        // (e.g. <h2> → &lt;h2&gt; → &amp;lt;h2&amp;gt;). wp_kses_post() then strips any
        // disallowed tags from the decoded value, keeping the sanitization intact.
        $footnotes_options[ 'pre_footnotes' ]  = wp_kses_post( html_entity_decode( wp_unslash( $post_array[ 'pre_footnotes' ] ), ENT_QUOTES, 'UTF-8' ) );
        $footnotes_options[ 'post_footnotes' ] = wp_kses_post( html_entity_decode( wp_unslash( $post_array[ 'post_footnotes' ] ), ENT_QUOTES, 'UTF-8' ) );
        
        $footnotes_options[ 'no_display_home' ] = ( array_key_exists( 'no_display_home', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_preview' ] = ( array_key_exists( 'no_display_preview', $post_array) ) ? true : false;
        $footnotes_options[ 'no_display_archive' ] = ( array_key_exists( 'no_display_archive', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_date' ] = ( array_key_exists( 'no_display_date', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_category' ] = ( array_key_exists( 'no_display_category', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_search' ] = ( array_key_exists( 'no_display_search', $post_array ) ) ? true : false;
        $footnotes_options[ 'no_display_feed' ] = ( array_key_exists( 'no_display_feed', $post_array ) ) ? true : false;
        $footnotes_options[ 'combine_identical_notes' ] = ( array_key_exists( 'combine_identical_notes', $post_array ) ) ? true : false;
        $footnotes_options[ 'priority' ] = sanitize_text_field( $post_array[ 'priority' ] );
        $footnotes_options[ 'footnotes_open' ] = sanitize_text_field( $post_array[ 'footnotes_open' ] );
        $footnotes_options[ 'footnotes_close' ] = sanitize_text_field( $post_array[ 'footnotes_close' ] );
        $footnotes_options[ 'pretty_tooltips' ] = ( array_key_exists( 'pretty_tooltips', $post_array ) ) ? true : false;
        $footnotes_options[ 'exclude_urls' ] = sanitize_textarea_field( $post_array[ 'exclude_urls' ] ?? '' );
        $footnotes_options[ 'exclude_categories' ] = sanitize_textarea_field( $post_array[ 'exclude_categories' ] ?? '' );

        update_option( 'swas_footnote_options', $footnotes_options );
        $this->current_options = $footnotes_options;
    }
	
	/**
	 * Check if the current URL is excluded from footnote processing
	 *
	 * @since 3.2.0
	 *
	 * @return bool True if the current page should be excluded
	 */
	function is_excluded_url() {

		$exclude_urls = $this->current_options[ 'exclude_urls' ] ?? '';
		if ( empty( trim( $exclude_urls ) ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$current_path = isset( $_SERVER[ 'REQUEST_URI' ] ) ? wp_parse_url( sanitize_url( wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) ), PHP_URL_PATH ) : '';
		$current_path = untrailingslashit( $current_path );
		if ( empty( $current_path ) ) {
			$current_path = '/';
		}

		$lines = explode( "\n", $exclude_urls );
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( empty( $line ) ) {
				continue;
			}

			// If it looks like a full URL, extract the path component
			if ( false !== strpos( $line, '://' ) ) {
				$parsed = wp_parse_url( $line, PHP_URL_PATH );
				$line = $parsed ? $parsed : $line;
			}

			// If no leading slash, treat as a bare slug
			if ( '/' !== substr( $line, 0, 1 ) ) {
				$line = '/' . $line;
			}

			$line = untrailingslashit( $line );

			if ( strpos( $current_path, $line ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the current post belongs to an excluded category
	 *
	 * @since 3.2.0
	 *
	 * @return bool True if the post's categories intersect the exclusion list
	 */
	function is_excluded_category() {

		$exclude_categories = $this->current_options[ 'exclude_categories' ] ?? '';
		if ( empty( trim( $exclude_categories ) ) ) {
			return false;
		}

		$lines = explode( "\n", $exclude_categories );
		$slugs = array();
		$ids   = array();

		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( empty( $line ) ) {
				continue;
			}
			if ( ctype_digit( $line ) ) {
				$ids[] = (int) $line;
			} else {
				$slugs[] = $line;
			}
		}

		if ( ! empty( $ids ) && has_category( $ids ) ) {
			return true;
		}

		if ( ! empty( $slugs ) && has_category( $slugs ) ) {
			return true;
		}

		return false;
	}
	
	/**
	* Searches the text and extracts footnotes
	*
	* Adds the identifier links and creats footnotes list
	*
	* @since	1.0
	*
	* @param	$data	string	The content of the post
	* @return			string 	The new content with footnotes generated
	*/

	function process( $data ) {

		global $post;
		
		// check against post existing before processing
		if( ! $post ) {
			return $data;
		}

		// Protect <script> blocks — replace them with placeholders so footnote
		// tags inside JS code are never processed. Restored at the end.
		$script_blocks = array();
		$data = preg_replace_callback(
			'|<script[^>]*>.*?</script>|is',
			function( $matches ) use ( &$script_blocks ) {
				$placeholder = '<!--FME_SCRIPT_' . count( $script_blocks ) . '-->';
				$script_blocks[] = $matches[0];
				return $placeholder;
			},
			$data
		);

		// Check for and setup the starting number

		$start_number = ( 1 === preg_match( "|<!\-\-startnum=(\d+)\-\->|", $data,$start_number_array ) ) ? $start_number_array[ 1 ] : 1;

		// Regex extraction of all footnotes (or return if there are none)

		if ( ! preg_match_all( "/(" . preg_quote( $this->current_options[ 'footnotes_open' ], "/" ) . ")(.*)(" . preg_quote( $this->current_options[ 'footnotes_close' ], "/" ) . ")/Us", $data, $identifiers, PREG_SET_ORDER ) ) {
			// Restore script blocks before early return
			if ( ! empty( $script_blocks ) ) {
				foreach ( $script_blocks as $index => $script ) {
					$data = str_replace( '<!--FME_SCRIPT_' . $index . '-->', $script, $data );
				}
			}
			return $data;
		}

		// Check whether we are displaying them or not

		$display = true;
		if ( $this->current_options[ 'no_display_home' ] && is_home() ) $display = false;
		if ( $this->current_options[ 'no_display_archive' ] && is_archive() ) $display = false;
		if ( $this->current_options[ 'no_display_date' ] && is_date() ) $display = false;
		if ( $this->current_options[ 'no_display_category' ] && is_category() ) $display = false;
		if ( $this->current_options[ 'no_display_search' ] && is_search() ) $display = false;
		if ( $this->current_options[ 'no_display_feed' ] && is_feed() ) $display = false;
		if ( $this->current_options[ 'no_display_preview' ] && is_preview() ) $display = false;
		if ( $this->is_excluded_url() ) $display = false;
		if ( $this->is_excluded_category() ) $display = false;

		$footnotes = array();

		// Check if this post is using a different list style to the settings

		if ( get_post_meta( $post->ID, 'footnote_style', true ) && array_key_exists( get_post_meta( $post->ID, 'footnote_style', true ), $this->styles ) ) {
			$style = get_post_meta( $post->ID, 'footnote_style', true );
		} else {
			$style = $this->current_options[ 'list_style_type' ];
		}

		// Create 'em

		for ( $i = 0; $i < count( $identifiers ); $i++ ){

			// Look for ref: and replace in identifiers array

			if ( 'ref:' === substr( $identifiers[ $i ][ 2 ], 0, 4 ) ){
				$ref = ( int )substr( $identifiers[ $i ][ 2 ],4 );
				$identifiers[ $i ][ 'text' ] = $identifiers[ $ref-1 ][ 2 ];
			}else{
				$identifiers[ $i ][ 'text' ] = $identifiers[ $i ][ 2 ];
			}

			// if we're combining identical notes check if we've already got one like this & record keys

			if ( $this->current_options[ 'combine_identical_notes' ] ){
				for ( $j = 0; $j < count( $footnotes ); $j++ ){
					if ( $footnotes[ $j ][ 'text' ] === $identifiers[ $i ][ 'text' ] ){
						$identifiers[ $i ][ 'use_footnote' ] = $j;
						$footnotes[ $j ][ 'identifiers' ][] = $i;
						break;
					}
				}
			}

			if ( !isset( $identifiers[ $i ][ 'use_footnote' ] ) ){

				// Add footnote and record the key

				$identifiers[ $i ][ 'use_footnote' ] = count( $footnotes );
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'text' ] = $identifiers[ $i ][ 'text' ];
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'symbol' ] = ( $style === 'symbol' ) ? $this->convert_num( count( $footnotes ), $style, count( $identifiers ) ) : '';
				$footnotes[ $identifiers[ $i ][ 'use_footnote' ] ][ 'identifiers' ][] = $i;
			}
		}

		// Footnotes and identifiers are stored in the array

		$use_full_link = false;
		if ( is_feed() ) $use_full_link = true;

		if ( is_preview() ) $use_full_link = false;

		// Display identifiers

		foreach ( $identifiers as $key => $value ) {
			$id_id = "identifier_" . ( $key + 1 ) . "_" . $post->ID;
			$id_num = ( $style === 'decimal' ) ? $value[ 'use_footnote' ] + $start_number : $this->convert_num( $value[ 'use_footnote' ] + $start_number, $style, count( $footnotes ) );
			$id_href = ( ( $use_full_link ) ? get_permalink( $post->ID ) : '' ) . "#footnote_" . ( $value[ 'use_footnote' ] + $start_number ) . "_" . $post->ID;
			$id_title = str_replace( '"', "&quot;", htmlentities( html_entity_decode( wp_strip_all_tags( $value[ 'text' ] ), ENT_QUOTES, 'UTF-8' ), ENT_QUOTES, 'UTF-8' ) );
			$id_replace = esc_html( $this->current_options[ 'pre_identifier' ] ) . '<a href="' . esc_url( $id_href ) . '" id="' . esc_attr( $id_id ) . '" class="footnote-link footnote-identifier-link" title="' . esc_attr( $id_title ) . '">' . esc_html( $this->current_options[ 'inner_pre_identifier' ] ) . esc_html( (string) $id_num ) . esc_html( $this->current_options[ 'inner_post_identifier' ] ) . '</a>' . esc_html( $this->current_options[ 'post_identifier' ] );
			if ( $this->current_options[ 'superscript' ] ) $id_replace = '<sup>' . $id_replace . '</sup>';
			if ( $display ) $data = substr_replace( $data, $id_replace, strpos( $data,$value[ 0 ] ), strlen( $value[ 0 ] ) );
			else $data = substr_replace( $data, '', strpos( $data, $value[ 0 ] ), strlen( $value[ 0 ] ) );
		}

		// Display footnotes

		$start = ( $start_number !== 1 ) ? 'start="' . $start_number . '" ' : '';

		// Re-fetch pre_footnotes and post_footnotes directly from get_option() rather than
		// reading from the cached $this->current_options. This allows WPML (and any other
		// plugin that filters option_swas_footnote_options) to return the correct translated
		// value for the active language at the point of output, rather than the value that
		// was cached at construction time before the language context was fully established.
		$fme_options_live = get_option( 'swas_footnote_options', array() );
		$pre_footnotes    = isset( $fme_options_live['pre_footnotes'] ) ? $fme_options_live['pre_footnotes'] : $this->current_options['pre_footnotes'];
		$post_footnotes   = isset( $fme_options_live['post_footnotes'] ) ? $fme_options_live['post_footnotes'] : $this->current_options['post_footnotes'];

		// SECURITY FIX: Escape output to prevent XSS
		$footnotes_markup = wp_kses_post( $pre_footnotes );

		$footnotes_markup = $footnotes_markup . '<ol ' . $start . 'class="footnotes">';

		foreach ( $footnotes as $key => $value ) {
			$footnotes_markup = $footnotes_markup . '<li id="footnote_' . ( $key + $start_number ) . '_' . $post->ID . '" class="footnote"';
			if ( 'symbol' === $style ) {
				$footnotes_markup = $footnotes_markup . ' value="' . esc_attr( $value[ 'symbol' ] ) . '"';
			}
			$footnotes_markup = $footnotes_markup . '>';
			if ( 'symbol' === $style ) {
				$footnotes_markup = $footnotes_markup . esc_html( $value[ 'symbol' ] ) . ' ';
			}

			// Build back-link HTML (empty string on feeds, or when all backlink fields are blank)
			$backlinks_html = '';
			if ( ! is_feed() ) {
				foreach ( $value[ 'identifiers' ] as $identifier ) {
					$backlinks_html .= '<span class="footnote-back-link-wrapper">' . esc_html( $this->current_options[ 'pre_backlink' ] ) . '<a href="' . esc_url( ( ( $use_full_link ) ? get_permalink( $post->ID ) : '' ) . '#identifier_' . ( $identifier + 1 ) . '_' . $post->ID ) . '" class="footnote-link footnote-back-link">' . esc_html( $this->current_options[ 'backlink' ] ) . '</a>' . esc_html( $this->current_options[ 'post_backlink' ] ) . '</span>';
				}
			}

			$backlink_position = isset( $this->current_options[ 'backlink_position' ] ) ? $this->current_options[ 'backlink_position' ] : 'end';

			if ( 'start' === $backlink_position ) {
				$footnotes_markup = $footnotes_markup . $backlinks_html . ' ' . wp_kses_post( $value[ 'text' ] );
			} else {
				$footnotes_markup = $footnotes_markup . wp_kses_post( $value[ 'text' ] ) . $backlinks_html;
			}

			$footnotes_markup = $footnotes_markup . '</li>';
		}
		
		// SECURITY FIX: Escape output to prevent XSS
		$footnotes_markup = $footnotes_markup . '</ol>' . wp_kses_post( $post_footnotes );

		if ( $display ) {
			$data = $data . $footnotes_markup;
		}

		// Restore any <script> blocks that were protected at the start
		if ( ! empty( $script_blocks ) ) {
			foreach ( $script_blocks as $index => $script ) {
				$data = str_replace( '<!--FME_SCRIPT_' . $index . '-->', $script, $data );
			}
		}

		return $data;
	}

	/**
	* Plugion Meta Links
	*
	* Add links to plugin meta line
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return   string			Links, now with settings added
	*/

	function plugin_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) {
			$links[] = '<a href="https://docs.altvisewp.com/footnotes-made-easy/" target="_blank" rel="noopener noreferrer">' . __( 'Docs', 'footnotes-made-easy' ) . '</a>';
		}
		return $links;
	}

	/**
	* Add Settings Link
	*
	* Add a link to the options page from the plugins list
	*
	* @since	1.0
	*
	* @param	string  $links	Current links
	* @param	string  $file	File in use
	* @return   string			Links, now with settings added
	*/

	function add_settings_link( $links, $file ) {

		static $this_plugin;

		if ( empty( $this_plugin ) ) { $this_plugin = plugin_basename( __FILE__ ); }

		if ( $file === $this_plugin ) {
			// In network-managed mode the Dashboard page is not registered on
			// subsites, so don't show a link that would 404. Show it only where
			// the menu actually exists.
			if ( is_multisite() && self::get_ms_mode() === 'network' && ! is_network_admin() ) {
				return $links;
			}

			$settings_link = '<a href="' . esc_url( self::get_admin_page_url( 'footnotes-made-easy' ) ) . '" style="font-weight: 700; color: #534AB7;">' . __( 'Dashboard', 'footnotes-made-easy' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		
		return $links;
	}

	/**
	 * Dashboard link on the network admin plugins list.
	 * Points super admins to the network-admin Dashboard.
	 *
	 * @since 3.2.0
	 *
	 * @param array  $links Current action links.
	 * @param string $file  Plugin file in use.
	 * @return array Links, with the network Dashboard link added.
	 */
	function add_network_settings_link( $links, $file ) {

		if ( plugin_basename( __FILE__ ) === $file && is_super_admin() ) {
			$settings_link = '<a href="' . esc_url( network_admin_url( 'admin.php?page=footnotes-made-easy' ) ) . '" style="font-weight: 700; color: #534AB7;">' . __( 'Dashboard', 'footnotes-made-easy' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	* Options Page
	*
	* Get the options and display the page
	*
	* @since	1.0
	*/


	/**
	 * Permissions (Subsites) page — network admin only.
	 */
	function footnotes_options_page() {

		$this->current_options = get_option( 'swas_footnote_options' );
		// Backfill any keys that may be missing from older saved option sets
		foreach ( $this->default_options as $key => $value ) {
			if ( ! array_key_exists( $key, $this->current_options ) ) {
				$this->current_options[ $key ] = $value;
			}
		}
		// Do not pre-encode options here. Template functions (esc_textarea, esc_attr) handle
		// escaping at the point of output; running htmlentities() here causes double-encoding
		// of HTML fields like pre_footnotes / post_footnotes.
		include( dirname( __FILE__ ) . '/includes/settings.php' );
	}

	/**
	* Remove Help Tabs
	*
	* Removes the WP contextual help tab and screen options
	* from our settings page for a cleaner UI.
	*
	* @since 3.1.1
	*/
	function remove_help_tabs() {
		$screen = get_current_screen();
		if ( $screen ) {
			$screen->remove_help_tabs();
		}
	}


	/**
	* Add to Admin
	*
	* Add the options page to the admin menu
	*
	* @since	1.0
	*/


    /**
     * Multisite helpers
     */

    /**
     * Get the multisite mode.
     * Returns 'network' or 'override' (default 'override' = no network management).
     */
    public static function get_ms_mode(): string {
        if ( ! is_multisite() ) return 'override';
        return get_site_option( 'fme_ms_mode', 'override' );
    }

    /**
     * Is the current user a network super admin?
     */
    public static function is_network_admin(): bool {
        return is_multisite() && is_super_admin();
    }

    /**
     * Get the correct URL for a plugin admin page, accounting for network admin.
     * In the network admin, uses network_admin_url(); otherwise admin_url().
     *
     * @param string    $page         The page slug (e.g. 'footnotes-pro').
     * @param bool|null $force_network Optional. Force network (true) or subsite (false) context.
     *                                 When null, auto-detects via is_network_admin().
     * @return string The full admin URL.
     */
    public static function get_admin_page_url( string $page, ?bool $force_network = null ): string {
        $path       = 'admin.php?page=' . $page;
        $is_network = ( null !== $force_network ) ? $force_network : is_network_admin();
        return $is_network ? network_admin_url( $path ) : admin_url( $path );
    }

    /**
     * Get options — respects multisite network settings.
     * In network-managed mode, returns network options.
     * In override mode, returns subsite options with network defaults as fallback.
     */
    public function get_options(): array {
        if ( ! is_multisite() ) {
            return $this->current_options;
        }
        $mode = self::get_ms_mode();
        if ( $mode === 'network' ) {
            $network_opts = get_site_option( 'fme_network_options', [] );
            return ! empty( $network_opts ) ? $network_opts : $this->current_options;
        }
        // Override mode — subsite opts with network defaults as fallback
        $network_defaults = get_site_option( 'fme_network_options', [] );
        $subsite_opts     = get_option( 'swas_footnote_options', [] );
        if ( empty( $network_defaults ) ) {
            return ! empty( $subsite_opts ) ? $subsite_opts : $this->current_options;
        }
        // Merge: subsite values override network defaults
        return array_merge( $network_defaults, ! empty( $subsite_opts ) ? $subsite_opts : [] );
    }

    /**
     * Should the current user see the plugin menu?
     * In network-managed mode, hide from non-super-admins.
     */
    public static function user_can_see_menu(): bool {
        if ( ! is_multisite() ) return current_user_can( 'manage_options' );
        // In network-managed mode, hide menu on ALL regular admin pages (subsites AND main site)
        // Only show in the network admin itself
        if ( self::get_ms_mode() === 'network' && ! is_network_admin() ) return false;
        return current_user_can( 'manage_options' );
    }

    /**
     * Should upgrade/upsell cards show for the current user?
     */
    public static function show_upsell(): bool {
        if ( ! is_multisite() ) return true;
        // Only show upsell in the network admin, to super admins
        if ( is_network_admin() && is_super_admin() ) return true;
        return false;
    }

	function add_options_page() {

		// In network-managed mode, hide menu from non-super-admins
		if ( ! self::user_can_see_menu() ) {
			return;
		}

		global $footnotes_hook;

		// Standalone top-level menu item, positioned after Posts (Posts = 5)
		$footnotes_hook = add_menu_page(
			__( 'Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Footnotes', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' ),
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjQwIDM1IDQ4IDYwIj4KPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTYwLjY1NCwzNi42NTFjMS4zNzktMS4zNTQsMy41ODMtMS43MDgsNS4zNDktMC45NjJjMS4yNzIsMC41NjUsMi4zMzgsMS42NzIsMi43MTEsMy4wMzRjMC40NDgsMS4zMTUsMC4yMDMsMi43MjMsMC4wNDYsNC4wNjdjLTAuMjQyLDEuNjQ3LTAuNDUzLDMuMjk4LTAuNzI0LDQuOTRjLTAuMDYzLDAuNTU2LTAuMTQ4LDEuMTEtMC4yNzQsMS42NTdjLTAuMTgsMS40MjEtMC40NywyLjgyNi0wLjY4Myw0LjI0MmMtMC4wNDUsMC4yNzQtMC4wNzcsMC41NTEtMC4xMzcsMC44MjNjLTAuMDQ3LDAuMjcxLTAuMDg2LDAuNTQ1LTAuMTQ3LDAuODExYy0wLjA5NywwLjc0NS0wLjI1MSwxLjQ3OC0wLjQsMi4yMWMtMC4xNzMsMS4xNTItMC41MDUsMi4yNzctMC42MzQsMy40MzhjMi44MzQtMi43MDcsNS45MDgtNS4xNDMsOC45NDItNy42MThjMi4zMzUtMS44MzMsNC42NDctMy42OTYsNy4wNjMtNS40MjVjMS4wMjYtMC43MjIsMi4zMTMtMS4xNSwzLjU3Mi0wLjkxN2MxLjg1NSwwLjI2MSwzLjQzNSwxLjc0NywzLjg5LDMuNTUxYzAuNDA5LDEuNTY1LDAuMDExLDMuMzYxLTEuMTQ1LDQuNTJjLTAuNjQ2LDAuNzY5LTEuNTY2LDEuMjEtMi40NTcsMS42MjZjLTIuNDY1LDEuMTM2LTQuOTgsMi4xNjItNy40ODEsMy4yMjZjLTIuMzc1LDAuOTQxLTQuNzI0LDEuOTUzLTcuMTQxLDIuNzgxYy0xLjIsMC40NjUtMi40MjUsMC44NjctMy42MDMsMS4zOWMzLjE3NiwxLjAzNCw2LjMxNywyLjE4Niw5LjQwMywzLjQ2NmMwLjIyOSwwLjA5MSwwLjQ1OSwwLjE4MywwLjY4OCwwLjI3NWMwLjUxNSwwLjIxLDEuMDMsMC40MTMsMS41NDQsMC42MjRjMC4zMTksMC4xNDQsMC42NCwwLjI4MywwLjk2NSwwLjQxNmMwLjI2LDAuMTE0LDAuNTIyLDAuMjIyLDAuNzgzLDAuMzM2YzAuNTE0LDAuMjA1LDEuMDI2LDAuNDIxLDEuNTQsMC42MjljMC4zMTksMC4xNCwwLjY0LDAuMjc4LDAuOTY2LDAuNDExYzAuMzU0LDAuMTU2LDAuNzEsMC4zMTUsMS4wNjQsMC40NzljMC45NTQsMC40MzEsMS45NDYsMC44LDIuODE4LDEuMzg5YzEuMjEyLDAuODE1LDIuMDksMi4xNjYsMi4xOTYsMy42NDFjMC4xMDQsMS4yNTUtMC4yNDcsMi41NjMtMS4wNTgsMy41MzljLTAuOTU3LDEuMjkzLTIuNjQyLDEuOTczLTQuMjI5LDEuNzU0Yy0xLjM1NC0wLjExLTIuNDYtMC45NzItMy41MTItMS43NTRjLTUuMTE4LTMuODE5LTEwLjA1Mi03Ljg4Mi0xNC44MjItMTIuMTIyYzAuNDk2LDIuNjM2LDEuMDgzLDUuMjYsMS40NjUsNy45MTljMC4wNjcsMC4zMTMsMC4wOTMsMC42MzIsMC4xNCwwLjk1YzAuMTM5LDAuODIzLDAuMjc1LDEuNjQ4LDAuNDA4LDIuNDc0YzAuMzYyLDIuMDk4LDAuNjY0LDQuMjA1LDAuOTU4LDYuMzE0YzAuMDg5LDAuNDAxLDAuMTAxLDAuODE1LDAuMTM2LDEuMjI1YzAuMjI2LDEuNDI0LDAuMjExLDIuOTc2LTAuNTUsNC4yNDljLTAuNzM3LDEuMjQtMi4wMzQsMi4xNzMtMy40NzcsMi4zNzNjLTEuNDU4LDAuMjI4LTMuMDQ0LTAuMTYxLTQuMTI1LTEuMTkyYy0xLjI0OC0xLjA0Ny0xLjc2LTIuNzY1LTEuNjIzLTQuMzUyYzAuMTU5LTEuNjQ0LDAuMzY3LTMuMjg0LDAuNjI0LTQuOTE1YzAuMTM0LTEuMTExLDAuMzY2LTIuMjA5LDAuNTExLTMuMzE3YzAuMjY0LTEuMzY2LDAuNDU0LTIuNzQ0LDAuNjg3LTQuMTE1YzAuMDY4LTAuMzA4LDAuMTA1LTAuNjIyLDAuMTcyLTAuOTI4YzAuMTYzLTEuMDEsMC4zNzItMi4wMTEsMC41NS0zLjAyYzAuMTE0LTAuNzM0LDAuMjcyLTEuNDYzLDAuNDAzLTIuMTk2YzAuMDQzLTAuMjMxLDAuMDc5LTAuNDY2LDAuMTQyLTAuNjkyYzAuMDM3LTAuMjQ4LDAuMDY2LTAuNDk1LDAuMDkzLTAuNzRjLTQuNTc4LDQuMTAzLTkuMzU4LDcuOTcyLTE0LjIwNSwxMS43NTFjLTEuMDkxLDAuODIyLTIuMjA0LDEuNzI2LTMuNTc1LDIuMDExYy0xLjAyNywwLjIwNS0yLjE0NSwwLjE1My0zLjA4NC0wLjM0NWMtMS40MTUtMC42NzUtMi40NjQtMi4wODMtMi42NjctMy42NDJjLTAuMjcxLTEuNDg4LDAuMjUtMy4wNzIsMS4yODItNC4xNjhjMS4wMDUtMS4wODQsMi40NDUtMS41MzcsMy43NDYtMi4xNDVjMC4zMi0wLjE0LDAuNjM5LTAuMjgsMC45NjEtMC40MTJjMC4zMjgtMC4xMzEsMC42NS0wLjI3MiwwLjk3MS0wLjQxOGMwLjIyNi0wLjA5MywwLjQ0OS0wLjE4NCwwLjY3Mi0wLjI3NmMwLjI4Ni0wLjExOSwwLjU2OS0wLjI0NCwwLjg1Ny0wLjM1NGMwLjI2NC0wLjExNCwwLjUyOS0wLjIyMiwwLjc5Mi0wLjMzYzAuMjI5LTAuMDkyLDAuNDU2LTAuMTg0LDAuNjg0LTAuMjc4YzAuMjg3LTAuMTE2LDAuNTcyLTAuMjM3LDAuODU1LTAuMzU5YzMuNjQ5LTEuNDQ3LDcuMjg5LTIuOTMzLDExLjAxNy00LjE2OGMtMy4wOTQtMS4xNjctNi4yMDctMi4yODItOS4yNjctMy41MzRjLTIuNTYtMS4wMS01LjA4My0yLjEwNi03LjYwNy0zLjIwNWMtMS4yMDQtMC41OTMtMi41MjktMS4wMjgtMy41MTktMS45NzRjLTAuNzc1LTAuNjk2LTEuMjc4LTEuNjU3LTEuNDY5LTIuNjc4Yy0wLjUzNS0yLjUxLDEuMzAzLTUuMjA5LDMuODE5LTUuNjY4YzEuMzc1LTAuMjc2LDIuNzk2LDAuMTk4LDMuOTExLDEuMDAzYzIuMTU4LDEuNTEyLDQuMjAzLDMuMTc5LDYuMjYsNC44MjhjMy4yMzksMi42MzksNi40NTIsNS4zMTUsOS41NSw4LjExN2MtMC4wNTMtMC40MjQtMC4xNTItMC44NDEtMC4yMDktMS4yNjNjLTAuMjAzLTAuOTEyLTAuNDE3LTEuODI0LTAuNTQ5LTIuNzQ5Yy0wLjA2OS0wLjMwNi0wLjEwNy0wLjYxNy0wLjE3My0wLjkyM2MtMC4wNDQtMC4yMy0wLjA4Ny0wLjQ2MS0wLjEzOS0wLjY5Yy0wLjExNy0wLjc0My0wLjI1NS0xLjQ4Mi0wLjM3Ny0yLjIyNWMtMC4wNjMtMC4yNzEtMC4wOTYtMC41NDgtMC4xMzUtMC44MjJjLTAuMDc4LTAuMzUtMC4xMDEtMC43MS0wLjE3Ny0xLjA2Yy0wLjA1Ny0wLjUxNy0wLjE2NC0xLjAyNi0wLjIzNi0xLjU0Yy0wLjE1NS0wLjkwOS0wLjMyNi0xLjgxOS0wLjQxNi0yLjczOGMtMC4wNzctMC4zNTMtMC4wOTktMC43MTUtMC4xNzUtMS4wN2MtMC4wNjQtMC42OTgtMC4xODYtMS4zODktMC4yMy0yLjA4OGMtMC4wODEtMC4zNjItMC4wOTktMC43MzMtMC4xMzgtMS4xYy0wLjA5Ny0wLjQwOS0wLjA5NS0wLjgzMi0wLjEzMy0xLjI0N2MtMC4xMTItMC41MzctMC4wNjktMS4wODgtMC4wNTQtMS42MzFDNTkuMjg1LDM4LjYwOCw1OS43NzIsMzcuNDYxLDYwLjY1NCwzNi42NTF6Ii8+Cjwvc3ZnPg==',
			6 // just after Posts (5) and before Media (10)
		);

		// Dashboard submenu (mirrors the top-level entry)
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Dashboard', 'footnotes-made-easy' ),
			__( 'Dashboard', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' )
		);

		// Settings submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Settings', 'footnotes-made-easy' ),
			__( 'Footnotes Settings', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-settings',
			array( $this, 'footnotes_options_page' )
		);

		add_action( 'load-' . $footnotes_hook, array( $this, 'remove_help_tabs' ) );

		// Tools and Help register at priority 30 so Pro (priority 20) inserts Library between Settings and Tools
		add_action( 'admin_menu', array( $this, 'add_secondary_menu_pages' ), 30 );
	}

	/**
	 * Register the full plugin menu in the network admin.
	 * Mirrors the regular admin menu plus a Permissions (Subsites) item.
	 */
	function add_network_menu() {
		if ( ! is_multisite() || ! is_super_admin() ) return;

		add_menu_page(
			__( 'Footnotes Made Easy', 'footnotes-made-easy' ),
			__( 'Footnotes', 'footnotes-made-easy' ),
			'manage_network_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' ),
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjQwIDM1IDQ4IDYwIj4KPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTYwLjY1NCwzNi42NTFjMS4zNzktMS4zNTQsMy41ODMtMS43MDgsNS4zNDktMC45NjJjMS4yNzIsMC41NjUsMi4zMzgsMS42NzIsMi43MTEsMy4wMzRjMC40NDgsMS4zMTUsMC4yMDMsMi43MjMsMC4wNDYsNC4wNjdjLTAuMjQyLDEuNjQ3LTAuNDUzLDMuMjk4LTAuNzI0LDQuOTRjLTAuMDYzLDAuNTU2LTAuMTQ4LDEuMTEtMC4yNzQsMS42NTdjLTAuMTgsMS40MjEtMC40NywyLjgyNi0wLjY4Myw0LjI0MmMtMC4wNDUsMC4yNzQtMC4wNzcsMC41NTEtMC4xMzcsMC44MjNjLTAuMDQ3LDAuMjcxLTAuMDg2LDAuNTQ1LTAuMTQ3LDAuODExYy0wLjA5NywwLjc0NS0wLjI1MSwxLjQ3OC0wLjQsMi4yMWMtMC4xNzMsMS4xNTItMC41MDUsMi4yNzctMC42MzQsMy40MzhjMi44MzQtMi43MDcsNS45MDgtNS4xNDMsOC45NDItNy42MThjMi4zMzUtMS44MzMsNC42NDctMy42OTYsNy4wNjMtNS40MjVjMS4wMjYtMC43MjIsMi4zMTMtMS4xNSwzLjU3Mi0wLjkxN2MxLjg1NSwwLjI2MSwzLjQzNSwxLjc0NywzLjg5LDMuNTUxYzAuNDA5LDEuNTY1LDAuMDExLDMuMzYxLTEuMTQ1LDQuNTJjLTAuNjQ2LDAuNzY5LTEuNTY2LDEuMjEtMi40NTcsMS42MjZjLTIuNDY1LDEuMTM2LTQuOTgsMi4xNjItNy40ODEsMy4yMjZjLTIuMzc1LDAuOTQxLTQuNzI0LDEuOTUzLTcuMTQxLDIuNzgxYy0xLjIsMC40NjUtMi40MjUsMC44NjctMy42MDMsMS4zOWMzLjE3NiwxLjAzNCw2LjMxNywyLjE4Niw5LjQwMywzLjQ2NmMwLjIyOSwwLjA5MSwwLjQ1OSwwLjE4MywwLjY4OCwwLjI3NWMwLjUxNSwwLjIxLDEuMDMsMC40MTMsMS41NDQsMC42MjRjMC4zMTksMC4xNDQsMC42NCwwLjI4MywwLjk2NSwwLjQxNmMwLjI2LDAuMTE0LDAuNTIyLDAuMjIyLDAuNzgzLDAuMzM2YzAuNTE0LDAuMjA1LDEuMDI2LDAuNDIxLDEuNTQsMC42MjljMC4zMTksMC4xNCwwLjY0LDAuMjc4LDAuOTY2LDAuNDExYzAuMzU0LDAuMTU2LDAuNzEsMC4zMTUsMS4wNjQsMC40NzljMC45NTQsMC40MzEsMS45NDYsMC44LDIuODE4LDEuMzg5YzEuMjEyLDAuODE1LDIuMDksMi4xNjYsMi4xOTYsMy42NDFjMC4xMDQsMS4yNTUtMC4yNDcsMi41NjMtMS4wNTgsMy41MzljLTAuOTU3LDEuMjkzLTIuNjQyLDEuOTczLTQuMjI5LDEuNzU0Yy0xLjM1NC0wLjExLTIuNDYtMC45NzItMy41MTItMS43NTRjLTUuMTE4LTMuODE5LTEwLjA1Mi03Ljg4Mi0xNC44MjItMTIuMTIyYzAuNDk2LDIuNjM2LDEuMDgzLDUuMjYsMS40NjUsNy45MTljMC4wNjcsMC4zMTMsMC4wOTMsMC42MzIsMC4xNCwwLjk1YzAuMTM5LDAuODIzLDAuMjc1LDEuNjQ4LDAuNDA4LDIuNDc0YzAuMzYyLDIuMDk4LDAuNjY0LDQuMjA1LDAuOTU4LDYuMzE0YzAuMDg5LDAuNDAxLDAuMTAxLDAuODE1LDAuMTM2LDEuMjI1YzAuMjI2LDEuNDI0LDAuMjExLDIuOTc2LTAuNTUsNC4yNDljLTAuNzM3LDEuMjQtMi4wMzQsMi4xNzMtMy40NzcsMi4zNzNjLTEuNDU4LDAuMjI4LTMuMDQ0LTAuMTYxLTQuMTI1LTEuMTkyYy0xLjI0OC0xLjA0Ny0xLjc2LTIuNzY1LTEuNjIzLTQuMzUyYzAuMTU5LTEuNjQ0LDAuMzY3LTMuMjg0LDAuNjI0LTQuOTE1YzAuMTM0LTEuMTExLDAuMzY2LTIuMjA5LDAuNTExLTMuMzE3YzAuMjY0LTEuMzY2LDAuNDU0LTIuNzQ0LDAuNjg3LTQuMTE1YzAuMDY4LTAuMzA4LDAuMTA1LTAuNjIyLDAuMTcyLTAuOTI4YzAuMTYzLTEuMDEsMC4zNzItMi4wMTEsMC41NS0zLjAyYzAuMTE0LTAuNzM0LDAuMjcyLTEuNDYzLDAuNDAzLTIuMTk2YzAuMDQzLTAuMjMxLDAuMDc5LTAuNDY2LDAuMTQyLTAuNjkyYzAuMDM3LTAuMjQ4LDAuMDY2LTAuNDk1LDAuMDkzLTAuNzRjLTQuNTc4LDQuMTAzLTkuMzU4LDcuOTcyLTE0LjIwNSwxMS43NTFjLTEuMDkxLDAuODIyLTIuMjA0LDEuNzI2LTMuNTc1LDIuMDExYy0xLjAyNywwLjIwNS0yLjE0NSwwLjE1My0zLjA4NC0wLjM0NWMtMS40MTUtMC42NzUtMi40NjQtMi4wODMtMi42NjctMy42NDJjLTAuMjcxLTEuNDg4LDAuMjUtMy4wNzIsMS4yODItNC4xNjhjMS4wMDUtMS4wODQsMi40NDUtMS41MzcsMy43NDYtMi4xNDVjMC4zMi0wLjE0LDAuNjM5LTAuMjgsMC45NjEtMC40MTJjMC4zMjgtMC4xMzEsMC42NS0wLjI3MiwwLjk3MS0wLjQxOGMwLjIyNi0wLjA5MywwLjQ0OS0wLjE4NCwwLjY3Mi0wLjI3NmMwLjI4Ni0wLjExOSwwLjU2OS0wLjI0NCwwLjg1Ny0wLjM1NGMwLjI2NC0wLjExNCwwLjUyOS0wLjIyMiwwLjc5Mi0wLjMzYzAuMjI5LTAuMDkyLDAuNDU2LTAuMTg0LDAuNjg0LTAuMjc4YzAuMjg3LTAuMTE2LDAuNTcyLTAuMjM3LDAuODU1LTAuMzU5YzMuNjQ5LTEuNDQ3LDcuMjg5LTIuOTMzLDExLjAxNy00LjE2OGMtMy4wOTQtMS4xNjctNi4yMDctMi4yODItOS4yNjctMy41MzRjLTIuNTYtMS4wMS01LjA4My0yLjEwNi03LjYwNy0zLjIwNWMtMS4yMDQtMC41OTMtMi41MjktMS4wMjgtMy41MTktMS45NzRjLTAuNzc1LTAuNjk2LTEuMjc4LTEuNjU3LTEuNDY5LTIuNjc4Yy0wLjUzNS0yLjUxLDEuMzAzLTUuMjA5LDMuODE5LTUuNjY4YzEuMzc1LTAuMjc2LDIuNzk2LDAuMTk4LDMuOTExLDEuMDAzYzIuMTU4LDEuNTEyLDQuMjAzLDMuMTc5LDYuMjYsNC44MjhjMy4yMzksMi42MzksNi40NTIsNS4zMTUsOS41NSw4LjExN2MtMC4wNTMtMC40MjQtMC4xNTItMC44NDEtMC4yMDktMS4yNjNjLTAuMjAzLTAuOTEyLTAuNDE3LTEuODI0LTAuNTQ5LTIuNzQ5Yy0wLjA2OS0wLjMwNi0wLjEwNy0wLjYxNy0wLjE3My0wLjkyM2MtMC4wNDQtMC4yMy0wLjA4Ny0wLjQ2MS0wLjEzOS0wLjY5Yy0wLjExNy0wLjc0My0wLjI1NS0xLjQ4Mi0wLjM3Ny0yLjIyNWMtMC4wNjMtMC4yNzEtMC4wOTYtMC41NDgtMC4xMzUtMC44MjJjLTAuMDc4LTAuMzUtMC4xMDEtMC43MS0wLjE3Ny0xLjA2Yy0wLjA1Ny0wLjUxNy0wLjE2NC0xLjAyNi0wLjIzNi0xLjU0Yy0wLjE1NS0wLjkwOS0wLjMyNi0xLjgxOS0wLjQxNi0yLjczOGMtMC4wNzctMC4zNTMtMC4wOTktMC43MTUtMC4xNzUtMS4wN2MtMC4wNjQtMC42OTgtMC4xODYtMS4zODktMC4yMy0yLjA4OGMtMC4wODEtMC4zNjItMC4wOTktMC43MzMtMC4xMzgtMS4xYy0wLjA5Ny0wLjQwOS0wLjA5NS0wLjgzMi0wLjEzMy0xLjI0N2MtMC4xMTItMC41MzctMC4wNjktMS4wODgtMC4wNTQtMS42MzFDNTkuMjg1LDM4LjYwOCw1OS43NzIsMzcuNDYxLDYwLjY1NCwzNi42NTF6Ii8+Cjwvc3ZnPg==',
			6
		);

		add_submenu_page(
			'footnotes-made-easy',
			__( 'Dashboard', 'footnotes-made-easy' ),
			__( 'Dashboard', 'footnotes-made-easy' ),
			'manage_network_options',
			'footnotes-made-easy',
			array( $this, 'footnotes_dashboard_page' )
		);

		add_submenu_page(
			'footnotes-made-easy',
			__( 'Settings', 'footnotes-made-easy' ),
			__( 'Footnotes Settings', 'footnotes-made-easy' ),
			'manage_network_options',
			'footnotes-settings',
			array( $this, 'footnotes_options_page' )
		);

		// Tools, Get Help registered at priority 30 so Pro (priority 20) inserts Library between Settings and Tools
		add_action( 'network_admin_menu', array( $this, 'add_network_secondary_pages' ), 30 );
	}

	/**
	 * Register Tools and Get Help in network admin at priority 30.
	 * Pro inserts Library at priority 20 — between Settings and Tools.
	 */
	function add_network_secondary_pages() {
		if ( ! is_multisite() || ! is_super_admin() ) return;

		add_submenu_page(
			'footnotes-made-easy',
			__( 'Tools', 'footnotes-made-easy' ),
			__( 'Tools', 'footnotes-made-easy' ),
			'manage_network_options',
			'footnotes-tools',
			array( $this, 'footnotes_tools_page' )
		);

		add_submenu_page(
			'footnotes-made-easy',
			__( 'Get Help', 'footnotes-made-easy' ),
			__( 'Get Help', 'footnotes-made-easy' ),
			'manage_network_options',
			'footnotes-help',
			array( $this, 'footnotes_help_page' )
		);

		// Pro Coming Soon — only shown when Pro is not installed
		if ( ! defined( 'FME_PRO_VERSION' ) && swas_wp_footnotes::show_upsell() ) {
			add_submenu_page(
				'footnotes-made-easy',
				__( 'Upgrade to Pro', 'footnotes-made-easy' ),
				'<span style="display:flex;align-items:center;gap:6px;">✦ ' . esc_html__( 'Upgrade to Pro', 'footnotes-made-easy' ) . '</span>',
				'manage_network_options',
				'footnotes-pro',
				array( $this, 'footnotes_pro_page' )
			);
		}
	}

	function add_secondary_menu_pages() {
		// Tools submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Tools', 'footnotes-made-easy' ),
			__( 'Tools', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-tools',
			array( $this, 'footnotes_tools_page' )
		);

		// Help submenu
		add_submenu_page(
			'footnotes-made-easy',
			__( 'Get Help', 'footnotes-made-easy' ),
			__( 'Get Help', 'footnotes-made-easy' ),
			'manage_options',
			'footnotes-help',
			array( $this, 'footnotes_help_page' )
		);

		// Pro Coming Soon — only shown when Pro is not installed
		if ( ! defined( 'FME_PRO_VERSION' ) && swas_wp_footnotes::show_upsell() ) {
			add_submenu_page(
				'footnotes-made-easy',
				__( 'Upgrade to Pro', 'footnotes-made-easy' ),
				'<span style="display:flex;align-items:center;gap:6px;">✦ ' . esc_html__( 'Upgrade to Pro', 'footnotes-made-easy' ) . '</span>',
				'manage_options',
				'footnotes-pro',
				array( $this, 'footnotes_pro_page' )
			);
		}

	} // end add_secondary_menu_pages()

	/**
	 * Pro Coming Soon page
	 */
	function footnotes_pro_page() {
		// The in-plugin Pro page has been retired in favour of the external
		// pricing page. Direct or same-tab hits to admin.php?page=footnotes-pro
		// are forwarded there. (The menu item and CTAs open it in a new tab via
		// target="_blank"; this redirect is the safety net for any other access.)
		wp_redirect( FME_PRO_URL ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- Intentional redirect to our external pricing page.
		exit;
	}

	/**
	* Dashboard Page
	*
	* Renders the Dashboard subpage (About tab content)
	*
	* @since 3.2.0
	*/
	function footnotes_dashboard_page() {
		$this->current_options = get_option( 'swas_footnote_options' );
		foreach ( $this->default_options as $key => $value ) {
			if ( ! array_key_exists( $key, $this->current_options ) ) {
				$this->current_options[ $key ] = $value;
			}
		}
		// Do not pre-encode options here — same reason as footnotes_options_page().
		include( dirname( __FILE__ ) . '/includes/dashboard.php' );
	}

	/**
	* Help Page
	*
	* Renders the Help subpage
	*
	* @since 3.2.0
	*/
	function footnotes_help_page() {
		include( dirname( __FILE__ ) . '/includes/help.php' );
	}

	function footnotes_tools_page() {
		include( dirname( __FILE__ ) . '/includes/tools.php' );
	}

	/**
	* Insert additional CSS
	*
	* Add additional CSS to the page for the footnotes styling
	*
	* @since	1.0
	*/

	function insert_styles(){
		$css = "ol.footnotes { color:#666666; }\nol.footnotes li { font-size:80%; }\n";
		if ( 'symbol' !== $this->current_options[ 'list_style_type' ] ) {
			$css .= 'ol.footnotes>li {list-style-type:' . esc_attr( $this->current_options[ 'list_style_type' ] ) . ';}';
		}
		// Attach the inline CSS to a self-owned, always-enqueued handle rather than
		// 'wp-block-library'. Many themes (and performance plugins) do not load
		// wp-block-library on the front end, in which case wp_add_inline_style() on
		// that handle silently outputs nothing and the footnote sizing is lost.
		wp_register_style( 'fme-footnotes-inline', false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'fme-footnotes-inline' );
		wp_add_inline_style( 'fme-footnotes-inline', $css );
	}

	/**
	* Convert number
	*
	* Convert number to a specific style
	*
	* @since	1.0
	*
	* @param	$num	string	The number to be converted
	* @param	$style	string	The style of output required
	* @param	$total	string	The total length
	* @return			string 	The converted number
	*/

	function convert_num ( $num, $style, $total ){

		switch ( $style ) {
			case 'decimal-leading-zero' :
				$width = max( 2, strlen( $total ) );
				return sprintf( "%0{$width}d", $num );
			case 'lower-roman' :
				return $this->roman( $num, 'lower' );
			case 'upper-roman' :
				return $this->roman( $num );
			case 'lower-alpha' :
				return $this->alpha( $num, 'lower' );
			case 'upper-alpha' :
				return $this->alpha( $num );
			case 'symbol' :
				$sym = '';
				for ( $i = 0; $i < $num; $i++ ) {
					$sym .= $this->current_options[ 'list_style_symbol' ];
				}
				return $sym;
		}
	}

	/**
	* Convert to a roman numeral
	*
	* Convert a provided number into a roman numeral
	*
	* @since	1.0
	*
	* @param	int		$num	The number to convert.
	* @param	string	$case	Upper or lower case.
	* @return	string			The roman numeral
	*/

	function roman( $num, $case= 'upper' ){

		$num = ( int ) $num;
		$conversion = array(
							'M' => 1000,
							'CM' => 900,
							'D' => 500,
							'CD' => 400,
							'C' => 100,
							'XC' => 90,
							'L' => 50,
							'XL' => 40,
							'X' => 10,
							'IX' => 9,
							'V' => 5,
							'IV' => 4,
							'I' => 1
							);
		$roman = '';

		foreach ( $conversion as $r => $d ){
			$roman .= str_repeat( $r, ( int )( $num / $d ) );
			$num %= $d;
		}

		return ( $case === 'lower' ) ? strtolower( $roman ) : $roman;
	}

	function alpha( $num, $case='upper' ){
		$j = 1;
		for ( $i = 'A'; $i <= 'ZZ'; $i++ ){
			if ( $j === $num ){
				if ( 'lower' === $case )
					return strtolower( $i );
				else
					return $i;
			}
			$j++;
		}

	}

	/**
	* Tooltip Scripts
	*
	* Add scripts and CSS for pretty tooltips
	*
	* @since	1.0
	*/

	function tooltip_scripts() {

		$tt_js_path  = plugin_dir_path( __FILE__ ) . 'assets/js/tooltips.min.js';
		$tt_css_path = plugin_dir_path( __FILE__ ) . 'assets/css/tooltips.min.css';

		wp_enqueue_script(
							'wp-footnotes-tooltips',
							plugins_url( 'assets/js/tooltips.min.js' , __FILE__ ),
							array(
									'jquery',
									'jquery-ui-widget',
									'jquery-ui-tooltip',
									'jquery-ui-core',
									'jquery-ui-position'
								),
							file_exists( $tt_js_path ) ? (string) filemtime( $tt_js_path ) : '3.0.8',
							true
							);

		wp_enqueue_style( 'wp-footnotes-tt-style', plugins_url( 'assets/css/tooltips.min.css' , __FILE__ ), array(), file_exists( $tt_css_path ) ? (string) filemtime( $tt_css_path ) : '3.0.8' );
	}

	/**
	 * Handle export settings.
	 * Outputs a JSON file download containing all plugin settings.
	 * Pro settings are included if Pro is active and license is valid.
	 */
	function handle_export_settings() {
		check_admin_referer( 'fme_export_settings_nonce', 'fme_export_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}

		$data = array(
			'plugin'   => 'footnotes-made-easy',
			'version'  => self::OPTIONS_VERSION,
			'exported' => gmdate( 'Y-m-d H:i:s' ),
			'settings' => array(
				'free' => get_option( 'swas_footnote_options', array() ),
			),
		);

		// Include Pro settings if Pro is active and licensed
		if ( defined( 'FME_PRO_VERSION' ) && function_exists( 'fmep_fs' ) && fmep_fs() && fmep_fs()->is_paying() ) {
			$data['settings']['pro'] = array(
				'fme_pro_citation_style'            => get_option( 'fme_pro_citation_style', 'apa' ),
				'fme_preserve_settings_on_uninstall' => get_option( 'fme_preserve_settings_on_uninstall', '0' ),
			);
		}

		$filename = 'footnotes-made-easy-settings-' . gmdate( 'Y-m-d' ) . '.json';
		$json     = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Content-Length: ' . strlen( $json ) );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Handle import settings.
	 * Reads a JSON file and writes known option keys to the database.
	 */
	function handle_import_settings() {
		check_admin_referer( 'fme_import_settings_nonce', 'fme_import_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}

		$fme_is_network = isset( $_POST['fme_network'] ) && '1' === $_POST['fme_network'];
		$redirect_base = self::get_admin_page_url( 'footnotes-tools', $fme_is_network );

		// Check file was uploaded
		if ( empty( $_FILES['fme_import_file']['tmp_name'] ) ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'No file uploaded.', 'footnotes-made-easy' ) ) );
			exit;
		}

		$file = array(
			'name'     => isset( $_FILES['fme_import_file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['fme_import_file']['name'] ) ) : '',
			'tmp_name' => isset( $_FILES['fme_import_file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['fme_import_file']['tmp_name'] ) ) : '',
		);

		// Validate file type
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( $ext !== 'json' ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'Invalid file type. Please upload a .json file.', 'footnotes-made-easy' ) ) );
			exit;
		}

		// Read and decode
		$raw  = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$data = json_decode( $raw, true );

		// Validate structure
		if ( ! $data || ( $data['plugin'] ?? '' ) !== 'footnotes-made-easy' ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'Invalid settings file. This file was not exported from Footnotes Made Easy.', 'footnotes-made-easy' ) ) );
			exit;
		}

		$imported = 0;

		// Import free settings — only write known keys
		if ( ! empty( $data['settings']['free'] ) && is_array( $data['settings']['free'] ) ) {
			$allowed_keys = array_keys( $this->default_options );
			$current      = get_option( 'swas_footnote_options', array() );
			foreach ( $allowed_keys as $key ) {
				if ( isset( $data['settings']['free'][ $key ] ) ) {
					$current[ $key ] = $data['settings']['free'][ $key ];
				}
			}
			update_option( 'swas_footnote_options', $current );
			$imported++;
		}

		// Import Pro settings — only if Pro is active and licensed
		if ( ! empty( $data['settings']['pro'] ) ) {
			if (
				defined( 'FME_PRO_VERSION' ) &&
				function_exists( 'fmep_fs' ) &&
				fmep_fs() &&
				fmep_fs()->is_paying()
			) {
				$pro         = $data['settings']['pro'];
				$allowed_pro = array( 'fme_pro_citation_style', 'fme_preserve_settings_on_uninstall' );
				foreach ( $allowed_pro as $key ) {
					if ( isset( $pro[ $key ] ) ) {
						update_option( $key, sanitize_text_field( $pro[ $key ] ) );
					}
				}
				$imported++;
			} else {
				// Pro settings present in file but Pro not active — warn the user
				wp_safe_redirect( $redirect_base . '&import=partial&import_message=' . urlencode( __( 'Free settings imported successfully. The file also contains Pro settings which were skipped — activate Footnotes Made Easy Pro to import them.', 'footnotes-made-easy' ) ) );
				exit;
			}
		}

		if ( $imported === 0 ) {
			wp_safe_redirect( $redirect_base . '&import=error&import_message=' . urlencode( __( 'No settings were found in the file.', 'footnotes-made-easy' ) ) );
			exit;
		}

		wp_safe_redirect( $redirect_base . '&import=success' );
		exit;
	}

	/**
	 * Handle reset settings form submission.
	 * Replaces all options with plugin defaults.
	 */
	function handle_reset_settings() {
		check_admin_referer( 'fme_reset_settings_nonce', 'fme_reset_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}
		update_option( 'swas_footnote_options', $this->default_options );
		// Also reset Pro citation style if Pro is active
		if ( defined( 'FME_PRO_VERSION' ) ) {
			delete_option( 'fme_pro_citation_style' );
		}
		$fme_is_network = isset( $_POST['fme_network'] ) && '1' === $_POST['fme_network'];
		wp_safe_redirect( self::get_admin_page_url( 'footnotes-tools', $fme_is_network ) . '&reset=1' );
		exit;
	}

	/**
	 * Handle preserve settings toggle form submission.
	 */
	function handle_preserve_settings() {
		check_admin_referer( 'fme_preserve_settings_nonce', 'fme_preserve_settings_nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'footnotes-made-easy' ) );
		}
		$preserve = isset( $_POST['fme_preserve_settings'] ) ? '1' : '0';
		update_option( 'fme_preserve_settings_on_uninstall', $preserve );
		$fme_is_network = isset( $_POST['fme_network'] ) && '1' === $_POST['fme_network'];
		wp_safe_redirect( self::get_admin_page_url( 'footnotes-tools', $fme_is_network ) . '&preserve_saved=1' );
		exit;
	}

	/**
	* Remove Footer Text
	*
	* Removes the default WordPress admin footer text on the plugin's settings page
	*
	* @since	3.1.4
	*
	* @param	string	$footer_text	The existing footer text
	* @return	string					Empty string on our settings page, unchanged elsewhere
	*/

	function remove_footer_text( $footer_text ) {
		if ( $this->is_our_page() ) {
			return '';
		}
		return $footer_text;
	}

	/**
	* Remove Footer Version
	*
	* Removes the default WordPress version text on the plugin's settings page
	*
	* @since	3.1.4
	*
	* @param	string	$footer_version	The existing footer version text
	* @return	string					Empty string on our settings page, unchanged elsewhere
	*/

	function remove_footer_version( $footer_version ) {
		if ( $this->is_our_page() ) {
			return '';
		}
		return $footer_version;
	}

	/**
	 * Suppress admin notices from other plugins on our pages.
	 * Fires on admin_notices before other plugins output their notices.
	 */
	function suppress_other_notices() {
		if ( ! $this->is_our_page() ) {
			return;
		}
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'network_admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}

	/**
	 * Returns true when the current request is one of our admin pages.
	 * Uses $_GET['page'] which is always reliable on admin pages.
	 */
	private function is_our_page() {
		if ( ! is_admin() ) {
			return false;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$our_pages = array(
			'footnotes-made-easy',
			'footnotes-settings',
			'footnotes-help',
			'footnotes-tools',
			'footnotes-pro',
			'fme-pro-library',
			'footnotes-made-easy-account',
		);
		return in_array( $page, $our_pages, true );
	}

}