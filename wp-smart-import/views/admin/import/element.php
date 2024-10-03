<?php 
if (!defined('ABSPATH')) { exit; }
if (empty($session)) {
    echo '<h1>' . esc_html__('No Data Found', 'wp-smart-import') . '</h1>';
    exit();
}
$file_path = $session['file_path'];
if (isset($file_path)) {
    $wpsiAjaxController = new wpsiAjaxController;
    $xmlfile_path = wpSmartImportCommon::current_xmlfile_path();
    $pathinfo = pathinfo($xmlfile_path);
    $extension = isset( $pathinfo['extension'] ) ? sanitize_text_field(  wp_unslash( $pathinfo['extension'] ) ) : ''; 
    if ( !empty( $extension ) ) {
        if ($extension == 'xml' && file_exists( $xmlfile_path ) && filesize( $xmlfile_path ) > 22 ) {
            $wpsiAjaxController::$xml = @simplexml_load_file( $xmlfile_path );
            $xml = $wpsiAjaxController::$xml;
            if( !empty( $xml ) ){
                $parent = $xml->getName();
                $wpsiAjaxController->wpsi_recurse_xml($xml,$parent); 
                //count repeated all keys in XML  file
                $xmlcnt = wpsi_helper::key_count($wpsiAjaxController::$xmlcnt); 
                    ?>
                <div class="wpsi-portlet" >
                    <div class="wpsi-portlet-title">
                        <div class="caption">
                            <span class="caption-subject">
                                <?php esc_attr_e("Elements", 'wp-smart-import') ?>
                            </span>
                        </div>
                        <div class="actions">
                            <a class="wpsi-go-upload-file" href="<?php echo esc_url(admin_url('admin.php?page=wp_smart_import&action=index')); ?>">
                                <?php esc_attr_e("Upload New File", 'wp-smart-import'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="wpsi-portlet-body">
                        <form action="" method="POST" class="wpsi_element_form">
                            <div class="wpsi-element-view">
                                <div class="xml_element_list">
                                    <h3>
                                        <?php esc_attr_e('Select XML element you want to import', 'wp-smart-import'); ?>
                                    </h3>
                                    <?php foreach ($xmlcnt as $key => $cnt): ?>
                                        <a rel="<?php echo esc_attr($key); ?>" href="javascript:void(0)" class="wpsi-root-element" data-count="<?php echo esc_attr($cnt) ?>">
                                            <span class="elm_name"><?php echo esc_html($key); ?></span>
                                            <span class="elm_cnt"><?php echo esc_html($cnt); ?></span>
                                        </a>                                <?php endforeach; ?>
                                    <input type="hidden" name="wpsi_element[node]" id="input_node" value="">
                                    <input type="hidden" name="wpsi_element[node_count]" id="input_nodecount" value="">
                                </div>
                                <div class="wpsi-nodes-preview"></div>
                            </div> 
                            <div class="wpsi-step-next">
                                <div class="upload-button-group">
                                    <a class="wpsi-button wpsi-button-big btn-grp btn-next" href="<?php echo esc_url(admin_url('admin.php?page=wp_smart_import&action=index')); ?>" >
                                        <?php esc_attr_e("<< Previous Step 1", 'wp-smart-import'); ?>
                                    </a>
                                    <?php wp_nonce_field( 'wpsi_nonce', '_nonce' ); ?>
                                    <button name="wpsi_submit" class="wpsi-button wpsi-button-big btn-grp btn-next" type="submit" value="element">
                                        <?php esc_attr_e("Next Step 3 >>", 'wp-smart-import'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> 
                </div>
                <?php  
            }else{
                ?>
                <div class="wpsi-portlet" >
                    <div class="wpsi-portlet-title upload-button-group">
                        <h2><?php esc_attr_e("XML file is empty or does not contain any data", 'wp-smart-import'); ?></h2>
                    </div>
                    <div class="wpsi-portlet-body upload-button-group">
                        <a class="wpsi-button wpsi-button-big btn-grp btn-next" href="<?php echo esc_url( site_url( '/wp-admin/admin.php?page=wp_smart_import' ) ); ?>">
                            <?php esc_attr_e("Back", 'wp-smart-import'); ?>
                        </a>
                    </div> 
                </div>
                <?php
            }
        }else{
            ?>
            <div class="wpsi-portlet" >
                <div class="wpsi-portlet-title upload-button-group">
                    <h2><?php esc_attr_e("XML file is empty or does not contain any data", 'wp-smart-import'); ?></h2>
                </div>
                <div class="wpsi-portlet-body upload-button-group">
                    <a class="wpsi-button wpsi-button-big btn-grp btn-next" href="<?php echo esc_url( site_url( '/wp-admin/admin.php?page=wp_smart_import' ) ); ?>">
                        <?php esc_attr_e("Back", 'wp-smart-import'); ?>
                    </a>
                </div> 
            </div>
            <?php
        }
    }else{
        wp_die( esc_html__( 'Oops! Something went wrong. Please try uploading the file again.', 'wp-smart-import' ) );
    }
} 