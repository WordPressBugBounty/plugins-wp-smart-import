<?php 
if (!defined('ABSPATH')) { exit; }
if(!class_exists('wpSmartImportCheck')){
    class wpSmartImportCheck {

        function __construct() {
            $this->check_redirect();
        }

        private function check_redirect() {
            $pages = wpSmartImport::getVar('pages');
            $wpSmartImportCommon = new wpSmartImportCommon;
            $wpsiQuery = new wpSmartImportQuery;
            $request = wpsi_helper::recursive_sanitize_text_field( wp_unslash( $_POST ) );
            if(!empty($request) && isset($request['_nonce'])) { // Check if nonce is set
                if ( isset( $request['_nonce'] ) && wp_verify_nonce($request['_nonce'], 'wpsi_nonce')) { // Verify nonce
                    $page = $pages[0];
                    if (isset( $request['wpsi_submit'] )) {
                        $submit = $request['wpsi_submit'];
                        switch ($submit) {
                            case 'upload':
                                if ($wpSmartImportCommon->update_session($request['wpsi_upload']))
                                {
                                    if (isset($request['wpsi_upload']['willUse']) && !empty($request['wpsi_upload']['file_name'])) { 
                                        $wpsiQuery->wpsi_saveFile($request['wpsi_upload']);
                                    }
                                    wpSmartImport::wpsi_redirect(array('page' => $page, 'action' => 'element', '_nonce' => $request['_nonce'] ) );
                                }
                                break;
                            case 'element':
                                if ($wpSmartImportCommon->update_session($request['wpsi_element'])) {
                                    wpSmartImport::wpsi_redirect(array('page' => $page, 'action' => 'template', '_nonce' => $request['_nonce'] ) );
                                }
                                break;
                            default:
                                break;
                        }
                    }
                } else {
                    // Nonce verification failed, handle accordingly
                    // For example: return an error message or redirect
                    die('Nonce verification failed!');
                }
            }
        }

        public function session_check() {
            $pages = wpSmartImport::getVar('pages');
            global $session;
            /*if (empty($session) && (isset($_GET['action']) && $_GET['action'] !='index') && !isset($_GET['id']) || (isset($_POST['id']) && empty($_POST['id']))) {
                wpSmartImport::wpsi_redirect( array( 'page' => $pages[0], 'action' => 'index' ));
            }*/
        }
    } // End wpSmartImportCheck Class
    new wpSmartImportCheck;    
}