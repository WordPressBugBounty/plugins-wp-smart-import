<?php 
	if (!defined('ABSPATH')) { exit; } 
	
	$nonce = isset( $_SESSION['manage_default_nonce'] ) ? $_SESSION['manage_default_nonce'] : false;
	if ( $nonce && wp_verify_nonce( $nonce, 'manage_default_nonce' ) ) {
	
	?>
	<div class="wsi-wrap">
		<div class="wsi-body-header-title">
			<h2 class="plugin-title"><?php esc_attr_e( 'Wp Smart Import', 'wp-smart-import' ); ?></h2>
			<h1 class="page-title"><?php esc_attr_e( 'Import XML', 'wp-smart-import' ); ?></h1>
		</div>
		<div class="wpsi-body">
			<?php $action = isset( $_GET['action'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) : '';
				if( isset( $_GET['page'] ) ){
					echo wpSmartImportView::load( esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ), $action );
				} ?>
			<div id="ajax-wait"></div>
		</div>
	</div>
<?php
} else {
    wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}