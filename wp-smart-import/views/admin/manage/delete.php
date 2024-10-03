<?php
$request = wpsi_helper::recursive_sanitize_text_field( wp_unslash( $_REQUEST ) );
if (!isset($request['_nonce']) || !wp_verify_nonce($request['_nonce'], 'wpsi_nonce')) {
	echo '<h1>Invalid HTTP Request</h1>';
	wp_die();
}
$data = array();
$wpsiQuery = new wpSmartImportQuery;
if (isset($request['id']) && $request['id'] != 0) {
	if (substr_count($request['id'], ",") > 0) {
		$ids = array_filter(explode(',', $request['id']));
		foreach ($ids as $id) {
			$data[] = $wpsiQuery->wpsi_getRow('wpsi_imports', $id);
		}
		$data = array_filter($data);
	} else {
		$ids = $request['id'];
		$data = $wpsiQuery->wpsi_getRow('wpsi_imports', $request['id']);
	}
}
if (empty($data) || wpsi_helper::array_key_exists_r('error', $data)) {
	echo "<h1 class='text-center error-text'> Error Data Not Found ! </h1>";
	exit();
}
if (is_object($data)) {
    $post_data = json_decode($data->post_data);
    $post_type = $data->post_type;
} else {
    $post_data = [];
    $post_type = [];
    foreach ($data as $obj) {
        $post_data[] = json_decode($obj->post_data);
        $post_type[] = $obj->post_type;
    }
}
?>
<h1><?php esc_attr_e("Delete", 'wp-smart-import' ); ?></h1>
<!-- Progress Bar -->
<div class="meter animate_progress" style="display: none;" >
  <span class="text-center progress-text" style="width: 0%"><span></span></span>
</div>
<div id="Response-content" style="display: none;"> </div>
<!-- Progress Bar -->
<form accept="" method="post" id="wpsi-delete-import">
	<?php if (is_object($data)) : ?>
		<div class="wpsi-portlet" >
		    <div class="wpsi-portlet-title">
		        <div class="caption">
		            <span class="caption-subject"><?php esc_attr_e("Import Data", 'wp-smart-import' ); ?></span>
		        </div>
		    </div>
			<table class="wpsi-table wpsi-table-border wpsi-table-hoverable wpsi-custom-field-tab list-table">
			 	<tbody>
			 		<tr> <td><?php esc_attr_e("ID", 'wp-smart-import' ); ?></td>
					    <td><?php echo esc_html($data->id); ?> </td>
			  		</tr>
			 		<tr> <td><?php esc_attr_e("File Name", 'wp-smart-import' ); ?> </td>
					    <td><?php echo esc_html($data->name); ?> </td>
			  		</tr>
			  		<tr> <td><?php esc_attr_e("File Path", 'wp-smart-import' ); ?> </td>
					    <td><?php echo esc_html($data->file_path); ?> </td>
			  		</tr>
			  		<tr> <td><?php esc_attr_e("Unique Key", 'wp-smart-import' ); ?> </td>
					    <td><?php echo isset( $post_data['unique_key'] ) ? esc_html( $post_data['unique_key'] ) : ''; ?> </td>
			  		</tr>
			  		<tr> <td><?php esc_attr_e("Post Type", 'wp-smart-import' ); ?> </td>
					    <td><?php echo isset( $post_data['post_type'] ) ? esc_html( $post_data['post_type'] ) : ''; ?> </td>
			  		</tr>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<?php foreach ($data as $idx => $obj): ?>
			<div class="wpsi-portlet" >
			    <div class="wpsi-portlet-title">
			        <div class="caption">
			            <span class="caption-subject"><?php echo esc_attr( "Id : ") . esc_attr( $obj->id ); ?></span>
			        </div>
			    </div>
				<table class="wpsi-table wpsi-table-border wpsi-table-hoverable wpsi-custom-field-tab list-table">
				 	<tbody>
				 		<tr> <td><?php esc_attr_e("ID", 'wp-smart-import' ); ?></td>
						    <td><?php echo esc_html($obj->id); ?> </td>
				  		</tr>
				 		<tr> <td><?php esc_attr_e("File Name", 'wp-smart-import' ); ?></td>
						    <td><?php echo esc_html($obj->name); ?></td>
				  		</tr>
				  		<tr> <td><?php esc_attr_e("File Path", 'wp-smart-import' ); ?></td>
						    <td><?php echo esc_html($obj->file_path); ?></td>
				  		</tr>
				  		<tr> <td><?php esc_attr_e("Unique Key", 'wp-smart-import' ); ?></td>
						    <td><?php echo isset( $post_data[$idx]['unique_key'] ) ? esc_html( $post_data[$idx]['unique_key'] ) : ''; ?></td>
				  		</tr>
				  		<tr> <td><?php esc_attr_e("Post Type", 'wp-smart-import' ); ?></td>
						    <td><?php $post_type = isset( $post_data[$idx]['post_type'] ) ? esc_html( $post_data[$idx]['post_type'] ) : '';
						    	echo esc_attr( $post_type ); $post_types[] = $post_type; ?></td>
				  		</tr>
					</tbody>
				</table>
			</div>
		<?php endforeach; 
		endif; ?>
	<div class="wpsi-portlet" >
	    <div class="wpsi-portlet-title">
	        <div class="caption">
	            <span class="caption-subject"><?php esc_attr_e("Options", 'wp-smart-import' ); ?></span>
	        </div>
	    </div>
		<div class="wpsi-portlet-body">
			<label>  
				<input type="checkbox" name="delete_import" checked="" class="mycxk" id="delete_import" />
				<?php esc_attr_e("Delete Import", 'wp-smart-import' ); ?>
			</label>
			<label>  
				<input type="checkbox" name="delete_post" checked="" class="mycxk" id="delete_post"/>
				<?php if (!empty($post_types)) {
						$post_types = array_unique((array_filter($post_types)));
						$post_types = implode(' , ', $post_types);
					} else {
						$post_types = '';
					} ?>
				<?php 

				$postd_type = isset( $post_data['post_type'] ) ? $post_data['post_type'] : '';
				$str = is_object( $data ) ? $postd_type : $post_types;
				echo esc_attr("Delete all $str which are created by this import");
				?>
			</label>
		</div>
	</div>
	<div class="wpsi-step-next">
		<div class="upload-button-group">
			<button class="wpsi-button target-disabled wpsi-button-big btn-grp btn-next" type="button" id="delete_imports"><?php esc_attr_e("Delete", 'wp-smart-import' ); ?></button>
		</div>
	</div>
</form>