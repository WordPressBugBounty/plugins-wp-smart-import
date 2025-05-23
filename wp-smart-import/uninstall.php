<?php 
if (!defined('WP_UNINSTALL_PLUGIN')) //if uninstall not called from WordPress exit
    exit();

global $wpdb;
// Delete created table when plugin uninstall
$tables_to_drop = array( 'files', 'posts', 'imports' );
foreach ( $tables_to_drop as $table_name ) {
    // Construct the table name with prefix
    $table_name_with_prefix = $wpdb->prefix . 'wpsi_' . $table_name;

    // Prepare the SQL query with a placeholder and Execute the query
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
    $wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $table_name_with_prefix ) );
}

// Delete options when plugin uninstall
delete_option('wp-smart-import-settings');
delete_option('wp-smart-import-session');