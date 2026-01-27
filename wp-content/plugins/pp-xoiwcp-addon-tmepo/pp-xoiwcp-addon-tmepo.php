<?php
/**
 * Plugin Name: Extra Product Options Addon for Export Order Items Pro
 * Description:
 * Version: 1.0.3
 * Author: WP Zone
 * Author URI: http://wpzone.co/?utm_source=xoi-addon-extra-product-options&utm_medium=link&utm_campaign=wp-plugin-credit-link
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/*
    Extra Product Options Addon for Export Order Items Pro
    Copyright (C) 2023 WP Zone

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

/* CREDITS:
 * This plugin contains code copied from and/or based on the following third-party products,
 * in addition to any others indicated in code comments or license files:
 *
 * WordPress, by Automattic, GPLv2+
 * WooCommerce, by Automattic, GPLv3+
 *
 * This file contains code from the Easy Digital Downloads Software Licensing addon.
 * Copyright (c) Sandhills Development, LLC; released under the GNU General Public License version 2 or later;
 * used in this project under GNU General Public License version 3 or later (see license/LICENSE.TXT).
*/
update_option ('pp_xoiepo_license_status', 'valid');
update_option ('pp_xoiepo_license_key', '*********');
add_filter('hm_xoiwcp_addon_fields', 'pp_xoiepo_get_fields');
function pp_xoiepo_get_fields($fields) {
	global $wpdb;
	
	if (get_option('pp_xoiepo_license_status')) {
		$postIds = get_posts(array(
			'post_type' => array('product', 'tm_global_cp'),
			'post_status' => 'any',
			'meta_key' => 'tm_meta',
			'fields' => 'ids',
			'nopaging' => true
		));
		
		foreach ($postIds as $postId) {
			$tmMeta = get_post_meta($postId, 'tm_meta', true);
			if (!isset($tmMeta['tmfbuilder'])) {
				continue;
			}
			
			foreach ($tmMeta['tmfbuilder'] as $key => $values) {
				if (substr($key, -13) == '_header_title' && $key != 'section_header_title') {
					foreach ($values as $valueIndex => $value) {
						$fields['tmepo_'.$value] = array(
							'label' => $value,
							'cb' => 'pp_xoiepo_get_field_value'
						);
						if (!empty($tmMeta['tmfbuilder'][substr($key, 0, -12).'quantity'][$valueIndex])) {
							$fields['tmepoq_'.$value] = array(
								'label' => sprintf('%s quantity', $value),
								'cb' => 'pp_xoiepo_get_field_value'
							);
						}
					}
				}
			}
		}
	}
	
	return $fields;
}

function pp_xoiepo_get_field_value($product, $type, $fieldId) {
	global $pp_xoiepo_cache;
	if (!isset($pp_xoiepo_cache[$product->order_item_id])) {
		if (!isset($pp_xoiepo_cache)) {
			$pp_xoiepo_cache = array();
		}
		$pp_xoiepo_cache[$product->order_item_id] = wc_get_order_item_meta($product->order_item_id, '_tmcartepo_data', true);
	}
	if (empty($pp_xoiepo_cache[$product->order_item_id])) {
		return '';
	}
	$isQuantityField = substr($fieldId, 0, 7) == 'tmepoq_';
	$fieldId = substr($fieldId, $isQuantityField ? 7 : 6);
	$value = '';
	$valueField = $isQuantityField ? 'quantity' : 'value';
	foreach ($pp_xoiepo_cache[$product->order_item_id] as $field) {
		if ($field['name'] == $fieldId) {
			$value .= (empty($value) ? '' : '; ').(isset($field[$valueField]) ? $field[$valueField] : '');
		}
	}
	return $value;
}

function pp_xoiepo_register_addon($addons) {
	$addons['pp_xoiepo'] = array(
		'title' => 'Extra Product Options Integration',
		'author' => 'WP Zone',
		'url' => 'https://wpzone.co/product/extra-product-options-addon-for-export-order-items-pro/?utm_source=xoi-addon-extra-product-options&utm_medium=link&utm_campaign=wp-plugin-credit-link',
		'licensing' => 1,
		'license_status' => get_option('pp_xoiepo_license_status'),
		'license_key' => get_option('pp_xoiepo_license_key'),
		'activate_cb' => 'pp_xoiepo_activate_license',
		'deactivate_cb' => 'pp_xoiepo_deactivate_license'
	);
	return $addons;
}
add_filter('hm_xoiwcp_addons', 'pp_xoiepo_register_addon');


/** Licensing **/

function pp_xoiepo_activate_license($license) {

	// run a quick security check
	/*if( ! check_admin_referer( 'pp_xoiepo_license_activate_nonce', 'pp_xoiepo_license_activate_nonce' ) )
		return; // get out if we didn't click the Activate button*/

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode( PP_XOIEPO_ITEM_NAME ), // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( PP_XOIEPO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return array();

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
	update_option('pp_xoiepo_license_key', $license, false);
	// $license_data->license will be either "valid" or "invalid"
	if ($license_data->license == 'valid') {
		update_option( 'pp_xoiepo_license_status', 1, false );
	} else {
		delete_option( 'pp_xoiepo_license_status' );
	}
	
	return ($license_data->license == 'valid' ?
				array('license_key' => $license, 'license_status' => true)
				: array()
			);
}

function pp_xoiepo_deactivate_license() {

	// run a quick security check
	/*if( ! check_admin_referer( 'pp_xoiepo_license_deactivate_nonce', 'pp_xoiepo_license_deactivate_nonce' ) )
		return; // get out if we didn't click the dectivate button*/

	// retrieve the license from the database
	$license = get_option( 'pp_xoiepo_license_key' );

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'deactivate_license',
		'license' 	=> $license,
		'item_name' => urlencode( PP_XOIEPO_ITEM_NAME ), // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( PP_XOIEPO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return array();

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "deactivated" or "failed"
	if( $license_data->license == 'deactivated' ) {
		delete_option( 'pp_xoiepo_license_status' );
		delete_option( 'pp_xoiepo_license_key' );
		return array(
			'license_status' => false
		);
	}
	return array();
}



// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'PP_XOIEPO_STORE_URL', 'https://wpzone.co/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'PP_XOIEPO_ITEM_NAME', 'Extra Product Options Addon for Export Order Items Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'PP_XOIEPO_EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}
function pp_xoiepo_register_option() {
	// creates our settings in the options table
	register_setting('pp_xoiepo_license', 'pp_xoiepo_license_key', 'pp_xoiepo_sanitize_license' );
}
add_action('admin_init', 'pp_xoiepo_register_option');

function pp_xoiepo_plugin_updater() {

	// retrieve our license key from the DB
	$license_key =  get_option('pp_xoiepo_license_key');

	// setup the updater
	$edd_updater = new PP_XOIEPO_EDD_SL_Plugin_Updater( PP_XOIEPO_STORE_URL, __FILE__, array(
			'version' 	=> '1.0.3', 			// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => PP_XOIEPO_ITEM_NAME, // name of this plugin
			'author' 	=> 'WP Zone'  // author of this plugin
		)
	);

}
add_action( 'admin_init', 'pp_xoiepo_plugin_updater', 0 );

function pp_xoiepo_sanitize_license( $new ) {
	$old = get_option( 'pp_xoiepo_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'pp_xoiepo_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}
?>