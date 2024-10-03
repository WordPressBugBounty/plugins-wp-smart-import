<?php 
if (!defined('ABSPATH')) { exit; }
$data = array();
$request = wpsi_helper::recursive_sanitize_text_field( wp_unslash( $_REQUEST ) );
if (!isset($request['_nonce']) || !wp_verify_nonce(@$request['_nonce'], 'wpsi_nonce')) {
	echo '<h1>' . esc_html__('Invalid HTTP Request', 'wp-smart-import') . '</h1>';
	wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}
$wpsiQuery = new wpSmartImportQuery;
if (isset($request['id'])) {
	if (substr_count( $request['id'], ",") > 0) {
		$ids = array_filter(explode(',', $request['id']));
		foreach ($ids as $id) {
			$data[] = $wpsiQuery->wpsi_getRow('wpsi_files',$id);
		}
		$data = array_filter($data);
	} else {
		$ids = $request['id'];
		$data[] = $wpsiQuery->wpsi_getRow('wpsi_files', $request['id']);
	}
}
if (empty($data) || wpsi_helper::array_key_exists_r('error', $data))
	exit("<h1 class='text-center error-text'> Error Data Not Found ! </h1>");
?>
<h1><?php esc_attr_e('Delete', 'wp-smart-import'); ?></h1>
 <!-- Progress Bar -->
<div class="meter animate_progress" style="display: none;" >
  <span class="text-center progress-text" style="width: 0%"><span></span></span>
</div>
<div id="Response-content" style="display: none;">
	<h1><?php esc_attr_e('Deleted Record', 'wp-smart-import'); ?></h1>
	<table class="wpsi-table wpsi-table-border wpsi-table-hoverable list-table display-none" id="manage-file-record">
		<thead class="head-color-green">
			<tr>
				<th><?php esc_attr_e('File Name', 'wp-smart-import'); ?></th>
				<th><?php esc_attr_e('file', 'wp-smart-import'); ?></th>
				<th><?php esc_attr_e('Import', 'wp-smart-import'); ?></th>
				<th><?php esc_attr_e('Post', 'wp-smart-import'); ?></th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot></tfoot>
	</table>
</div>
<!-- Progress Bar -->
<form accept="" method="post" id="wpsi-delete-file">
		<?php foreach ($data as $idx => $obj): ?>
			<div class="wpsi-portlet" >
			    <div class="wpsi-portlet-title">
			        <div class="caption">
			            <span class="caption-subject">
			            	<?php esc_attr_e('File Data', 'wp-smart-import'); ?>
			            	<?php esc_attr_e("Id : ", 'wp-smart-import'); ?>
			            	<?php echo esc_attr( $obj->id ); ?> 
			            </span>
			        </div>
			    </div>
				<table class="wpsi-table wpsi-table-border wpsi-table-hoverable wpsi-custom-field-tab list-table">
				 	<tbody>
				 		<tr>
						    <td><?php esc_attr_e('ID', 'wp-smart-import'); ?></td>
						    <td><?php echo esc_attr( $obj->id ); ?></td>
				  		</tr>
				 		<tr>
						    <td><?php esc_attr_e('File Name', 'wp-smart-import'); ?></td>
						    <td><?php echo esc_attr( $obj->name ); ?> </td>
				  		</tr>
				  		<tr>
						    <td><?php esc_attr_e('File Path', 'wp-smart-import'); ?></td>
						    <td><?php echo esc_attr( $obj->file_path ); ?></td>
				  		</tr>
				  		<input type="hidden" name="manage_file[id][]" value="<?php echo esc_attr($obj->id); ?>">
						<input type="hidden" name="manage_file[file_name][]" value="<?php echo esc_attr($obj->name); ?>">
						<input type="hidden" name="manage_file[file_path][]" value="<?php echo esc_attr($obj->file_path); ?>">
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
	<div class="wpsi-portlet" >
	    <div class="wpsi-portlet-title">
	        <div class="caption">
	            <span class="caption-subject"><?php esc_attr_e('Options', 'wp-smart-import'); ?></span>
	        </div>
	    </div>
		<div class="wpsi-portlet-body">
			<label>  
				<input type="radio" name="manage_file[delete]" checked="" value="record" class="mycxk">
				<?php esc_attr_e('Delete File Record Only ( File will not show in existing file list )', 'wp-smart-import'); ?>
			</label>
			<label>  
				<input type="radio" name="manage_file[delete]" value="import" class="mycxk">
				<?php esc_attr_e('Delete All Imports Created by File', 'wp-smart-import'); ?>
			</label>
			<label>  
				<input type="radio" name="manage_file[delete]" value="post" class="mycxk">
				<?php esc_attr_e('Delete All Post Created by File', 'wp-smart-import'); ?>
			</label>
			<label>  
				<input type="radio" name="manage_file[delete]" value="full" class="mycxk">
				<?php esc_attr_e('Delete All Imports and Post Created by File', 'wp-smart-import'); ?>				
			</label>
		</div>
	</div>
	<div class="wpsi-step-next">
		<div class="upload-button-group">
			<button class="wpsi-button target-disabled wpsi-button-big btn-grp btn-next" type="button" id="delete_files"><?php esc_attr_e('Delete', 'wp-smart-import'); ?></button>
		</div>
	</div>
</form>