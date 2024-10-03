<?php 
if ( isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash(  $_REQUEST['_nonce'] ) ), 'wpsi_nonce')) {
	$data = array();
	$wpsiQuery = new wpSmartImportQuery;
	$id = isset($_GET['id']) ? sanitize_text_field(wp_unslash($_GET['id'])) : 0 ;
	$id = wpsi_helper::check_schar($id);
	if ($id) {
		$data = $wpsiQuery->wpsi_getRow('wpsi_imports', $id);
	}
	if (empty($data) || wpsi_helper::array_key_exists_r('error', $data)) {
		echo "<h1 class='text-center error-text'> Error Data Not Found ! </h1>";
		exit();
	}
	$post_data = unserialize( $data->post_data, array( 'allowed_classes' => array( 'wpsi_epostdata' ) ) );
	?>
	<h1 class="page-title"><?php echo esc_attr( "#Id : ".$id );  ?></h1><br/>
	<?php include_once wpSmartImport::getVar('admin_view', 'path').'import/template.php';

}else{
	wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}