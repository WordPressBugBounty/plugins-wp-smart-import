<div class="wpsi-portlet" id="sec_options">
    <div class="wpsi-portlet-title">
        <div class="caption">
            <span class="caption-subject">
                <?php
                    // translators: %s: Post type name, e.g. "Product", "Page", etc.
                    printf( esc_attr__( '%s Options', 'wp-smart-import' ), esc_attr( ucfirst( $post_type ) ) ); 
                ?>
            </span>
        </div>
    </div>
    <div class="wpsi-portlet-body">
        <div class="wpsi-portlet-single-block">
            <h4 class="block-title"> <?php esc_attr_e( 'Set Post Password', 'wp-smart-import' ); ?> :</h4>
            <div class="wpsi-single-block-content">
                <div class="get_post_types_list input-group ">
                    <input type="password" name="post_password" class="wpsi-form-control drop-target" placeholder="Enter Post Password here.."  value="<?php echo esc_attr( wpsi_helper::_d( $post_data, 'post_password' ) ); ?>" >
                </div>
            </div>
        </div>
        <div class="wpsi-portlet-single-block">
            <?php wpsi_helper::_dref($post_data, 'comment_status', 'open'); ?>
            <h4 class="block-title"><?php esc_attr_e( 'Comment Status  :', 'wp-smart-import' ); ?></h4>
            <div class="wpsi-single-block-content">
                <label>
                    <input type="radio" name="comment_status" value="open" <?php checked($post_data['comment_status'], 'open'); ?> ><?php esc_attr_e('Open', 'wp-smart-import' ); ?>
                </label>
                <label>
                    <input type="radio" name="comment_status" value="closed" <?php checked($post_data['comment_status'], 'closed'); ?> > <?php esc_attr_e("Closed", 'wp-smart-import' ); ?>
                </label>
            </div>
        </div>
        <div class="wpsi-portlet-single-block">
            <?php wpsi_helper::_dref($post_data, 'date_type', 'auto'); ?>
            <div class="wpsi-portlet-single-title">
                <div class="caption">
                    <span><?php esc_attr_e('Set Date  :', 'wp-smart-import' ); ?></span>
                </div>
            </div>
            <div class="wpsi-single-block-content">
                <div class="wpsi-date-container">
                    <label>
                        <input type="radio" name="date_type" value="auto" class="show_hide_radio" <?php checked($post_data['date_type'], 'auto'); ?> ><?php esc_attr_e('Auto', 'wp-smart-import' ); ?>
                    </label>
                    <label>
                        <input type="radio" name="date_type" value="specific" class="show_hide_radio" <?php checked($post_data['date_type'], 'specific'); ?> ><?php esc_attr_e('As specified', 'wp-smart-import' ); ?>
                    </label>
                    <div class="slidingDiv wpsi-inner-block">
                        <div class="inner-content">
                            <input type="text" name="post_date" class="wpsi-form-control drop-target wpsi-cat-list date-picker" value="<?php echo esc_attr(wpsi_helper::_d($post_data,'post_date')); ?>" />
                        </div>
                    </div>
                    <label>
                        <input type="radio" name="date_type" value="random" class="show_hide_radio" <?php checked($post_data['date_type'], 'random'); ?> > 
                        <?php esc_attr_e('Random Date', 'wp-smart-import' ); ?>
                    </label>
                    <div class="slidingDiv wpsi-inner-block">
                        <div class="inner-content">
                            <div class="wpsi-col-6">
                                <input type="text" name="post_date_start" class="wpsi-form-control drop-target wpsi-cat-list from-date" placeholder="Start Date" value="<?php echo esc_attr(wpsi_helper::_d($post_data, 'post_date_start')); ?>" />
                            </div>
                            <div class="wpsi-col-6">
                                <input type="text" name="post_date_end" class="wpsi-form-control wpsi-cat-list to-date" placeholder="End Date" value="<?php echo esc_attr(wpsi_helper::_d($post_data, 'post_date_end')); ?>"/>
                            </div>
                            <div class="wpsi-clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsi-portlet-single-block">
            <h4 class="block-title">
                <?php esc_attr_e('Menu Order :', 'wp-smart-import' ); ?>
            </h4>
            <div class="wpsi-single-block-content">
                <div class="get_post_types_list input-group ">
                    <input type="text" name="menu_order" class="wpsi-form-control drop-target" value="<?php echo esc_attr(wpsi_helper::_d($post_data, 'menu_order',0)); ?>" />
                </div>
            </div>
        </div>
        <div class="wpsi-portlet-single-block">
            <?php wpsi_helper::_dref($post_data, 'ping_status','open'); ?>
            <h4 class="block-title"><?php esc_attr_e('Trackbacks and Pingbacks : ', 'wp-smart-import' ); ?></h4>
            <div class="wpsi-single-block-content">
                <label>
                    <input type="radio" name="ping_status" value="open" <?php checked($post_data['ping_status'] , 'open'); ?> /><?php esc_attr_e('Open', 'wp-smart-import' ); ?>
                </label>
                <label>
                    <input type="radio" name="ping_status" value="closed" <?php checked($post_data['ping_status'], 'closed'); ?> /><?php esc_attr_e("Closed", 'wp-smart-import' ); ?>
                </label>
            </div>
        </div>
        <div class="wpsi-portlet-single-block">
            <h4 class="block-title" title="<?php esc_attr_e('Default slug is the sanitized post title when creating a new post.', 'wp-smart-import' ); ?>"><?php esc_attr_e('Slug : ', 'wp-smart-import' ); ?></h4>
            <div class="wpsi-single-block-content">
                <div class="get_post_types_list input-group ">
                    <?php $post_slug = isset( $post_data['slug'] ) ?  $post_data['slug'] : ''; ?>
                    <input type="text" name="slug" class="wpsi-form-control drop-target" value="<?php  echo esc_attr( $post_slug ) ?>"  >
                </div>
            </div>
        </div>
    </div>
    <!-- wpsi-portlet-body END -->
</div>
<!-- wpsi-portlet END -->