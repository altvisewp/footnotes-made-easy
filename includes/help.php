<?php
/**
 * Help Page — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included from within class method scope.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version    = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php', false, false )['Version'] ?? '';
$fme_pro_active  = defined( 'FME_PRO_VERSION' ) && function_exists( 'fmep_fs' ) && fmep_fs() && fmep_fs()->is_paying();
$fme_show_upsell = class_exists( 'swas_wp_footnotes' ) ? swas_wp_footnotes::show_upsell() : true;
?>
<div class="wrap fme-wrap">

    <!-- Topbar -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <span class="fme-topbar-icon" aria-hidden="true">
                <img src="data:image/png;base64,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" width="16" height="16" alt="" style="width:16px;height:16px;object-fit:contain;" />
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
            <?php if ( $fme_pro_active ) : ?>
            <span class="fme-version-badge fme-version-badge--pro">PRO</span>
            <?php elseif ( $fme_version ) : ?>
            <span class="fme-version-badge">v<?php echo esc_html( $fme_version ); ?></span>
            <?php endif; ?>
        </div>
        <div class="fme-topbar-links">
            <a href="<?php echo esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-help' ) ); ?>"><?php esc_html_e( 'Help', 'footnotes-made-easy' ); ?></a>
            <a href="https://docs.altvisewp.com/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        </div>
    </div>

    <div class="fme-settings-grid">

        <!-- MAIN COLUMN -->
        <div class="fme-settings-main">

            <!-- Quick start -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Quick start', 'footnotes-made-easy' ); ?></h3>
                <p class="description"><?php esc_html_e( 'Wrap any text in double parentheses anywhere in your post or page content:', 'footnotes-made-easy' ); ?></p>
                <div class="fme-code-block">This is a sentence with a footnote <span class="fme-code-marker">((This is the footnote text))</span>.</div>
                <p class="description" style="margin-top:10px;"><?php esc_html_e( 'The plugin removes the marker from the text, adds a numbered reference in its place, and appends a footnotes list at the bottom of the post.', 'footnotes-made-easy' ); ?></p>
            </div>

            <!-- How it works -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'How it works', 'footnotes-made-easy' ); ?></h3>
                <div class="fme-help-steps">
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '1. Write content', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'Add (( )) markers anywhere in your post or page to insert a footnote.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                    <div class="fme-help-step__arrow" aria-hidden="true">→</div>
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '2. Plugin processes', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'Markers are replaced with numbered references on save.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                    <div class="fme-help-step__arrow" aria-hidden="true">→</div>
                    <div class="fme-help-step">
                        <div class="fme-help-step__icon" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="fme-help-step__title"><?php esc_html_e( '3. Output rendered', 'footnotes-made-easy' ); ?></p>
                            <p class="fme-help-step__desc"><?php esc_html_e( 'A footnotes list appears at the bottom of the post.', 'footnotes-made-easy' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings overview -->
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Settings overview', 'footnotes-made-easy' ); ?></h3>
                <table class="fme-form-table">
                    <?php
                    $fme_tabs = [
                        [ 'label' => __( 'Display', 'footnotes-made-easy' ),    'desc' => __( 'Control how footnote markers look — numbering style, superscript, brackets, and the footnotes list label.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Behaviour', 'footnotes-made-easy' ),  'desc' => __( 'Set how footnotes behave — tooltips, back-links, and whether to combine identical footnotes.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Suppress', 'footnotes-made-easy' ),   'desc' => __( 'Choose where footnotes should not appear — home page, archives, search results, and more.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Advanced', 'footnotes-made-easy' ),   'desc' => __( 'Change opening and closing delimiters, and control plugin priority in the content filter.', 'footnotes-made-easy' ), 'pro' => false ],
                        [ 'label' => __( 'Citations', 'footnotes-made-easy' ),  'desc' => __( 'Format footnotes as structured citations in APA, MLA, or Chicago style. Set the default citation style site-wide.', 'footnotes-made-easy' ), 'pro' => true ],
                    ];
                    foreach ( $fme_tabs as $fme_tab ) :
                        // Hide Citations row on subsites where upsell is not shown
                        if ( $fme_tab['pro'] && ! $fme_show_upsell && ! $fme_pro_active ) continue;
                    ?>
                    <tr>
                        <th style="width:140px;">
                            <span class="fme-help-tab-badge">
                                <?php echo esc_html( $fme_tab['label'] ); ?>
                                <?php if ( $fme_tab['pro'] && ! $fme_pro_active ) : ?>
                                <span class="fme-badge-pro">PRO</span>
                                <?php endif; ?>
                            </span>
                        </th>
                        <td><p class="description" style="margin:0;"><?php echo esc_html( $fme_tab['desc'] ); ?></p></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Pro features -->
            <?php if ( defined( 'FME_PRO_VERSION' ) && ! $fme_pro_active && $fme_show_upsell ) : ?>
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Pro features', 'footnotes-made-easy' ); ?></h3>
                <div class="fme-help-pro-grid">
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 13V5a2 2 00-2-2H4a2 2 00-2 2v8a2 2 002 2h3l3 3 3-3h3a2 2 002-2zM5 7a1 1 011-1h8a1 1 110 2H6a1 1 01-1-1zm1 3a1 1 100 2h3a1 1 100-2H6z" clip-rule="evenodd"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Citations', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'APA, MLA, and Chicago. 10 source types. Auto-fetch metadata from DOI or ISBN.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Library', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Save footnotes once and reuse them across all posts in seconds.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M3 4a1 1 011-1h12a1 1 011 1v2a1 1 01-1 1H4a1 1 01-1-1V4zM3 10a1 1 011-1h6a1 1 011 1v6a1 1 01-1 1H4a1 1 01-1-1v-6zM14 9a1 1 00-1 1v6a1 1 001 1h2a1 1 001-1v-6a1 1 00-1-1h-2z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Gutenberg panel', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Manage all footnotes from the editor sidebar without leaving the post.', 'footnotes-made-easy' ); ?></p>
                    </div>
                </div>
                <a href="#" class="button button-primary fme-help-pro-cta activate-license-trigger footnotes-made-easy">
                    <?php esc_html_e( 'Activate Footnotes Made Easy Pro', 'footnotes-made-easy' ); ?> →
                </a>
            </div>

            <?php elseif ( ! $fme_pro_active && $fme_show_upsell ) : ?>
            <div class="fme-section">
                <h3 class="fme-section-label"><?php esc_html_e( 'Pro features', 'footnotes-made-easy' ); ?></h3>
                <div class="fme-help-pro-grid">
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Citations', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'APA, MLA, and Chicago. 10 source types. Auto-fetch metadata from DOI or ISBN.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Library', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Save footnotes once and reuse them across all posts in seconds.', 'footnotes-made-easy' ); ?></p>
                    </div>
                    <div class="fme-help-pro-card">
                        <svg class="fme-help-pro-card__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                        <p class="fme-help-pro-card__title"><?php esc_html_e( 'Gutenberg panel', 'footnotes-made-easy' ); ?></p>
                        <p class="fme-help-pro-card__desc"><?php esc_html_e( 'Manage all footnotes from the editor sidebar without leaving the post.', 'footnotes-made-easy' ); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-pro' ) ); ?>" class="button button-primary fme-help-pro-cta">
                    <?php esc_html_e( 'Upgrade to Footnotes Made Easy Pro', 'footnotes-made-easy' ); ?> →
                </a>
            </div>
            <?php endif; ?>

        </div><!-- /.fme-settings-main -->

        <!-- SIDEBAR -->
        <aside class="fme-settings-sidebar">

            <!-- Get help card -->
            <div class="fme-help-links-card">
                <div class="fme-help-links-card__head">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <h3><?php esc_html_e( 'Help &amp; resources', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-quicklinks">
                    <a href="https://docs.altvisewp.com/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Documentation', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="https://wordpress.org/support/plugin/footnotes-made-easy/" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Support forum', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php
                    // Report a bug routes to priority support for active Pro license
                    // holders, otherwise to the public GitHub issues tracker.
                    $fme_bug_url = $fme_pro_active
                        ? 'https://altvisewp.com/account/#!/support'
                        : 'https://github.com/altvisewp/footnotes-made-easy/issues';
                    ?>
                    <a href="<?php echo esc_url( $fme_bug_url ); ?>" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Report a bug', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="https://docs.altvisewp.com/footnotes-made-easy/faq" target="_blank" rel="noopener noreferrer" class="fme-quicklink-row">
                        <span><?php esc_html_e( 'Frequently asked questions', 'footnotes-made-easy' ); ?></span>
                        <svg viewBox="0 0 12 12" fill="none"><path d="M2.5 6h7M7 3.5l2.5 2.5L7 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            <?php include dirname( __FILE__ ) . '/sidebar.php'; ?>

        </aside>

    </div><!-- /.fme-settings-grid -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->