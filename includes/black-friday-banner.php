<?php
/**
 * Black Friday promotional banner.
 *
 * Shown at the top of the FME admin pages (Dashboard, Settings, Tools, Help)
 * to free users during the campaign window. It is dismissed per user, either
 * with the close button or by clicking the CTA. The CTA opens the Pro landing
 * page (fme_pro_page_url()), which shows the matching coupon above the plans.
 * Keep the code and discount in sync with the landing page.
 *
 * To run a new campaign next year: change footnotes_made_easy_bf_campaign() (new id, dates,
 * code, discount). A new id means the banner shows again for users who
 * dismissed the previous one.
 *
 * @package footnotes-made-easy
 * @since   3.2.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Campaign settings. Dates are UTC.
 *
 * The coupon must exist in Freemius before this goes live.
 *
 * @return array
 */
function footnotes_made_easy_bf_campaign() {
    return array(
        'id'       => 'bf2026',
        'start'    => gmmktime( 0, 0, 0, 11, 23, 2026 ), // Mon 23 Nov 2026 00:00 UTC.
        'end'      => gmmktime( 0, 0, 0, 12, 1, 2026 ),  // Exclusive: runs through Cyber Monday (30 Nov).
        'code'     => 'BLACKFRIDAY40',
        'discount' => 40,
    );
}

/**
 * Is the campaign window open right now?
 *
 * Filterable so QA can force it on or off without changing the system clock:
 * add_filter( 'footnotes_made_easy_bf_campaign_is_live', '__return_true' );
 *
 * @return bool
 */
function footnotes_made_easy_bf_campaign_is_live() {
    $campaign = footnotes_made_easy_bf_campaign();
    $now      = time();
    $live     = ( $now >= $campaign['start'] && $now < $campaign['end'] );

    return (bool) apply_filters( 'footnotes_made_easy_bf_campaign_is_live', $live, $campaign );
}

/**
 * User meta key that records the dismissal for the current campaign.
 *
 * @return string
 */
function footnotes_made_easy_bf_dismiss_meta_key() {
    $campaign = footnotes_made_easy_bf_campaign();
    return 'fme_bf_banner_dismissed_' . $campaign['id'];
}

/**
 * Should the banner render for the current user on this request?
 *
 * @return bool
 */
function footnotes_made_easy_bf_banner_should_show() {
    // Same audience rules as the other upgrade prompts: never for Pro users,
    // and on multisite only for super admins in the network admin.
    if ( defined( 'FME_PRO_VERSION' ) || ! swas_wp_footnotes::show_upsell() ) {
        return false;
    }

    if ( ! footnotes_made_easy_bf_campaign_is_live() ) {
        return false;
    }

    return ! get_user_meta( get_current_user_id(), footnotes_made_easy_bf_dismiss_meta_key(), true );
}

/**
 * Output the banner. Called from the page templates, right under the top bar.
 *
 * Rendered inline (not via admin_notices) because the plugin strips all
 * admin notices on its own pages.
 */
function footnotes_made_easy_render_bf_banner() {
    if ( ! footnotes_made_easy_bf_banner_should_show() ) {
        return;
    }

    $campaign = footnotes_made_easy_bf_campaign();
    $ends     = wp_date( 'F j', $campaign['end'] - 1, new DateTimeZone( 'UTC' ) );
    ?>
    <div class="fme-bf-banner"
         role="region"
         aria-label="<?php esc_attr_e( 'Black Friday offer', 'footnotes-made-easy' ); ?>"
         data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
         data-nonce="<?php echo esc_attr( wp_create_nonce( 'fme_bf_banner_nonce' ) ); ?>">
        <span class="fme-bf-banner__badge"><?php esc_html_e( 'Black Friday', 'footnotes-made-easy' ); ?></span>
        <div class="fme-bf-banner__text">
            <strong class="fme-bf-banner__title"><?php
                /* translators: %d: discount percentage */
                echo esc_html( sprintf( __( 'Save %d%% on Footnotes Made Easy Pro', 'footnotes-made-easy' ), absint( $campaign['discount'] ) ) );
            ?></strong>
            <span class="fme-bf-banner__sub"><?php
                printf(
                    /* translators: 1: coupon code, 2: end date, e.g. "November 30" */
                    esc_html__( 'Use code %1$s at checkout. Offer ends %2$s.', 'footnotes-made-easy' ),
                    '<button type="button" class="fme-bf-banner__code" data-code="' . esc_attr( $campaign['code'] ) . '" data-copied="' . esc_attr__( 'Copied!', 'footnotes-made-easy' ) . '" title="' . esc_attr__( 'Click to copy', 'footnotes-made-easy' ) . '"><span class="fme-bf-banner__code-text">' . esc_html( $campaign['code'] ) . '</span><span class="fme-bf-banner__copied" role="status" aria-live="polite"></span></button>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
                    esc_html( $ends )
                );
            ?></span>
        </div>
        <a class="fme-bf-banner__cta" href="<?php echo esc_url( fme_pro_page_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Claim the offer', 'footnotes-made-easy' ); ?></a>
        <button type="button" class="fme-bf-banner__close" aria-label="<?php esc_attr_e( 'Dismiss Black Friday offer', 'footnotes-made-easy' ); ?>">
            <svg viewBox="0 0 14 14" width="14" height="14" fill="none" aria-hidden="true" focusable="false"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>
    <?php
}

/**
 * AJAX: remember that this user dismissed the banner.
 *
 * Fired by the close button and by the CTA click. The campaign is taken from
 * footnotes_made_easy_bf_campaign(), never from the request.
 */
add_action( 'wp_ajax_fme_dismiss_bf_banner', function () {
    check_ajax_referer( 'fme_bf_banner_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( null, 403 );
    }

    update_user_meta( get_current_user_id(), footnotes_made_easy_bf_dismiss_meta_key(), 1 );
    wp_send_json_success();
} );
