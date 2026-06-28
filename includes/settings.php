<?php
/**
 * Settings Page — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   1.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included from within class method scope.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php' )['Version'] ?? ''; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap fme-wrap">

    <!-- ── Top bar ──────────────────────────────────────────── -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <span class="fme-topbar-icon" aria-hidden="true">
                <img src="data:image/png;base64,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" width="16" height="16" alt="" style="width:16px;height:16px;object-fit:contain;" />
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span>
            <?php if ( defined( 'FME_PRO_VERSION' ) && function_exists( 'fmep_fs' ) && fmep_fs() && fmep_fs()->is_paying() ) : ?>
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

    <!-- ── Saved notice ─────────────────────────────────────── -->
    <?php if ( ! empty( $_POST['save_options'] ) && ! empty( $_POST['footnotes_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['footnotes_nonce'] ) ), 'footnotes-nonce' ) ) : ?>
    <?php do_action( 'fme_settings_saved' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- fme_ is the plugin prefix. ?>
    <div class="fme-notice fme-notice-success fme-notice-autodismiss" id="fme-saved-notice">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor"><path d="M7 0a7 7 0 100 14A7 7 0 007 0zm3.293 4.793l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L5.586 6.672l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
        <?php esc_html_e( 'Settings saved successfully.', 'footnotes-made-easy' ); ?>
        <button type="button" class="fme-notice-close" aria-label="<?php esc_attr_e( 'Dismiss', 'footnotes-made-easy' ); ?>">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>
    <?php endif; ?>

    <!-- ── Tab nav (CheckoutPress style) ────────────────────── -->
    <nav class="fme-inner-tabs" id="fme-tabs-nav">
        <button type="button" class="fme-inner-tab fme-active" data-tab="display"><?php esc_html_e( 'Display', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="behaviour"><?php esc_html_e( 'Behaviour', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="suppress"><?php esc_html_e( 'Suppress', 'footnotes-made-easy' ); ?></button>
        <button type="button" class="fme-inner-tab" data-tab="advanced"><?php esc_html_e( 'Advanced', 'footnotes-made-easy' ); ?></button>
        <?php
        $fme_show_citations_tab = ! is_multisite() || is_network_admin() || ( class_exists( 'swas_wp_footnotes' ) && swas_wp_footnotes::show_upsell() ) || defined( 'FME_PRO_VERSION' );
        if ( $fme_show_citations_tab ) :
            if ( defined( 'FME_PRO_VERSION' ) ) : ?>
        <button type="button" class="fme-inner-tab fme-inner-tab--pro" data-tab="citations">
            <?php esc_html_e( 'Citations', 'footnotes-made-easy' ); ?>
            <?php if ( ! ( function_exists( 'fmep_fs' ) && fmep_fs() && fmep_fs()->is_paying() ) ) : ?>
            <span class="fme-pro-tab-badge">PRO</span>
            <?php endif; ?>
        </button>
        <?php else : ?>
        <a href="<?php echo esc_url( FME_PRO_URL ); ?>" target="_blank" rel="noopener noreferrer"
           class="fme-inner-tab fme-inner-tab--pro fme-inner-tab--link">
            <?php esc_html_e( 'Citations', 'footnotes-made-easy' ); ?>
            <span class="fme-pro-tab-badge">PRO</span>
        </a>
        <?php endif; ?>
        <?php endif; ?>
    </nav>

    <!-- ── Two-column layout ────────────────────────────────── -->
    <div class="fme-settings-grid">

        <!-- MAIN COLUMN -->
        <div class="fme-settings-main">
            <form method="post" id="fme-settings-form">
                <?php wp_nonce_field( 'footnotes-nonce', 'footnotes_nonce' ); ?>
                <input type="hidden" name="save_footnotes_made_easy_options" value="1">
                <input type="hidden" name="fme_active_tab" id="fme-active-tab-input" value="<?php echo esc_attr( isset( $_POST['fme_active_tab'] ) ? sanitize_key( $_POST['fme_active_tab'] ) : 'display' ); ?>">

                <!-- ══ DISPLAY ══════════════════════════════════ -->
                <div id="fme-panel-display" class="fme-tab-panel">

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Footnote identifier', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Identifier format', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <div class="fme-inline-inputs">
                                        <input type="text" name="pre_identifier" value="<?php echo esc_attr( $this->current_options['pre_identifier'] ); ?>" placeholder="[" />
                                        <input type="text" name="inner_pre_identifier" value="<?php echo esc_attr( $this->current_options['inner_pre_identifier'] ); ?>" placeholder="(" />
                                        <select name="list_style_type">
                                            <?php foreach ( $this->styles as $fme_key => $fme_val ) : ?>
                                            <option value="<?php echo esc_attr( $fme_key ); ?>" <?php selected( $this->current_options['list_style_type'], $fme_key ); ?>><?php echo esc_html( $fme_val ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="inner_post_identifier" value="<?php echo esc_attr( $this->current_options['inner_post_identifier'] ); ?>" placeholder=")" />
                                        <input type="text" name="post_identifier" value="<?php echo esc_attr( $this->current_options['post_identifier'] ); ?>" placeholder="]" />
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Defines how the link to the footnote is displayed. The outer text will not be linked.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Symbol', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <input type="text" name="list_style_symbol" class="fme-input-md" value="<?php echo esc_attr( $this->current_options['list_style_symbol'] ); ?>" />
                                    <p class="description"><?php esc_html_e( 'Used only when symbol is chosen as the list style.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Superscript', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <label class="fme-toggle"><input type="checkbox" name="superscript" <?php checked( $this->current_options['superscript'], true ); ?> /><span class="fme-toggle-slider"></span></label>
                                    <p class="description"><?php esc_html_e( 'Show identifier as superscript in post content.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Back-links', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Back-link style', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <div class="fme-inline-inputs">
                                        <input type="text" name="pre_backlink" value="<?php echo esc_attr( $this->current_options['pre_backlink'] ); ?>" placeholder="[" />
                                        <input type="text" name="backlink" class="fme-input-sm" value="<?php echo esc_attr( $this->current_options['backlink'] ); ?>" placeholder="↩" />
                                        <input type="text" name="post_backlink" value="<?php echo esc_attr( $this->current_options['post_backlink'] ); ?>" placeholder="]" />
                                    </div>
                                    <p class="description"><?php /* translators: %s: example back-link character (↩) */ echo esc_html( sprintf( __( 'Affects how back-links after each footnote look. A good character is %s. Leave all blank to remove back-links.', 'footnotes-made-easy' ), '↩' ) ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Back-link position', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <label style="display:inline-flex;align-items:center;gap:0.4em;margin-right:1.2em;">
                                        <input type="radio" name="backlink_position" value="end"
                                               <?php checked( ( $this->current_options['backlink_position'] ?? 'end' ), 'end' ); ?> />
                                        <?php esc_html_e( 'End of footnote', 'footnotes-made-easy' ); ?>
                                    </label>
                                    <label style="display:inline-flex;align-items:center;gap:0.4em;">
                                        <input type="radio" name="backlink_position" value="start"
                                               <?php checked( ( $this->current_options['backlink_position'] ?? 'end' ), 'start' ); ?> />
                                        <?php esc_html_e( 'Beginning of footnote', 'footnotes-made-easy' ); ?>
                                    </label>
                                    <p class="description"><?php esc_html_e( 'Where the back-link appears relative to each footnote\'s text.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Footnotes header &amp; footer', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Header text', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <textarea name="pre_footnotes" rows="3"><?php echo esc_textarea( $this->current_options['pre_footnotes'] ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Content displayed before the footnotes at the bottom of the post. Accepts HTML, including heading tags — e.g. <h3>References</h3> for a styled heading.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Footer text', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <textarea name="post_footnotes" rows="3"><?php echo esc_textarea( $this->current_options['post_footnotes'] ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Content displayed after the footnotes at the bottom of the post. Accepts HTML, including heading tags, paragraphs, and links.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Tooltips', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Pretty tooltips', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <label class="fme-toggle"><input type="checkbox" name="pretty_tooltips" id="pretty_tooltips" <?php checked( $this->current_options['pretty_tooltips'], true ); ?> /><span class="fme-toggle-slider"></span></label>
                                    <p class="description"><?php esc_html_e( 'Uses jQuery UI to display styled tooltips on hover.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div><!-- /#fme-panel-display -->


                <!-- ══ BEHAVIOUR ════════════════════════════════ -->
                <div id="fme-panel-behaviour" class="fme-tab-panel" style="display:none">

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Footnote processing', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Combine identical footnotes', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <label class="fme-toggle"><input type="checkbox" name="combine_identical_notes" id="combine_identical_notes" <?php checked( $this->current_options['combine_identical_notes'], true ); ?> /><span class="fme-toggle-slider"></span></label>
                                    <p class="description"><?php esc_html_e( 'Identical footnotes across a post will be merged into one entry.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Execution priority', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <input type="text" name="priority" id="priority" class="fme-input-num" value="<?php echo esc_attr( $this->current_options['priority'] ); ?>" />
                                    <p class="description"><?php esc_html_e( 'Controls the order in which this plugin runs relative to others. Changing this may affect other plugins. Default is 11.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div><!-- /#fme-panel-behaviour -->


                <!-- ══ SUPPRESS ═════════════════════════════════ -->
                <div id="fme-panel-suppress" class="fme-tab-panel" style="display:none">

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Where to suppress footnotes', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <?php
                            $fme_suppress_rows = [
                                [ 'no_display_home',     __( 'Home page',                'footnotes-made-easy' ), __( 'Do not render footnotes on the site home page.',                     'footnotes-made-easy' ) ],
                                [ 'no_display_preview',  __( 'Post / page previews',     'footnotes-made-easy' ), __( 'Suppress when displaying a preview of a post or page.',              'footnotes-made-easy' ) ],
                                [ 'no_display_search',   __( 'Search results',           'footnotes-made-easy' ), __( 'Suppress footnotes on search result pages.',                         'footnotes-made-easy' ) ],
                                [ 'no_display_feed',     __( 'RSS / Atom feeds',         'footnotes-made-easy' ), __( 'Suppress footnotes in all syndication feeds.',                       'footnotes-made-easy' ) ],
                                [ 'no_display_archive',  __( 'Any kind of archive',      'footnotes-made-easy' ), __( 'Suppress on all archive page types.',                                'footnotes-made-easy' ) ],
                                [ 'no_display_category', __( 'Category archives',        'footnotes-made-easy' ), __( 'Suppress specifically on category archive pages.',                   'footnotes-made-easy' ) ],
                                [ 'no_display_date',     __( 'Date-based archives',      'footnotes-made-easy' ), __( 'Suppress on day, month, and year archive pages.',                   'footnotes-made-easy' ) ],
                            ];
                            foreach ( $fme_suppress_rows as $fme_row ) : ?>
                            <tr>
                                <th><?php echo esc_html( $fme_row[1] ); ?></th>
                                <td>
                                    <label class="fme-toggle"><input type="checkbox" name="<?php echo esc_attr( $fme_row[0] ); ?>" id="<?php echo esc_attr( $fme_row[0] ); ?>" <?php checked( $this->current_options[ $fme_row[0] ], true ); ?> /><span class="fme-toggle-slider"></span></label>
                                    <p class="description"><?php echo esc_html( $fme_row[2] ); ?></p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Exclude specific URLs', 'footnotes-made-easy' ); ?> <span class="fme-badge-new"><?php esc_html_e( 'new', 'footnotes-made-easy' ); ?></span></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Excluded URLs or paths', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <textarea name="exclude_urls" id="exclude_urls" rows="5" placeholder="/about-us/&#10;/private-page/&#10;https://yoursite.com/members/"><?php echo esc_textarea( $this->current_options['exclude_urls'] ?? '' ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Enter URLs or paths where footnotes should be completely disabled (one per line). Both full URLs and path slugs are accepted.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Exclude specific categories', 'footnotes-made-easy' ); ?> <span class="fme-badge-new"><?php esc_html_e( 'new', 'footnotes-made-easy' ); ?></span></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Excluded categories', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <textarea name="exclude_categories" id="exclude_categories" rows="5" placeholder="news&#10;tutorials&#10;42"><?php echo esc_textarea( $this->current_options['exclude_categories'] ?? '' ); ?></textarea>
                                    <p class="description"><?php esc_html_e( 'Enter category slugs or IDs where footnotes should be suppressed (one per line). Applies to individual posts belonging to these categories.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div><!-- /#fme-panel-suppress -->


                <!-- ══ ADVANCED ═════════════════════════════════ -->
                <div id="fme-panel-advanced" class="fme-tab-panel" style="display:none">

                    <div class="fme-warning-banner">
                        <svg viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <p><?php esc_html_e( 'Changing these settings will alter how footnotes are parsed. You will need to manually update all existing posts with footnotes for them to continue working correctly after saving.', 'footnotes-made-easy' ); ?></p>
                    </div>

                    <div class="fme-section">
                        <h3 class="fme-section-label"><?php esc_html_e( 'Footnote delimiters', 'footnotes-made-easy' ); ?></h3>
                        <table class="fme-form-table">
                            <tr>
                                <th><?php esc_html_e( 'Opening tag', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <input type="text" name="footnotes_open" id="footnotes_open" class="fme-input-md" value="<?php echo esc_attr( $this->current_options['footnotes_open'] ); ?>" />
                                    <p class="description"><?php esc_html_e( 'Characters that mark the beginning of a footnote in post content.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Closing tag', 'footnotes-made-easy' ); ?></th>
                                <td>
                                    <input type="text" name="footnotes_close" id="footnotes_close" class="fme-input-md" value="<?php echo esc_attr( $this->current_options['footnotes_close'] ); ?>" />
                                    <p class="description"><?php esc_html_e( 'Characters that mark the end of a footnote in post content.', 'footnotes-made-easy' ); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div><!-- /#fme-panel-advanced -->

                <?php do_action( 'fme_settings_after_panels' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- fme_ is the plugin prefix. ?>

                <p class="submit fme-submit-row">
                    <button type="submit" name="save_options" value="1" class="button button-primary"><?php esc_html_e( 'Save changes', 'footnotes-made-easy' ); ?></button>
                </p>

            </form>
        </div><!-- /.fme-settings-main -->

        <!-- SIDEBAR -->
        <?php include dirname( __FILE__ ) . '/sidebar.php'; ?>

    </div><!-- /.fme-settings-grid -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->

