<?php 
if (!defined('ABSPATH')) { exit; }
if(!class_exists('wpSmartImportAdminMenu')){
	class wpSmartImportAdminMenu{
		
		public function __construct() {
			add_action('admin_menu', array(&$this, 'admin_menu'), 9);
		}

		public function admin_menu() {
	       $lang = wpSmartImport::getVar('lang');
	       $main_page = add_menu_page("wpSmartImport", "WP Smart Import", "manage_options", $lang, array($this, 'wp_smart_import_default'), 'dashicons-welcome-widgets-menus', 55);
	        add_submenu_page($lang, "New Import", "New Import", 'manage_options', $lang, array($this, 'wp_smart_import_default'));
	        add_submenu_page($lang, "Manage Import", "Manage Import", 'manage_options', $lang."_manage", array($this, 'wp_smart_import_manage'));
	        add_submenu_page($lang, "Manage Files", "Manage Files", 'manage_options', $lang."_manage_file", array($this, 'wp_smart_import_manage_file'));
	    }

	    public function wp_smart_import_default() {
			$nonce = wp_create_nonce('manage_default_nonce');
			$_SESSION['manage_default_nonce'] = $nonce;
	      	echo wpSmartImportView::load_menu_page('default' );
	    }

	    public function wp_smart_import_manage() {
	    	require_once wpSmartImport::getVar('base', 'path').'controller/manage_controller.php';
			$nonce = wp_create_nonce('manage_import_nonce');
			$_SESSION['manage_import_nonce'] = $nonce;
			echo wpSmartImportView::load_menu_page('manage_import' );
	    }

		public function wp_smart_import_manage_file() {
			require_once wpSmartImport::getVar('base', 'path').'controller/file_manage_controller.php';
			$nonce = wp_create_nonce( 'manage_files_nonce' );
			$_SESSION['manage_files_nonce'] = $nonce;
			echo wpSmartImportView::load_menu_page('manage_file' );
		}
	}
}
new wpSmartImportAdminMenu;