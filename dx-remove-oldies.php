<?php
/**
 * Plugin Name: DX Remove Oldies
 * Description: Remove posts from DB for post types that are no longer registered
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 0.1
 * License: GPLv2 or later
 * 
 */
/*
 * Copyright (C) 2013 Mario Peshev

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 */


/**
 * Main class for oldies removal
 * @author nofearinc
 *
 */
class DX_Remove_Oldies {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	}
	
	public function add_plugin_page() {
		add_options_page( 'Remove Oldies','Remove Oldies', 'manage_options', 'remove_oldies', array( $this, 'settings_page' ) );
	}
	
	/**
	 * Settings -> Remove Oldies page
	 */
	public function settings_page() {
		// Get all active CPTs
		$post_types = get_post_types();
		global $wpdb;
		
		// Delete has been fired?
		if( isset( $_POST['delete'] ) && ! empty( $_POST['cpt_post_type'] ) ) {
			if( isset( $_POST['oldie'] ) ) { // fix this && wp_verify_nonce( $_POST['oldie'], 'delete_oldies' ) ) ) {
				if( ! empty( $post_types ) && is_array( $post_types ) ) {
					// Get the delete candidate
					$cpt_to_delete = $_POST['cpt_post_type'];
					
					// Prevent deleting useful stuff
					if( in_array( $cpt_to_delete, $post_types ) ) wp_die( 'Cheater!' );
					
					// Action!
					$wpdb->delete( $wpdb->posts, array( 'post_type' => $cpt_to_delete ) );
					
					echo "<p>Posts in post_type $cpt_to_delete have been removed.</p>";
				}
			}	
		}
		
		// After delete
		if( ! empty( $post_types ) && is_array( $post_types ) ) {
			// NOTE: Not using prepared statement as it doesn't wrap properly 
			// imploded string and the arguments array is larger (just FYI)
			
			$sql = "SELECT DISTINCT post_type FROM $wpdb->posts WHERE post_type NOT IN (";
			foreach( $post_types as $post_type ) {
				$post_type = esc_sql( $post_type );
				$sql .= "'{$post_type}',";
			}
		 
			// unset the trailing comma 
			$sql = rtrim( $sql, "," );
			$sql .= ")";
			
			// get the data-free post types
			$other_post_types = $wpdb->get_results( $sql );
		}
		
		include_once 'oldies_admin_view.php';
	}
}

// Instantiate
new DX_Remove_Oldies();