<?php
/*
Plugin Name: Display Active Plugins First
Plugin URI: 
Description: Changes plugins display order in the admin, showing active plugins first.
Version: 1.1
Author: Sergey Biryukov
Author URI: https://profiles.wordpress.org/sergeybiryukov/
*/

class Display_Active_Plugins_First {

	public function __construct() {
		add_action( 'admin_head-plugins.php', array( $this, 'sort_plugins_by_status' ) );
	}

	public function sort_plugins_by_status() {
		global $wp_list_table, $status;

		if ( ! in_array( $status, array( 'active', 'inactive', 'recently_activated', 'mustuse' ), true ) ) {
			uksort( $wp_list_table->items, array( $this, '_order_callback' ) );
		}
	}

	private function _order_callback( $a, $b ) {
		global $wp_list_table;

		$a_active = is_plugin_active( $a );
		$b_active = is_plugin_active( $b );

		if ( $a_active && ! $b_active ) {
			return -1;
		} elseif ( ! $a_active && $b_active ) {
			return 1;
		} else {
			return strcasecmp( $wp_list_table->items[ $a ]['Name'], $wp_list_table->items[ $b ]['Name'] );
		}
	}

}

new Display_Active_Plugins_First;
