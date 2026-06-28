<?php
/**
 * Shared page footer — Footnotes Made Easy
 *
 * @package footnotes-made-easy
 * @since   3.2.0
 */
defined( 'ABSPATH' ) || exit;
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template file; variables are prefixed with fme_.
?>
<div class="fme-page-footer">

    <div class="fme-page-footer__brand">
        <span><?php esc_html_e( 'Made with', 'footnotes-made-easy' ); ?></span>
        <svg class="fme-page-footer__heart" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M8 14s-6-3.84-6-8a4 4 0 0 1 6-3.46A4 4 0 0 1 14 6c0 4.16-6 8-6 8z"/></svg>
        <span><?php esc_html_e( 'by AltviseWP, LLC', 'footnotes-made-easy' ); ?></span>
    </div>

    <nav class="fme-page-footer__links">
        <a href="https://altvisewp.com/blog/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Our blog', 'footnotes-made-easy' ); ?></a>
        <span class="fme-page-footer__sep">/</span>
        <?php
        // Route Support to priority support for active Pro license holders,
        // otherwise to the public WordPress.org support forum.
        $fme_pro_licensed = defined( 'FME_PRO_VERSION' )
            && function_exists( 'fmep_fs' )
            && fmep_fs()
            && fmep_fs()->is_paying();
        $fme_support_url  = $fme_pro_licensed
            ? 'https://altvisewp.com/account/#!/support'
            : 'https://wordpress.org/support/plugin/footnotes-made-easy/';
        ?>
        <a href="<?php echo esc_url( $fme_support_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'footnotes-made-easy' ); ?></a>
        <span class="fme-page-footer__sep">/</span>
        <a href="https://docs.altvisewp.com/footnotes-made-easy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'footnotes-made-easy' ); ?></a>
        <span class="fme-page-footer__sep">/</span>
        <a href="https://altvisewp.com/terms-of-service/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Terms of Service', 'footnotes-made-easy' ); ?></a>
        <span class="fme-page-footer__sep">/</span>
        <a href="https://altvisewp.com/privacy-policy/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Privacy Policy', 'footnotes-made-easy' ); ?></a>
    </nav>

    <div class="fme-page-footer__social">
        <?php
        $fme_social = [
            'twitter'  => [
                'url'   => 'https://x.com/altvisewp',
                'label' => __( 'Follow us on X (Twitter)', 'footnotes-made-easy' ),
                'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            ],
            'linkedin' => [
                'url'   => 'https://www.linkedin.com/company/altvisewp/',
                'label' => __( 'Follow us on LinkedIn', 'footnotes-made-easy' ),
                'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>',
            ],
            'youtube'  => [
                'url'   => 'https://www.youtube.com/@altvise-wp',
                'label' => __( 'Subscribe on YouTube', 'footnotes-made-easy' ),
                'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.96-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>',
            ],
        ];
        foreach ( $fme_social as $fme_social_key => $fme_social_item ) : ?>
        <a href="<?php echo esc_url( $fme_social_item['url'] ); ?>"
           class="fme-page-footer__social-link"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php echo esc_attr( $fme_social_item['label'] ); ?>">
            <?php echo $fme_social_item['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </a>
        <?php endforeach; ?>
    </div>

</div>
