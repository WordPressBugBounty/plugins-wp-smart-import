<div class="wpsi-portlet" id="sec_content">
   <div class="wpsi-portlet-title">
      <div class="caption">
         <span class="caption-subject"> <?php esc_attr_e("Content", 'wp-smart-import'); ?> </span>
      </div>
      <div class="actions">
         <a class="wpsi-go-upload-file" href="<?php echo esc_url(admin_url('admin.php?page=wp_smart_import&action=index')); ?>">
            <?php esc_attr_e("Upload New File", 'wp-smart-import'); ?>
         </a>
      </div>
   </div>
   <div class="wpsi-portlet-body">
      <p class="wpsi-note">
         <?php esc_attr_e("NOTE : Drag and Drop node element for input", 'wp-smart-import'); ?>
      </p>
      <div class="wpsi-post-field-container wpsi-clear">
         <div class="wpsi-post-field-list-contaner">
            <div class="get_post_types_list input-group input-group-inline">
               <label for="post_title">
                  <?php esc_attr_e('Post title', 'wp-smart-import'); ?> 
                  <span class="required"> * </span> 
               </label>
               <input type="text" name="post_title" class="wpsi-form-control drop-target"  required placeholder="<?php esc_attr_e('Enter title here..', 'wp-smart-import'); ?>" value="<?php echo esc_attr(wpsi_helper::_d($post_data,'post_title')); ?>" id="title" />
            </div>
            <div class="get_post_types_list input-group input-group-inline">
               <label for="post_des">
                  <?php esc_attr_e('Description', 'wp-smart-import'); ?>
                  <span class="required"> * </span>
               </label>
               <textarea name="post_des" id="content" class="wpsi-form-control drop-target" required placeholder="<?php esc_attr_e("Enter description here..", 'wp-smart-import'); ?>" rows="5"><?php echo esc_textarea(wpsi_helper::_d($post_data, 'post_des')); ?></textarea>
            </div>
            <div class="get_post_types_list input-group input-group-inline" >
               <?php wpsi_helper::_dref($post_data, 'post_status','publish'); ?> 
               <label for="post_status"><?php esc_attr_e( 'Post Status', 'wp-smart-import'); ?></label>
               <select name="post_status" class="wpsi-form-control" >
                  <option value="publish" <?php selected($post_data['post_status'], "publish" ); ?> >
                     <?php esc_attr_e('Published', 'wp-smart-import'); ?>
                  </option>
                  <option value="pending" <?php selected($post_data['post_status'], "pending"); ?> >
                     <?php esc_attr_e('Pending review', 'wp-smart-import'); ?>
                  </option>
                  <option value="draft" <?php selected($post_data['post_status'], "draft"); ?> >
                     <?php esc_attr_e('Draft', 'wp-smart-import'); ?>
                  </option>
               </select>
            </div>
            <div class="get_post_types_list input-group input-group-inline">
               <label for="post_types"> <?php esc_attr_e('Post author', 'wp-smart-import'); ?> </label>
               <select name="post_auth" class="wpsi-form-control">
                  <?php $wp_users = get_users(array('role__in' => array('administrator', 'editor', 'author'))); ?>
                  <?php foreach ($wp_users as $wp_user) : ?>
                  <option value="<?php echo esc_attr( $wp_user->ID ); ?>" 
                     <?php if (wpsi_helper::_d($post_data, 'post_auth', false)) {
                           selected($post_data['post_auth'], $wp_user->ID);
                        } else {
                           selected($wp_user->ID, get_current_user_id());
                        } ?> > 
                        <?php echo esc_html($wp_user->user_nicename); ?>
                  </option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>
      </div>
      <!-- wpsi-post-container END -->
   </div>
   <!-- wpsi-portlet-body END -->
</div>
<!-- wpsi-portlet END -->