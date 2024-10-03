<div class="wpsi-portlet" id="sec_images_import">
   <div class="wpsi-portlet-title">
      <div class="caption">
         <span class="caption-subject">
            <?php esc_attr_e( 'Images Import', 'wp-smart-import' ); ?>
         </span>
      </div>
      <div class="actions">
         <button class="img-preview" data-popup-open="popup-1" id="image_preview" type="button">
            <?php esc_attr_e('Image Preview', 'wp-smart-import' ); ?>
         </button>
      </div>
   </div>
   <div class="wpsi-portlet-body">
      <ul id="wpsi-accordion">
         <li class="title"><?php esc_attr_e('Use images currently in Media Library', 'wp-smart-import' ); ?></li>
         <li class="content">
            <input type="radio" name="img_from" value="media-file" class="img_from" style="display: none" 
               <?php checked(wpsi_helper::_d($post_data, 'img_from'), 'media-file');
                  if(!isset($post_data['img_from'])){ echo "checked"; }
               ?> >
            <div class="get_post_types_list input-group input-group-inline">
               <span for="post_title"> <?php esc_attr_e('Enter image filenames one per line, or separate them with a <b class="imp-text ">( , )</b> seperate', 'wp-smart-import'); ?> </span>
               <textarea id="media_imgs" type="text" name="media_imgs" class="wpsi-form-control drop-target"  placeholder="Example : image-1.jpg , image-2.jpg ..." rows ="5"><?php echo esc_textarea(wpsi_helper::_d($post_data, 'media_imgs')); ?></textarea>
            </div>
         </li>
         <li class="title"><?php esc_attr_e('Download images hosted elsewhere', 'wp-smart-import' ); ?></li>
         <li class="content" >
            <input type="radio" name="img_from" value="download" class="img_from" style="display:none" 
               <?php checked(wpsi_helper::_d($post_data, 'img_from'), 'download'); ?> >
            <div class="get_post_types_list input-group input-group-inline">
               <span for="post_title">
                  <?php esc_attr_e('Enter image filenames one per line, or separate them with a <b class="imp-text ">( , )</b> seperate ', 'wp-smart-import' ); ?>
               </span>
               <textarea id="download_imgs" type="text" name="download_imgs" class="wpsi-form-control drop-target"  placeholder="Example : image-1.jpg , image-2.jpg ..." rows ="5" ><?php echo esc_textarea(wpsi_helper::_d($post_data, 'download_imgs')); ?></textarea>
            </div>
         </li>
      </ul>
      <div class="options">
         <h4 class="block-title"><?php esc_attr_e('Image Options', 'wp-smart-import' ); ?> </h4>
         <label>
         <input type="checkbox" name="set_featured_image" value="yes" <?php checked(wpsi_helper::_d($post_data, 'set_featured_image'), 'yes'); ?> >
         <?php esc_attr_e('Set the first image to the Featured Image (_thumbnail_id)', 'wp-smart-import' ); ?>
         </label>
      </div>
      <!-- Model -->
      <div class="wpsi-popup" data-popup="popup-1">
         <div class="wpsi-popup-inner preview-image"></div>
         <a class="wpsi-popup-close" data-popup-close="popup-1" href="#">
            <span class="dashicons dashicons-no-alt"></span>
         </a>
      </div>
   </div>
   <!-- wpsi-portlet-body END -->
</div>
<!-- wpsi-portlet END -->