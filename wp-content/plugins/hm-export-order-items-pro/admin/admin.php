<?php
/**
 * Author:      WP Zone
 * License:     GNU General Public License version 3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 */
if (!defined('ABSPATH')) exit; ?>

<?php
if (!ags_xoiwcp_license_check())
    return;

$addons = apply_filters('hm_xoiwcp_addons', array());
foreach ($addons as $addonId => &$addon) {
    if (isset($_POST[$addonId . '_license_deactivate'])
        && is_callable($addon['deactivate_cb'])
        && check_admin_referer($addonId . '_license_deactivate_nonce', $addonId . '_license_deactivate_nonce')) {
        $addon = array_merge($addon, call_user_func($addon['deactivate_cb']));
    } else if (isset($_POST[$addonId . '_license_key'])
        && ctype_alnum($_POST[$addonId . '_license_key'])
        && is_callable($addon['activate_cb'])
        && check_admin_referer($addonId . '_license_activate_nonce', $addonId . '_license_activate_nonce')) {
        $addon = array_merge($addon, call_user_func($addon['activate_cb'], trim($_POST[$addonId . '_license_key'])));
    }

    if (!empty($addon['licensing']) && !$addon['license_status'] && empty($inactiveAddonMessageShown)) {
        echo('
                    <div class="notice notice-warning is-dismissable">
                        <p><strong>You have one or more Export Order Items Pro addon(s) missing license keys.</strong> Please enter the license key(s) at the bottom of this page to use those addon(s).</p>
                    </div>');
        $inactiveAddonMessageShown = true;
    }
}
?>

<div id="ags-xoiwcp-settings-container">
    <div id="ags-xoiwcp-settings">
        <div id="ags-xoiwcp-settings-header">
            <div class="ags-xoiwcp-settings-logo">
                <h3><?php echo ags_xoiwcp_ITEM_NAME; ?></h3>
            </div>
            <div id="ags-xoiwcp-settings-header-links">
                <a id="ags-xoiwcp-settings-header-link-support" href=https://docs.divi.space/docs/plugin/export-order-items-pro/"
                   target="_blank">Documentation</a>
            </div>
        </div>
        <?php if (get_option('hm_xoiwcp_license_status') === 'valid') { // check if license is active ?>
            <ul id="ags-xoiwcp-settings-tabs">
                <li class="ags-xoiwcp-settings-active"><a href="#settings">Export Settings</a></li>
                 <li><a href="#about">About</a></li>
                <li><a href="#addons">Addons</a></li>
                <li><a href="#license">License</a></li>

            </ul>
        <?php } ?>

        <div id="ags-xoiwcp-settings-tabs-content">

            <?php
            if (!ags_xoiwcp_license_check())
                return;

            // Check for WooCommerce
            if (!class_exists('WooCommerce')) {
                echo('<p class="ags-xoiwcp-notification ags-xoiwcp-notification-error">
                    This plugin requires that <strong>WooCommerce</strong> is installed and activated.
                    </p></div> ');
                return;
            } else if (!function_exists('wc_get_order_types')) {
                echo('<p class="ags-xoiwcp-notification ags-xoiwcp-notification-error">
                    The Export Order Items plugin requires <strong>WooCommerce 2.2 or higher</strong>. Please update your WooCommerce install.
                    </p></div>');
                return;
            }


			// XLS format is no longer supported by this plugin
			if ($reportSettings['format'] == 'xls') {
				$reportSettings['format'] = 'xlsx';
			}

            $customFieldsByType = ags_xoiwcp_get_custom_fields(true);

            // Print form
            echo('
<div id="ags-xoiwcp-settings-settings" class="ags-xoiwcp-settings-active">  
<form action="" method="post" id="ags_xoiwcp_form">
        <div id="ags_xoiwcp-current-preset">
            <input type="text" name="preset_name" placeholder="Preset Name"' . (isset($_GET['preset']) &&
                isset($savedReportSettings[$_GET['preset']]['preset_name']) ? ' value="' .
                    esc_attr($savedReportSettings[$_GET['preset']]['preset_name']) . '"' : '') . ' />
            ' . (isset($_GET['preset']) ? '
            <button class="button-primary" name="ags_xoiwcp_action" value="preset-save"
                    onclick="jQuery(\'#ags_xoiwcp_form\').attr(\'target\', \'\'); return true;">Save Changes
            </button>
            <button class="button-secondary" type="button" onclick="location.href=\'?page=hm_xoiwcp\';">Close Preset
            </button>
            ' : '') . '
            <button class="button-secondary" name="ags_xoiwcp_action" value="preset-create"
                    onclick="jQuery(\'#ags_xoiwcp_form\').attr(\'target\', \'\'); return true;">Create New Preset
            </button>
        </div>

        <h2 id="ags_xoiwcp_tabs" class="nav-tab-wrapper">
            ' . (count($savedReportSettings) > 1 ? '<a id="ags_xoiwcp_tab_presets" class="nav-tab" href="#presets">Presets</a>' : '') . '
            <a id="ags_xoiwcp_tab_orders" class="nav-tab" href="#orders">Order Filtering</a>
            <a id="ags_xoiwcp_tab_lines" class="nav-tab" href="#lines">Line Item Filtering</a>
            <a id="ags_xoiwcp_tab_fields" class="nav-tab" href="#fields">Export Fields</a>
            <a id="ags_xoiwcp_tab_output" class="nav-tab" href="#output">Output</a>
        </h2>

        <div id="ags_xoiwcp_tab_panels">
            <table id="ags_xoiwcp_tab_presets_panel" class="ags_xoiwcp_tab_panel">
                <tbody>');
            foreach ($savedReportSettings as $presetId => $preset) {
                if ($presetId == 0) continue;
                echo('
                    <tr>
                        <td>' . esc_html($preset['preset_name']) . '</td>
                        <td>
                            <a href="?page=hm_xoiwcp&amp;ags_xoiwcp_action=run&amp;preset=' . ((int) $presetId) . '" target="_blank"
                               class="dashicons dashicons-download"/>
                            <a href="?page=hm_xoiwcp&amp;preset=' . ((int) $presetId) . '#orders" class="dashicons dashicons-edit"/>
                            <a href="?page=hm_xoiwcp&amp;ags_xoiwcp_action=preset-del&amp;preset=' . ((int) $presetId) . '"
                               class="dashicons dashicons-trash"
                               onclick="return confirm(\'Are you sure that you want to delete this preset?\');"/>
                        </td>
                    </tr>
                    ');
            }
            echo('
                </tbody>
            </table>

            <div id="ags_xoiwcp_tab_orders_panel" class="ags_xoiwcp_tab_panel">

                <div class="ags-xoiwcp-settings-box">
                    <label for="ags_xoiwcp_field_report_time">
                       <span class="label">Export Period:</span>
                       <select name="report_time" id="ags_xoiwcp_field_report_time">
                            <option value="0d"
                            ' . ($reportSettings['report_time'] == '0d' ? ' selected="selected"' : '') .
                '>Today</option>
                            <option value="1d"
                            ' . ($reportSettings['report_time'] == '1d' ? ' selected="selected"' : '') .
                '>Yesterday</option>
                            <option value="7d"
                            ' . ($reportSettings['report_time'] == '7d' ? ' selected="selected"' : '') . '>Previous 7
                            days (excluding today)</option>
                            <option value="30d"
                            ' . ($reportSettings['report_time'] == '30d' ? ' selected="selected"' : '') . '>Previous 30
                            days (excluding today)</option>
                            <option value="0cm"
                            ' . ($reportSettings['report_time'] == '0cm' ? ' selected="selected"' : '') . '>Current
                            calendar month</option>
                            <option value="1cm"
                            ' . ($reportSettings['report_time'] == '1cm' ? ' selected="selected"' : '') . '>Previous
                            calendar month</option>
                            <option value="+7d"
                            ' . ($reportSettings['report_time'] == '+7d' ? ' selected="selected"' : '') . '>Next 7 days
                            (future dated orders)</option>
                            <option value="+30d"
                            ' . ($reportSettings['report_time'] == '+30d' ? ' selected="selected"' : '') . '>Next 30
                            days (future dated orders)</option>
                            <option value="+1cm"
                            ' . ($reportSettings['report_time'] == '+1cm' ? ' selected="selected"' : '') . '>Next
                            calendar month (future dated orders)</option>
                            <option value="all"
                            ' . ($reportSettings['report_time'] == 'all' ? ' selected="selected"' : '') . '>All
                            time</option>
                            <option value="custom"
                            ' . ($reportSettings['report_time'] == 'custom' ? ' selected="selected"' : '') . '>Custom
                            date range</option>
                        </select>
                    </label>
                </div>

                <div class="ags-xoiwcp-settings-box ags_xoiwcp_custom_time">
                    <label for="ags_xoiwcp_field_report_start">
                       <span class="label">Start Date:</span>
                         <input type="date" name="report_start" id="ags_xoiwcp_field_report_start"
                               value="' . esc_attr($reportSettings['report_start']) . '"/>
						 <input type="text" class="ags-xoiwcp-date-dynamic-field'.(empty($reportSettings['report_start_dynamic']) ? ' hidden' : '').'" name="report_start_dynamic" id="ags_xoiwcp_field_report_start_dynamic" value="'.esc_attr($reportSettings['report_start_dynamic']).'" placeholder="e.g. -7 days" />
						 <a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['report_start_dynamic']) ? 'dynamic' : 'fixed').' date</a>
                        <input type="text" name="report_start_time" id="ags_xoiwcp_field_report_start_time"
                               value="' . esc_attr($reportSettings['report_start_time']) . '"/>
                    </label>
                </div>

                <div class="ags-xoiwcp-settings-box ags_xoiwcp_custom_time">
                    <label for="ags_xoiwcp_field_report_end">
                       <span class="label">End Date:</span>
                         <input type="date" name="report_end" id="ags_xoiwcp_field_report_end"
                               value="' . esc_attr($reportSettings['report_end']) . '"/>
						 <input type="text" class="ags-xoiwcp-date-dynamic-field'.(empty($reportSettings['report_end_dynamic']) ? ' hidden' : '').'" name="report_end_dynamic" id="ags_xoiwcp_field_report_end_dynamic" value="'.esc_attr($reportSettings['report_end_dynamic']).'" placeholder="e.g. -7 days" />
						 <a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['report_end_dynamic']) ? 'dynamic' : 'fixed').' date</a>
                        <input type="text" name="report_end_time" id="ags_xoiwcp_field_report_end_time"
                               value="' . esc_attr($reportSettings['report_end_time']) . '"/>
                    </label>
                    <p class="ags-xoiwcp-settings-description">Enter times in the format hour:minute:second (AM/PM optional). The end
                            time is exclusive.</p>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Include Orders With Status:</span>
                        </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">');
            foreach (wc_get_order_statuses() as $status => $statusName) {
                echo('<label class="ags-xoiwcp-settings-cb-list-item"><input type="checkbox" name="order_statuses[]"' . (in_array($status,
                        $reportSettings['order_statuses']) ? ' checked="checked"' : '') . ' value="' . esc_attr($status) . '"/> ' . esc_html($statusName) . '</label>');
            }
            echo('     </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">');
				
			$orderFilterFields = ags_xoiwcp_get_order_filter_fields();
			$customerFilterFields = ags_xoiwcp_get_customer_filter_fields();
			for ($i = 1; $i < 4; ++$i) {
				$fieldPre = 'order_meta_filter'.($i > 1 ? '_'.((int) $i) : '').'_';
				echo('
                    <div class="ags-xoiwcp-settings-multirow has-checkbox">
						<label class="ags-xoiwcp-settings-title">
                           <span class="label"'.($i > 1 ? ' style="display: none;"' : '').'>Only Orders With Field:</span>
						</label>
                        <div id="ags_xoiwcp_'.$fieldPre.'settings" class="ags-xoiwcp-settings-content">
                             <input type="checkbox" name="'.$fieldPre.'on"' .
                (empty($reportSettings[$fieldPre.'on']) ? '' : ' checked="checked"') . ' />'.($i > 1
							? '<select style="width: auto;" name="'.$fieldPre.'logic">
								<option value="AND"'.($reportSettings[$fieldPre.'logic'] == 'AND' ? ' selected="selected"' : '').'>and</option>
								<option value="OR"'.($reportSettings[$fieldPre.'logic'] == 'OR' ? ' selected="selected"' : '').'>or</option>
							</select>'
							: '')
                            .'<select name="'.$fieldPre.'key">
                                <optgroup label="Order" class="ags-xoiwcp-select-other" data-ags-xoiwcp-other-field-prefix="O">
                                    ');
            $customerFields = false;
			$selectedFieldExists = in_array($reportSettings[$fieldPre.'key'], $orderFilterFields);
            foreach ($orderFilterFields as $orderField => $orderFieldDisplay) {
                echo('
                                    <option value="' . esc_attr($orderField) . '"
                                    ' . ($reportSettings[$fieldPre.'key'] == $orderField ? ' selected="selected"' :
                        '') . '>' . esc_html($orderFieldDisplay) . '</option>');
            }
			if (!$selectedFieldExists && $reportSettings[$fieldPre.'key']) {
				echo('<option value="' . esc_attr( $reportSettings[$fieldPre.'key'] ) . '" selected="selected">' . esc_html( substr($reportSettings[$fieldPre.'key'], 1) ) . '</option>');
			}
            echo('
                                </optgroup>
                            </select>
							<select class="ags-xoiwcp-order-meta-filter-cast" name="'.$fieldPre.'cast">
                                <option value=""'.selected(empty($reportSettings[$fieldPre.'cast'])).'>as text/number</option>
                                <option value="date"'.selected(($reportSettings[$fieldPre.'cast'] ?? null) == 'date').'>as date/time with format:</option>
                            </select>
							<input type="text" name="'.$fieldPre.'date_format" value="' . esc_attr($reportSettings[$fieldPre.'date_format']) . '">
                            <select id="ags_xoiwcp_'.$fieldPre.'op" name="'.$fieldPre.'op">
                                <option value="="
                                ' . ($reportSettings[$fieldPre.'op'] == '=' ? ' selected="selected"' : '') . '>equal
                                to</option>
                                <option value="!="
                                ' . ($reportSettings[$fieldPre.'op'] == '!=' ? ' selected="selected"' : '') . '>not
                                equal to</option>
                                <option value="&lt;"
                                ' . ($reportSettings[$fieldPre.'op'] == '<' ? ' selected="selected"' : '') . '>less
                                than</option>
                                <option value="&lt;="
                                ' . ($reportSettings[$fieldPre.'op'] == '<=' ? ' selected="selected"' : '') . '>less
                                than or equal to</option>
                                <option value="&gt;"
                                ' . ($reportSettings[$fieldPre.'op'] == '>' ? ' selected="selected"' : '') .
                '>greater than</option>
                                <option value="&gt;="
                                ' . ($reportSettings[$fieldPre.'op'] == '>=' ? ' selected="selected"' : '') .
                '>greater than or equal to</option>
                                <option value="BETWEEN"
                                ' . ($reportSettings[$fieldPre.'op'] == 'BETWEEN' ? ' selected="selected"' : '') .
                '>between</option>
                            </select>
                            <select class="hm-xoiwcp-value-preset" data-hm-xoiwcp-for="'.$fieldPre.'value">
							    <option value="">value:</option>
							    <option value="@@ags_xoiwcp_USER_ID">current user ID</option>
					    	</select>

                            <input type="text" name="'.$fieldPre.'value"
                                   value="' . esc_attr($reportSettings[$fieldPre.'value']) . '"/>
                            <span id="ags_xoiwcp_'.$fieldPre.'value_2" style="display: none;">
                                and
                                <input type="text" name="'.$fieldPre.'value_2"
                                       value="' . esc_attr($reportSettings[$fieldPre.'value_2']) . '"/>
                            </span>
                        </div>
                    </div>');
			}
				
			echo('
			
				<div class="ags-xoiwcp-settings-tooltip bottom tooltip-visible">
                    <div class="ags-xoiwcp-settings-tooltiptext">
                        <span>Help</span>
						<p>The Only Orders With Field settings are added to the reporting database query in the order specified above, omitting any that are unchecked. If a combination of &quot;and&quot; and &quot;or&quot; logic is used, the operator precedence rules of the database engine will be applied. For example, for <a href="https://dev.mysql.com/doc/refman/8.0/en/operator-precedence.html" target="_blank">MySQL (v8.0)</a> this means that &quot;and&quot; conditions will be evalulated first; A or B and C will be evaluated as A or (B and C).</p>
						
						<p>When &quot;as date/time with format:&quot; is selected, the date/time format should be specified in using the format specification from the <a href="https://dev.mysql.com/doc/refman/8.4/en/date-and-time-functions.html#function_date-format" target="_blank">MySQL DATE_FORMAT() function</a>, for example: %d/%m/%Y. Date/time values to match against should always use the format 2020-01-01 23:59:59, regardless of the field value format. The time component in both the field value and the match value is optional and will be considered as 00:00:00 if not specified (for example, 2020-01-01 10:00:00 is considered greater than 2020-01-01). Time-only formats are not supported.</p>
						
						<p>If you are including line item refunds in the report, these settings will be applied to the original order, not the refund order.</p>
                    </div>
                 </div>
			
                </div>
				
				 <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-multirow has-checkbox">
						<label class="ags-xoiwcp-settings-title">
                           <span class="label">Only Orders from Customer Users With Field:</span>
						</label>
                        <div id="ags_xoiwcp_customer_meta_filter_settings" class="ags-xoiwcp-settings-content">
                             <input type="checkbox" name="customer_meta_filter_on"' .
                (empty($reportSettings['customer_meta_filter_on']) ? '' : ' checked="checked"') . ' />
							<select name="customer_meta_filter_key">');
			$selectedFieldExists = in_array($reportSettings['customer_meta_filter_key'], $customerFilterFields);
            foreach ($customerFilterFields as $customerField) {
                echo('
                                    <option value="' . esc_attr($customerField) . '"
                                    ' . ($reportSettings['customer_meta_filter_key'] == $customerField ? ' selected="selected"' :
                        '') . '>' . esc_html($customerField) . '</option>');
            }
			if (!$selectedFieldExists) {
				echo('<option value="' . esc_attr( $reportSettings['customer_meta_filter_key'] ) . '" selected="selected">' . esc_html( substr($reportSettings['customer_meta_filter_key'], 1) ) . '</option>');
			}
            echo('
                                </optgroup>
                            </select>
                            <select id="ags_xoiwcp_customer_meta_filter_op" name="customer_meta_filter_op">
                                <option value="="
                                ' . ($reportSettings['customer_meta_filter_op'] == '=' ? ' selected="selected"' : '') . '>equal
                                to</option>
                                <option value="!="
                                ' . ($reportSettings['customer_meta_filter_op'] == '!=' ? ' selected="selected"' : '') . '>not
                                equal to</option>
                                <option value="&lt;"
                                ' . ($reportSettings['customer_meta_filter_op'] == '<' ? ' selected="selected"' : '') . '>less
                                than</option>
                                <option value="&lt;="
                                ' . ($reportSettings['customer_meta_filter_op'] == '<=' ? ' selected="selected"' : '') . '>less
                                than or equal to</option>
                                <option value="&gt;"
                                ' . ($reportSettings['customer_meta_filter_op'] == '>' ? ' selected="selected"' : '') .
                '>greater than</option>
                                <option value="&gt;="
                                ' . ($reportSettings['customer_meta_filter_op'] == '>=' ? ' selected="selected"' : '') .
                '>greater than or equal to</option>
                                <option value="BETWEEN"
                                ' . ($reportSettings['customer_meta_filter_op'] == 'BETWEEN' ? ' selected="selected"' : '') .
                '>between</option>
                            </select>
                            <select class="hm-xoiwcp-value-preset" data-hm-xoiwcp-for="customer_meta_filter_value">
							    <option value="">value:</option>
							    <option value="@@ags_xoiwcp_USER_ID">current user ID</option>
					    	</select>

                            <input type="text" name="customer_meta_filter_value"
                                   value="' . esc_attr($reportSettings['customer_meta_filter_value']) . '"/>
                            <span id="ags_xoiwcp_customer_meta_filter_value_2" style="display: none;">
                                and
                                <input type="text" name="customer_meta_filter_value_2"
                                       value="' . esc_attr($reportSettings['customer_meta_filter_value_2']) . '"/>
                            </span>
                        </div>
                    </div>
					
					
				<div class="ags-xoiwcp-settings-tooltip bottom tooltip-visible">
                    <div class="ags-xoiwcp-settings-tooltiptext">
                        <span>Help</span>
						If you are including line item refunds in the report, the Only Orders from Customer Users With Field setting will be applied to the original order, not the refund order.
                    </div>
                 </div>
				</div>
				
                <div class="ags-xoiwcp-settings-box">
					   <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Include Orders by Shipping Method:</span>
                        </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">');
            foreach (ags_xoiwcp_get_order_shipping_filter_options() as $shippingMethodId => $shippingMethod) {
                echo('<label class="ags-xoiwcp-settings-cb-list-item"><input type="checkbox" name="order_shipping_filter[]"' . checked(in_array($shippingMethodId,
                        $reportSettings['order_shipping_filter']), true, false) . ' value="' . esc_attr($shippingMethodId) . '"> ' . esc_html($shippingMethod) . '</label>');
            }
            echo('     </div>
                    </div>
					
				<div class="ags-xoiwcp-settings-tooltip bottom tooltip-visible">
                    <div class="ags-xoiwcp-settings-tooltiptext">
                        <span>Help</span>
						If you are including line item refunds in the report, the Include Orders by Shipping Method setting will be applied to the original order, not the refund order.
                    </div>
                 </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <label for="ags_xoiwcp_customer_role">
                       <span class="label">Include Orders by Customer Role:</span>
                       <select name="customer_role" id="ags_xoiwcp_customer_role">
                            <option value="0"
                            ' . (empty($reportSettings['customer_role']) ? ' selected="selected"' : '') . '>(All
                            Customers)</option>
                            <option value="-1"
                            ' . ($reportSettings['customer_role'] == -1 ? ' selected="selected"' : '') . '>(Guest
                            Customers)</option>');
            global $wp_roles;
            foreach ($wp_roles->roles as $roleId => $role) {
                echo('<option value="' . esc_attr($roleId) . '" ' . ($reportSettings['customer_role'] === $roleId ? ' selected="selected"' : '') . '>' .
                    esc_attr($role['name']) . '</option>');
            }
            echo(' </select>
                    </label>
					
				<div class="ags-xoiwcp-settings-tooltip bottom tooltip-visible">
                    <div class="ags-xoiwcp-settings-tooltiptext">
                        <span>Help</span>
						If you are including line item refunds in the report, the Include Orders by Customer Role setting will be applied to the original order, not the refund order.
                    </div>
                 </div>
                </div>

            </div> <!-- ags_xoiwcp_tab_orders_panel -->

            <div id="ags_xoiwcp_tab_lines_panel" class="ags_xoiwcp_tab_panel">

              <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Include Products:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item">
                                <input type="radio" name="products" value="all"' . ($reportSettings['products'] == 'all'
                    ? ' checked="checked"' : '') . ' /> All products
                            </label>
                            <label class="ags-xoiwcp-settings-cb-list-item">
                               <input type="radio" name="products" value="cats"' . ($reportSettings['products'] ==
                'cats' ? ' checked="checked"' : '') . ' /> Products in categories:
                           </label>
                           <label class="ags-xoiwcp-settings-cb-list-item ags-xoiwcp-settings-cb-list-item-child">
                               <div id="ags_xoiwcp_filter_include_product_cats">
                            ');
            foreach (get_terms('product_cat', array('hierarchical' => false, 'hide_empty' => false)) as $term) {
                echo('<label><input type="checkbox" name="product_cats[]"' . (in_array($term->term_id,
                        $reportSettings['product_cats']) ? ' checked="checked"' : '') . ' value="' .
                    ((int) $term->term_id) . '" /> ' . esc_html($term->name) . '</label>');
            }
            echo(' </div>
                            </label>
                            <label class="ags-xoiwcp-settings-cb-list-item">
                              <input type="radio" name="products" value="ids"' . ($reportSettings['products'] == 'ids'
                    ? ' checked="checked"' : '') . ' /> Product ID(s):
                        </label>
                        <label class="ags-xoiwcp-settings-cb-list-item ags-xoiwcp-settings-cb-list-item-child">
                            <input type="text" name="product_ids"
                                   placeholder="Use commas to separate multiple product IDs"
                                   value="' . esc_attr($reportSettings['product_ids']) . '"/>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Only Products Tagged:</span>
                         </label>
                        <div id="ags_xoiwcp_product_tag_filter_settings" class="ags-xoiwcp-settings-cb-list-content">
                           <input type="checkbox" name="product_tag_filter_on"' .
                (empty($reportSettings['product_tag_filter_on']) ? '' : ' checked="checked"') . ' />
                <input type="text" id="ags_xoiwcp_product_tag_filter" name="product_tag_filter"
                               value="' . esc_attr($reportSettings['product_tag_filter']) . '"
                               class="hm-xoiwcp-w400"/>
                            <select id="ags_xoiwcp_product_tag_filter_select">
                                <option value="">Select tag...</option>
                                ');
            foreach (get_terms(array('taxonomy' => 'product_tag', 'hide_empty' => false, 'fields' =>
                'names')) as $term) {
                echo('
                                <option value="' . esc_attr($term). '">' . esc_html($term) . '</option>
                                ');
            }
            echo('</select>
                        </div>
                    </div>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-hover">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            Enter tag names separated by commas.
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Only Products With Field:</span>
                         </label>
                        <div id="ags_xoiwcp_product_meta_filter_settings" class="ags-xoiwcp-settings-cb-list-content">
                              <input type="checkbox" name="product_meta_filter_on"' .
                (empty($reportSettings['product_meta_filter_on']) ? '' : ' checked="checked"') . ' />
               <select name="product_meta_filter_key" class="ags-xoiwcp-select-other">
                                ');
			$selectedFieldExists = false;
            foreach (array_keys($customFieldsByType['Product']) as $customField) {
                $customField = substr($customField, 11);
				$isSelectedField = $reportSettings['product_meta_filter_key'] == $customField;
				if ($isSelectedField) {
					$selectedFieldExists = true;
				}
                echo('<option value="'.esc_attr($customField).'"'.($isSelectedField ? ' selected="selected"' : '').'>'.esc_html($customField).'</option>');
            }
			if ( !$selectedFieldExists ) {
				echo('<option value="'.esc_attr( $reportSettings['product_meta_filter_key'] ).'" selected="selected">'.esc_html( $reportSettings['product_meta_filter_key'] ).'</option>');
			}

            echo('
                            </select>
                            <select id="ags_xoiwcp_product_meta_filter_op" name="product_meta_filter_op">
                                <option value="="
                                ' . ($reportSettings['product_meta_filter_op'] == '=' ? ' selected="selected"' : '') .
                '>equal to</option>
                                <option value="!="
                                ' . ($reportSettings['product_meta_filter_op'] == '!=' ? ' selected="selected"' : '') .
                '>not equal to</option>
                                <option value="&lt;"
                                ' . ($reportSettings['product_meta_filter_op'] == '<' ? ' selected="selected"' : '') .
                '>less than</option>
                                <option value="&lt;="
                                ' . ($reportSettings['product_meta_filter_op'] == '<=' ? ' selected="selected"' : '') .
                '>less than or equal to</option>
                                <option value="&gt;"
                                ' . ($reportSettings['product_meta_filter_op'] == '>' ? ' selected="selected"' : '') .
                '>greater than</option>
                                <option value="&gt;="
                                ' . ($reportSettings['product_meta_filter_op'] == '>=' ? ' selected="selected"' : '') .
                '>greater than or equal to</option>
                                <option value="BETWEEN"
                                ' . ($reportSettings['product_meta_filter_op'] == 'BETWEEN' ? ' selected="selected"' : '') .
                '>between</option>
                            </select>
                            <select class="hm-xoiwcp-value-preset" data-hm-xoiwcp-for="product_meta_filter_value">
							    <option value="">value:</option>
							    <option value="@@ags_xoiwcp_USER_ID">current user ID</option>
					    	</select>
                            <input type="text" name="product_meta_filter_value"
                                   value="' . esc_attr($reportSettings['product_meta_filter_value']) . '"/>
                            <span id="ags_xoiwcp_product_meta_filter_value_2" style="display: none;">
                                and
                                <input type="text" name="product_meta_filter_value_2"
                                       value="' . esc_attr($reportSettings['product_meta_filter_value_2']) . '"/>
                            </span>
                        </div>
                    </div>
                 </div>

                <div class="ags-xoiwcp-settings-box">
                    <label>
                       <span class="label">Lines Per Order:</span>
                        <input type="checkbox" name="one_line_per_order"' .
                (empty($reportSettings['one_line_per_order']) ? '' : ' checked="checked"') . ' />
                                                    Only include one line per order
                    </label>
                </div>

                 <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Free Items:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                         <input type="checkbox" name="exclude_free"' . (empty($reportSettings['exclude_free']) ? '' :
                    ' checked="checked"') . ' />
                            Exclude items with a gross amount of zero
                            <select name="exclude_free_after_discount">
                                <option value="0"
                                ' . ($reportSettings['exclude_free_after_discount'] == 0 ? ' selected="selected"' : '')
                . '>before discount(s)</option>
                                <option value="1"
                                ' . ($reportSettings['exclude_free_after_discount'] == 1 ? ' selected="selected"' : '')
                . '>after discount(s)</option>
                            </select>
                        </div>
                    </div>
                </div>
				
				<div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-multirow has-checkbox">
                        <label class="ags-xoiwcp-settings-title">
                           <span class="label">Only Order Items With Field:</span>
						</label>
                        <div id="hm_xoiwcp_order_item_meta_filter_settings_1" class="ags-xoiwcp-settings-content">
                            <input type="checkbox" name="order_item_meta_filter_1_on" value="1"' . (empty($reportSettings['order_item_meta_filter_1_on']) ? '' : ' checked="checked"') . ' />
						<select name="order_item_meta_filter_1_key" class="ags-xoiwcp-select-other" style="width: auto;">
			');
			$orderItemFields = ags_xoiwcp_get_order_item_fields();
			
            $orderItemFieldFound = false;
			foreach ($orderItemFields as $orderItemField) {
				$orderItemFieldFound = $orderItemFieldFound || $reportSettings['order_item_meta_filter_1_key'] == $orderItemField;
				echo('<option value="'.esc_attr($orderItemField).'"'.($reportSettings['order_item_meta_filter_1_key'] == $orderItemField ? ' selected="selected"' : '').'>'.esc_html($orderItemField).'</option>');
			}
            if (!$orderItemFieldFound) {
				echo('<option value="'.esc_attr($reportSettings['order_item_meta_filter_1_key']).'" selected>'.esc_html($reportSettings['order_item_meta_filter_1_key']).'</option>');
			}
			echo('
						</select>
						<select style="width: auto;" id="hm_xoiwcp_order_item_meta_filter_1_op" name="order_item_meta_filter_1_op">
							<option value="="'.($reportSettings['order_item_meta_filter_1_op'] == '=' ? ' selected="selected"' : '').'>equal to</option>
							<option value="!="'.($reportSettings['order_item_meta_filter_1_op'] == '!=' ? ' selected="selected"' : '').'>not equal to</option>
							<option value="&lt;"'.($reportSettings['order_item_meta_filter_1_op'] == '<' ? ' selected="selected"' : '').'>less than</option>
							<option value="&lt;="'.($reportSettings['order_item_meta_filter_1_op'] == '<=' ? ' selected="selected"' : '').'>less than or equal to</option>
							<option value="&gt;"'.($reportSettings['order_item_meta_filter_1_op'] == '>' ? ' selected="selected"' : '').'>greater than</option>
							<option value="&gt;="'.($reportSettings['order_item_meta_filter_1_op'] == '>=' ? ' selected="selected"' : '').'>greater than or equal to</option>
							<option value="BETWEEN"'.($reportSettings['order_item_meta_filter_1_op'] == 'BETWEEN' ? ' selected="selected"' : '').'>between</option>
							<option value="EXISTS"'.($reportSettings['order_item_meta_filter_1_op'] == 'EXISTS' ? ' selected="selected"' : '').'>exists</option>
							<option value="NOTEXISTS"'.($reportSettings['order_item_meta_filter_1_op'] == 'NOTEXISTS' ? ' selected="selected"' : '').'>does not exist</option>
						</select>
						<span class="hm_xoiwcp_filter_value_container">
							<input type="text" style="width: auto;" id="hm_xoiwcp_order_item_meta_filter_1_value" name="order_item_meta_filter_1_value" value="'.esc_attr(empty($reportSettings['order_item_meta_filter_1_value_dynamic']) ? $reportSettings['order_item_meta_filter_1_value'] : ags_xoiwcp_resolve_dynamic_date($reportSettings['order_item_meta_filter_1_value_dynamic'], true, $reportSettings['use_wp_date'])).'"'.(empty($reportSettings['order_item_meta_filter_1_value_dynamic']) ? '' : ' disabled="disabled"').' />
							<input type="text" style="width: auto;" class="ags-xoiwcp-date-dynamic-field ags-xoiwcp-date-dynamic-field-with-format'.(empty($reportSettings['order_item_meta_filter_1_value_dynamic']) ? ' hidden' : '').'" name="order_item_meta_filter_1_value_dynamic" value="'.esc_attr($reportSettings['order_item_meta_filter_1_value_dynamic']).'" placeholder="e.g. -7 days" />
							<a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['order_item_meta_filter_1_value_dynamic']) ? 'dynamic date' : 'fixed value').'</a>
						</span>
						<span id="hm_xoiwcp_order_item_meta_filter_1_value_2" style="display: none;">
							and
							<span class="hm_xoiwcp_order_item_meta_filter_1_value_container">
								<input type="text" style="width: auto;" name="order_item_meta_filter_1_value_2" value="'.esc_attr(empty($reportSettings['order_item_meta_filter_1_value_2_dynamic']) ? $reportSettings['order_item_meta_filter_1_value_2'] : ags_xoiwcp_resolve_dynamic_date($reportSettings['order_item_meta_filter_1_value_2_dynamic'], true, $reportSettings['use_wp_date'])).'"'.(empty($reportSettings['order_item_meta_filter_1_value_2_dynamic']) ? '' : ' disabled="disabled"').' />
								<input type="text" style="width: auto;" class="ags-xoiwcp-date-dynamic-field ags-xoiwcp-date-dynamic-field-with-format'.(empty($reportSettings['order_item_meta_filter_1_value_2_dynamic']) ? ' hidden' : '').'" name="order_item_meta_filter_1_value_2_dynamic" value="'.esc_attr($reportSettings['order_item_meta_filter_1_value_2_dynamic']).'" placeholder="e.g. -7 days" />
								<a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['order_item_meta_filter_1_value_2_dynamic']) ? 'dynamic date' : 'fixed value').'</a>
							</span>
						</span>
						</div>
						</div>
						
						
						 <div class="ags-xoiwcp-settings-multirow has-checkbox">
                        <label class="ags-xoiwcp-settings-title">
                           <span class="label" style="display: none;">Only Order Items With Field 2:</span>
						</label>
                        <div id="hm_xoiwcp_order_item_meta_filter_settings_2" class="ags-xoiwcp-settings-content">
						<select style="width: auto;" name="order_item_meta_filter_2_logic">
							<option value="AND"'.($reportSettings['order_item_meta_filter_2_logic'] == 'AND' ? ' selected="selected"' : '').'>and</option>
							<option value="OR"'.($reportSettings['order_item_meta_filter_2_logic'] == 'OR' ? ' selected="selected"' : '').'>or</option>
						</select>
						<input type="checkbox" name="order_item_meta_filter_2_on" value="1"' . (empty($reportSettings['order_item_meta_filter_2_on']) ? '' : ' checked="checked"') . ' />
						
						<select style="width: auto;" name="order_item_meta_filter_2_key" class="ags-xoiwcp-select-other">
			');
			$orderItemFields = ags_xoiwcp_get_order_item_fields();
			
            $orderItemFieldFound = false;
			foreach ($orderItemFields as $orderItemField) {
				$orderItemFieldFound = $orderItemFieldFound || $reportSettings['order_item_meta_filter_2_key'] == $orderItemField;
				echo('<option value="'.esc_attr($orderItemField).'"'.($reportSettings['order_item_meta_filter_2_key'] == $orderItemField ? ' selected="selected"' : '').'>'.esc_html($orderItemField).'</option>');
			}
            if (!$orderItemFieldFound) {
				echo('<option value="'.esc_attr($reportSettings['order_item_meta_filter_2_key']).'" selected>'.esc_html($reportSettings['order_item_meta_filter_2_key']).'</option>');
			}
			echo('
						</select>
						<select style="width: auto;" id="hm_xoiwcp_order_item_meta_filter_2_op" name="order_item_meta_filter_2_op">
							<option value="="'.($reportSettings['order_item_meta_filter_2_op'] == '=' ? ' selected="selected"' : '').'>equal to</option>
							<option value="!="'.($reportSettings['order_item_meta_filter_2_op'] == '!=' ? ' selected="selected"' : '').'>not equal to</option>
							<option value="&lt;"'.($reportSettings['order_item_meta_filter_2_op'] == '<' ? ' selected="selected"' : '').'>less than</option>
							<option value="&lt;="'.($reportSettings['order_item_meta_filter_2_op'] == '<=' ? ' selected="selected"' : '').'>less than or equal to</option>
							<option value="&gt;"'.($reportSettings['order_item_meta_filter_2_op'] == '>' ? ' selected="selected"' : '').'>greater than</option>
							<option value="&gt;="'.($reportSettings['order_item_meta_filter_2_op'] == '>=' ? ' selected="selected"' : '').'>greater than or equal to</option>
							<option value="BETWEEN"'.($reportSettings['order_item_meta_filter_2_op'] == 'BETWEEN' ? ' selected="selected"' : '').'>between</option>
							<option value="NOTEXISTS"'.($reportSettings['order_item_meta_filter_2_op'] == 'NOTEXISTS' ? ' selected="selected"' : '').'>does not exist</option>
						</select>
						<span class="hm_xoiwcp_filter_value_container">
							<input style="width: auto;" type="text" id="hm_xoiwcp_order_item_meta_filter_2_value" name="order_item_meta_filter_2_value" value="'.esc_attr(empty($reportSettings['order_item_meta_filter_2_value_dynamic']) ? $reportSettings['order_item_meta_filter_2_value'] : ags_xoiwcp_resolve_dynamic_date($reportSettings['order_item_meta_filter_2_value_dynamic'], true, $reportSettings['use_wp_date'])).'"'.(empty($reportSettings['order_item_meta_filter_2_value_dynamic']) ? '' : ' disabled="disabled"').' />
							<input style="width: auto;" type="text" class="ags-xoiwcp-date-dynamic-field ags-xoiwcp-date-dynamic-field-with-format'.(empty($reportSettings['order_item_meta_filter_2_value_dynamic']) ? ' hidden' : '').'" name="order_item_meta_filter_2_value_dynamic" value="'.esc_attr($reportSettings['order_item_meta_filter_2_value_dynamic']).'" placeholder="e.g. -7 days" />
							<a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['order_item_meta_filter_2_value_dynamic']) ? 'dynamic date' : 'fixed value').'</a>
						</span>
						<span id="hm_xoiwcp_order_item_meta_filter_2_value_2" style="display: none;">
							and
							<span class="hm_xoiwcp_order_item_meta_filter_2_value_container">
								<input style="width: auto;" type="text" name="order_item_meta_filter_2_value_2" value="'.esc_attr(empty($reportSettings['order_item_meta_filter_2_value_2_dynamic']) ? $reportSettings['order_item_meta_filter_1_value_2'] : ags_xoiwcp_resolve_dynamic_date($reportSettings['order_item_meta_filter_2_value_2_dynamic'], true, $reportSettings['use_wp_date'])).'"'.(empty($reportSettings['order_item_meta_filter_2_value_2_dynamic']) ? '' : ' disabled="disabled"').' />
								<input style="width: auto;" type="text" class="ags-xoiwcp-date-dynamic-field ags-xoiwcp-date-dynamic-field-with-format'.(empty($reportSettings['order_item_meta_filter_2_value_2_dynamic']) ? ' hidden' : '').'" name="order_item_meta_filter_2_value_2_dynamic" value="'.esc_attr($reportSettings['order_item_meta_filter_2_value_2_dynamic']).'" placeholder="e.g. -7 days" />
								<a href="javascript:void(0);" class="ags-xoiwcp-date-dynamic-toggle">'.(empty($reportSettings['order_item_meta_filter_2_value_2_dynamic']) ? 'dynamic date' : 'fixed value').'</a>
							</span>
						</span>
                        </div>
                    </div>
                    
                    <div class="ags-xoiwcp-settings-tooltip tooltip-visible">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            Note: If you are including line-item refunds in the report, the order item field filtering settings will apply to the refund line items using the fields on those line items, not the original line items.</div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <label>
                       <span class="label">Line Item Refunds:</span>
                        <input type="checkbox" name="include_refunds"' . (empty($reportSettings['include_refunds'])
                    ? '' : ' checked="checked"') . ' /> Include line-item refunds
                    </label>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-visible ags-xoiwcp-mt-10">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
							Note: This setting <strong>only applies to refunds that are entered on a line-item basis</strong>. This means that refunds generated by changing the order status to Refunded, and refunds created by simply entering a total refund amount instead of line-item specific amounts and/or quantities, will not be reflected by this setting. Fully refunded orders (i.e. Refunded status) can be included/excluded from the report using the <strong>Order Filtering &gt; Include Orders With Status</strong> setting. If a Refunded status order also has a line-item refund, it may still be included in the report if this option is enabled and the Refunded order status is included.<br><br>
                            If checked, the report will include line-item refunds <b>entered</b> during the report date range (regardless of the original order date). Only refunds with "Completed" status (made on orders with the statuses defined in the Order Filtering tab) will be included.
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Shipping:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item">
                                <input type="checkbox" name="include_shipping"' . (empty($reportSettings['include_shipping']) ? '' : ' checked="checked"') . ' />
                                Include shipping order items
                            </label>
                        </div>
                    </div>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-hover">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            Of the built-in fields, only the following apply to shipping items: Product ID, Product Name, Line Item Gross, Line Item Gross After Discounts, Line Item Tax, Line Item Total With Tax.
                        </div>
                    </div>
                </div>

                 <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Shipping product name template:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                        <label id="ags_xoiwcp_shiping_product_name_template" class="ags-xoiwcp-settings-cb-list-item">
                                      <input type="text" name="shipping_product_name"' .
                 (empty($reportSettings['shipping_product_name']) ? '' : ' value="' .
                                                                         esc_attr($reportSettings['shipping_product_name']) . '"') . ' class="hm-xoiwcp-w400">
                            </label>
                        </div>
                        <div class="ags-xoiwcp-settings-tooltip tooltip-hover">
                            <div class="ags-xoiwcp-settings-tooltiptext">
                                <span>Help</span>
                                This is the text that appears in the Product Name field for shipping line items. You can use the [method_name] and [line_name] tags to insert the shipping method name and the line item name respectively.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Custom Order Item Types:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content"> ');
            foreach (ags_xoiwcp_get_order_item_types() as $orderItemType) {
                if ($orderItemType == 'shipping')
                    continue;
              echo('<label><input type="checkbox" name="order_item_types[]" value="'.esc_attr($orderItemType).'"'.(in_array($orderItemType, $reportSettings['order_item_types']) ? ' checked="checked"' : '').' />'.esc_html($orderItemType).'</label>');
            }
            echo('
                        </div>
                    </div>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-visible">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            The built-in fields may not work with order item types other than the default line_item type.
                        </div>
                    </div>
                </div>

            </div><!-- ags_xoiwcp_tab_lines_panel -->

            <div id="ags_xoiwcp_tab_fields_panel" class="ags_xoiwcp_tab_panel">

            <div class="ags-xoiwcp-settings-box">
                <div class="ags-xoiwcp-settings-cb-list">
                    <label class="ags-xoiwcp-settings-cb-list-title">
                       <span class="label">Export Fields:</span>
                     </label>
                    <div class="ags-xoiwcp-settings-cb-list-content">
                        <div id="ags_xoiwcp_report_field_selection">
                            <div id="ags_xoiwcp_report_fields">');
                                $fieldOptions2 = $fieldOptions;
                                $customFields = ags_xoiwcp_get_custom_fields();
                                $addonFields = ags_xoiwcp_get_addon_fields();
                                $noTotalFields = array(
                                        'product_id', 'product_sku', 'product_name', 'product_desc', 'variation_id', 'variation_sku',
                                        'variation_attributes', 'item_sku', 'product_categories', 'order_id', 'order_status', 'order_date',
                                        'billing_name', 'billing_phone', 'billing_email', 'billing_address', 'billing_state', 'shipping_name',
                                        'shipping_phone', 'shipping_email', 'shipping_address', 'shipping_state', 'customer_order_note',
                                        'order_note_most_recent', 'order_shipping_methods', 'order_item_type', 'order_item_id', 'customer_roles',
										'creator_roles', 'order_notes_user', 'order_date_only', 'order_parent', 'order_item_name', );

                                foreach ($reportSettings['fields'] as $fieldId) {
                                        $fieldNamesValue = isset($reportSettings['field_names'][$fieldId]) ? $reportSettings['field_names'][$fieldId] : (isset($fieldOptions2[$fieldId]) ? $fieldOptions2[$fieldId] : $fieldId);
                                        echo('<div>
                                                <input type="checkbox" name="fields[]" class="ags_xoiwcp_field_cb" checked="checked" value="'.esc_attr($fieldId).'" />
                                                <input type="text" name="field_names['.esc_attr($fieldId).']" value="'.esc_attr( $fieldNamesValue ).'" />
                                                <span class="ags-xoiwcp-drag-drop"></span>
                                                <label class="ags_xoiwcp_total_field'.(in_array($fieldId, $noTotalFields) ? ' no-total' : '').'">
                                                    <span>Total</span>
                                                    <input type="checkbox" name="total_fields[]" value="'.esc_attr($fieldId).'"'.(in_array($fieldId, $reportSettings['total_fields']) ? ' checked="checked"' : '').' />
                                                </label>
                                              </div>');
                                    unset($fieldOptions2[$fieldId]);
                                }

                                foreach ($fieldOptions2 as $fieldId => $fieldDisplay) {
                                    $fieldNamesValue = isset($reportSettings['field_names'][$fieldId]) ? $reportSettings['field_names'][$fieldId] : $fieldDisplay;
                                    echo('<div>
                                            <input type="checkbox" name="fields[]" class="ags_xoiwcp_field_cb" value="'.esc_attr($fieldId).'" />
                                            <input type="text" name="field_names['.esc_attr($fieldId).']" value="'.esc_attr($fieldNamesValue).'" />
                                            <span class="ags-xoiwcp-drag-drop"></span>
                                            <label class="ags_xoiwcp_total_field'.(in_array($fieldId, $noTotalFields) ? ' no-total' : '').'">
                                                <span>Total</span>
                                                <input type="checkbox" name="total_fields[]" value="'.esc_attr($fieldId).'"'.(in_array($fieldId, $reportSettings['total_fields']) ? ' checked="checked"' : '').' />
                                            </label>
                                        </div>');
                                }
                                unset($fieldOptions2);
                                echo('
                            </div> <!--  ags_xoiwcp_report_fields  -->
                        </div><!-- ags_xoiwcp_report_field_selection -->

                        <div id="ags_xoiwcp_export_add_custom_field">
                            <strong>Add Field:</strong>
                            <select id="ags_xoiwcp_custom_field">');
                            foreach ($customFieldsByType as $type => $fields) {
                                switch ($type) {
                                    case 'Product':
                                        $fieldPrefix = '__product__';
                                        break;
                                    case 'Product Variation':
                                        $fieldPrefix = '__product_variation__';
                                        break;
                                    case 'Order':
                                        $fieldPrefix = '__shop_order__';
                                        break;
                                    case 'Order Line Item':
                                        $fieldPrefix = '__order_item__';
                                        break;
                                    case 'Customer User':
                                        $fieldPrefix = '__customer_user__';
                                        break;
                                }
                                echo('<optgroup label="' . esc_attr($type) . '" class="ags-xoiwcp-select-other" data-ags-xoiwcp-other-field-prefix="' . esc_attr($fieldPrefix) . '">');
                                foreach ($fields as $fieldId => $fieldDisplay) {
                                    echo('<option value="'. esc_attr($fieldId).'">' . esc_html($fieldDisplay) .'</option>');
                                }
                                echo('</optgroup>');
                            }

                            $newAddonFields = array_diff_key($addonFields, $fieldOptions, $customFields);
                            if (!empty($newAddonFields)) {
                                echo('<optgroup label="Other">');
                                foreach ($newAddonFields as $fieldId => $fieldData) {
                                    echo('<option value="' . esc_attr($fieldId) . '">' . esc_html($fieldData['label']) . '</option>');
                                }
                                echo('</optgroup>');
                            }
                            echo('</select>
            
                            <button type="button" class="ags-xoiwcp-button-dark" onclick="ags_xoiwcp_add_custom_field();">Add</button>
                        </div><!-- ags_xoiwcp_export_add_custom_field -->
                    </div> <!-- ags-xoiwcp-settings-cb-list-content -->
                </div>
                
                 <div class="ags-xoiwcp-settings-tooltip bottom tooltip-visible">
                    <div class="ags-xoiwcp-settings-tooltiptext">
                        <span>Help</span>
                        Click and drag to the right of the field name text box to re-order fields.
                    </div>
                 </div>
            </div>

            </div><!-- ags_xoiwcp_tab_fields_panel -->

            <div id="ags_xoiwcp_tab_output_panel" class="ags_xoiwcp_tab_panel">

              <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Sort By:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <select name="orderby" id="ags_xoiwcp_field_orderby"></select>
                            <select name="orderdir">
                                <option value="asc"
                                ' . ($reportSettings['orderdir'] == 'asc' ? ' selected="selected"' : '') .
                '>ascending</option>
                                <option value="desc"
                                ' . ($reportSettings['orderdir'] == 'desc' ? ' selected="selected"' : '') .
                '>descending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <label for="ags_xoiwcp_field_orderby">
                       <span class="label">Header:</span>
                         <input type="checkbox" name="include_header"' . (empty($reportSettings['include_header']) ? '' : ' checked="checked"') . ' />
                            Include header row
                    </label>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-hover">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            If checked, the first row of the export will contain the field names.
                        </div>
                    </div>
                </div>

                  <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Totals:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item">
                                <input type="checkbox" id="ags_xoiwcp_field_include_totals" name="include_totals"' .
                (empty($reportSettings['include_totals']) ? '' : ' checked="checked"') . ' />
                                Include totals row
                            </label>
                            <label class="ags-xoiwcp-settings-cb-list-item ags-xoiwcp-settings-cb-list-item-child">
                                <input type="checkbox" name="totals_by_type"' .
                (empty($reportSettings['totals_by_type']) ? '' : ' checked="checked"') . ' value="1" />
                                Calculate separate totals by line item type
                            </label>
                            <label class="ags-xoiwcp-settings-cb-list-item ags-xoiwcp-settings-cb-list-item-child">
                                <input type="checkbox" name="order_total_once"' .
                (empty($reportSettings['order_total_once']) ? '' : ' checked="checked"') . ' />
                                Only total the order total field once per individual order
                            </label>
                            <label class="ags-xoiwcp-settings-cb-list-item ags-xoiwcp-settings-cb-list-item-child">
                                 <input type="checkbox" name="order_shipping_total_once"' .
                (empty($reportSettings['order_shipping_total_once']) ? '' : ' checked="checked"') . ' />
                                Only total order shipping fields once per individual order
                            </label> 
                        </div>
                    </div>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-hover">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            If checked, the last row of the export will contain totals.<br>
                            <b>"Only total order shipping fields once per individual order"</b> setting only applies to the following fields: Order Shipping Cost, Order Shipping Tax, and Order Shipping Cost With Tax.
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">

                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Order Separation</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item ags_xoiwcp_mb5">
							<input type="checkbox" name="order_fields_once"'.(empty($reportSettings['order_fields_once']) ? '' : ' checked="checked"').' />
							Only show order-related fields on the first row of a group of rows belonging to the same order
						</label>
						<label class="ags-xoiwcp-settings-cb-list-item">
							<input type="checkbox" name="order_group_empty_row"'.(empty($reportSettings['order_group_empty_row']) ? '' : ' checked="checked"').' />
							Add an empty row after each group of rows belonging to the same order
						</label>
                        </div>
                    </div>
                    <div class="ags-xoiwcp-settings-tooltip tooltip-visible">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                             Order Separation options may work best when the report is sorted by order ID or another field  that results in items belonging to the same order being grouped together. The &quot;Only show order-related fields on the first row of a group of rows belonging to the same order&quot; option does affect totals but it should not affect the sort order.
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label" >Amount Formatting</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item" for="ags_xoiwcp_field_format_amounts">
							 <input id="ags_xoiwcp_field_format_amounts" type="checkbox" name="format_amounts" value="1"'
                 . (empty($reportSettings['format_amounts']) ? '' : ' checked="checked"') . ' />
                            Format and round amounts (applies to certain built-in fields only)
                            </label>
                            Number of decimals:
                            <label>
                                <input type="text" name="format_amounts_decimals"'.(empty($reportSettings['format_amounts_decimals']) ? '' : ' value="'.esc_attr($reportSettings['format_amounts_decimals']).'"').'>
                            </label>
                            Decimal separator:
                            <label>
                                <input type="text" name="format_amounts_decimal_sep"'.(empty($reportSettings['format_amounts_decimal_sep']) ? '' : ' value="'.esc_attr($reportSettings['format_amounts_decimal_sep']).'"').'>
                            </label>
                            Thousands separator:
                            <label>
                                <input type="text" name="format_amounts_thousands_sep"'.(empty($reportSettings['format_amounts_thousands_sep']) ? '' : ' value="'.esc_attr($reportSettings['format_amounts_thousands_sep']).'"').'>
                            </label>
                        </div>
                    </div>
                     <div class="ags-xoiwcp-settings-tooltip tooltip-visible ags-xoiwcp-mt-10">
                        <div class="ags-xoiwcp-settings-tooltiptext">
                            <span>Help</span>
                            Calculations are done based on the amounts stored in the WooCommerce database without intermediate rounding, so selecting this option may introduce small rounding variances. The totals row is based on the rounded values if this option is enabled.
                        </div>
                    </div>
                </div>

                <div class="ags-xoiwcp-settings-cb-list">
                    <label for="ags_xoiwcp_field_format" class="ags-xoiwcp-settings-cb-list-title">
                       <span class="label">Format:</span>
                     </label>
                    <div id="ags_xoiwcp_field_format_settings" class="ags-xoiwcp-settings-cb-list-content">
                        <select name="format" id="ags_xoiwcp_field_format">
                            <option value="csv"
                            ' . ($reportSettings['format'] == 'csv' ? ' selected="selected"' : '') . '>CSV</option>
                            <option value="csv-ascii"
                            ' . ($reportSettings['format'] == 'csv-ascii' ? ' selected="selected"' : '') . '>CSV -
                            ASCII</option>
                            <option value="xlsx"
                            ' . ($reportSettings['format'] == 'xlsx' ? ' selected="selected"' : '') . '>XLSX</option>
                            <option value="html"
                            ' . ($reportSettings['format'] == 'html' ? ' selected="selected"' : '') . '>HTML</option>
                            <option value="html-enhanced"
                            ' . ($reportSettings['format'] == 'html-enhanced' ? ' selected="selected"' : '') . '>HTML
                            (enhanced)</option>
                        </select>
                        <div id="ags_xoiwcp_format_options_csv" class="ags_xoiwcp_format_options">
                            <label>
                                Separate fields with:
                                <input type="text" name="format_csv_delimiter" maxlength="1"' .
                (empty($reportSettings['format_csv_delimiter']) ? '' : ' value="' .
                    esc_attr($reportSettings['format_csv_delimiter']) . '"') . '>
                            </label>
                            <label>
                                Surround fields with:
                                <input type="text" name="format_csv_surround" maxlength="1"' .
                (empty($reportSettings['format_csv_surround']) ? '' : ' value="' .
                    esc_attr($reportSettings['format_csv_surround']) . '"') . '>
                            </label>
                            <label>
                                Escape surround character with:
                                <input type="text" name="format_csv_escape" maxlength="1"' .
                (empty($reportSettings['format_csv_escape']) ? '' : ' value="' .
                    esc_attr($reportSettings['format_csv_escape']) . '"') . '>
                            </label>
                        </div>
                    </div>
                </div>

                 <div class="ags-xoiwcp-settings-box">
                    <div class="ags-xoiwcp-settings-cb-list">
                        <label class="ags-xoiwcp-settings-cb-list-title">
                           <span class="label">Advanced:</span>
                         </label>
                        <div class="ags-xoiwcp-settings-cb-list-content">
                            <label class="ags-xoiwcp-settings-cb-list-item">
                              <input type="checkbox" name="report_unfiltered"' . (empty($reportSettings['report_unfiltered']) ? '' : ' checked="checked"') . '>
                            Attempt to prevent other plugins or code from changing the export query or output
                            </label>
                             <p class="description ags-xoiwcp-ml-30">Enabling this option can help resolve issues caused by conflicting
                            plugins, but be sure to verify the accuracy and completeness of the export output when using
                            this option.</p>
							
                            <label class="ags-xoiwcp-settings-cb-list-item">
                                <input type="checkbox" id="ags_xoiwcp_use_wp_date" name="use_wp_date"' . (empty($reportSettings['use_wp_date']) ? '' : ' checked="checked"') . '>
                                Use WordPress date formatting functionality for dynamic date values
                            </label>
                             <p class="description ags-xoiwcp-ml-30">If the dynamic date feature is not producing output in the language that your field values are using, and your WordPress language is set to that language, try enabling this option.</p>

                            <label class="ags-xoiwcp-settings-cb-list-item">
                                <input type="checkbox" name="ags_xoiwcp_debug"' . (empty($reportSettings['ags_xoiwcp_debug']) ? '' : ' checked="checked"') . '>
                                Enable debug mode
                            </label>
                        </div>
                    </div>
                </div>

            </div><!-- ags_xoiwcp_tab_output_panel -->
        </div>

        ');
        echo('
        <p class="submit">
            <button type="submit" 
                    class="ags-xoiwcp-button-primary" 
                    name="ags_xoiwcp_action" value="run"
                    onclick="jQuery(\'#ags_xoiwcp_form\').attr(\'target\', \'_blank\'); return true;">
                    Export
            </button>
        </p>
    </form>
    </div>
    <div id="ags-xoiwcp-settings-license">
    ');
            $accepted_license = esc_html(get_option('hm_xoiwcp_license_key'));
            $display_license = str_repeat('*', strlen($accepted_license) - 4) . substr($accepted_license, -4);
            ?>

            <div id="ags-xoiwcp_license_key_box">
                <form action="" method="post" id="ags-xoiwcp_license_key_form">
                    <div id="ags-xoiwcp_license_key_form_logo_container">
                        <a href="https://wpzone.co/?utm_source=export-order-items-pro&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-link"
                           target="_blank"><img
                                    src="<?php echo(esc_url(plugins_url('../images/wp_zone_logo.png', __FILE__))); ?>"
                                    alt="WP Zone"/></a>
                    </div>

                    <div id="ags-xoiwcp_license_key_form_body">
                        <h3>
                            <?php echo ags_xoiwcp_ITEM_NAME; ?>
                        </h3>
                        <label>
                            <span>License Key:</span>
                            <input type="text" readonly="readonly" value="<?php echo(esc_html($display_license)); ?>"/>
                        </label>
                        <?php wp_nonce_field('ags_xoiwcp_license_deactivate_nonce', 'ags_xoiwcp_license_deactivate_nonce'); ?>
                        <p class="submit">
                            <button type="submit" class="button-secondary" name="ags_xoiwcp_license_deactivate"
                                    value="1">
                                Deactivate License Key
                            </button>
                        </p>
                    </div>
                </form>
            </div>

            <?php // todo: license is not activating
            foreach ($addons as $addonId => $addon) {
                if (!empty($addon['licensing'])) { ?>
                    <div id="ags-xoiwcp_license_key_box">
                        <form action="" method="post" id="ags-xoiwcp_addon_license_key_form">
                            <div id="ags-xoiwcp_license_key_form_logo_container">
                                <a href="#" target="_blank"><img
                                            src="<?php echo(esc_url(plugins_url('../images/wp_zone_logo.png', __FILE__))); ?>"
                                            alt="WP Zone"/></a>
                            </div>

                            <div id="ags-xoiwcp_license_key_form_body">
                                <h3>
                                    <?php echo esc_html($addon['title']); ?>
                                    <small>by <a href="<?php echo esc_attr($addon['url']); ?>"
                                                 target="_blank"><?php echo esc_html($addon['author']); ?></a></small>
                                </h3>

                                <?php
                                if ($addon['license_status']) {
                                    echo('<label><span>License Key:</span> ' . esc_html(str_repeat('*', strlen($addon['license_key']) - 4) . substr($addon['license_key'], -4)) . '</label>');
                                    wp_nonce_field($addonId . '_license_deactivate_nonce', $addonId . '_license_deactivate_nonce');
                                    echo('<p class="submit"><button type="submit" name="' . esc_attr($addonId) . '_license_deactivate" value="1" class="button-secondary">Deactivate License Key</button></p>');
                                } else {
                                    echo('<label><span>License Key:</span> <input type="text" name="' . esc_attr($addonId) . '_license_key"/></label>');
                                    wp_nonce_field($addonId . '_license_activate_nonce', $addonId . '_license_activate_nonce');
                                    echo(' <p class="submit"><button type="submit" class="button-secondary">Activate License Key</button></p>');
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                    <?php
                }
            } // end foreach
            ?>
        </div>
        <div id="ags-xoiwcp-settings-about">
            <div class="ags-xoiwcp-settings-tabs-content">
                <div id="ags-xoiwcp-settings-about" class="ags-xoiwcp-settings-active">
                    <h2><?php esc_html_e('Export Order Items Pro', 'export-order-items-pro') ?></h2>
                    <p><?php esc_html_e('Export Order Items Pro for WooCommerce generates in-depth data exports that provide details of customers’ product purchases.', 'export-order-items-pro') ?></p>
                    <h2><?php esc_html_e('Main features', 'export-order-items-pro') ?></h2>
                    <ul>
                        <li><?php esc_html_e('Use a date range preset, or specify custom start and end dates.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Select from a variety of predefined data fields that can be included in the report.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Filter line items by order status (Pending Payment, Processing, Completed, Cancelled, etc.).', 'export-order-items-pro') ?> </li>
                        <li> <?php printf( esc_html__('Integrates with the %sScheduled Email Reports for WooCommerce%s plugin to automatically send reports as email attachments on a recurring basis.', 'export-order-items-pro'), '<a href="https://wpzone.co/product/scheduled-email-reports-for-woocommerce/" target="_blank">', '</a>'  ); ?></li>
                        <li><?php printf( esc_html__('Compatible with the Frontend Reports for WooCommerce plugin to enable access to preset reports from the frontend via a shortcode or widget.', 'export-order-items-pro'), '<a href="https://wpzone.co/product/frontend-reports-for-woocommerce/" target="_blank">', '</a>'  ); ?></li>
                        <li><?php esc_html_e('Create multiple export presets to save time.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Include product variation details.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Include custom fields defined on an order, product order line item, product, or product variation.', 'export-order-items-pro') ?> </li>
                        <li><?php esc_html_e('Limit the export to only include certain product IDs or product categories.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Change the names and order of fields in the report.', 'export-order-items-pro') ?></li>
                        <li><?php esc_html_e('Export in XLS, XLSX, or HTML format (in addition to CSV).', 'export-order-items-pro') ?></li>
                        <li><strong><?php printf( esc_html__('Integrates with the %sExtra Product Options Addon for Export Order Items Pro plugin%s to export an fields from the WooCommerce Extra Product Options plugin.', 'export-order-items-pro'), '<a href=" https://wpzone.co/product/extra-product-options-addon-for-export-order-items-pro/" target="_blank">', '</a>'  ); ?></strong></li>
                    </ul>
                    <a href="https://wpzone.co/product/export-order-items-pro-for-woocommerce/" target="_blank"><?php esc_html_e ('Read More about plugin features', 'export-order-items-pro') ?>.</a>

                    <h3><?php esc_html_e('Product documentation', 'export-order-items-pro') ?></h3>
                    <?php printf( esc_html__('Get started your adventure with Export Order Items Pro for WooCommerce with a %splugin documentation%s that covers the basics ', 'export-order-items-pro'), '<a href="https://docs.divi.space/docs/plugin/export-order-items-pro/" target="_blank">', '</a>'  ); ?>

                    <!--  <h3>Premade layouts</h3 >-->
                    <!--  Divi Shop Builder ships great premade layouts that you can use to jumpstart your design. <a href="https://wpzone.co/docs/divi-shop-builder/#layouts" target="_blank">Learn how to import layouts to your site.</a>-->


                </div>
            </div>

        </div>
        <div id="ags-xoiwcp-settings-addons">
	        <?php
	        define('AGS_Export_Order_Items_Pro_Addons_URL', 'https://wpzone.co/wp-content/uploads/product-addons/export-order-items-pro.json');
	        require_once( plugin_dir_path( __FILE__ ) . '/addons/addons.php');
	        AGS_Export_Order_Items_Pro_Addons::outputList();
	        ?>
        </div>
    </div> <!-- ags-xoiwcp-settings-tabs-content -->
</div> <!-- ags-xoiwcp-settings -->
</div> <!-- ags-xoiwcp-settings-container-->

<script type="text/javascript" src="<?php echo plugins_url('../js/export-order-items.js', __FILE__); ?>"></script>

<script>
    var ags_xoiwcp_tabs_navigate = function () {
        var tabs = [
                {
                    tabsContainerId: 'ags-xoiwcp-settings-tabs',
                    contentIdPrefix: 'ags-xoiwcp-settings-'
                },
                {
                    tabsContainerId: 'ags-xoiwcp-settings-sub-tabs',
                    contentIdPrefix: 'ags-xoiwcp-settings-sub-'
                }
            ],
            activeClass = 'ags-xoiwcp-settings-active';
        for (var i = 0; i < tabs.length; ++i) {
            var $tabContent = jQuery('#' + tabs[i].contentIdPrefix + location.hash.substr(1));
            if ( $tabContent.length ) {
                var $tabs = jQuery('#' + tabs[i].tabsContainerId + ' > li');
                $tabContent
                    .siblings()
                    .add($tabs)
                    .removeClass(activeClass);
                $tabContent.addClass(activeClass);
                $tabs
                    .filter(':has(a[href="' + location.hash + '"])')
                    .addClass(activeClass);
                break;
            }
        }
    };
    if (location.hash) {
        ags_xoiwcp_tabs_navigate();
    }

    jQuery(window).on('hashchange', ags_xoiwcp_tabs_navigate);
</script>
