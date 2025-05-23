<?php
/**
 * Plugin Name:       WP Smart Import
 * Plugin URI:        https://wordpress.org/plugins/wp-smart-import/
 * Description:       The most powerful solution for importing XML files to WordPress. Create Posts and Pages with content from any XML or CSV file.
 * Version:           1.1.4
 * Author:            Xylus Themes
 * Author URI:        http://xylusthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-smart-import
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) { exit; }  // Exit if accessed directly
if(!class_exists('wpSmartImport')) {
	class wpSmartImport {

		static public $_var = array();
		public function __construct() {
			if ($this->restrict_current_user_role()) {
				$this->define_constants(); // Define constants
				$this->includes(); // Include required files
				$this->update_check();
				$this->load_textdomain();
			}
		}

		function restrict_current_user_role() {
			// Useful when you are checking something for the current user
		  	if (is_user_logged_in()) {
		   		$ID = get_current_user_id();
		    	if (user_can($ID, 'publish_posts'))
			  		return true;
		  	}
		    return false;
		}

		private function update_check() {
		    $v1 = self::getVar('version');
		    $v2 = self::getVar('version', 'settings');
		    if ($v2 == '') { //first time install
		    	$defaultSettings = array('version' => $v1);
		    	update_option(self::getVar('wpsi_settings'), $defaultSettings);
		    	update_option(self::getVar('wpsi_session'), "");
	        	require_once self::getVar('base', 'path').'table/schema.php';
		    }
		    if (version_compare($v1, $v2) > 0) { //do upgrade
		    }
		}

		static public function init() {
	        // Named global variable to make access for other scripts easier.
	        $GLOBALS[__CLASS__] = new self;
	    }

	    static public function wpsi_redirect($data) {
	    	if (!empty($data)) {
		    	wp_safe_redirect(add_query_arg($data, admin_url('admin.php')));
		    	exit();	
	    	}
	    }

		/**
		 * Loads the plugin language files.
		 * 
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		private function load_textdomain(){

			load_plugin_textdomain(
				'wp-smart-import',
				false,
				basename( dirname( __FILE__ ) ) . '/languages'
			);
		
		}
	    
	    private function define_constants() {
			$arr = array();
			$arr['version'] = '1.1.4';
			$arr['unique'] = 'wp_smart_import';
			$arr['plugin'] = __FILE__;
			//paths
			$arr['path']['base'] = plugin_dir_path(__FILE__);
			$arr['path']['inc'] = $arr['path']['base'].'/includes/';
			$arr['path']['admin_view'] = $arr['path']['base'].'/views/admin/';
			$arr['lang'] = $arr['unique'];
			//urls
			$arr['url']['home'] = trim(plugin_dir_url(__FILE__),'/').'/';
			$arr['url']['css'] = $arr['url']['home'].'assets/css/';
			$arr['url']['js'] = $arr['url']['home'].'assets/js/';
			$arr['url']['images'] = $arr['url']['home'].'assets/images/';
			$arr['url']['admin'] = get_option('siteurl').'/wp-admin/admin.php';
			//settings
			$arr['settings'] = get_option('wp-smart-import-settings');
			$arr['settings'] = $arr['settings'] && is_array($arr['settings']) ? $arr['settings'] : array();
			// $arr['session'] = isset( $_REQUEST['id'] ) && !empty( $_REQUEST['id'] ) ? self::get_import_option( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ) : get_option('wp-smart-import-session' );
			if (isset($_REQUEST['id']) && !empty( $_REQUEST['id']) && isset( $_REQUEST['_nonce'] ) ) {
				if ( isset( $_REQUEST['_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash(  $_REQUEST['_nonce'] ) ), 'wpsi_nonce')) {
					$arr['session'] = self::get_import_option(sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) );
				} else {
					wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'wp-smart-import' ) );
				}
			} else {
				$arr['session'] = get_option('wp-smart-import-session');
			}
			$arr['wpsi_settings'] = "wp-smart-import-settings";
			$arr['wpsi_session'] = "wp-smart-import-session";
			$arr['folder_name'] = $arr['unique'];
			$arr['pages'] = array($arr['unique'], $arr['unique'].'_manage', $arr['unique'].'_manage_file');
			self::$_var = $arr;
		}

		private function includes() {
			if (is_admin()) {
				require_once self::getVar('inc','path').'admin.php';
				require_once self::getVar('inc','path').'view.php';
				require_once self::getVar('inc','path').'common_function.php';
				require_once self::getVar('inc','path').'lib/helper.php';
				require_once self::getVar('inc','path').'admin_menu.php';
				require_once self::getVar('inc','path').'admin_ajax.php';
				require_once self::getVar('inc','path').'query.php';
				require_once self::getVar('inc','path').'check.php';
				require_once self::getVar('inc','path').'upload.php';
			}
		}

		static public function getVar($key, $key1 = '') {
			if ($key1 == '' && isset(self::$_var[$key])) {
				return self::$_var[$key];
			} else if (isset(self::$_var[$key1][$key])) {
				return self::$_var[$key1][$key];
			}
			return '';
		}

		static public function setVar($key, $value) {
			self::$_var[$key] = $value;
			return true;
		}


		static public function get_import_option($import_id = 0) {
			global $wpdb;
		    $esc_arr = array("," => " ", "&" => " ", "?" => " ", "|" => " ");
            $newstr = strtr($import_id, $esc_arr);
            foreach (explode(' ', $newstr) as $key => $value) {
                if(!empty($value)) {
                    $import_id = $value;
                    break;
                }
            }
		    if (!empty($import_id)) {
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
				$import = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpsi_imports WHERE id = %d", absint( $import_id ) ), ARRAY_A );
			    return !empty($import) && isset($import['options']) ? maybe_unserialize($import['options']) : [];
		    }
		    return false;
		}
	} // END wpSmartImport Class
	add_action('plugins_loaded', array('wpSmartImport', 'init'), 10);
}