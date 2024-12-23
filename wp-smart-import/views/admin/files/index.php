<?php 
if (!defined('ABSPATH')) { exit; } 
$nonce = isset( $_SESSION['manage_files_nonce'] ) ? $_SESSION['manage_files_nonce'] : false;
if ( $nonce && wp_verify_nonce( $nonce, 'manage_files_nonce' ) ) {
	$ipage = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : '';
	?>
	<form method="post">
		<input type="hidden" name="page" value="<?php echo esc_attr( $ipage ); ?>" /> 
	<?php 
		$wpsi_manage_controller = new wpsi_manage_file_controller(); 
		if (isset($_REQUEST['s'])) {
			$wpsi_manage_controller->prepare_items( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) );
		} else {
			$wpsi_manage_controller->prepare_items();
		}
		$wpsi_manage_controller->search_box( 'search', 'search_id' );
		$wpsi_manage_controller->display(); 
	?>
	</form>
	<?php
}