<?php
if ( isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash(  $_REQUEST['_nonce'] ) ), 'wpsi_nonce')) {
    if (!defined('ABSPATH')) { exit; }
    $wpsiQuery = new wpSmartImportQuery;
    $node = $session['node'];
    if (empty($node)) {
        echo "<h1>Error Please Try again !</h1>"; exit();   
    }
    $post_data = isset($post_data) ? $post_data : array();
    if (isset($_POST) && !empty($_POST)) {
        $_POST['post_type'] = $session['post_type'];

        $id          = isset( $_GET['id'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['id'] ) ) ) : '';
        $unique_key  = isset( $_GET['unique_key'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['unique_key'] ) ) ) : '';
        $wpsi_submit = isset( $_POST['wpsi_submit'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['wpsi_submit'] ) ) ) : '';

        if ($wpsiQuery->check_unique_key( $unique_key ) || !empty( $id ) ){
            $task = sanitize_text_field( $wpsi_submit ) == 'save_import' ? 'save' : 'save_run';
            $res = wpsiAjaxController::wpsi_insertImport(wpsi_helper::recursive_sanitize_text_field( wp_unslash( $_POST ) ), $task);
        } else {
            $post_data = $_POST;
            $res =  array('status' => "error" , "msg" => "Unique key Already Exist");
        }
    }
    ?> 
    <?php if (isset($res)): ?>
        <div id="message" class="notice notice-<?php echo esc_attr($res['status']); ?>  is-dismissible">
            <p><?php echo esc_html($res['msg']); ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php esc_attr_e("Dismiss this notice.", 'wp-smart-import'); ?></span>
            </button>
        </div>
    <?php endif; ?>
    <form action="" method="POST" class="wpsi_template_form" id="wpsi_template_form">
        <input type="hidden" name="id" value="<?php if (isset($data->id)){ echo esc_attr($data->id); } ?>" >
        <div class="wpsi-template-wraper">
            <div class="wpsi-template-container">
                <!-- CONTENT -->
                <?php include('template/content.php'); ?>

                <!-- Woocommerce Addons -->
                <?php $woocommerce_postTypse = array('product', 'shop_order');
                    if (wpSmartImportCommon::woocommerce_exist() && in_array($session['post_type'], $woocommerce_postTypse))
                        do_action('woocommerce_add_on', $session['post_type'], $post_data);
                ?>
                        
                <!-- IMAGES-IMPORT -->
                <?php include('template/images_import.php'); ?>
                <!-- TAXONOMY,CATEGORY,TAGS -->
                <?php include('template/tax_cat_tag.php'); ?>
                <!-- OPTIONS -->
                <?php include('template/options.php'); ?>
                <!-- CUSTOME-FIELDS -->
                <?php include('template/custom_fields.php'); ?>
                <!-- UNIQUE-KEY -->
                <?php include('template/unique_key.php'); ?>
            </div> <!-- End wpsi-container -->
            <!-- Show Previwe of Node Elemenents -->
            <div class="draggable wpsi-nodes-preview-sticky" id="wpsi-nodes-preview-sticky" >
                <button type="button" class="toggle-show" style="background: none;" ><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                <input type="hidden" id="element_input" value="<?php echo esc_attr(wpsi_helper::_d($session, 'node')); ?>" data-cnt="<?php echo esc_attr(trim(wpsi_helper::_d($session, 'node_count', 0))); ?>">
                <div class="wpsi-nodes-preview"></div>
            </div>
            <!-- Toggle Preview-bar -->
            <div style="position: relative;">  
                <button type="button" id="toggle-show" class="toggle-show button-fixed" style="display:none;"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
            </div>
        </div>
        <div class="wpsi-step-next">
            <div class="upload-button-group" style="text-align: left; margin-left: 10%;">
                <button name="wpsi_submit" class="wpsi-button wpsi-button-big btn-grp btn-next" type="submit" value="save_import"> <?php esc_attr_e('Save Import', 'wp-smart-import'); ?> </button>
                <button name="wpsi_submit" class="wpsi-button wpsi-button-big btn-grp btn-next" type="submit" value="save_import_run"> <?php esc_attr_e('Save and Run Import', 'wp-smart-import'); ?> </button>
            </div>
        </div>
    </form>
    <?php

}else{
	wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
}
?>