<?php
/**
 * Dashboard Page — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file included from within class method scope.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$fme_version = get_plugin_data( plugin_dir_path( __FILE__ ) . '../footnotes-made-easy.php' )['Version'] ?? '';

// Count footnotes across posts
$fme_open  = preg_quote( $this->current_options['footnotes_open'],  '/' );
$fme_close = preg_quote( $this->current_options['footnotes_close'], '/' );

$fme_posts_with_footnotes = 0;
$fme_pages_with_footnotes = 0;
$fme_total_footnotes      = 0;

$fme_all_content = get_posts( [
    'post_type'      => [ 'post', 'page' ],
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
] );

foreach ( $fme_all_content as $fme_pid ) {
    $fme_content = get_post_field( 'post_content', $fme_pid );
    $fme_count   = preg_match_all( '/' . $fme_open . '.+?' . $fme_close . '/s', $fme_content );
    if ( $fme_count ) {
        $fme_total_footnotes += $fme_count;
        if ( get_post_type( $fme_pid ) === 'page' ) {
            $fme_pages_with_footnotes++;
        } else {
            $fme_posts_with_footnotes++;
        }
    }
}
?>
<div class="wrap fme-wrap">

    <!-- ── Top bar ──────────────────────────────────────────── -->
    <div class="fme-topbar">
        <div class="fme-topbar-brand">
            <a href="<?php echo esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-made-easy' ) ); ?>" class="fme-topbar-brand-link"><span class="fme-topbar-icon" aria-hidden="true">
                <img src="data:image/png;base64,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" width="16" height="16" alt="" style="width:16px;height:16px;object-fit:contain;" />
            </span>
            <span class="fme-topbar-name"><?php esc_html_e( 'Footnotes Made Easy', 'footnotes-made-easy' ); ?></span></a>
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

    <?php footnotes_made_easy_render_bf_banner(); ?>

    <!-- ── Two-column layout ────────────────────────────────── -->
    <div class="fme-dashboard">

        <!-- MAIN COLUMN -->
        <div class="fme-dashboard__main">

            <!-- Welcome strip -->
            <div class="fme-welcome">
                <div class="fme-welcome__text">
                    <h1 class="fme-welcome__heading"><?php esc_html_e( 'Welcome to Footnotes Made Easy', 'footnotes-made-easy' ); ?></h1>
                    <p class="fme-welcome__sub"><?php esc_html_e( 'Add clean, accessible footnotes to your posts and pages using simple double-parenthesis syntax — no shortcodes, no blocks needed.', 'footnotes-made-easy' ); ?></p>
                    <div class="fme-welcome__actions">
                        <button type="button" class="fme-welcome__btn-video" id="fme-watch-video-btn">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M5.5 4.5l4 2.5-4 2.5V4.5z" fill="currentColor"/></svg>
                            <?php esc_html_e( 'Watch Video', 'footnotes-made-easy' ); ?>
                        </button>
                        <a href="<?php echo esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-settings' ) ); ?>" class="fme-welcome__btn-settings">
                            <?php esc_html_e( 'Open Settings', 'footnotes-made-easy' ); ?>
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
                <div class="fme-welcome__graphic" aria-hidden="true">
                    <svg viewBox="0 0 180 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document background -->
                        <rect x="16" y="12" width="148" height="106" rx="10" fill="#EEF2FF"/>
                        <!-- Title line -->
                        <rect x="30" y="28" width="100" height="8" rx="3" fill="#C7D2FE"/>
                        <!-- Body lines -->
                        <rect x="30" y="44" width="118" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="56" width="90" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="68" width="110" height="6" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="80" width="70" height="6" rx="2" fill="#C7D2FE"/>
                        <!-- Footnote divider -->
                        <rect x="30" y="96" width="40" height="2" rx="1" fill="#A5B4FC"/>
                        <!-- Footnote lines -->
                        <rect x="30" y="104" width="80" height="5" rx="2" fill="#C7D2FE"/>
                        <rect x="30" y="114" width="60" height="5" rx="2" fill="#C7D2FE"/>
                        <!-- Superscript badge -->
                        <circle cx="152" cy="30" r="14" fill="#534AB7"/>
                        <text x="152" y="35" text-anchor="middle" fill="white" font-size="12" font-weight="700" font-family="serif">¹</text>
                    </svg>
                </div>
            </div>

            <!-- Video modal -->
            <div id="fme-video-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999999;align-items:center;justify-content:center;">
                <div style="position:relative;background:#000;border-radius:10px;overflow:hidden;width:min(720px,92vw);box-shadow:0 24px 60px rgba(0,0,0,.5);">
                    <button id="fme-video-close" type="button" style="position:absolute;top:10px;right:10px;z-index:2;background:rgba(0,0,0,.55);border:none;color:#fff;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:16px;line-height:1;">✕</button>
                    <div style="position:relative;padding-bottom:56.25%;height:0;">
                        <iframe id="fme-video-iframe" src="" style="position:absolute;inset:0;width:100%;height:100%;border:none;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="fme-stats-card">
                <div class="fme-card-head">
                    <span class="dashicons dashicons-chart-bar"></span>
                    <h3><?php esc_html_e( 'Site overview', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div class="fme-stats-grid">
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_total_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Total footnotes', 'footnotes-made-easy' ); ?></div>
                    </div>
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_posts_with_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Posts', 'footnotes-made-easy' ); ?></div>
                    </div>
                    <div class="fme-stat-item">
                        <div class="fme-stat-number"><?php echo esc_html( number_format_i18n( $fme_pages_with_footnotes ) ); ?></div>
                        <div class="fme-stat-label"><?php esc_html_e( 'Pages', 'footnotes-made-easy' ); ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick-start -->
            <div class="fme-stats-card">
                <div class="fme-card-head">
                    <span class="dashicons dashicons-editor-help"></span>
                    <h3><?php esc_html_e( 'Quick start', 'footnotes-made-easy' ); ?></h3>
                </div>
                <div style="padding: 20px;">
                    <p style="font-size:13px;color:#646970;margin:0 0 12px;line-height:1.6;">
                        <?php esc_html_e( 'Wrap any text in double parentheses anywhere in your post or page content:', 'footnotes-made-easy' ); ?>
                    </p>
                    <div class="fme-code-block" style="background:#f6f7f7;border:1px solid #e2e4e7;border-radius:6px;padding:14px 18px;font-family:'Courier New',monospace;font-size:13px;color:#1d2327;">
                        <?php echo esc_html( 'This is a sentence ' . $this->current_options['footnotes_open'] . 'and this is your footnote' . $this->current_options['footnotes_close'] . '.' ); ?>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:6px;padding:10px 14px;margin:12px 0 0;">
                        <svg style="width:14px;height:14px;fill:#D97706;flex-shrink:0;margin-top:1px;" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <p style="font-size:12px;color:#92400E;margin:0;line-height:1.6;">
                            <strong><?php esc_html_e( 'Important:', 'footnotes-made-easy' ); ?></strong>
                            <?php esc_html_e( 'Make sure you include a space before your opening double parentheses or the footnote will not work!', 'footnotes-made-easy' ); ?>
                        </p>
                    </div>
                    <p style="font-size:12px;color:#8c8f94;margin:10px 0 0;font-style:italic;">
                        <?php /* translators: %s: link to Settings → Advanced page */ printf( esc_html__( 'The opening and closing tags can be changed on the %s page.', 'footnotes-made-easy' ), '<a href="' . esc_url( swas_wp_footnotes::get_admin_page_url( 'footnotes-settings#advanced' ) ) . '">' . esc_html__( 'Settings → Advanced', 'footnotes-made-easy' ) . '</a>' ); ?>
                    </p>
                </div>
            </div>

        </div><!-- /.fme-dashboard__main -->

        <!-- SIDEBAR -->
        <aside class="fme-dashboard__sidebar">
            <?php include dirname( __FILE__ ) . '/sidebar.php'; ?>
        </aside><!-- /.fme-dashboard__sidebar -->

    </div><!-- /.fme-dashboard -->

    <?php include dirname( __FILE__ ) . '/footer.php'; ?>

</div><!-- /.fme-wrap -->
