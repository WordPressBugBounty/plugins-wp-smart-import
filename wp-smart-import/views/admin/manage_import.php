<?php 
$nonce = isset( $_SESSION['manage_import_nonce'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_SESSION['manage_import_nonce'] ) ) ) : false;
if ( $nonce && wp_verify_nonce( $nonce, 'manage_import_nonce' ) ) {
    ?>
    <div class="wsi-wrap">
        <div class="wsi-body-header-title">
            <h2 class="plugin-title"> <?php esc_attr_e( 'Wp Smart Import', 'wp-smart-import' ); ?> </h2>
            <h1 class="page-title"> <?php esc_attr_e( 'Manage Import', 'wp-smart-import' ); ?> </h1>
        </div>
        <div class="wpsi-body">
    <?php   if(isset($_SESSION['res'])): ?>
            <div id="message" class="updated notice is-dismissible">
                <p> <?php echo isset(  $_SESSION['res']['msg'] ) ? wp_kses_post( $_SESSION['res']['msg'] ) : ''; ?> </p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">
                    <?php esc_attr_e( 'Dismiss this notice.', 'wp-smart-import'); ?> </span></button>
            </div>
    <?php session_destroy(); 
            endif;
            $action = isset($_GET['action']) ? esc_attr( sanitize_text_field(wp_unslash($_GET['action'] ) ) ) : '';
            $page = isset($_GET['page']) ? esc_attr( sanitize_text_field(wp_unslash($_GET['page'] ) ) ) : '';
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo wpSmartImportView::load( $page, $action );
    ?>
        </div>
        <div id="ajax-wait"></div>
    </div>
    <?php
} else {
    wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}