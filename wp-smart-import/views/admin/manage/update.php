<?php 
$data = array();
$wpsiQuery = new wpSmartImportQuery;
if ( isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ), 'wpsi_nonce') ) {
	$id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : 0 ;
}else{
	wp_die( esc_html__( 'Nonce verification failed!', 'wp-smart-import' ) );
}
$id = wpsi_helper::check_schar( $id );
if ( $id ) {
	$data = $wpsiQuery->wpsi_getRow( 'wpsi_imports', $id );
}
if (empty($data) || wpsi_helper::array_key_exists_r('error', $data)) {
	echo "<h1 class='text-center error-text'> Error Data Not Found ! </h1>";
	exit();
}
global $session;
$options   = unserialize( $data->options, array( 'allowed_classes' => array( 'wpsi_uoption' ) ) );
$post_data = unserialize( $data->post_data, array( 'allowed_classes' => array( 'wpsi_upostdata' ) ) );


?>
<div id="response-content" style="display: none;">
	<div class="meter animate_progress" >
	  <span class="text-center progress-text" style="width: 0%"><span></span></span>
	</div>
	<div class="flex-container"></div>
</div>
<!-- Progress Bar -->
<div class="wpsi-template-container">
	<div class="wpsi-portlet" >
	    <div class="wpsi-portlet-title">
	        <div class="caption">
	            <span class="caption-subject">
	            	<?php esc_attr_e("Run Import", 'wp-smart-import' ); ?>
	            </span>
	        </div>
	        <div class="actions">
	        <?php
	        	$pages     = wpSmartImport::getVar('pages');
	        	$admin_url = admin_url('admin.php');
				$enonce    = wp_create_nonce('wpsi_nonce'); 
		      	echo "<a href='". esc_url("$admin_url?page=$pages[1]&action=edit&id=$data->id&_nonce=$enonce") ."'>";
		      	esc_attr_e("Edit Import", 'wp-smart-import' );
		      	echo "</a>";
		      	echo "<a href='". esc_url("$admin_url?page=$pages[1]") ."'>";
		      	esc_attr_e("Manage Import", 'wp-smart-import' );
		      	echo "</a>";
	        ?>
	        </div>
	    </div>
		<div class="wpsi-portlet-body">
			<h3><?php esc_attr_e( "File is Ready to import Click on below button and Run import", 'wp-smart-import' ); ?> </h3>
			<form accept="" method="post">
				<button name="wpsi_submit" class="wpsi-button wpsi-button-big btn-grp btn-next" type="button" value="update" id="run_import">
					<?php esc_attr_e("Run Import", 'wp-smart-import' ); ?>
				</button>
			</form>
		</div>
	</div>
	<div class="wpsi-portlet" >
	    <div class="wpsi-portlet-title">
	        <div class="caption">
	            <span class="caption-subject">
	            	<?php esc_attr_e("Import Data", 'wp-smart-import' ); ?>
	            </span>
	        </div>
	    </div>
		<table class="wpsi-table wpsi-table-border wpsi-table-hoverable wpsi-custom-field-tab list-table">
			<thead class="head-color-blue">
				<tr><th><?php esc_attr_e("Field", 'wp-smart-import' ); ?></th>
				    <th><?php esc_attr_e("Value", 'wp-smart-import' ); ?></th></tr>
			</thead>
		 	<tbody>
		 		<tr><td><?php esc_attr_e("ID", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($data->id); ?></td>
				</tr>
		 		<tr><td><?php esc_attr_e("File Name", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($data->name); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Root Element", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($data->root_element); ?> </td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Unique Key", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['unique_key']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Post Title", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['post_title']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Post Description", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['post_des']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Post Status", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['post_status']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Post Author", 'wp-smart-import' ); ?> </td>
				    <td><?php echo esc_attr( get_the_author_meta('display_name', $post_data['post_auth'] ) );
				     ?> </td>
		  		</tr>
		  		<tr><td> <?php esc_attr_e("Post Date", 'wp-smart-import' ); ?> </td>
				    <td> <?php 
				    		if ( array_key_exists("date_type", $post_data ) ) {
				    			if($post_data['date_type'] == 'auto')
									esc_attr_e('Auto', 'wp-smart-import' );
				    			else if($post_data['date_type'] == 'specific')
				    				echo esc_attr($post_data['post_date']);
				    			else
									echo 'Random Date Between : '. esc_attr( $post_data['post_date_start'] )." to ". esc_attr( $post_data['post_date_end'] );
						    }
						?>
				    </td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Media Images", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['media_imgs']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Download Images", 'wp-smart-import' );?></td>
				    <td><?php echo esc_html($post_data['download_imgs']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Set Featured Image", 'wp-smart-import' ); ?></td>
				    <td><?php echo isset( $post_data['set_featured_image'] ) ? esc_html( $post_data['set_featured_image'] ) : ''; ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Post Type", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($post_data['post_type']); ?></td>
		  		</tr>
		  		<tr><td><?php esc_attr_e("Last Activity", 'wp-smart-import' ); ?></td>
				    <td><?php echo esc_html($data->last_activity); ?></td>
		  		</tr>
			</tbody>
		</table>
		<!--List Custom Fields -->
		<?php if (!empty($post_data['custom_field_name'][0])): ?>
		<br><hr>
		<h2>Custom Fields</h2>
		<table class="wpsi-table wpsi-table-border wpsi-table-hoverable wpsi-custom-field-tab list-table">
			<thead class="head-color-blue">
				<tr><th><?php esc_attr_e('Key', 'wp-smart-import' ); ?></th>
				    <th><?php esc_attr_e('Value', 'wp-smart-import' ); ?></th>
				</tr>
			</thead>
		 	<tbody>
			<?php   $custom_field_name = $post_data['custom_field_name'];
					$custom_field_value = $post_data['custom_field_value'];
					foreach ($custom_field_name as  $idx => $value) {
						echo "<tr><td>". esc_attr( $value ) . "</td>
								<td>". esc_attr( $custom_field_value[$idx] ) ."</td>
							</tr>";
					}
			?>
			</tbody>
		</table>
		<?php endif; ?>
	</div>
</div>
<!-- Show Preview of Node Elemenents -->
<div class="wpsi-nodes-preview-sticky" id="wpsi-nodes-preview-sticky">
    <input type="hidden" id="element_input" value="<?php echo esc_attr(wpsi_helper::_d($session, 'node')); ?>" data-cnt="<?php echo esc_attr(trim(wpsi_helper::_d($session, 'node_count', 0))); ?>">
	<div class="wpsi-nodes-preview"></div>
</div>