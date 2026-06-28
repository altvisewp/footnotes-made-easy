<?php
/**
 * Shared sidebar — used on all plugin pages (free and Pro).
 *
 * @package footnotes-made-easy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file; variables are prefixed with fme_.

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$fme_current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

$fme_pro_active = defined( 'FME_PRO_VERSION' )
    && function_exists( 'fmep_fs' )
    && fmep_fs()
    && fmep_fs()->is_paying();

$fme_show_upsell = class_exists( 'swas_wp_footnotes' )
    ? swas_wp_footnotes::show_upsell()
    : true;

// Citations tab URL for the settings tip link
$fme_citations_url = swas_wp_footnotes::get_admin_page_url( 'footnotes-settings#citations' );

// Contextual tips per page — HTML allowed for bold and links
$fme_tips = [
    'footnotes-settings'  => sprintf(
        /* translators: %s = link to Citations tab */
        __( 'You can use the <a href="%s"><strong>Citations</strong></a> tab to format footnotes in APA, MLA, or Chicago style — automatically, site-wide.', 'footnotes-made-easy' ),
        esc_url( $fme_citations_url )
    ),
    'fme-pro-library'     => __( 'You can save frequently cited sources once, then insert them into any post in seconds from the <strong>Footnotes Pro panel</strong> on the Gutenberg editor.', 'footnotes-made-easy' ),
    'footnotes-tools'     => sprintf(
        __( 'You can export your <strong>Footnotes Made Easy</strong> settings and reuse the same settings on another site in just one click.', 'footnotes-made-easy' )
    ),
    'footnotes-made-easy' => __( 'You can manage, reorder, and format all footnotes without leaving the editor — using the <strong>Footnotes Pro panel</strong> in Gutenberg.', 'footnotes-made-easy' ),
    'fme-pro-license'     => __( 'Your license unlocks Citations, Library, and the <strong>Footnotes Pro panel</strong> across your entire site.', 'footnotes-made-easy' ),
    'footnotes-help'      => __( 'Wrap any text in <strong>(( ))</strong> to create a footnote. Use the <strong>Footnotes Pro panel</strong> on the editor to add citations or insert from your library.', 'footnotes-made-easy' ),
];

$fme_tip = $fme_tips[ $fme_current_page ]
    ?? __( 'Wrap any text in <strong>(( ))</strong> to create a footnote anywhere in your posts.', 'footnotes-made-easy' );

$fme_allowed_tip_html = [
    'a'      => [ 'href' => [], 'target' => [], 'rel' => [] ],
    'strong' => [],
];

// FME brand icon (same mark used in the page topbar), reused in the suite rows.
$fme_suite_icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAADKklEQVR4nJWWTUhVURDH5/qelRRFm8QohAoSij4kiYhoXZFhkVHZUqJFEUQQbdoGLYIoghYVBRHVIogMV7aRWrUK+kD6NrMvKNNM372/FnfGO+9431MHDuecmf+ZM+ecmf+9AsgM2yXgDzCs4xmtny6wqP0pUkmAWMcnAkzVVpTpCdpvFZFE54hIJCKbAkxVqdE+EpGCm4cSaf9JMTj9YIAJpaAtEhEp6iTWZkHYCUJ5n6N7WSXIyPkVESnUqKJRRHaJyFrJrizvul8HDkVEXmnvAyzqPBGRFhFpFZEGEYkFaAd+uWS4BSzSRy4AkTYB1igmUfw4sExthivovB6467DfgVYBfrvFZvwC7HXZVav9fOArmbwFZuVk6X6HS9Q3QL8Af92GsTMCXHEOrX/q7F2qm6P9XOCas5fU55gdRIBOpzDQuNv4ObDORX/VYc85/RagzwU/rr5MhoH9Bl5NyhoDlMuo9v+Ao4o97Oy7VXeGjAhGKZd3wFn0rS0xbOMFwCGgO1hob3sJ2Eh6I8NAC3AnwAAMAfeANqDO+S9MDJhMTcuBk6Rv5p29IOXSIY3epAT0AEeAJYGvIprpIddFaqzJ2bwTeBxcVwI8BDqApcEaO0Tk9VNRmUmsRRznYGPJGGtqqXKly4BjQC9ZQgC81/f7A3wITtsLHAcac660QE7SLNTr6SKrT3MGcJM0af7pphuA2wEGYAR4AOwjTcRJSbOetMgHgzeyTE2A04ptd/Y9ZGWRBGtMPgMXSUtPrK78lYWF3wdsdlFecNjzTr8VeKP6vMIfAw4YlxrfxQHoJildeWrrcfZu1c3Wfh5wIwjeU9s3IWVxi8oz+0EXvZF3HdDvHL5xtlqH73B+PXl/FFJ6+umM98lqKvw8NeVcU6XP02JStrFD/AB2WkQNwHagmfJUDsdt7qps420uuLy1zcAO3WOiYAe0iWT/NaWcsl1l5et0K0XkUUAWJefnmdMXisoUkQPksYnJihxdUwVsYpu4eWz/LUyxkS1u1HHk9PXOR56U+a3EpaEYrkfH9kdWIyJP1FbpN7Fc3ONWa5FLjOuktDcCXJ7m+on2H5S5Yr5ZGq25AAAAAElFTkSuQmCC';
?>
<aside class="fme-settings-sidebar">

    <?php if ( $fme_pro_active ) : ?>
    <!-- Pro tip card -->
    <div class="fme-tip-card">
        <div class="fme-tip-card__icon" aria-hidden="true">💡</div>
        <h3 class="fme-tip-card__heading"><?php esc_html_e( 'Did you know?', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-tip-card__text"><?php echo wp_kses( $fme_tip, $fme_allowed_tip_html ); ?></p>
    </div>

    <?php elseif ( defined( 'FME_PRO_VERSION' ) && ! $fme_pro_active && is_multisite() && ! is_super_admin() ) : ?>
    <!-- Subsite notice — Pro installed but not licensed, contact network admin -->
    <div class="fme-tip-card" style="border-left-color:#f59e0b;background:#fffbeb;">
        <div class="fme-tip-card__icon" aria-hidden="true">🔒</div>
        <h3 class="fme-tip-card__heading" style="color:#78350f;"><?php esc_html_e( 'Pro not activated', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-tip-card__text" style="color:#78350f;"><?php esc_html_e( 'Footnotes Made Easy Pro is installed but not yet licensed. Please contact your network administrator to activate the license.', 'footnotes-made-easy' ); ?></p>
    </div>

    <?php elseif ( $fme_show_upsell ) : ?>
    <?php
    // Suite card states:
    //   $fme_pro_installed_unlicensed — Pro plugin present but no active license
    //                                   (offer "Activate"); otherwise Pro is not
    //                                   installed at all (offer "Get Pro").
    $fme_pro_installed_unlicensed = defined( 'FME_PRO_VERSION' ) && ! $fme_pro_active;
    ?>
    <!-- Footnotes Made Easy Suite card -->
    <div class="fme-suite-card">
        <div class="fme-suite-card__head">
            <h3 class="fme-suite-card__title"><?php esc_html_e( 'Footnotes Made Easy Suite', 'footnotes-made-easy' ); ?></h3>
            <p class="fme-suite-card__desc"><?php esc_html_e( 'From simple footnotes to full academic citations — everything you need to reference sources beautifully in WordPress.', 'footnotes-made-easy' ); ?></p>
        </div>

        <!-- Free row -->
        <div class="fme-suite-row">
            <span class="fme-suite-row__icon fme-suite-row__icon--free" aria-hidden="true">
                <img src="<?php echo esc_attr( $fme_suite_icon ); ?>" alt="" width="20" height="20" />
            </span>
            <div class="fme-suite-row__body">
                <span class="fme-suite-row__name"><?php esc_html_e( 'Footnotes Made Easy Free', 'footnotes-made-easy' ); ?></span>
                <div class="fme-suite-row__meta">
                    <span class="fme-suite-row__status fme-suite-row__status--active">
                        <span class="fme-suite-dot fme-suite-dot--on"></span><?php esc_html_e( 'Active', 'footnotes-made-easy' ); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Pro row -->
        <div class="fme-suite-row">
            <span class="fme-suite-row__icon fme-suite-row__icon--pro" aria-hidden="true">
                <img src="<?php echo esc_attr( $fme_suite_icon ); ?>" alt="" width="20" height="20" />
            </span>
            <div class="fme-suite-row__body">
                <span class="fme-suite-row__name"><?php esc_html_e( 'Footnotes Made Easy Pro', 'footnotes-made-easy' ); ?></span>
                <div class="fme-suite-row__meta">
                    <?php if ( $fme_pro_active ) : ?>
                    <span class="fme-suite-row__status fme-suite-row__status--active">
                        <span class="fme-suite-dot fme-suite-dot--on"></span><?php esc_html_e( 'Active', 'footnotes-made-easy' ); ?>
                    </span>
                    <?php elseif ( $fme_pro_installed_unlicensed ) : ?>
                    <span class="fme-suite-row__status fme-suite-row__status--installed">
                        <span class="fme-suite-dot fme-suite-dot--warn"></span><?php esc_html_e( 'Installed', 'footnotes-made-easy' ); ?>
                    </span>
                    <?php else : ?>
                    <span class="fme-suite-row__status">
                        <span class="fme-suite-dot"></span><?php esc_html_e( 'Inactive', 'footnotes-made-easy' ); ?>
                    </span>
                    <?php endif; ?>

                    <?php if ( $fme_pro_installed_unlicensed ) : ?>
                    <a href="#" class="fme-suite-row__link activate-license-trigger footnotes-made-easy">
                        <?php esc_html_e( 'Activate license', 'footnotes-made-easy' ); ?>
                    </a>
                    <?php elseif ( ! $fme_pro_active ) : ?>
                    <a href="<?php echo esc_url( FME_PRO_URL ); ?>" target="_blank" rel="noopener noreferrer" class="fme-suite-row__link">
                        <?php esc_html_e( 'Get Pro', 'footnotes-made-easy' ); ?>
                        <svg viewBox="0 0 14 14" fill="none"><path d="M4 10L10 4M5 4h5v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ( ! $fme_pro_active ) : ?>
        <!-- Footer upsell strip -->
        <div class="fme-suite-card__foot">
            <span class="fme-suite-card__foot-star" aria-hidden="true">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8z"/></svg>
            </span>
            <p class="fme-suite-card__foot-text">
                <?php if ( $fme_pro_installed_unlicensed ) : ?>
                    <?php
                    printf(
                        /* translators: %s: "Activate license" link */
                        esc_html__( 'Activate your license to unlock citations, a reusable library, and more. %s', 'footnotes-made-easy' ),
                        '<a href="#" class="fme-suite-card__foot-link activate-license-trigger footnotes-made-easy">' . esc_html__( 'Activate license', 'footnotes-made-easy' ) . '</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, safe markup.
                    );
                    ?>
                <?php else : ?>
                    <?php
                    printf(
                        /* translators: %s: "Get Pro" link */
                        esc_html__( 'Unlock citations, a reusable library, and more. %s', 'footnotes-made-easy' ),
                        '<a href="' . esc_url( FME_PRO_URL ) . '" target="_blank" rel="noopener noreferrer" class="fme-suite-card__foot-link">' . esc_html__( 'Get Pro', 'footnotes-made-easy' ) . ' ↗</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- URL and text escaped above.
                    );
                    ?>
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Review nudge -->
    <div class="fme-review-card">
        <div class="fme-review-card__icon" aria-hidden="true">
            <svg viewBox="0 0 20 20"><path d="M10 15s-7-4.5-7-9a5 5 0 0 1 7-4.58A5 5 0 0 1 17 6c0 4.5-7 9-7 9z"/></svg>
        </div>
        <h3 class="fme-review-card__heading"><?php esc_html_e( 'Enjoying Footnotes Made Easy?', 'footnotes-made-easy' ); ?></h3>
        <p class="fme-review-card__text"><?php esc_html_e( 'A 5-star review on WordPress.org helps other writers and researchers find the plugin. It takes less than a minute!', 'footnotes-made-easy' ); ?></p>
        <div class="fme-review-card__stars" aria-hidden="true">
            <?php for ( $fme_star_i = 0; $fme_star_i < 5; $fme_star_i++ ) : ?>
            <svg viewBox="0 0 20 20"><path d="M10 2l2.4 5 5.6.8-4 3.9.9 5.5L10 14.5l-4.9 2.7.9-5.5L2 7.8l5.6-.8z"/></svg>
            <?php endfor; ?>
        </div>
        <a href="https://wordpress.org/support/plugin/footnotes-made-easy/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="fme-review-card__btn">
            <?php esc_html_e( 'Write a review', 'footnotes-made-easy' ); ?>
            <svg viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5h8M7 3.5l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>

</aside><!-- /.fme-settings-sidebar -->
