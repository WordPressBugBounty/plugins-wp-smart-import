<?php 
$nonce = isset( $_SESSION['manage_files_nonce'] ) ? $_SESSION['manage_files_nonce'] : false;
if ( $nonce && wp_verify_nonce( $nonce, 'manage_files_nonce' ) ) {
    ?>
    <div class="wsi-wrap">
        <div class="wsi-body-header-title">
            <h2 class="plugin-title"> <?php esc_attr_e( 'Wp Smart Import', 'wp-smart-import' ); ?> </h2>
            <h1 class="page-title"> <?php esc_attr_e( 'Manage Files', 'wp-smart-import' ); ?> </h1>
        </div>
        <div class="wpsi-body">
    <?php if (isset($_SESSION['res'])): ?>
            <div id="message" class="updated notice is-dismissible">
                <p><?php echo esc_attr($_SESSION['res']['msg']); ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php esc_attr_e("Dismiss this notice.", 'wp-smart-import'); ?>
                    </span>
                </button>
            </div>
    <?php session_destroy();  
        endif;
        $page   = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : '';
        $action = isset( $_GET['action'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) : '';
        echo wpSmartImportView::load( $page, $action );
    ?>
        </div>
        <div id="ajax-wait"></div>
    </div>
    <?php
} else {
    wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}