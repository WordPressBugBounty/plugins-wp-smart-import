<?php
$download_file = isset( $session['download_file'] ) ? sanitize_text_field( wp_unslash( $session['download_file'] ) ) : '';
$file_names    = isset( $session['file_name'] ) ? sanitize_text_field( wp_unslash( $session['file_name'] ) ) : '';
$file_path     = isset( $session['file_path'] ) ? sanitize_text_field( wp_unslash( $session['file_path'] ) ) : '';
?>
<div class="wpsi-portlet" >
	<div class="wpsi-portlet-body">
		<form action="" method="POST" class="wpsi_upload_form" enctype="multipart/form-data">
			<div class="upload-button-group button-tabPannel">
			  	<input type="hidden" name="wpsi_upload[file_path]" id="wpsi_file_path"  value="<?php echo esc_attr($file_path); ?>" />
				<div class="file-input-wrapper">
					<label for="file-upload" class="file-upload-btn btn-file-input wpsi-button btn-grp" data-tab="file-upload">
						<span class="dashicons dashicons-upload"></span>
						<?php esc_html_e('Upload File', 'wp-smart-import'); ?>
					</label>
					<input id="file-upload" type="file" class="file-input" accept=".xml" />
				</div>
				<div class="file-input-wrapper">
					<label class="file-upload-btn btn-file-input wpsi-button btn-grp" data-tab="download">
						<span class="dashicons dashicons-download"></span> 
						<?php esc_html_e('Download From URL', 'wp-smart-import'); ?>
					</label>
				</div>
				<div class="file-input-wrapper">
					<label class="file-upload-btn btn-file-input wpsi-button btn-grp" data-tab="existing">
						<span class="dashicons dashicons-paperclip"></span>
						<?php esc_html_e('Select Existing File', 'wp-smart-import'); ?>
					</label>
				</div>
			</div>
			<!-- Tab Content -->
			<div class="wpsi-container">
			    <div id="download" class="tab-content">
			    	<div class="input-group input-group-inline">
						<input type="url" name="wpsi_upload[download_file]" class="wpsi-form-control wpsi-input-with-button wpsi-input-lg download_file file-input "  placeholder="Enter URL to download file.." value="<?php echo esc_attr( $download_file ); ?>">
						<div class="input-group-append">
							<button class="wpsi-button" id="download_file">
								<?php esc_attr_e('Download', 'wp-smart-import'); ?>
							</button>
					    </div>
					</div>
				</div>
			    <div id="existing" class="tab-content"> 
			    	<div class="input-group input-group-inline">
						<select name="wpsi_upload[existing_file]" class="wpsi-form-control	 wpsi-input-lg file-input select-file" >
							<option value=""><?php esc_attr_e('Select File', 'wp-smart-import'); ?></option>
							<?php $files = wpSmartImportQuery::retrieve_files();  
								foreach ($files as $idx => $farray): ?>
							 	<option value="<?php echo esc_attr($farray['file_path']); ?>"
							 		<?php selected(wpsi_helper::_d($session, 'existing_file'), $farray['file_path']); ?> >
							 		<?php echo esc_attr($farray['name']); ?>
							 		<?php echo esc_attr('[ File : '.$farray['file_name'].' ]'); ?>
							 	</option>
							 <?php endforeach ?> 
						</select>
					</div>
			    </div>
			</div>   
			<?php 
				$file_name = '';
				$display = "display:none";
				if (!empty($session)) {
					$file_name = basename($session['file_path']);
					$display = "display:block";
				}
			?>
			<div class="after-success-upload">
				<div class="upload-msg" id="upload_msg" style="<?php echo esc_attr($display); ?>">
					<?php echo esc_html($file_name); ?> 
				</div>
			</div>
			<div class="wpsi-post-container wpsi-container" style="<?php echo esc_attr($display); ?>">
				<div class="wpsi-choose-post-type">
					<div class="upload-button-group">
						<button class="wpsi-button wpsi-button-big btn-grp active" type="button">
							<?php esc_attr_e('New Items', 'wp-smart-import'); ?> </button>
					</div>
					<div class="wpsi-post-list-contaner"> 
						<div class="get_post_types_list input-group">
							<label for="post_types">
								<?php esc_attr_e('Create New', 'wp-smart-import'); ?>
							</label>
							<select name="wpsi_upload[post_type]" class="inline-form-control wpsi-input-lg">
							<?php 
								$post_types = get_post_types(array('public' => true), 'objects');
								foreach ($post_types as $key=> $array) {
									// if WooCommerce class exist 
									if (class_exists('WooCommerce') && $array->name == 'product') { 
									  	$array->label = 'WooCommerce Product';
									}
							?>
									<option value="<?php echo esc_attr($array->name); ?>"
										<?php echo wpsi_helper::_d($session, 'post_type') == $array->name ? 'selected="selected"' :'' ; ?> >
											<?php echo esc_html($array->label); ?>
									</option>
							<?php } ?>
							</select>
							<p class="willUse">
								<label>
									<?php 
										
										$willUse = isset( $session['willUse'] ) ? sanitize_text_field( wp_unslash( $session['willUse'] ) ) : '';
										$willUse = $willUse == 1 ? 'checked="checked"' : '';
									?>
									<input type="checkbox" name="wpsi_upload[willUse]" value="1" id="useFile" <?php echo esc_attr( $willUse ); ?> />
									<?php esc_html_e('Use this file in Future', 'wp-smart-import'); ?> 
								</label>
							</p>
						</div>
						<div class="willUse-data" style="<?php echo !empty( $willUse ) ? 'display:block;' : 'display:none;' ?>">
							<input type="text" name="wpsi_upload[file_name]" class="inline-form-control " placeholder="Enter File Name as you wish.." value="<?php echo esc_attr( $file_names ); ?>" >
							<button class="wpsi-button" type="button" id="file_name_check">
								<?php esc_attr_e("Check Name Exist", 'wp-smart-import') ?>
							</button>
						</div>
					</div>
				</div>
				<div class="wpsi-step-next">
					<div class="upload-button-group">
						<?php wp_nonce_field( 'wpsi_nonce', '_nonce' ); ?>
						<button name="wpsi_submit" class="wpsi-button wpsi-button-big btn-grp btn-next" type="submit" value="upload">
							<?php esc_html_e("Next Step 2 >>", 'wp-smart-import'); ?> 
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>