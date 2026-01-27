<?php
/**
 * Plugin Name:       Export Order Items Pro for WooCommerce
 * Description:       Export order items (products ordered) in CSV (Comma Separated Values) format, with product, line item, order, and customer data.
 * Version:           2.1.35
 * WC tested up to:   10.1.2
 * Author:            WP Zone
 * Author URI:        https://wpzone.co/?utm_source=export-order-items-pro&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License:           GNU General Public License version 3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * GitLab Plugin URI: https://gitlab.com/aspengrovestudios/hm-export-order-items-pro/
 * Domain Path:       /languages/
 * Text Domain:       export-order-items-pro
 * AGS Info:          ids.aspengrove 394250 ids.divispace 445680 legacy.key hm_xoiwcp_license_key legacy.status hm_xoiwcp_license_status adminPage admin.php?page=hm_xoiwcp
 */


/*
    Export Order Items Pro for WooCommerce
    Copyright (C) 2025 WP Zone

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
 * League.Csv - see credit and licensing information in lib/League.Csv/ByteSequence.php and lib/League.Csv/LICENSE
 *
 * This file contains code from the Easy Digital Downloads Software Licensing addon.
 * Copyright (c) Sandhills Development, LLC; released under the GNU General Public License version 2 or later; used in this project under GNU General Public License version 3 or later (see license/LICENSE.TXT)
*/
update_option ('hm_xoiwcp_license_status', 'valid');
update_option ('hm_xoiwcp_license_key', '*********');
$ags_xoiwcp_VER = '2.1.35';

// Following code copied from Easy Digital Downloads Software Licensing addon - see comment near the top of this file for details
define( 'ags_xoiwcp_STORE_URL', 'https://wpzone.co/' );
define( 'ags_xoiwcp_ITEM_NAME', 'Export Order Items Pro for WooCommerce' );
define('ags_xoiwcp_VER', $ags_xoiwcp_VER);
// End code copied from Easy Digital Downloads Software Licensing addon

// Localisation
load_plugin_textdomain( 'export-order-items-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


// Add Export Order Items to the WordPress admin
add_action('admin_menu', 'ags_xoiwcp_admin_menu');
function ags_xoiwcp_admin_menu()
{
    add_submenu_page('woocommerce', 'Export Order Items', 'Export Order Items', 'view_woocommerce_reports', 'hm_xoiwcp', 'ags_xoiwcp_page');
}

function ags_xoiwcp_default_report_settings()
{
    return array(
        'report_time' => '30d',
        'report_start' => date('Y-m-d', current_time('timestamp') - (86400 * 31)),
        'report_start_time' => '12:00:00 AM',
        'report_start_dynamic' => '',
        'report_end' => date('Y-m-d', current_time('timestamp') - 86400),
        'report_end_time' => '12:00:00 AM',
        'report_end_dynamic' => '',
        'order_statuses' => array('wc-processing', 'wc-on-hold', 'wc-completed'),
        'order_item_types' => array('line_item'),
        'order_meta_filter_on' => 0,
        'order_meta_filter_key' => '',
        'order_meta_filter_cast' => '',
        'order_meta_filter_date_format' => '%d/%m/%Y',
        'order_meta_filter_value' => '',
        'order_meta_filter_value_2' => '',
        'order_meta_filter_op' => '=',
        'order_meta_filter_2_logic' => 'AND',
        'order_meta_filter_2_on' => 0,
        'order_meta_filter_2_key' => '',
        'order_meta_filter_2_cast' => '',
        'order_meta_filter_2_date_format' => '%d/%m/%Y',
        'order_meta_filter_2_value' => '',
        'order_meta_filter_2_value_2' => '',
        'order_meta_filter_2_op' => '=',
        'order_meta_filter_3_logic' => 'AND',
        'order_meta_filter_3_on' => 0,
        'order_meta_filter_3_key' => '',
        'order_meta_filter_3_cast' => '',
        'order_meta_filter_3_date_format' => '%d/%m/%Y',
        'order_meta_filter_3_value' => '',
        'order_meta_filter_3_value_2' => '',
        'order_meta_filter_3_op' => '=',
        'customer_meta_filter_on' => 0,
        'customer_meta_filter_key' => '',
        'customer_meta_filter_value' => '',
        'customer_meta_filter_value_2' => '',
        'customer_meta_filter_op' => '=',
		'order_item_meta_filter_1_on' => 0,
		'order_item_meta_filter_1_key' => '',
		'order_item_meta_filter_1_value' => '',
		'order_item_meta_filter_1_value_dynamic' => '',
		'order_item_meta_filter_1_value_2' => '',
		'order_item_meta_filter_1_value_2_dynamic' => '',
		'order_item_meta_filter_1_op' => '=',
		'order_item_meta_filter_2_logic' => '',
		'order_item_meta_filter_2_on' => 0,
		'order_item_meta_filter_2_key' => '',
		'order_item_meta_filter_2_value' => '',
		'order_item_meta_filter_2_value_dynamic' => '',
		'order_item_meta_filter_2_value_2' => '',
		'order_item_meta_filter_2_value_2_dynamic' => '',
		'order_item_meta_filter_2_op' => '=',
        'customer_role' => 0,
        'orderby' => 'order_id',
        'orderdir' => 'asc',
        'products' => 'all',
        'product_cats' => array(),
        'product_ids' => '',
        'product_tag_filter_on' => 0,
        'product_tag_filter' => '',
        'product_meta_filter_on' => 0,
        'product_meta_filter_key' => '',
        'product_meta_filter_value' => '',
        'product_meta_filter_value_2' => '',
        'product_meta_filter_op' => '=',
        'one_line_per_order' => 0,
        'exclude_free' => 0,
        'exclude_free_after_discount' => 0,
        'include_refunds' => 1,
        'include_shipping' => 0,
        'fields' => array('product_id', 'product_name', 'quantity', 'line_total', 'order_date', 'billing_name', 'billing_email'),
        'total_fields' => array('quantity', 'line_subtotal', 'line_total', 'line_tax', 'line_total_with_tax'),
        'order_total_once' => 0,
        'order_shipping_total_once' => 0,
        'field_names' => array(),
        'include_header' => 1,
        'include_totals' => 0,
        'format' => 'CSV',
        'format_csv_delimiter' => ',',
        'format_csv_surround' => '"',
        'format_csv_escape' => '\\',
        'shipping_product_name' => 'Shipping - [method_name]',
        'report_unfiltered' => 0,
        'ags_xoiwcp_debug' => 0,
        'format_amounts' => 0,
        'format_amounts_decimals' => 2,
        'format_amounts_decimal_sep' => '.',
        'format_amounts_thousands_sep' => '',
        'order_fields_once' => 0,
        'order_group_empty_row' => 0,
		'use_wp_date' => 0,
        'totals_by_type' => 0,
		'order_shipping_filter' => array_keys(ags_xoiwcp_get_order_shipping_filter_options())
    );
}

function ags_xoiwcp_get_saved_report_settings() {
	$savedReportSettings = get_option('ags_xoiwcp_report_settings');
	$oldSavedReportSettings = get_option('hm_xoiwcp_report_settings'); // backwards compat
    if (empty($savedReportSettings)) {
        $savedReportSettings = array(
            empty($oldSavedReportSettings) ? ags_xoiwcp_default_report_settings() : $oldSavedReportSettings[0]
        );
    }
	
	if ($oldSavedReportSettings) {
		unset($oldSavedReportSettings[0]);
		return array_merge($savedReportSettings, $oldSavedReportSettings);
	} else {
		return $savedReportSettings;
	}
}

// This function generates the page HTML
function ags_xoiwcp_page()
{
   
	$savedReportSettings = ags_xoiwcp_get_saved_report_settings();

    if (isset($_REQUEST['ags_xoiwcp_action'])) {
        if ($_REQUEST['ags_xoiwcp_action'] == 'preset-save' && !empty($_GET['preset']) && isset($savedReportSettings[$_GET['preset']])) {

            $_POST = stripslashes_deep($_POST);

            // Also update checkbox fields in on_init
            if (empty($_POST['include_header']))
                $_POST['include_header'] = 0;
            if (empty($_POST['include_refunds']))
                $_POST['include_refunds'] = 0;

            if (isset($savedReportSettings[$_GET['preset']]['key'])) {
                $_POST['key'] = $savedReportSettings[$_GET['preset']]['key'];
            }

            $savedReportSettings[$_GET['preset']] = apply_filters('ags_xoiwcp_report_settings', $_POST, 'save');
            update_option('ags_xoiwcp_report_settings', $savedReportSettings, false); 
			delete_option('hm_xoiwcp_report_settings'); // backwards compat
        } else if ($_REQUEST['ags_xoiwcp_action'] == 'preset-del' && !empty($_GET['preset']) && isset($savedReportSettings[$_GET['preset']])) {
            unset($savedReportSettings[$_GET['preset']]);
            update_option('ags_xoiwcp_report_settings', $savedReportSettings, false);
			delete_option('hm_xoiwcp_report_settings'); // backwards compat
            unset($_GET['preset']);
            echo('<script type="text/javascript">location.href = \'?page=hm_xoiwcp\';</script>');
            return;
        } else if ($_REQUEST['ags_xoiwcp_action'] == 'preset-create' && !empty($_POST['preset_name'])) {
            $savedReportSettings[] = apply_filters('ags_xoiwcp_report_settings', stripslashes_deep($_POST), 'save');
            update_option('ags_xoiwcp_report_settings', $savedReportSettings, false);
			delete_option('hm_xoiwcp_report_settings'); // backwards compat
            echo('<script type="text/javascript">location.href = \'?page=hm_xoiwcp&preset=' . (count($savedReportSettings) - 1) . '\';</script>');
            return;
        }
    }

    $reportSettings = apply_filters(
		'ags_xoiwcp_report_settings',
		array_merge(ags_xoiwcp_default_report_settings(),
			$savedReportSettings[isset($_GET['preset']) && isset($savedReportSettings[$_GET['preset']]) ? $_GET['preset'] : 0]
		),
		'view'
	);

    $fieldOptions = array(
        'product_id' => 'Product ID',
        'product_sku' => 'Product SKU',
        'product_name' => 'Product Name',
        'product_desc' => 'Product Description',
        'variation_id' => 'Variation ID',
        'variation_sku' => 'Variation SKU',
        'variation_attributes' => 'Variation Attributes',
        'item_sku' => 'Item SKU',
        'product_categories' => 'Product Categories',
        'order_id' => 'Order ID',
        'order_status' => 'Order Status',
        'order_total' => 'Order Total',
        'order_date' => 'Order Date/Time',
        'order_date_only' => 'Order Date',
        'order_parent' => 'Parent Order',
        'order_item_type' => 'Order Item Type',
        'order_item_name' => 'Line Item Name',
        'quantity' => 'Line Item Quantity',
        'line_subtotal' => 'Line Item Gross',
        'line_total' => 'Line Item Gross After Discounts',
        'line_tax' => 'Line Item Tax',
        'line_total_with_tax' => 'Line Item Total With Tax',
        'billing_name' => 'Billing Name',
        'billing_phone' => 'Billing Phone',
        'billing_email' => 'Billing Email',
        'billing_address' => 'Billing Address',
        'billing_state' => 'Billing State',
        'shipping_name' => 'Shipping Name',
        'shipping_phone' => 'Shipping Phone',
        'shipping_email' => 'Shipping Email',
        'shipping_address' => 'Shipping Address',
        'shipping_state' => 'Shipping State',
        'customer_order_note' => 'Customer Order Note',
        'order_note_most_recent' => 'Order Note - Most Recent',
		'order_notes_user' => 'User Order Notes',
        'order_shipping_methods' => 'Order Shipping Methods',
        'order_shipping_cost' => 'Order Shipping Cost',
        'order_shipping_tax' => 'Order Shipping Tax',
        'order_shipping_cost_with_tax' => 'Order Shipping Cost With Tax',
        'order_total_qty' => 'Order Total Item Quantity',
        'order_total_fees' => 'Total Order Fees',
        'order_total_fees_with_tax' => 'Total Order Fees With Tax',
        'customer_roles' => 'Customer User Roles',
        'creator_roles' => 'Order Creator User Roles',
		'order_item_id' => 'Order Item ID',
		'order_product_total' => 'Order Product Total'
    );

    include(dirname(__FILE__) . '/admin/admin.php');

}

add_filter('ags_xoiwcp_report_settings', 'ags_xoiwcp_customer_meta_compat');
function ags_xoiwcp_customer_meta_compat($reportSettings) {

	if (!empty($reportSettings['order_meta_filter_key']) && $reportSettings['order_meta_filter_key'][0] == 'C') {
		$reportSettings = array_merge(
			$reportSettings,
			[
				'order_meta_filter_on' => 0,
				'order_meta_filter_key' => '',
				'order_meta_filter_value' => '',
				'order_meta_filter_value_2' => '',
				'order_meta_filter_op' => '=',
				'customer_meta_filter_on' => $reportSettings['order_meta_filter_on'],
				'customer_meta_filter_key' => substr($reportSettings['order_meta_filter_key'], 1),
				'customer_meta_filter_value' => $reportSettings['order_meta_filter_value'],
				'customer_meta_filter_value_2' => $reportSettings['order_meta_filter_value_2'],
				'customer_meta_filter_op' => $reportSettings['order_meta_filter_op']
			]
		);
	}
	return $reportSettings;
}

function ags_xoiwcp_filter_nocache_headers($headers) {
	// Reference: https://owasp.org/www-community/OWASP_Application_Security_FAQ
	
	$cacheControl = array_map( 'trim', explode(',', $headers['Cache-Control']) );
	$cacheControl = array_unique( array_merge( [
		'no-cache',
		'no-store',
		'must-revalidate',
		'pre-check=0',
		'post-check=0',
		'max-age=0',
		's-maxage=0'
	], $cacheControl ) );
	
	$headers['Cache-Control'] = implode(', ', $cacheControl);
	$headers['Pragma'] = 'no-cache';
	
	return $headers;
}

// Hook into WordPress init; this function performs report generation when
// the admin form is submitted
add_action('init', 'ags_xoiwcp_on_init', 9999);
function ags_xoiwcp_on_init()
{
    global $pagenow;

    // Check if we are in admin and on the report page
    if (!is_admin())
        return;
    if ($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'hm_xoiwcp') {
		
		add_filter('nocache_headers', 'ags_xoiwcp_filter_nocache_headers', 9999);
		nocache_headers();
		
		if ( current_user_can('view_woocommerce_reports') && !empty($_REQUEST['ags_xoiwcp_action']) && $_REQUEST['ags_xoiwcp_action'] == 'run') {

			$savedReportSettings = ags_xoiwcp_get_saved_report_settings();
			if (empty($_POST) && isset($_GET['preset']) && isset($savedReportSettings[$_GET['preset']])) {
				$_POST = $savedReportSettings[$_GET['preset']];
			} else {
				// Process actual POST
				$_POST = stripslashes_deep($_POST);
			}

			$newSettings = array_intersect_key($_POST, ags_xoiwcp_default_report_settings());
			/*foreach ($newSettings as $key => $value)
				if (!is_array($value))
					$newSettings[$key] = htmlspecialchars($value);*/

			// Also update checkbox fields in preset-save
			if (empty($newSettings['include_header']))
				$newSettings['include_header'] = 0;
			if (empty($newSettings['include_refunds']))
				$newSettings['include_refunds'] = 0;

			// Update the saved report settings
			$savedReportSettings[0] = apply_filters('ags_xoiwcp_report_settings', array_merge(ags_xoiwcp_default_report_settings(), $newSettings), 'save');

			if (!empty($_POST['save_preset']))
				$savedReportSettings[] = apply_filters('ags_xoiwcp_report_settings', array_merge($savedReportSettings[0], array('preset_name' => strip_tags($_POST['save_preset']))), 'save');
			
			update_option('ags_xoiwcp_report_settings', $savedReportSettings, false);
			delete_option('hm_xoiwcp_report_settings'); // backwards compat
			
			$_POST = apply_filters('ags_xoiwcp_report_settings', $_POST, 'run');

			// Check if no fields are selected
			if (empty($_POST['fields']))
				return;
			
			// XLS format is no longer supported by this plugin
			if ($_POST['format'] == 'xls') {
				$_POST['format'] = 'xlsx';
			}

			// Assemble the filename for the report download
			if ($_POST['format'] != 'html' && $_POST['format'] != 'html-enhanced') {
				$filename = 'Order Items Export - ';
				$filename .= date('Y-m-d', current_time('timestamp'));
			}

			// Send headers
			if ($_POST['format'] == 'xlsx') {
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				$filename .= '.xlsx';
			} else if ($_POST['format'] == 'xls') {
				header('Content-Type: application/vnd.ms-excel');
				$filename .= '.xls';
			} else if ($_POST['format'] == 'html' || $_POST['format'] == 'html-enhanced') {
				header('Content-Type: text/html; charset=utf-8');
			} else if ($_POST['format'] == 'csv-ascii') {
				header('Content-Type: text/csv; charset=iso-8859-1');
				$filename .= '.csv';
			} else {
				header('Content-Type: text/csv; charset=utf-8');
				$filename .= '.csv';
			}
			if ($_POST['format'] != 'html' && $_POST['format'] != 'html-enhanced') {
				header('Content-Disposition: attachment; filename="' . $filename . '"');
			}

			// Output the report header row (if applicable) and body
			$stdout = fopen('php://output', 'w');
			if ($_POST['format'] == 'xlsx' || $_POST['format'] == 'xls') {
				include_once(__DIR__ . '/HM_XLS_Export.php');
				$dest = new HM_XOIWCP_Export\XLS();
			} else if ($_POST['format'] == 'html') {
				include_once(__DIR__ . '/HM_HTML_Export.php');
				$dest = new HM_XOIWCP_Export\HTML($stdout);
			} else if ($_POST['format'] == 'html-enhanced') {
				include_once(__DIR__ . '/HM_HTML_Enhanced_Export.php');
				$dest = new HM_XOIWCP_Export\HTMLEnhanced($stdout);
			} else if ($_POST['format'] == 'csv-ascii') {
				include_once(__DIR__ . '/HM_CSV_ASCII_Export.php');
				$dest = new HM_XOIWCP_Export\CSV_ASCII($stdout);
			} else {
				include_once(__DIR__ . '/HM_CSV_Export.php');
				$dest = new HM_XOIWCP_Export\CSV($stdout, array(
					'delimiter' => $_POST['format_csv_delimiter'],
					'surround' => $_POST['format_csv_surround'],
					'escape' => $_POST['format_csv_escape'],
				));
			}
			if (!empty($_POST['include_header']))
				ags_xoiwcp_export_header($dest);
			ags_xoiwcp_export_body($dest);

			if ($_POST['format'] == 'xlsx')
				$dest->outputXLSX('php://output');
			else if ($_POST['format'] == 'xls')
				$dest->outputXLS('php://output');
			else {
				// Call destructor, if any
				$dest = null;

				fclose($stdout);
			}

			exit;
		}
	
	}
}

function ags_xoiwcp_is_hpos()
{
	return method_exists('Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled') && Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
}

function ags_xoiwcp_on_before_woocommerce_init()
{
	class_exists('Automattic\WooCommerce\Utilities\FeaturesUtil') && Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__);
}
add_action('before_woocommerce_init', 'ags_xoiwcp_on_before_woocommerce_init');


// This function outputs the report header row
function ags_xoiwcp_export_header($dest)
{
    $header = array();

    foreach ($_POST['fields'] as $field) {
        $header[] = $_POST['field_names'][$field];
    }

    $dest->putRow($header, true);
}

// This function generates and outputs the report body rows
/*
The following function contains code copied from from WooCommerce; see license/woocommerce-license.txt for copyright and licensing information
*/
function ags_xoiwcp_export_body($dest)
{
    global $woocommerce, $wpdb, $hm_wc_report_extra_sql;
    $hm_wc_report_extra_sql = array();

    // Check order item types
    if (empty($_POST['order_item_types']))
        return;
    $_POST['order_item_types'] = array_intersect($_POST['order_item_types'], ags_xoiwcp_get_order_item_types());
    if (empty($_POST['order_item_types']))
        return;

    // Check order statuses
    if (empty($_POST['order_statuses']))
        return;
    $_POST['order_statuses'] = array_intersect($_POST['order_statuses'], array_keys(wc_get_order_statuses()));
    if (empty($_POST['order_statuses']))
        return;

    list($start_date, $end_date) = ags_xoiwcp_get_report_dates();

    // Assemble order by string
	$orderby = 'order_id ASC'; // actual ordering happens later on, this is the secondary order

    // Create a new WC_Admin_Report object
	if ( ags_xoiwcp_is_hpos() ) {
		include_once(__DIR__.'/includes/class-wc-admin-report-hpos.php');
		$wc_report = new WC_Admin_Report_HPOS_WPZ();
		$isHpos = true;
	} else {
		include_once($woocommerce->plugin_path().'/includes/admin/reports/class-wc-admin-report.php');
		$wc_report = new WC_Admin_Report();
		$isHpos = false;
	}
    $wc_report->start_date = $start_date;
    $wc_report->end_date = $end_date;

    // Get report data

    $reportData = array(
        '_product_id' => array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'product_id',
            //'join_type' => (empty($_POST['include_shipping']) ? 'INNER' : 'LEFT')
            'join_type' => 'LEFT'
        ),
        '_variation_id' => array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'variation_id',
            'join_type' => 'LEFT'
        ),
        'order_id' => array(
            'type' => 'order_item',
            'function' => '',
            'name' => 'order_id',
            //'join_type' => 'LEFT'
        ),
        'order_item_id' => array(
            'type' => 'order_item',
            'function' => '',
            'name' => 'order_item_id',
            //'join_type' => 'LEFT'
        ),
        'order_item_type' => array(
            'type' => 'order_item',
            'function' => '',
            'name' => 'order_item_type'
        )
    );

    if (in_array('quantity', $_POST['fields'])) {
        $reportData['_qty'] = array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'quantity',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('line_subtotal', $_POST['fields'])) {
        $reportData['_line_subtotal'] = array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'line_subtotal',
            'join_type' => 'LEFT'
        );
    }

    if (in_array('line_total', $_POST['fields']) || in_array('line_total_with_tax', $_POST['fields'])) {
        $reportData['_line_total'] = array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'line_total',
            'join_type' => 'LEFT'
        );
    }

    if (in_array('order_status', $_POST['fields'])) {
        $reportData[$isHpos ? 'status' : 'post_status'] = array(
            'type' => 'post_data',
            'function' => '',
            'name' => 'order_status'
        );
    }
    if (in_array('order_total', $_POST['fields'])) {
        $reportData['_order_total'] = array(
            'type' => 'meta',
            'function' => '',
            'name' => 'order_total',
            'join_type' => 'LEFT'
        );
    }
    if ( in_array('creator_roles', $_POST['fields']) ) {
        $reportData[$isHpos ? 'customer_id' : 'post_author'] = array(
            'type' => 'post_data',
            'function' => '',
            'name' => 'order_creator_id'
        );
    }
    if (in_array('order_date', $_POST['fields']) || in_array('order_date_only', $_POST['fields'])) {
        $reportData[$isHpos ? 'date_created_gmt' : 'post_date'] = array(
            'type' => 'post_data',
            'function' => '',
            'name' => 'order_date'
        );
    }
    if (in_array('billing_name', $_POST['fields'])) {
        $reportData['_billing_first_name'] = array(
            'type' => 'meta',
            'name' => 'billing_first_name',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_billing_last_name'] = array(
            'type' => 'meta',
            'name' => 'billing_last_name',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('billing_phone', $_POST['fields'])) {
        $reportData['_billing_phone'] = array(
            'type' => 'meta',
            'name' => 'billing_phone',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('billing_email', $_POST['fields'])) {
        $reportData['_billing_email'] = array(
            'type' => 'meta',
            'name' => 'billing_email',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('billing_address', $_POST['fields'])) {
        $reportData['_billing_address_1'] = array(
            'type' => 'meta',
            'name' => 'billing_address_1',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_billing_address_2'] = array(
            'type' => 'meta',
            'name' => 'billing_address_2',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_billing_city'] = array(
            'type' => 'meta',
            'name' => 'billing_city',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_billing_postcode'] = array(
            'type' => 'meta',
            'name' => 'billing_postcode',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $hasBillingAddressField = true;
    }
    if (!empty($hasBillingAddressField) || in_array('billing_state', $_POST['fields'])) {
        $reportData['_billing_state'] = array(
            'type' => 'meta',
            'name' => 'billing_state',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_billing_country'] = array(
            'type' => 'meta',
            'name' => 'billing_country',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('shipping_name', $_POST['fields'])) {
        $reportData['_shipping_first_name'] = array(
            'type' => 'meta',
            'name' => 'shipping_first_name',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_shipping_last_name'] = array(
            'type' => 'meta',
            'name' => 'shipping_last_name',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('shipping_phone', $_POST['fields'])) {
        $reportData['_shipping_phone'] = array(
            'type' => 'meta',
            'name' => 'shipping_phone',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('shipping_email', $_POST['fields'])) {
        $reportData['_shipping_email'] = array(
            'type' => 'meta',
            'name' => 'shipping_email',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('shipping_address', $_POST['fields'])) {
        $reportData['_shipping_address_1'] = array(
            'type' => 'meta',
            'name' => 'shipping_address_1',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_shipping_address_2'] = array(
            'type' => 'meta',
            'name' => 'shipping_address_2',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_shipping_city'] = array(
            'type' => 'meta',
            'name' => 'shipping_city',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_shipping_postcode'] = array(
            'type' => 'meta',
            'name' => 'shipping_postcode',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $hasShippingAddressField = true;
    }
    if (!empty($hasShippingAddressField) || in_array('shipping_state', $_POST['fields'])) {
        $reportData['_shipping_state'] = array(
            'type' => 'meta',
            'name' => 'shipping_state',
            'function' => '',
            'join_type' => 'LEFT'
        );
        $reportData['_shipping_country'] = array(
            'type' => 'meta',
            'name' => 'shipping_country',
            'function' => '',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('customer_order_note', $_POST['fields'])) {
        $reportData[$isHpos ? 'customer_note' : 'post_excerpt'] = array(
            'type' => 'post_data',
            'function' => '',
            'name' => 'customer_order_note'
        );
    }
    if (in_array('line_total_with_tax', $_POST['fields']) || in_array('line_tax', $_POST['fields'])) {
        $reportData['_line_tax'] = array(
            'type' => 'order_item_meta',
            //'order_item_type' => 'line_item',
            'function' => '',
            'name' => 'line_tax',
            'join_type' => 'LEFT'
        );
    }
    if (in_array('order_item_name', $_POST['fields'])) {
        $reportData['order_item_name'] = array(
            'type' => 'order_item',
            'function' => '',
            'name' => 'order_item_name'
        );
    }

    // Shipping line item fields
    if (!empty($_POST['include_shipping'])) {
        $hasProductIdField = in_array('product_id', $_POST['fields']);
        if ($hasProductIdField || in_array('product_name', $_POST['fields'])) {
            if (in_array('product_name', $_POST['fields'])) {
                $shippingProductNameHasMethodName = strpos($_POST['shipping_product_name'], '[method_name]') !== false;
                $shippingProductNameHasLineName = strpos($_POST['shipping_product_name'], '[line_name]') !== false;

                if ($shippingProductNameHasMethodName) {
                    // We need this to resolve the shipping method names later on
                    $woocommerce->shipping->load_shipping_methods();
                    $shippingMethods = $woocommerce->shipping->get_shipping_methods();
                    if ($shippingProductNameHasLineName) {
                        $shippingProductNameMergeFields = array('[method_name]', '[line_name]');
                    } else {
                        $shippingMethodProductNames = array();
                        $shippingProductNameMergeFields = '[method_name]';
                    }
                }

                if ($shippingProductNameHasLineName) {
                    if (!isset($shippingProductNameMergeFields)) {
                        $shippingProductNameMergeFields = '[line_name]';
                    }
					if ( !isset( $reportData['order_item_name'] ) ) {
						$reportData['order_item_name'] = array(
							'type' => 'order_item',
							'function' => '',
							'name' => 'order_item_name'
						);
					}
                }
            }

            if ($hasProductIdField || $shippingProductNameHasMethodName) {
                $reportData['method_id'] = array(
                    'type' => 'order_item_meta',
                    //'order_item_type' => 'shipping',
                    'function' => '',
                    'name' => 'shipping_method_id',
                    'join_type' => 'LEFT'
                );
            }
        }
        if (in_array('line_subtotal', $_POST['fields']) || in_array('line_total', $_POST['fields']) || in_array('line_total_with_tax', $_POST['fields'])) {
            $reportData['cost'] = array(
                'type' => 'order_item_meta',
                //'order_item_type' => 'shipping',
                'function' => '',
                'name' => 'shipping_cost',
                'join_type' => 'LEFT'
            );
        }
        if (in_array('line_total_with_tax', $_POST['fields']) || in_array('line_tax', $_POST['fields'])) {
            $reportData['taxes'] = array(
                'type' => 'order_item_meta',
                //'order_item_type' => 'shipping',
                'function' => '',
                'name' => 'shipping_taxes',
                'join_type' => 'LEFT'
            );
        }

        $_POST['order_item_types'][] = 'shipping';
    }
	
    foreach ($_POST['fields'] as $customField) {
		if ( strlen($customField) > 4 && $customField[0] == '_'  && $customField[1] == '_' ) {
			$secondDoubleUnderscorePos = strpos($customField, '__', 2);
			if ($secondDoubleUnderscorePos) {
				$fieldType = substr($customField, 2, $secondDoubleUnderscorePos - 2);
				if ($fieldType == 'shop_order' || $fieldType == 'order_item') {
					$fieldName = substr($customField, $secondDoubleUnderscorePos + 2);
					if (preg_match('/^[a-z0-9_]+$/i', $fieldName)) {
						$reportData[$fieldName] = array(
							'type' => ($fieldType == 'shop_order' ? 'meta' : 'order_item_meta'),
							'name' => $customField,
							'function' => '',
							'join_type' => 'LEFT'
						);
					}
				}
			}
		}
    }

    if ($_POST['products'] == 'ids') {
        $product_ids = array();
        foreach (explode(',', $_POST['product_ids']) as $productId) {
            $productId = trim($productId);
            if (is_numeric($productId))
                $product_ids[] = $productId;
        }
    }
    if ($_POST['products'] == 'cats' || !empty($_POST['product_tag_filter_on']) || !empty($_POST['product_meta_filter_on'])) {
        $params = array(
            'post_type' => 'product',
            'post_status' => 'any',
            'nopaging' => true,
            'fields' => 'ids',
            'ignore_sticky_posts' => true,
            'tax_query' => array()
        );

        if (isset($product_ids)) {
            $params['post__in'] = $product_ids;
        }
        if ($_POST['products'] == 'cats') {
            $cats = array();
            foreach ($_POST['product_cats'] as $cat)
                if (is_numeric($cat))
                    $cats[] = $cat;
            $params['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'terms' => $cats
            );
        }
        if (!empty($_POST['product_tag_filter_on'])) {
            $tags = array();
            foreach (explode(',', $_POST['product_tag_filter']) as $tag) {
                $tag = trim($tag);
                if (!empty($tag))
                    $tags[] = $tag;
            }
            $params['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'name',
                'terms' => $tags
            );
        }

        if (count($params['tax_query']) > 1) {
            $params['tax_query']['relation'] = 'AND';
        }

        // Product meta field filtering
        if (!empty($_POST['product_meta_filter_on'])) {
            if (in_array($_POST['product_meta_filter_op'], array('=', '!=', '<', '<=', '>', '>=', 'BETWEEN'))) {

	            $firstFilterValue = $_POST['product_meta_filter_op'] == 'BETWEEN'
		            ? $_POST['product_meta_filter_value']
		            : ags_xoiwcp_process_filter_value($_POST['product_meta_filter_value']);

	            $params['meta_query'] = array(array(
                    'key' => $_POST['product_meta_filter_key'],
                    'compare' => $_POST['product_meta_filter_op'],
                    'value' => ($_POST['product_meta_filter_op'] == 'BETWEEN' ? array($firstFilterValue, $_POST['product_meta_filter_value_2']) : $firstFilterValue)
                ));
	            if (is_numeric($firstFilterValue) &&
                    ($_POST['product_meta_filter_op'] != 'BETWEEN' || is_numeric($_POST['product_meta_filter_value_2']))) {
                    $params['meta_query'][0]['type'] = 'NUMERIC';
                }
            }
        }
		
        $product_ids = get_posts($params);

    }

    // Customer filtering
	$hasCustomerMetaFiltering = !empty($_POST['customer_meta_filter_on']) && !empty($_POST['customer_meta_filter_key']) && in_array($_POST['customer_meta_filter_op'], array('=', '!=', '<', '<=', '>', '>=', 'BETWEEN'));
    if ((!empty($_POST['customer_role']) && $_POST['customer_role'] != -1) || $hasCustomerMetaFiltering) {
        $getUsersArgs = array('fields' => 'ID');

        // Customer User order field filter
        if ($hasCustomerMetaFiltering) {

            // If the customer role filter is set to Guest Customers AND a customer user meta field filter is enabled, $customerIds is empty
            if (!empty($_POST['customer_role']) && $_POST['customer_role'] == -1) {
                $customerIds = array();
            } else {
				$metaValue = ags_xoiwcp_process_filter_value($_POST['customer_meta_filter_value']);
                $getUsersArgs['meta_query'] = array(array(
                    'key' => esc_sql($_POST['customer_meta_filter_key']),
                    'compare' => $_POST['customer_meta_filter_op'],
                    'value' => ($_POST['customer_meta_filter_op'] == 'BETWEEN' ? array($metaValue, $_POST['customer_meta_filter_value_2']) : $metaValue)
                ));
                if (is_numeric($metaValue) &&
                    ($_POST['customer_meta_filter_op'] != 'BETWEEN' || is_numeric($_POST['customer_meta_filter_value_2']))) {
                    $getUsersArgs['meta_query'][0]['type'] = 'NUMERIC';
                }
            }
        }

        if (!isset($customerIds)) {
			// If we make it to this point, customer_role is not -1

            // Customer role
            if (!empty($_POST['customer_role'])) {
                $getUsersArgs['role'] = esc_sql($_POST['customer_role']);
            }

            $customerIds = get_users($getUsersArgs);
        }

    }

    if ((!isset($product_ids) || !empty($product_ids))
        && (!isset($customerIds) || !empty($customerIds))) { // Do not run the report if product_ids or customerIds is set and empty

        // Get WHERE conditions
        $where_meta = array();
        if (isset($product_ids)) {
            if (empty($_POST['include_shipping']) && !count(array_diff($_POST['order_item_types'], array('line_item')))) {
                $where_meta[] = array(
                    'type' => 'order_item_meta',
                    'meta_key' => '_product_id',
                    'operator' => 'in',
                    'meta_value' => $product_ids
                );
            } else {
                $productPostFilter = true;
            }
        }
        if (!empty($_POST['customer_role']) && $_POST['customer_role'] == -1) {
			if ($isHpos) {
				$additionalSql = ' AND IF(posts.type=\'shop_order\', posts.customer_id, (SELECT customer_id FROM '.$wpdb->prefix.'wc_orders WHERE '.$wpdb->prefix.'wc_orders.id=posts.parent_order_id))=0';
			} else {
				$additionalSql = ' AND EXISTS(SELECT 1 FROM ' . $wpdb->postmeta . ' WHERE post_id=IF(posts.post_type=\'shop_order\', posts.ID, posts.post_parent) AND meta_key=\'_customer_user\' AND meta_value=0)';
			}
            $hm_wc_report_extra_sql['where'] = (isset($hm_wc_report_extra_sql['where']) ? $hm_wc_report_extra_sql['where'] : '') . $additionalSql;
        } else if (isset($customerIds)) {
            if (count($customerIds) <= 1000) {
				if ($isHpos) {
					$additionalSql = ' AND '.(empty($customerIds) ? 'FALSE' : 'IF(posts.type=\'shop_order\', posts.customer_id, (SELECT customer_id FROM '.$wpdb->prefix.'wc_orders WHERE '.$wpdb->prefix.'wc_orders.id=posts.parent_order_id)) IN ('.implode(',', $customerIds).')');
				} else {
					$additionalSql = ' AND EXISTS(SELECT 1 FROM ' . $wpdb->postmeta . ' WHERE post_id=IF(posts.post_type=\'shop_order\', posts.ID, posts.post_parent) AND meta_key=\'_customer_user\' AND ' . (empty($customerIds) ? 'FALSE' : 'meta_value IN (' . implode(',', $customerIds) . ')') . ')';
				}
                $hm_wc_report_extra_sql['where'] = (isset($hm_wc_report_extra_sql['where']) ? $hm_wc_report_extra_sql['where'] : '') . $additionalSql;
            } else {
                // We need to filter customer IDs *after* the report has run
                $customerIdPostFilter = true;
            }
        }

		$orderMetaFilterSql = '';
		
		if ($isHpos) {
			include_once(__DIR__.'/includes/class-wc-admin-report-hpos.php');
			$virtualMeta = WC_Admin_Report_HPOS_WPZ::getVirtualOrderMeta();
		}

		for ($i = 1; $i < 4; ++$i) {
			$fieldPre = 'order_meta_filter_'.($i == 1 ? '' : $i.'_');

			if (!empty($_POST[$fieldPre.'on'])
				&& !empty($_POST[$fieldPre.'key']) && $_POST[$fieldPre.'key'][0] == 'O'
				&& in_array($_POST[$fieldPre.'op'], array('=', '!=', '<', '<=', '>', '>=', 'BETWEEN'))) {

				$metaValue = ags_xoiwcp_process_filter_value( $_POST[$fieldPre.'value'] );
				$metaValue = (is_numeric($metaValue) ? $metaValue : '\'' . esc_sql($metaValue) . '\'');
				if ($_POST[$fieldPre.'op'] == 'BETWEEN') {
					if (is_numeric($_POST[$fieldPre.'value_2'])) {
						$metaValue .= ' AND ' . $_POST[$fieldPre.'value_2'];
					} else {
						$metaValue .= ' AND \'' . esc_sql($_POST[$fieldPre.'value_2']) . '\'';
					}
				}
				
				$metaValueField = ($_POST[$fieldPre.'cast'] ?? null) == 'date' ? 'STR_TO_DATE(meta_value, \''.esc_sql($_POST[$fieldPre.'date_format']).'\')' : 'meta_value';

				$orderMetaFilterSql .= (empty($orderMetaFilterSql) ? '' : ($_POST[$fieldPre.'logic'] == 'OR' ? ' OR ' : ' AND '));
				
				$orderMetaKey = substr($_POST[$fieldPre.'key'], 1);
				
				if ($isHpos && isset($virtualMeta[$orderMetaKey])) {
					
					$subquery = $virtualMeta[$orderMetaKey]['filterSubquery'];
					$subqueryConditionPos = strpos($subquery, '%condition%');
					$subqueryConditionAndPos = strrpos($subquery, 'AND ', $subqueryConditionPos - strlen($subquery));
					
					if ($subqueryConditionPos === false || $subqueryConditionAndPos === false) {
						throw new Exception();
					}
					
					$subquery = substr($subquery, 0, $subqueryConditionAndPos + 4)
									.str_replace('meta_value', substr($subquery, $subqueryConditionAndPos + 4, $subqueryConditionPos - $subqueryConditionAndPos - 4), $metaValueField)
									.substr($subquery, $subqueryConditionPos);
					
					$orderMetaFilterSql .= 'EXISTS('
								.str_replace(
									[
										'%orderId%',
										'%condition%'
									],
									[
										'IF(posts.'.($isHpos ? 'type' : 'post_type')
											.'=\'shop_order\', posts.'.($isHpos ? 'id' : 'ID')
											.', posts.'.($isHpos ? 'parent_order_id' : 'post_parent')
											.')',
										$_POST[$fieldPre.'op'] . ' ' . $metaValue
									],
									$subquery
								)
								.')';
				} else {
					$orderMetaFilterSql .= 'EXISTS(
							SELECT 1 FROM ' .($isHpos ? $wpdb->prefix.'wc_orders_meta' : $wpdb->postmeta) . '
								WHERE ' .($isHpos ? 'order_id' : 'post_id') . '=IF(posts.'.($isHpos ? 'type' : 'post_type').'=\'shop_order\', posts.'.($isHpos ? 'id' : 'ID').', posts.'.($isHpos ? 'parent_order_id' : 'post_parent').')
									AND meta_key=\'' . esc_sql($orderMetaKey) . '\'
									AND '.$metaValueField.' ' . $_POST[$fieldPre.'op'] . ' ' . $metaValue. '
							)';
				}
			}

		}

		if ($orderMetaFilterSql) {
			$hm_wc_report_extra_sql['where'] = (isset($hm_wc_report_extra_sql['where']) ? $hm_wc_report_extra_sql['where'] : '') . ' AND ('.$orderMetaFilterSql.')';
		}
		
		
		$hm_wc_report_extra_sql['where'] = (isset($hm_wc_report_extra_sql['where']) ? $hm_wc_report_extra_sql['where'] : '').ags_xoiwcp_get_order_item_field_filter_sql();
		
		if (is_array($_POST['order_shipping_filter'] ?? null)) {
			$hm_wc_report_extra_sql['where'] .= ags_xoiwcp_get_order_shipping_filter_sql();
		}

        // Zero-amount item filtering
        if (!empty($_POST['exclude_free'])) {
            if (empty($_POST['include_shipping']) && !count(array_diff($_POST['order_item_types'], array('line_item')))) {
                $where_meta[] = array(
                    'type' => 'order_item_meta',
                    'meta_key' => (empty($_POST['exclude_free_after_discount']) ? '_line_subtotal' : '_line_total'),
                    'operator' => '!=',
                    'meta_value' => 0
                );
            } else {
                $zeroAmountPostFilter = true;
                $zeroAmountFilterField = (empty($_POST['exclude_free_after_discount']) ? 'line_subtotal' : 'line_total');
                if (empty($_POST['exclude_free_after_discount'])) {
                    if (!isset($reportData['_line_subtotal'])) {
                        $reportData['_line_subtotal'] = array(
                            'type' => 'order_item_meta',
                            //'order_item_type' => 'line_item',
                            'function' => '',
                            'name' => 'line_subtotal',
                            'join_type' => 'LEFT'
                        );
                    }
                } else if (!isset($reportData['_line_total'])) {
                    $reportData['_line_total'] = array(
                        'type' => 'order_item_meta',
                        //'order_item_type' => 'line_item',
                        'function' => '',
                        'name' => 'line_total',
                        'join_type' => 'LEFT'
                    );
                }
            }
        }

        // Add the customer user field, if necessary
        if (empty($customerIdPostFilter)) {
			if ( in_array('customer_roles', $_POST['fields']) ) {
				$hasCustomerUserField = true;
			} else {
				foreach ($_POST['fields'] as $field) {
					if (substr($field, 0, 17) == '__customer_user__') {
						$hasCustomerUserField = true;
						break;
					}
				}
			}
            
        }
        if (!empty($customerIdPostFilter) || !empty($hasCustomerUserField)) {
            if (isset($reportData[$isHpos ? 'customer_id' : '_customer_user'])) {
                $customerIdField = $reportData[$isHpos ? 'customer_id' : '_customer_user']['name'];
            } else {
                $reportData[$isHpos ? 'customer_id' : '_customer_user'] = array(
                    'type' => $isHpos ? 'post_data' : 'meta',
                    'function' => '',
                    'name' => '_customer_user',
                    'join_type' => 'LEFT'
                );
                $customerIdField = '_customer_user';
            }
        }

        // Check if WooCommerce is pre-3.0
        $wcPre3 = (version_compare($woocommerce->version, '3.0') < 0);

        // Fix refund quantity issues in WC < 2.6.0; order_type is also needed for customer ID post filter
        $needsRefundQtyFix = version_compare(get_option('woocommerce_db_version', '1.0'), '2.6.0', '<');
        if ($needsRefundQtyFix || isset($customerIdPostFilter)) {
            $reportData[$isHpos ? 'type' : 'post_type'] = array(
                'type' => 'post_data',
                'function' => '',
                'name' => 'order_type'
            );
        }
        // We need the parent order ID for customer ID post filtering
        if ( isset($customerIdPostFilter) || in_array('order_parent', $_POST['fields']) ) {
            $reportData[$isHpos ? 'parent_order_id' : 'post_parent'] = array(
                'type' => 'post_data',
                'function' => '',
                'name' => 'parent_order_id'
            );
        }

        // Custom date range time fields
        $where = array(array(
            'key' => 'order_item_type',
            'operator' => 'IN',
            'value' => $_POST['order_item_types']
        ));
        if ($_POST['report_time'] == 'custom') {
            $where[] = array(
                'key' => $isHpos ? 'date_created_gmt' : 'post_date',
                'operator' => '>=',
                'value' => $isHpos ? get_gmt_from_date(date('Y-m-d H:i:s', $start_date)) : date('Y-m-d H:i:s', $start_date)
            );
            $where[] = array(
                'key' => $isHpos ? 'date_created_gmt' : 'post_date',
                'operator' => '<',
                'value' => $isHpos ? get_gmt_from_date(date('Y-m-d H:i:s', $end_date)) : date('Y-m-d H:i:s', $end_date)
            );
        }

        // Remove existing filters if the unfiltered option is on
        if (!empty($_POST['report_unfiltered'])) {
            remove_all_filters('woocommerce_reports_get_order_report_data_args');
            remove_all_filters('woocommerce_reports_order_statuses');
            remove_all_filters('woocommerce_reports_get_order_report_query');
            remove_all_filters('woocommerce_reports_get_order_report_data');
        }

        // Filter order statuses
        add_filter('woocommerce_reports_order_statuses', 'ags_xoiwcp_report_order_statuses', 9999);
        // Order status array has been sanitized and checked non-empty above
        $statusesStr = '';
        foreach ($_POST['order_statuses'] as $i => $orderStatus) {
            $statusesStr .= ($i ? ',\'' : '\'') . esc_sql($orderStatus) . '\'';
        }
        $hm_wc_report_extra_sql['where'] = (isset($hm_wc_report_extra_sql['where']) ? $hm_wc_report_extra_sql['where'] : '') . ' AND ' .
            (empty($_POST['include_refunds']) ? '(' : 'IF(posts.'.($isHpos ? 'type' : 'post_type').'=\'shop_order_refund\',
			(posts.'.($isHpos ? 'status' : 'post_status').'=\'wc-completed\' AND EXISTS(SELECT 1 FROM ' . ($isHpos ?  $wpdb->prefix.'wc_orders' : $wpdb->posts) . ' WHERE ' . ($isHpos ? $wpdb->prefix.'wc_orders.id' : $wpdb->posts.'.ID').'=posts.'.($isHpos ? 'parent_order_id' : 'post_parent').' AND '.($isHpos ? 'status' : 'post_status').' IN(' . $statusesStr . '))),') .
            'posts.'.($isHpos ? 'status' : 'post_status').' IN(' . $statusesStr . '))';

        // Filter report query
        add_filter('woocommerce_reports_get_order_report_query', 'ags_xoiwcp_filter_report_query');

        // Avoid max join size error
        $wpdb->query('SET SQL_BIG_SELECTS=1');

        $reportParams = array(
            'data' => $reportData,
            'nocache' => true, // added by JH 2019-12-17
            'query_type' => 'get_results',
            'order_by' => $orderby,
            'filter_range' => ($_POST['report_time'] != 'all' && $_POST['report_time'] != 'custom'),
            'order_types' => (empty($_POST['include_refunds']) ? array('shop_order') : array('shop_order', 'shop_order_refund')),
            //'order_status' => $orderStatuses,
            'where' => $where,
            'where_meta' => $where_meta,
            'group_by' => (empty($_POST['one_line_per_order']) ? 'order_item_id' : 'order_id')
        );

        if (!empty($_POST['ags_xoiwcp_debug'])) {
            $reportParams['debug'] = true;
        }

        // Based on woocoommerce/includes/admin/reports/class-wc-report-sales-by-product.php
        $sold_products = $wc_report->get_order_report_data($reportParams);

        // Remove report order statuses filter
        remove_filter('woocommerce_reports_order_statuses', 'ags_xoiwcp_report_order_statuses', 9999);

        // Remove report query filter
        remove_filter('woocommerce_reports_get_order_report_query', 'ags_xoiwcp_filter_report_query');

        $addonFields = ags_xoiwcp_get_addon_fields();

        if (!empty($_POST['include_totals']) && !empty($_POST['total_fields'])) {
			$totals = [ 'default' => array_combine($_POST['total_fields'], array_fill(0, count($_POST['total_fields']), 0)) ];
			$totalsByType = !empty($_POST['totals_by_type']);
        }

        $orderShippingCache = array();
        if (!empty($_POST['order_total_once']) || !empty($_POST['order_shipping_total_once'])) {
            $orderTotalSkipIds = array();
            $orderTotalSkipFields = (empty($_POST['order_shipping_total_once']) ? array() : array('order_shipping_cost', 'order_shipping_cost_with_tax', 'order_shipping_tax'));
            if (!empty($_POST['order_total_once'])) {
                $orderTotalSkipFields[] = 'order_total';
            }
        }

        // For post-query order meta filtering
        /*$skipOrderIds = array();*/

        // hm-product-sales-report-pro/hm-product-sales-report.php
	    $rows = array();
	    $orderIndex = array_search($_POST['orderby'], $_POST['fields']);


	    $lastOrderId = 0;


        // Output report rows
        foreach ($sold_products as $product) {
            $isShipping = ($product->order_item_type == 'shipping');

	        if ($product->order_id != $lastOrderId) {
		        $skipOrderFields = false;
		        if ( !empty($_POST['order_group_empty_row']) && isset($sortOrderFieldValue) ) { // $sortOrderFieldValue is from the previous row
			        $rows[ $sortOrderFieldValue ][] = array_fill(0, count($_POST['fields']), '');
		        }
		        $lastOrderId = $product->order_id;
	        } else {
		        $skipOrderFields = !empty($_POST['order_fields_once']);
	        }




	        // Apply order meta filter, if necessary
            /*if (!empty($orderMetaPostFilter)) {
                if (in_array($product->order_id, $skipOrderIds)) {
                    continue;
                }
                $filterValue = get_post_meta($product->order_id, substr($_POST['order_meta_filter_key'], 1), true);
                switch ($_POST['order_meta_filter_op']) {
                    case '=':
                        if ($filterValue != $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case '!=':
                        if ($filterValue == $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case '<':
                        if ($filterValue >= $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case '<=':
                        if ($filterValue > $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case '>':
                        if ($filterValue <= $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case '>=':
                        if ($filterValue < $_POST['order_meta_filter_value']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                    case 'BETWEEN':
                        if ($filterValue < $_POST['order_meta_filter_value'] || $filterValue > $_POST['order_meta_filter_value_2']) {
                            $skipOrderIds[] = $product->order_id;
                            continue 2;
                        }
                        break;
                }
            }*/

            // Apply customer ID filter, if necessary
            if (!empty($customerIdPostFilter)) {
				if ($product->order_type == 'shop_order') {
					if (!in_array($product->$customerIdField, $customerIds)) {
						continue;
					}
				} else if ($isHpos) { // refund order with HPOS
					$cuParentOrder = wc_get_order($product->parent_order_id);
					if (!in_array($cuParentOrder->get_customer_id(), $customerIds)) {
						continue;
					}
				} else { // refund order without HPOS
					if (!in_array(get_post_meta($product->parent_order_id, '_customer_user', true), $customerIds)) {
						continue;
					}
				}
            }

            // Apply product filter, if necessary
            if (!empty($productPostFilter) && $product->product_id !== null && !in_array($product->product_id, $product_ids)) {
                continue;
            }

            // Apply zero amount filter, if necessary
            if (!empty($zeroAmountPostFilter) && ($isShipping ? $product->shipping_cost : $product->$zeroAmountFilterField) == 0) {
                continue;
            }

            $formatAmounts = !empty($_POST['format_amounts']);

            // Calculate shipping line tax
            if ($isShipping) {

                if (class_exists('WC_Order_Item_Shipping')) { // WC 3.0+

                    $oi = new WC_Order_Item_Shipping($product->order_item_id);
                    $product->line_tax = $oi->get_total_tax();

                } else if (isset($product->shipping_taxes)) {
                    $product->line_tax = 0;
                    $taxArray = @unserialize($product->shipping_taxes);
                    if (!empty($taxArray)) {
                        foreach ($taxArray as $taxItem) {
                            $product->line_tax += $taxItem;
                        }
                    }
                }

            }

            $row = array();

	        foreach ($_POST['fields'] as $fieldIndex => $field) {
				
		        $isOrderField = false;
                if (isset($addonFields[$field]['cb'])) {
                    $row[] = call_user_func($addonFields[$field]['cb'], $product, null, $field);
                } else {
					
					$rowValue = '';
	                if ($skipOrderFields && $fieldIndex == $orderIndex) {
		                // Don't skip this order field for now because it is needed for sort
		                $_skipOrderFields = true;
		                $skipOrderFields = false;
	                }

                    switch ($field) {
                        case 'product_id':
                            if ($isShipping) {
                                $rowValue = $product->shipping_method_id;
                            } else {
                                $rowValue = $product->product_id;
                            }
                            break;
                        case 'order_id':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->order_id;
	                        break;
                        case 'order_status':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : wc_get_order_status_name($product->order_status);
	                        break;
                        case 'order_total':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        $rowValue = $formatAmounts ? number_format($product->order_total, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']) : $product->order_total;
	                        }
	                        break;
                        case 'order_item_type':
                            $rowValue = $product->order_item_type;
                            break;
                        case 'order_date':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : ($isHpos ? get_date_from_gmt($product->order_date) : $product->order_date);
	                        break;
                        case 'order_date_only':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : strstr(($isHpos ? get_date_from_gmt($product->order_date) : $product->order_date), ' ', true);
	                        break;
                        case 'order_parent':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->parent_order_id;
	                        break;
                        case 'product_sku':
                            $rowValue = (empty($product->product_id) ? '' : get_post_meta($product->product_id, '_sku', true));
                            break;
                        case 'product_name':
                            if ($isShipping) {
                                if ($shippingProductNameHasMethodName && isset($shippingMethodProductNames[$product->shipping_method_id])) {
                                    $rowValue = $shippingMethodProductNames[$product->shipping_method_id];
                                } else if (empty($shippingProductNameMergeFields)) {
                                    $rowValue = $_POST['shipping_product_name'];
                                } else {
                                    if ($shippingProductNameHasMethodName) {
                                        if (!empty($shippingMethods[$product->shipping_method_id]->method_title)) {
                                            $mergeValues = $shippingMethods[$product->shipping_method_id]->method_title;
                                        } else if (empty($product->shipping_method_id)) {
                                            $mergeValues = '';
                                        } else {
                                            $mergeValues = $product->shipping_method_id;
                                        }
                                    }

                                    if ($shippingProductNameHasLineName) {
                                        $mergeValues = ($shippingProductNameHasMethodName ? array($mergeValues, $product->order_item_name) : $product->order_item_name);
                                    }
                                    $productName = str_replace($shippingProductNameMergeFields, $mergeValues, $_POST['shipping_product_name']);
                                    $rowValue = $productName;
                                    if (isset($shippingMethodProductNames)) {
                                        $shippingMethodProductNames[$product->shipping_method_id] = $productName;
                                    }
                                }
                            } else {

                                if (!empty($product->product_id) && !isset($products[$product->product_id])) {
                                    if (!isset($products)) {
                                        $products = array();
                                    }
                                    $products[$product->product_id] = wc_get_product($product->product_id);
                                }

                                if (empty($product->product_id) || empty($products[$product->product_id])) {
                                    if (class_exists('WC_Order_Item')) {
                                        $orderItem = WC_Order_Factory::get_order_item($product->order_item_id);
                                        if (empty($orderItem)) {
                                            $rowValue = '';
                                        } else {
                                            $rowValue = $orderItem->get_name();
                                        }
                                    } else {
                                        $rowValue = '';
                                    }
                                } else {
                                    $rowValue = $products[$product->product_id]->get_title();
                                }
                            }
                            break;
                        case 'product_desc':
                            if (empty($product->product_id)) {
                                $rowValue = '';
                            } else {
                                $productPost = get_post($product->product_id);
                                if (empty($productPost)) {
                                    $rowValue = '';
                                } else {
                                    $rowValue = html_entity_decode(strip_tags(do_shortcode($productPost->post_content)));
                                }
                            }
                            break;
                        case 'product_categories':
                            if (empty($product->product_id)) {
                                $rowValue = '';
                            } else {
                                $terms = get_the_terms($product->product_id, 'product_cat');
                                if (empty($terms)) {
                                    $rowValue = '';
                                } else {
                                    $categories = array();
                                    foreach ($terms as $term)
                                        $categories[] = $term->name;
                                    $rowValue = implode(', ', $categories);
                                }
                            }
                            break;
                        case 'billing_name':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->billing_first_name.' '. $product->billing_last_name;
	                        break;
                        case 'billing_phone':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->billing_phone;
	                        break;
                        case 'billing_email':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->billing_email;
	                        break;
                        case 'billing_address':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        $addressComponents = array();
		                        if (!empty($product->billing_address_1))
			                        $addressComponents[] = $product->billing_address_1;
		                        if (!empty($product->billing_address_2))
			                        $addressComponents[] = $product->billing_address_2;
		                        if (!empty($product->billing_city))
			                        $addressComponents[] = $product->billing_city;
		                        if (!empty($product->billing_state))
			                        $addressComponents[] = $product->billing_state;
		                        if (!empty($product->billing_postcode))
			                        $addressComponents[] = $product->billing_postcode;
		                        if (!empty($product->billing_country))
			                        $addressComponents[] = $product->billing_country;
		                        $rowValue = implode(', ', $addressComponents);
	                        }
	                        break;
                        case 'billing_state':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        if (!isset($states[$product->billing_country])) {
			                        if (!isset($states)) {
				                        $states = array();
			                        }
			                        $states[$product->billing_country] = $woocommerce->countries->get_states($product->billing_country);
		                        }
		                        $rowValue = (isset($states[$product->billing_country][$product->billing_state]) ? $states[$product->billing_country][$product->billing_state] : '');
	                        }
	                        break;
                        case 'shipping_name':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->shipping_first_name.' '. $product->shipping_last_name;
	                        break;
                        case 'shipping_phone':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->shipping_phone;
	                        break;
                        case 'shipping_email':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->shipping_email;
	                        break;
                        case 'shipping_address':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        $addressComponents = array();
		                        if (!empty($product->shipping_address_1))
			                        $addressComponents[] = $product->shipping_address_1;
		                        if (!empty($product->shipping_address_2))
			                        $addressComponents[] = $product->shipping_address_2;
		                        if (!empty($product->shipping_city))
			                        $addressComponents[] = $product->shipping_city;
		                        if (!empty($product->shipping_state))
			                        $addressComponents[] = $product->shipping_state;
		                        if (!empty($product->shipping_postcode))
			                        $addressComponents[] = $product->shipping_postcode;
		                        if (!empty($product->shipping_country))
			                        $addressComponents[] = $product->shipping_country;
		                        $rowValue = implode(', ', $addressComponents);
	                        }

	                        break;
                        case 'shipping_state':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        if (!isset($states[$product->shipping_country])) {
			                        if (!isset($states)) {
				                        $states = array();
			                        }
			                        $states[$product->shipping_country] = $woocommerce->countries->get_states($product->shipping_country);
		                        }
		                        $rowValue = (isset($states[$product->shipping_country][$product->shipping_state]) ? $states[$product->shipping_country][$product->shipping_state] : '');
	                        }
	                        break;
                        case 'quantity':
                            $rowValue = ($needsRefundQtyFix && $product->order_type == 'shop_order_refund' ? $product->quantity * -1 : $product->quantity);
                            break;
                        case 'line_subtotal':
                            $amount = ($isShipping ? $product->shipping_cost : $product->line_subtotal);
                            $rowValue = $formatAmounts ? number_format($amount, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']) : $amount;
                            break;
                        case 'line_total':
                            $amount = ($isShipping ? $product->shipping_cost : $product->line_total);
                            $rowValue = $formatAmounts ? number_format($amount, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']) : $amount;
                            break;
                        case 'line_tax':
                            $rowValue = $formatAmounts ? number_format($product->line_tax, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']) : $product->line_tax;
                            break;
                        case 'line_total_with_tax':
                            $amount = ($isShipping ? $product->shipping_cost : $product->line_total) + $product->line_tax;
                            $rowValue = $formatAmounts ? number_format($amount, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']) : $amount;
                            break;
                        case 'variation_id':
                            $rowValue = (empty($product->variation_id) ? '' : $product->variation_id);
                            break;
                        case 'variation_sku':
                            $rowValue = (empty($product->variation_id) ? '' : get_post_meta($product->variation_id, '_sku', true));
                            break;
                        case 'variation_attributes':
                            if (empty($product->variation_id)) {
                                $rowValue = '';
                            } else {
                                $attr = wc_get_product_variation_attributes($product->variation_id);
                                foreach ($attr as $i => $v) {
                                    if ($v === '')
                                        unset($attr[$i]);
                                }
                                $rowValue = urldecode(implode(', ', $attr));
                            }
                            break;
                        case 'item_sku':
                            if (empty($product->product_id)) {
                                $rowValue = '';
                            } else {
                                $rowValue = (empty($product->variation_id) ? get_post_meta($product->product_id, '_sku', true) : get_post_meta($product->variation_id, '_sku', true));
                            }
                            break;
                        case 'customer_order_note':
	                        $isOrderField = true;
	                        $rowValue = $skipOrderFields ? '' : $product->customer_order_note;
	                        break;
                        case 'order_note_most_recent':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        // Copied from woocommerce/includes/admin/meta-boxes/class-wc-meta-box-order-notes.php and modified
		                        remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
		                        $note = get_comments(array(
			                        'post_id'   => $product->order_id,
			                        'orderby'   => 'comment_date',
			                        'order'     => 'DESC',
			                        'approve'   => 'approve',
			                        'type'      => 'order_note',
			                        'number'    => 1
		                        ));
		                        add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
		                        $rowValue = (empty($note[0]->comment_content) ? '' : $note[0]->comment_content);
	                        }

	                        break;
						case 'order_notes_user':
							$isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        // Copied from woocommerce/includes/admin/meta-boxes/class-wc-meta-box-order-notes.php and modified
		                        remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
		                        $notes = get_comments(array(
			                        'post_id'   => $product->order_id,
			                        'orderby'   => 'comment_date',
			                        'order'     => 'DESC',
			                        'approve'   => 'approve',
			                        'type'      => 'order_note',
		                        ));
		                        add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
								
								$rowValue = '';
								foreach ( $notes as $note ) {
									if ($note->comment_author !== 'WooCommerce' && $note->comment_content) {
										$rowValue .= ($rowValue ? "\n\n" : '').'['.$note->comment_date.' - '.$note->comment_author.']'."\n".$note->comment_content;
									}
								}
	                        }

	                        break;
                        case 'order_shipping_methods':
                        case 'order_shipping_cost':
                        case 'order_shipping_cost_with_tax':
                        case 'order_shipping_tax':
	                      $isOrderField = true;
	                       if ($skipOrderFields || $isShipping) {
		                    $rowValue = '';
                            } else {
                                if (!isset($orderShippingCache[$product->order_id])) {
                                    $orderShippingCache[$product->order_id] = ($wcPre3 ? ags_xoiwcp_get_order_shipping_fields_values_legacy($product->order_id, $_POST['fields']) : ags_xoiwcp_get_order_shipping_fields_values($product->order_id, $_POST['fields']));
                                }
                                $amount = ($orderShippingCache[$product->order_id] ? $orderShippingCache[$product->order_id][$field] : 'Error');
                                $rowValue = $field != 'order_shipping_methods' && $formatAmounts ? number_format($amount, $_POST['format_amounts_decimals'],$_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep']   ) : $amount;
                            }
                            break;
                        case 'order_total_qty':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        if (!isset($orders[$product->order_id])) {
			                        if (!isset($orders)) {
				                        $orders = array();
			                        }
			                        $orders[$product->order_id] = wc_get_order($product->order_id);
		                        }
		                        $rowValue = (empty($orders[$product->order_id]) ? '' : $orders[$product->order_id]->get_item_count());
	                        }
	                        break;
                        case 'order_total_fees':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        if (!isset($orders[$product->order_id])) {
			                        if (!isset($orders)) {
				                        $orders = array();
			                        }
			                        $orders[$product->order_id] = wc_get_order($product->order_id);
		                        }
								if ( $orders[$product->order_id] ) {
									$fees = $orders[$product->order_id]->get_items('fee');
									if (is_array($fees)) {
										$rowValue = 0;
										foreach ( $fees as $feeItem) {
											$rowValue += $feeItem->get_total();
										}
										if ( $formatAmounts ) {
											$rowValue = number_format( $rowValue, $_POST['format_amounts_decimals'], $_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep'] );
										}
									} else {
										$rowValue = '';
									}
								} else {
									$rowValue = '';
								}
	                        }
	                        break;
                        case 'order_total_fees_with_tax':
	                        $isOrderField = true;
	                        if ($skipOrderFields) {
		                        $rowValue = '';
	                        } else {
		                        if (!isset($orders[$product->order_id])) {
			                        if (!isset($orders)) {
				                        $orders = array();
			                        }
			                        $orders[$product->order_id] = wc_get_order($product->order_id);
		                        }
								if ( $orders[$product->order_id] ) {
									$fees = $orders[$product->order_id]->get_items('fee');
									if (is_array($fees)) {
										$rowValue = 0;
										foreach ( $fees as $feeItem) {
											$rowValue += $feeItem->get_total() + $feeItem->get_total_tax();
										}
										if ( $formatAmounts ) {
											$rowValue = number_format( $rowValue, $_POST['format_amounts_decimals'], $_POST['format_amounts_decimal_sep'], $_POST['format_amounts_thousands_sep'] );
										}
									} else {
										$rowValue = '';
									}
								} else {
									$rowValue = '';
								}
	                        }
	                        break;
                        case 'order_item_name':
                            $rowValue = $product->order_item_name;
                            break;
						case 'order_item_id':
							$rowValue = $product->order_item_id;
                            break;
						case 'customer_roles':
						case 'creator_roles':
							$isOrderField = true;
							if (!$skipOrderFields) {
                                $user = get_userdata( $field == 'customer_roles' ? $product->$customerIdField : $product->order_creator_id );
                                $rowValue = empty($user->roles) ? '' : implode(', ', array_map('translate_user_role', array_intersect_key( wp_roles()->get_names(), array_combine( $user->roles, $user->roles ) ) ) );
                            }
							break;
						case 'order_product_total':
							$isOrderField = true;
							if (!$skipOrderFields) {
								$order = wc_get_order($product->order_id);
								$rowValue = array_reduce($order->get_items(), function($total, $item) { return $total + $item->get_total(); }, 0);
							}


							break;
                        default:
							$fieldType = substr($field, 2, strpos($field, '__', 2) - 2);
							$fieldName = substr($field, strpos($field, '__', 2) + 2);
							switch ($fieldType) {
								case 'shop_order':
									$isOrderField = true;
									if ($skipOrderFields) {
										$fieldValue = '';
									} else {
										$fieldValue = (isset($product->$field) ? $product->$field : get_post_meta($product->order_id, $fieldName, true));
									}
									break;
								case 'order_item':
									$fieldValue = (isset($product->$field) ? $product->$field : wc_get_order_item_meta($product->order_item_id, $fieldName, true));
									break;
								case 'product':
									if (empty($product->product_id)) {
										$fieldValue = '';
									} else {
										$fieldValue = get_post_meta($product->product_id, $fieldName, true);
									}
									break;
								case 'product_variation':
									if (empty($product->variation_id)) {
										$fieldValue = '';
									} else {
										$fieldValue = get_post_meta($product->variation_id, $fieldName, true);
									}
									break;
								case 'customer_user':
									$isOrderField = true;
									$fieldValue = $skipOrderFields ? '' : get_user_meta($product->$customerIdField, $fieldName, true);
									break;
								default:
									$fieldValue = ''; // No field type match
							}

							$fieldValue = maybe_unserialize($fieldValue);
							$rowValue = (is_array($fieldValue) ? ags_xoiwcp_array_string($fieldValue) : $fieldValue);
                    }

                    $row[] = apply_filters('hm_xoiwcp_row_value', $rowValue, $field);
                }

		        if (!empty($_skipOrderFields)) {
			        if ($isOrderField) {
				        $clearSortOrderField = true;
			        }
			        unset($_skipOrderFields);
			        $skipOrderFields = true;
		        }
				if (isset($totals)) {
					if ($totalsByType && !isset($totals['t'.$product->order_item_type])) {
						$totals['t'.$product->order_item_type] = $totals['default'];
					}
					if (isset($totals[$totalsByType ? 't'.$product->order_item_type : 'default'][$field]) && (!$skipOrderFields || !$isOrderField) && (!isset($orderTotalSkipIds) || !in_array($field, $orderTotalSkipFields) || !isset($orderTotalSkipIds[$product->order_id]))) {
						$totals[$totalsByType ? 't'.$product->order_item_type : 'default'][$field] += (float) end($row);
					}
                }

            }

            if (isset($orderTotalSkipIds)) {
                // Skip future lines in this order for order and/or order shipping totals
                $orderTotalSkipIds[$product->order_id] = true;
            }


	        $sortOrderFieldValue = (string) $row[$orderIndex];
	        if (!empty($clearSortOrderField)) {
		        $row[$orderIndex] = '';
		        unset($clearSortOrderField);
	        }
	        if (isset($rows[$sortOrderFieldValue])) {
		        $rows[$sortOrderFieldValue][] = $row;
	        } else {
		        $rows[$sortOrderFieldValue] = array($row);
	        }

        }

	    if ($_POST['orderdir'] == 'desc') {
		    krsort($rows);
	    } else {
		    ksort($rows);
	    }

	    foreach ($rows as $filterValueRows) {
		    foreach ($filterValueRows as $row) {
			    $dest->putRow($row);
		    }
	    }


	    // Output the totals row(s) if applicable
        if (isset($totals)) {
			ksort($totals);
			foreach ($totals as $totalsRowId => $totalsRow) {
				if (!$totalsByType || $totalsRowId != 'default') {
					$row = array();
					foreach ($_POST['fields'] as $fieldId) {
						if ($fieldId == 'product_name') {
							$row[] = 'TOTAL';
						} else if ($fieldId == 'order_item_type' && $totalsByType) {
							$row[] = substr($totalsRowId, 1); // remove "t" prefix
						} else {
							$row[] = (isset($totalsRow[$fieldId]) ? $totalsRow[$fieldId] : '');
						}
					}
					$dest->putRow($row, false, true);
				}
			}
        }

    }
}

function ags_xoiwcp_get_order_item_field_filter_sql()
{
	global $wpdb;
	
	$sql = [];
	
	for ($i = 1; $i < 3; ++$i) {
			
		if (!empty($_POST['order_item_meta_filter_'.$i.'_on'])) {
			$operator = $_POST['order_item_meta_filter_'.$i.'_op'];
			if (in_array($operator, array('=', '!=', '<', '<=', '>', '>=', 'BETWEEN', 'EXISTS'))) {
				
				if ($operator != 'EXISTS') {
					$numericValue =
						empty($_POST['order_item_meta_filter_'.$i.'_value_dynamic'])
						&& is_numeric($_POST['order_item_meta_filter_'.$i.'_value'])
						&& (
							$_POST['order_item_meta_filter_'.$i.'_op'] != 'BETWEEN'
							|| (
								empty($_POST['order_item_meta_filter_'.$i.'_value_2_dynamic'])
								&& is_numeric($_POST['order_item_meta_filter_'.$i.'_value_2'])
							)
						);
				
					if (!empty($_POST['order_item_meta_filter_'.$i.'_value_dynamic'])) {
						$metaValue = '\''.esc_sql( ags_xoiwcp_resolve_dynamic_date( $_POST['order_item_meta_filter_'.$i.'_value_dynamic'], true, $_POST['use_wp_date'] ) ).'\'';
					} else if ($numericValue) {
						$metaValue = $_POST['order_item_meta_filter_'.$i.'_value'];
					} else {
						$metaValue = '\''.esc_sql($_POST['order_item_meta_filter_'.$i.'_value']).'\'';
					}
					
					if ($_POST['order_item_meta_filter_'.$i.'_op'] == 'BETWEEN') {
						
						if (!empty($_POST['order_item_meta_filter_'.$i.'_value_2_dynamic'])) {
							$metaValue .= ' AND \''.esc_sql( ags_xoiwcp_resolve_dynamic_date( $_POST['order_item_meta_filter_'.$i.'_value_2_dynamic'], true, $_POST['use_wp_date'] ) ).'\'';
						} else if ($numericValue) {
							$metaValue .= ' AND '.$_POST['order_item_meta_filter_'.$i.'_value_2'];
						} else {
							$metaValue .= ' AND \''.esc_sql($_POST['order_item_meta_filter_'.$i.'_value_2']).'\'';
						}
					}
				}
				
				
				$sql[] = 'EXISTS(
							SELECT 1 FROM '.$wpdb->prefix.'woocommerce_order_itemmeta
							WHERE order_item_id=order_items.order_item_id
									AND meta_key=\''.esc_sql($_POST['order_item_meta_filter_'.$i.'_key']).'\''
									.($operator == 'EXISTS' ? '' : ' AND meta_value'.($numericValue ? '*1' : '').' '.$_POST['order_item_meta_filter_'.$i.'_op'].' '.$metaValue)
							.')';
				
			} else if ($operator == 'NOTEXISTS') {
				
				$sql[] = 'NOT EXISTS(
							SELECT 1 FROM '.$wpdb->prefix.'woocommerce_order_itemmeta
							WHERE order_item_id=order_items.order_item_id
									AND meta_key=\''.esc_sql($_POST['order_item_meta_filter_'.$i.'_key']).'\'
						)';
				
			}
		}
		
	}
	
	return $sql ? ' AND ('.implode($_POST['order_item_meta_filter_2_logic'] == 'OR' ? ' OR ' : ' AND ', $sql).')' : '';
}

function ags_xoiwcp_get_order_shipping_filter_sql()
{
	global $wpdb;
	
	$methods = array_diff($_POST['order_shipping_filter'], ['-1']);
	$hasNoneMethod = count($methods) != count($_POST['order_shipping_filter']);
	
	if ($methods) {
		$instanceIds = [];
		foreach (WC_Shipping_Zones::get_zones() as $zone) {
			foreach ($zone['shipping_methods'] as $instanceId => $method) {
				if (in_array(strtolower($method->get_title()), $_POST['order_shipping_filter'])) {
					$instanceIds[] = $instanceId;
				}
			}
		}
		$sql = 'EXISTS(
					SELECT 1 FROM '.$wpdb->prefix.'woocommerce_order_items sfi
					JOIN '.$wpdb->prefix.'woocommerce_order_itemmeta sfim USING(order_item_id)
					WHERE sfi.order_id=IF(posts.type="shop_order_refund", '.(ags_xoiwcp_is_hpos() ? 'posts.parent_order_id, posts.id' : 'posts.post_parent, posts.ID').')
							AND sfi.order_item_type="shipping"
							AND sfim.meta_key="instance_id"
							AND sfim.meta_value IN ('.implode(',', array_map('intval', array_unique($instanceIds))).'))';
	}
	
		
	if ($hasNoneMethod) {
		$sql .= ($sql ? ' OR ' : '').'NOT EXISTS(
					SELECT 1 FROM '.$wpdb->prefix.'woocommerce_order_items sfi
					WHERE sfi.order_id=IF(posts.type="shop_order_refund", '.(ags_xoiwcp_is_hpos() ? 'posts.parent_order_id, posts.id' : 'posts.post_parent, posts.ID').')
							AND sfi.order_item_type="shipping")';
	}
	
	return ' AND ('.$sql.')';
	
}

function ags_xoiwcp_get_order_shipping_filter_options()
{
	$shippingMethods = ['-1' => '(no shipping)'];
	foreach (WC_Shipping_Zones::get_zones() as $zone) {
		foreach ($zone['shipping_methods'] as $method) {
			$methodTitle = $method->get_title();
			if ($methodTitle != '-1') {
				$shippingMethods[strtolower($methodTitle)] = $methodTitle;
			}
		}
	}
	
	return $shippingMethods;
}

function ags_xoiwcp_get_report_dates()
{
    // Calculate report start and end dates (timestamps)
    switch ($_POST['report_time']) {
        case '0d':
            $end_date = strtotime('midnight', current_time('timestamp'));
            $start_date = $end_date;
            break;
        case '1d':
            $end_date = strtotime('midnight', current_time('timestamp')) - 86400;
            $start_date = $end_date;
            break;
        case '7d':
            $end_date = strtotime('midnight', current_time('timestamp')) - 86400;
            $start_date = $end_date - (86400 * 6);
            break;
        case '1cm':
            $start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight -1month');
            $end_date = strtotime('+1month', $start_date) - 86400;
            break;
        case '0cm':
            $start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight');
            $end_date = strtotime('+1month', $start_date) - 86400;
            break;
        case '+1cm':
            $start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight +1month');
            $end_date = strtotime('+1month', $start_date) - 86400;
            break;
        case '+7d':
            $start_date = strtotime('midnight', current_time('timestamp')) + 86400;
            $end_date = $start_date + (86400 * 6);
            break;
        case '+30d':
            $start_date = strtotime('midnight', current_time('timestamp')) + 86400;
            $end_date = $start_date + (86400 * 29);
            break;
        case 'custom':
			if (!empty($_POST['report_start_dynamic'])) {
				$_POST['report_start'] = date('Y-m-d', strtotime($_POST['report_start_dynamic'], current_time('timestamp')));
			}
			if (!empty($_POST['report_end_dynamic'])) {
				$_POST['report_end'] = date('Y-m-d', strtotime($_POST['report_end_dynamic'], current_time('timestamp')));
			}
            $end_date = strtotime($_POST['report_end_time'], strtotime($_POST['report_end']));
            $start_date = strtotime($_POST['report_start_time'], strtotime($_POST['report_start']));
            break;
        default: // 30 days is the default
            $end_date = strtotime('midnight', current_time('timestamp')) - 86400;
            $start_date = $end_date - (86400 * 29);
    }
    return array($start_date, $end_date);
}

function ags_xoiwcp_array_string($arr)
{
    // Determine whether the array is indexed or associative
    $isIndexedArray = true;
    for ($i = 0; $i < count($arr); ++$i) {
        if (!isset($arr[$i])) {
            $isIndexedArray = false;
            break;
        }
    }

    $result = '';
    foreach ($arr as $key => $value) {
        $result .= (empty($result) ? '' : ', ') . ($isIndexedArray ? '' : $key . ': ') . (is_array($value) ? '(' . ags_xoiwcp_array_string($value) . ')' : $value);
    }

    return $result;
}

function ags_xoiwcp_get_order_item_fields()
{
	global $wpdb;
	$fields = $wpdb->get_col('SELECT DISTINCT meta_key FROM (
									SELECT meta_key
									FROM '.$wpdb->prefix.'woocommerce_order_itemmeta
									WHERE meta_key NOT IN ("_product_id", "_variation_id")
									ORDER BY order_item_id DESC
									LIMIT 10000
								) fields');
	sort($fields);
	return $fields;
}


function ags_xoiwcp_resolve_dynamic_date($value, $allowFormat = false, $useWpDate = false)
{
	
	$pipePos = strpos($value, '|');
	
	if ( $allowFormat && $pipePos !== false ) {
		if ( $pipePos ) {
			$dateValue = trim( substr($value, 0, $pipePos) );
		}
		if ( strlen($value) > $pipePos + 1 ) {
			$format = $pipePos ? trim( substr($value, $pipePos + 1) ) : '';
		}
	} else {
		$dateValue = trim($value);
	}
	
	if ( empty($dateValue) ) {
		$dateValue = current_time('timestamp');
	} else {
		$dateValue = strtotime($dateValue, current_time('timestamp'));
		if ( empty($dateValue) ) {
			return false;
		}
	}
	
	if ( empty($format) ) {
		$format = 'Y-m-d';
	}
	
	return
		$useWpDate
		? wp_date(
			$format,
			$dateValue,
			new DateTimeZone('UTC')
		)
		: date(
			$format,
			$dateValue
		);
}

add_action('wp_ajax_hm_xoiwcp_calc_dynamic_date', 'hm_xoiwcp_calc_dynamic_date');
function hm_xoiwcp_calc_dynamic_date()
{
	if (empty($_POST['date'])) {
		wp_send_json_error();
	}
	
	$result = ags_xoiwcp_resolve_dynamic_date( $_POST['date'], !empty($_POST['allowFormat']), !empty($_POST['use_wp_date']) );
	
	if (empty($result)) {
		wp_send_json_error();
	}
	wp_send_json_success($result);
}


function ags_xoiwcp_get_order_shipping_fields_values($orderId, $fields)
{
    $order = wc_get_order($orderId);
    if (empty($order)) {
        return false;
    }
    $shippingItems = $order->get_shipping_methods();
    if ($shippingItems === false) {
        return false;
    }

    $shippingFieldsValues = array();
    if (in_array('order_shipping_methods', $fields)) {
        $shippingFieldsValues['order_shipping_methods'] = '';
        foreach ($shippingItems as $shippingItem) {
            $shippingFieldsValues['order_shipping_methods'] = (empty($shippingFieldsValues['order_shipping_methods']) ? '' : ', ') . $shippingItem->get_method_title();
        }
    }
    if (in_array('order_shipping_cost', $fields) || in_array('order_shipping_cost_with_tax', $fields)) {
        $shippingFieldsValues['order_shipping_cost'] = 0;
        foreach ($shippingItems as $shippingItem) {
            $shippingFieldsValues['order_shipping_cost'] += $shippingItem->get_total();
        }
    }

    if (in_array('order_shipping_tax', $fields) || in_array('order_shipping_cost_with_tax', $fields)) {
        $shippingFieldsValues['order_shipping_tax'] = 0;
        foreach ($shippingItems as $shippingItem) {
            $shippingFieldsValues['order_shipping_tax'] += $shippingItem->get_total_tax();
        }
        $shippingFieldsValues['order_shipping_cost_with_tax'] = $shippingFieldsValues['order_shipping_cost'] + $shippingFieldsValues['order_shipping_tax'];
    }

    return $shippingFieldsValues;
}

function ags_xoiwcp_get_order_shipping_fields_values_legacy($orderId, $fields)
{
    $order = wc_get_order($orderId);
    if (empty($order)) {
        return false;
    }
    $shippingItems = $order->get_shipping_methods();
    if ($shippingItems === false) {
        return false;
    }

    $shippingFieldsValues = array();
    if (in_array('order_shipping_methods', $fields)) {
        $shippingFieldsValues['order_shipping_methods'] = '';
        foreach ($shippingItems as $shippingItem) {
            $shippingFieldsValues['order_shipping_methods'] = (empty($shippingFieldsValues['order_shipping_methods']) ? '' : ', ') . $shippingItem['name'];
        }
    }
    if (in_array('order_shipping_cost', $fields) || in_array('order_shipping_cost_with_tax', $fields)) {
        $shippingFieldsValues['order_shipping_cost'] = 0;
        foreach ($shippingItems as $shippingItem) {
            $shippingFieldsValues['order_shipping_cost'] += (empty($shippingItem['item_meta']['cost'][0]) ? 0 : $shippingItem['item_meta']['cost'][0]);
        }
    }

    if (in_array('order_shipping_tax', $fields) || in_array('order_shipping_cost_with_tax', $fields)) {
        $shippingFieldsValues['order_shipping_tax'] = 0;
        foreach ($shippingItems as $shippingItem) {
            if (isset($shippingItem['item_meta']['taxes'][0])) {
                $taxArray = @unserialize($shippingItem['item_meta']['taxes'][0]);
                if (!empty($taxArray)) {
                    foreach ($taxArray as $taxItem) {
                        $shippingFieldsValues['order_shipping_tax'] += $taxItem;
                    }
                }
            }
        }
        $shippingFieldsValues['order_shipping_cost_with_tax'] = $shippingFieldsValues['order_shipping_cost'] + $shippingFieldsValues['order_shipping_tax'];
    }

    return $shippingFieldsValues;
}

function ags_xoiwcp_get_custom_fields($byType = false)
{
    global $ags_xoiwcp_custom_fields, $ags_xoiwcp_custom_fields_by_type, $wpdb;

    $typeNames = array(
        'shop_order' => 'Order',
        'order_item' => 'Order Line Item',
        'customer_user' => 'Customer User',
        'product' => 'Product',
        'product_variation' => 'Product Variation',
    );
    if (!isset($ags_xoiwcp_custom_fields)) {
		$isHpos = ags_xoiwcp_is_hpos();
		
        $ags_xoiwcp_custom_fields = array();
        $ags_xoiwcp_custom_fields_by_type = array();
		
		$queries = [
			'SELECT DISTINCT "product" AS post_type, meta_key FROM (
				SELECT meta_key FROM ' . $wpdb->prefix . 'postmeta JOIN ' . $wpdb->prefix . 'posts ON (post_id=ID) WHERE post_type="product" ORDER BY ID DESC LIMIT 10000
			) t',
			'SELECT DISTINCT "product_variation" AS post_type, meta_key FROM (
				SELECT meta_key FROM ' . $wpdb->prefix . 'postmeta JOIN ' . $wpdb->prefix . 'posts ON (post_id=ID) WHERE post_type="product_variation" ORDER BY ID DESC LIMIT 10000
			) t',
			'SELECT DISTINCT "shop_order" AS post_type, meta_key FROM (
				SELECT meta_key
				FROM '.($isHpos ? $wpdb->prefix.'wc_orders_meta' : $wpdb->postmeta).'
				JOIN '.($isHpos ? $wpdb->prefix.'wc_orders' : $wpdb->posts).' orders ON ('.($isHpos ? 'order_id=orders.id' : 'post_id=ID').')
				WHERE '.($isHpos ? 'type' : 'post_type').'="shop_order"
				ORDER BY '.($isHpos ? 'orders.id' : 'ID').' DESC LIMIT 10000
			) t',
			'SELECT DISTINCT "order_item" AS post_type, meta_key FROM (
				SELECT meta_key FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta JOIN ' . $wpdb->prefix . 'woocommerce_order_items USING (order_item_id) ORDER BY order_item_id DESC LIMIT 10000
			) t',
			'SELECT DISTINCT "customer_user" AS post_type, meta_key FROM (
				SELECT meta_key FROM ' . $wpdb->prefix . 'usermeta ORDER BY user_id DESC LIMIT 10000
			) t',
			
		];

        $fields = @$wpdb->get_results( implode(' UNION ', $queries) );
		
        if (empty($fields)) {
            // The UNION query may fail if the tables have different collations.
            // In that case, we try to run each query separately.
			
			$fields = array_reduce( array_map( [ $wpdb, 'get_results' ], $queries ), 'array_merge', [] );
        }

        foreach ($fields as $field) {
            $fieldId = '__' . $field->post_type . '__' . $field->meta_key;
            $ags_xoiwcp_custom_fields[] = $fieldId;
            $ags_xoiwcp_custom_fields_by_type[$typeNames[$field->post_type]][$fieldId] = $field->meta_key;
        }
		
		if (ags_xoiwcp_is_hpos()) {
			include_once(__DIR__.'/includes/class-wc-admin-report-hpos.php');
			foreach ( array_keys(WC_Admin_Report_HPOS_WPZ::getVirtualOrderMeta()) as $orderMetaField ) {
				$fieldId = '__shop_order__' . $orderMetaField;
				$ags_xoiwcp_custom_fields[] = $fieldId;
				$ags_xoiwcp_custom_fields_by_type[$typeNames['shop_order']][$fieldId] = $orderMetaField;
			}
		}
		
		sort($ags_xoiwcp_custom_fields);
		foreach ($ags_xoiwcp_custom_fields_by_type as &$typeFields) {
			asort($typeFields);
		}
    }

    return $byType ? $ags_xoiwcp_custom_fields_by_type : $ags_xoiwcp_custom_fields;

}

/*
	Get fields added by other plugins.
	Plugins hooked to "hm_xoiwcp_addon_fields" must add their fields to the array in the following format:
		my_addon_field_id => array(
			'label' => 'My Addon Field',
			'cb' => my_callback_function
		);
	where "my_callback_function" takes the following arguments:
		$product: the row data object returned by $wc_report->get_order_report_data()
		$type: null for regular products (currently not used)
	and returns the field value to include in the report for the given row.
*/
function ags_xoiwcp_get_addon_fields()
{
    global $hm_xoiwcp_addon_fields;
    if (!isset($hm_xoiwcp_addon_fields)) {
        $hm_xoiwcp_addon_fields = apply_filters('hm_xoiwcp_addon_fields', array());
    }
    return $hm_xoiwcp_addon_fields;
}

add_action('admin_enqueue_scripts', 'ags_xoiwcp_admin_enqueue_scripts');
function ags_xoiwcp_admin_enqueue_scripts()
{
	if ( isset($_GET['page']) && $_GET['page'] == 'hm_xoiwcp' ) {
		wp_enqueue_style( 'ags_xoiwcp_admin_style', plugins_url( 'css/export-order-items.css', __FILE__ ) );
		// ags-product-addons
		wp_enqueue_style( 'ags_xoiwcp_wc_addons_admin', plugins_url( 'admin/addons/css/admin.css', __FILE__ ), array(), ags_xoiwcp_VER );
	}
    wp_enqueue_script('jquery-ui-sortable');
}


// Schedulable email report hook
add_filter('pp_wc_get_schedulable_email_reports', 'ags_xoiwcp_add_schedulable_email_reports');
function ags_xoiwcp_add_schedulable_email_reports($reports)
{

    $myReports = array('last' => 'Last used settings');
    $savedReportSettings = ags_xoiwcp_get_saved_report_settings();
    if (!empty($savedReportSettings)) {
        $updated = false;
        foreach ($savedReportSettings as $i => $settings) {
            if ($i == 0)
                continue;
            if (empty($settings['key'])) {
                $chars = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $numChars = strlen($chars);
                while (true) {
                    $key = '';
                    for ($j = 0; $j < 32; ++$j)
                        $key .= $chars[random_int(0, $numChars - 1)];
                    $unique = true;
                    foreach ($savedReportSettings as $settings2)
                        if (isset($settings2['key']) && $settings2['key'] == $key)
                            $unique = false;
                    if ($unique)
                        break;
                }
                $savedReportSettings[$i]['key'] = $key;
                $updated = true;
            }
            $myReports[$savedReportSettings[$i]['key']] = $settings['preset_name'];
        }
        if ($updated) {
            update_option('ags_xoiwcp_report_settings', $savedReportSettings, false);
			delete_option('hm_xoiwcp_report_settings'); // backwards compat
		}
    }

    $reports['hm_xoiwcp'] = array(
        'name' => 'Export Order Items Pro',
        'callback' => 'ags_xoiwcp_run_scheduled_report',
        'reports' => $myReports
    );
    return $reports;
}

function ags_xoiwcp_run_scheduled_report($reportId, $start, $end, $args = array(), $output = false)
{
    $savedReportSettings = ags_xoiwcp_get_saved_report_settings();
    if (!isset($savedReportSettings[0]))
        return false;

    if ($reportId == 'last') {
        $presetIndex = 0;
    } else {
        foreach ($savedReportSettings as $i => $settings) {
            if (isset($settings['key']) && $settings['key'] == $reportId) {
                $presetIndex = $i;
                break;
            }
        }
    }
    if (!isset($presetIndex))
        return false;

    $prevPost = $_POST;
    $_POST = $savedReportSettings[$presetIndex];
    if ($start === null && $end === null) {
        list($start, $end) = ags_xoiwcp_get_report_dates();
    } else {
        // Add one day to end since we're setting the time to midnight
        $end += 86400;

        $_POST['report_time'] = 'custom';
        $_POST['report_start'] = date('Y-m-d', $start);
        $_POST['report_start_time'] = '12:00:00 AM';
        $_POST['report_end'] = date('Y-m-d', $end);
        $_POST['report_end_time'] = '12:00:00 AM';
    }

    $_POST = array_merge($_POST, array_intersect_key($args, $_POST));
	
	// XLS format is no longer supported by this plugin
	if ($_POST['format'] == 'xls') {
		$_POST['format'] = 'xlsx';
	}

	if (!$output || $_POST['format'] == 'xlsx' || $_POST['format'] == 'xls') {

		if ( !function_exists('random_bytes') ) {
			return false;
		}

		$tempDir = ags_xoiwcp_get_temp_dir();

		$filepath = $tempDir . '/Order Items Export' . ($presetIndex == 0 ? '' : ' - ' . $_POST['preset_name']) . ' - ' .
			date('Y-m-d', current_time('timestamp')) . '.'
			. ($_POST['format'] == 'html-enhanced' ? 'html' : (in_array($_POST['format'], array('xlsx', 'xls', 'html')) ? $_POST['format'] : 'csv'));

	}


    if ($_POST['format'] == 'xlsx' || $_POST['format'] == 'xls') {
		include_once(__DIR__.'/HM_XLS_Export.php');
		$dest = new HM_XOIWCP_Export\XLS();
	} else if ($_POST['format'] == 'html') {
		include_once(__DIR__.'/HM_HTML_Export.php');
		$out = fopen($output ? 'php://output' : $filepath, 'w');
		$dest = new HM_XOIWCP_Export\HTML($out);
	} else if ($_POST['format'] == 'html-enhanced') {
		include_once(__DIR__.'/HM_HTML_Enhanced_Export.php');
		$out = fopen($output ? 'php://output' : $filepath, 'w');
		$dest = new HM_XOIWCP_Export\HTMLEnhanced($out);
	} else if ($_POST['format'] == 'csv-ascii') {
		include_once(__DIR__.'/HM_CSV_ASCII_Export.php');
		$out = fopen($output ? 'php://output' : $filepath, 'w');
		$dest = new HM_XOIWCP_Export\CSV_ASCII($out);
	} else {
		include_once(__DIR__.'/HM_CSV_Export.php');
		$out = fopen($output ? 'php://output' : $filepath, 'w');
		$dest = new HM_XOIWCP_Export\CSV($out);
	}

    if (!empty($_POST['include_header']))
        ags_xoiwcp_export_header($dest);
    ags_xoiwcp_export_body($dest, $start, $end);

    if ($_POST['format'] == 'xlsx') {
        $dest->outputXLSX($filepath);
        if ($output) {
            readfile($filepath);
            @unlink($filepath);
            @rmdir($tempDir);
        }
    } else if ($_POST['format'] == 'xls') {
        $dest->outputXLS($filepath);
        if ($output) {
            readfile($filepath);
            @unlink($filepath);
            @rmdir($tempDir);
        }
    } else {
        // Call destructor, if any
        $dest = null;
        fclose($out);
    }

    $_POST = $prevPost;

    return $filepath;
}

function ags_xoiwcp_get_temp_dir() {
	$tempDir = WP_CONTENT_DIR.'/potent-temp/'.sha1( random_bytes(256) );
	if ( !@mkdir($tempDir, 0755, true) ) {
		throw new Exception('Unable to create temporary directory');
	}
	return $tempDir;
}

function ags_xoiwcp_report_order_statuses()
{
    // We are now doing our own status filtering, so disable WooCommerce's status filtering
    return false;
    /*
    $wcOrderStatuses = wc_get_order_statuses();
    $orderStatuses = array();
    if (!empty($_POST['order_statuses'])) {
        foreach ($_POST['order_statuses'] as $orderStatus) {
            if (isset($wcOrderStatuses[$orderStatus]))
                $orderStatuses[] = substr($orderStatus, 3);
        }
    }
    return $orderStatuses;
    */
}

function ags_xoiwcp_get_order_filter_fields()
{
    global $wpdb, $ags_xoiwcp_order_filter_fields;
    if (!isset($ags_xoiwcp_order_filter_fields)) {
		$fields = ags_xoiwcp_get_custom_fields(true);
		if (!isset($fields['Order'])) {
			$fields['Order'] = [];
		}
		$orderKeys = $fields['Order'];
		array_walk( $orderKeys, 'ags_xoiwcp_array_value_prepend_str', 'O' );
        $ags_xoiwcp_order_filter_fields = array_combine($orderKeys, $fields['Order']);
    }

    return $ags_xoiwcp_order_filter_fields;
}
function ags_xoiwcp_get_customer_filter_fields()
{
	global $ags_xoiwcp_customer_filter_fields;
    if (!isset($ags_xoiwcp_customer_filter_fields)) {
		$fields = ags_xoiwcp_get_custom_fields(true);
		$ags_xoiwcp_customer_filter_fields = empty($fields['Customer User']) ? [] : $fields['Customer User'];
	}
	return $ags_xoiwcp_customer_filter_fields;
}

function ags_xoiwcp_array_value_prepend_str(&$value, $index, $str) {
	$value = $str.$value;
}

function ags_xoiwcp_get_order_item_types()
{
    global $ags_xoiwcp_order_item_types, $wpdb;
    if (!isset($ags_xoiwcp_order_item_types)) {
        $ags_xoiwcp_order_item_types = $wpdb->get_col('SELECT DISTINCT order_item_type FROM ' . $wpdb->prefix . 'woocommerce_order_items ORDER BY order_item_type ASC');
        if (empty($ags_xoiwcp_order_item_types))
            $ags_xoiwcp_order_item_types = array();
    }
    return $ags_xoiwcp_order_item_types;
}

function ags_xoiwcp_filter_report_query($sql, $refundOrders = false)
{
    // Add on any extra SQL
    global $hm_wc_report_extra_sql;
    if (!empty($hm_wc_report_extra_sql)) {
        foreach ($hm_wc_report_extra_sql as $key => $extraSql) {
            if (isset($sql[$key])) {
                $sql[$key] .= ' ' . $extraSql;
            }
        }
    }

    // Fix table aliases
    /*if (preg_match_all('/\\bJOIN (\\S+) AS (.*) ON\\b/iU', $sql['join'], $matches, PREG_SET_ORDER)) {
        $fullQuery = implode(' ', $sql);
        $tblId = 0;
        $oldTbl = array();
        $newTbl = array();
        foreach ($matches as $match) {
            // For refund order queries, meta joins should use the parent order meta, so change the join field
            //if ($refundOrders && $match[1] == 'wp_postmeta') {
                //$sql['join'] = preg_replace('/\\bON posts\\.ID\\s?=\\s?'.preg_quote($match[2]).'\\.post_id\\b/i', 'ON posts.post_parent = '.$match[2].'.post_id', $sql['join']);
            //}

            // Fix table aliases containing characters other than alphanumeric and underscore
            if (preg_match('/[^a-z0-9_]/i', $match[2])) {
                while (strpos($fullQuery, 'tbl'.$tblId++) !== false) { }
                $sql['join'] = str_replace($match[0], 'JOIN '.$match[1].' AS tbl'.$tblId.' ON', $sql['join']);
                $oldTbl[] = $match[2].'.';
                $newTbl[] = 'tbl'.$tblId.'.';
            }
        }
        foreach ($sql as $key => $value) {
            $sql[$key] = str_replace($oldTbl, $newTbl, $sql[$key]);
        }
    }*/

    return $sql;
}

/*function ags_xoiwcp_fix_report_query_refund_orders($sql) {
	return ags_xoiwcp_fix_report_query($sql, true);
}*/

function ags_xoiwcp_process_filter_value($value) {
	switch ($value) {
		case '@@ags_xoiwcp_USER_ID':
			return get_current_user_id();
	}
	return $value;
}


add_action('wp_ajax_hm_psr_calc_dynamic_date', 'ags_xoiwcp_calc_dynamic_date');
function ags_xoiwcp_calc_dynamic_date()
{
	if (empty($_POST['date'])) {
		wp_send_json_error();
	}
	
	$result = ags_xoiwcp_resolve_dynamic_date( $_POST['date'], !empty($_POST['allowFormat']), !empty($_POST['use_wp_date']) );
	
	if (empty($result)) {
		wp_send_json_error();
	}
	wp_send_json_success($result);
}

// Following code copied from Easy Digital Downloads Software Licensing addon - see comment near the top of this file for details

/** Licensing **/

function ags_xoiwcp_license_check()
{
    if (isset($_POST['ags_xoiwcp_license_deactivate'])) {
        ags_xoiwcp_deactivate_license();
    }

    if (get_option('hm_xoiwcp_license_status', 'invalid') == 'valid') {
        return true;
    } else {
        if (isset($_POST['ags_xoiwcp_license_activate']) && !empty($_POST['hm_xoiwcp_license_key']) && ctype_alnum($_POST['hm_xoiwcp_license_key'])) {
            update_option('hm_xoiwcp_license_key', trim($_POST['hm_xoiwcp_license_key']));
            ags_xoiwcp_activate_license();
            if (get_option('hm_xoiwcp_license_status', 'invalid') == 'valid')
                return true;
        } ?>

        <div id="ags-xoiwcp_license_key_box">
            <form action="" method="post" id="ags-xoiwcp_license_key_form">
                <?php wp_nonce_field('ags_xoiwcp_license_activate_nonce', 'ags_xoiwcp_license_activate_nonce'); ?>
                <div id="ags-xoiwcp_license_key_form_logo_container">
                    <a href="https://wpzone.co/?utm_source=frontend-reports-for-woocommerce&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-link"
                       target="_blank"><img src="<?php echo(esc_url(plugins_url('images/wp_zone_logo.png', __FILE__))); ?>"
                                            alt="WP Zone"/></a>
                </div>
                <div id="ags-xoiwcp_license_key_form_body">
                    <h3>
                        <?php echo ags_xoiwcp_ITEM_NAME; ?>
                        <small>v<?php echo ags_xoiwcp_VER; ?></small>
                    </h3>
                    <?php wp_nonce_field('ags_xoiwcp_license_activate_nonce', 'ags_xoiwcp_license_activate_nonce'); ?>
                    <p>Please enter the license key provided when you purchased the plugin:</p>
                    <label for="ags_xoiwcp_license_activate">
                        <span>License key:</span>
                        <input type="text" id="hm_xoiwcp_license_key" name="hm_xoiwcp_license_key"/>
                    </label>
                    <p class="submit">
                        <button type="submit" name="ags_xoiwcp_license_activate" value="1">
                            Activate License
                        </button>
                    </p>
                </div>
            </form>
        </div>

        <?php
        return false;
    }
}

function ags_xoiwcp_activate_license()
{

    // run a quick security check
    if (!check_admin_referer('ags_xoiwcp_license_activate_nonce', 'ags_xoiwcp_license_activate_nonce'))
        return; // get out if we didn't click the Activate button

    // retrieve the license
    $license = trim(get_option('hm_xoiwcp_license_key'));

    // data to send in our API request
    $api_params = array(
        'edd_action' => 'activate_license',
        'license' => $license,
        'item_name' => urlencode(ags_xoiwcp_ITEM_NAME), // the name of our product in EDD
        'url' => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post(ags_xoiwcp_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

    // make sure the response came back okay
    if (is_wp_error($response))
        return false;

    // decode the license data
    $license_data = json_decode(wp_remote_retrieve_body($response));

    // $license_data->license will be either "valid" or "invalid"

    update_option('hm_xoiwcp_license_status', $license_data->license);

}

function ags_xoiwcp_deactivate_license()
{

    // run a quick security check
    if (!check_admin_referer('ags_xoiwcp_license_deactivate_nonce', 'ags_xoiwcp_license_deactivate_nonce'))
        return; // get out if we didn't click the dectivate button

    // retrieve the license from the database
    $license = trim(get_option('hm_xoiwcp_license_key'));

    // data to send in our API request
    $api_params = array(
        'edd_action' => 'deactivate_license',
        'license' => $license,
        'item_name' => urlencode(ags_xoiwcp_ITEM_NAME), // the name of our product in EDD
        'url' => home_url()
    );

    // Call the custom API.
    $response = wp_remote_post(ags_xoiwcp_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

    // make sure the response came back okay
    if (is_wp_error($response))
        return false;

    delete_option('hm_xoiwcp_license_status');
    delete_option('hm_xoiwcp_license_key');
}


if (!class_exists('ags_xoiwcp_EDD_SL_Plugin_Updater')) {
    // load our custom updater
    include(dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php');
}
function ags_xoiwcp_register_option()
{
    // creates our settings in the options table
    register_setting('ags_xoiwcp_license', 'hm_xoiwcp_license_key', 'ags_xoiwcp_sanitize_license');
}

add_action('admin_init', 'ags_xoiwcp_register_option');

function ags_xoiwcp_plugin_updater()
{

    // retrieve our license key from the DB
    $license_key = trim(get_option('hm_xoiwcp_license_key'));

    // setup the updater
    $edd_updater = new ags_xoiwcp_EDD_SL_Plugin_Updater(ags_xoiwcp_STORE_URL, __FILE__, array(
            'version' => $GLOBALS['ags_xoiwcp_VER'], // current version number
            'license' => $license_key,        // license key (used get_option above to retrieve from DB)
            'item_name' => ags_xoiwcp_ITEM_NAME, // name of this plugin
            'author' => 'WP Zone'  // author of this plugin
        )
    );
}

add_action('admin_init', 'ags_xoiwcp_plugin_updater', 0);

function ags_xoiwcp_sanitize_license($new)
{
    $old = get_option('hm_xoiwcp_license_key');
    if ($old && $old != $new) {
        delete_option('hm_xoiwcp_license_status'); // new license has been entered, so must reactivate
    }
    return $new;
}

// End code copied from Easy Digital Downloads Software Licensing addon
?>
