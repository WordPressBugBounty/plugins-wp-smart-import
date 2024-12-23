<?php 
$nonce = isset( $_SESSION['manage_import_nonce'] ) ? $_SESSION['manage_import_nonce'] : false;
if ( $nonce && wp_verify_nonce( $nonce, 'manage_import_nonce' ) ) {
	$ipage = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : '';
	?>

	<form method="post">
	<input type="hidden" name="page" value="<?php echo esc_attr( $ipage ); ?>" /> 
	<?php $wpsi_manage_controller = new wpsi_manage_controller(); 
		if ( isset( $_POST['s'] ) ){
			$wpsi_manage_controller->prepare_items( sanitize_text_field( wp_unslash( $_POST['s'] ) ) );
		} else {
			$wpsi_manage_controller->prepare_items();
		}
		$wpsi_manage_controller->search_box( 'search', 'search_id' );
		$wpsi_manage_controller->display();
	?>
	</form>
	<?php
} else {
	wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}