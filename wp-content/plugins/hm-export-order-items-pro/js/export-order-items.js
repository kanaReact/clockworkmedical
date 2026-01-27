/**
 * Author: Potent Plugins
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
jQuery(document).ready(function($) {
	$('#ags_xoiwcp_field_report_time').change(function() {
		$('.ags_xoiwcp_custom_time').toggle(this.value == 'custom');
	});
	$('#ags_xoiwcp_field_report_time').change();

	$('#ags_xoiwcp_report_fields').sortable({
		update: ags_xoiwcp_update_sort_options
	});

	$('#ags_xoiwcp_field_report_preset').change(function() {
		$(this).closest('form').submit();
	});

	$('#ags_xoiwcp_field_save_preset').closest('form').submit(function(ev) {
		setTimeout(function() {
			location.reload();
		}, 2000);
	});


	$('#ags_xoiwcp_product_tag_filter_select').change(function() {
		var thisTag = $(this).val();
		if (thisTag != '') {
			var currentTags = $('#ags_xoiwcp_product_tag_filter').val();
			$('#ags_xoiwcp_product_tag_filter').val((currentTags == '' ? '' : currentTags + ', ') + thisTag);
		}
		$(this).val('');
	});

	$('#ags_xoiwcp_tabs > a').click(function() {
		$('#ags_xoiwcp_tab_panels > .ags_xoiwcp_tab_panel').hide();
		$('#ags_xoiwcp_tabs > a').removeClass('nav-tab-active');
		$('#' + $(this).attr('id') + '_panel').show();
		$(this).addClass('nav-tab-active');
	});
	if (location.hash.length > 1 && $('#ags_xoiwcp_tab_' + location.hash.substring(1)).length) {
		$('#ags_xoiwcp_tab_' + location.hash.substring(1)).click();
	} else {
		$('#ags_xoiwcp_tabs > a:first-child').click();
	}

	$('#ags_xoiwcp_field_include_totals').change(function() {
		if ($(this).is(':checked')) {
			$('.ags_xoiwcp_field_cb').change();
		} else {
			$('.ags_xoiwcp_total_field').hide();
		}
	});
	$('#ags_xoiwcp_field_include_totals').change();
	$('#ags_xoiwcp_report_fields').on('change', '.ags_xoiwcp_field_cb', null, function() {
		if ($('#ags_xoiwcp_field_include_totals').is(':checked')) {
			$(this).siblings('.ags_xoiwcp_total_field').css('display', ($(this).is(':checked') ? 'block' : 'none'));
		}

		ags_xoiwcp_update_sort_options();
	});
	
	$('#ags_xoiwcp_order_meta_filter_op, #ags_xoiwcp_order_meta_filter_2_op, #ags_xoiwcp_order_meta_filter_3_op, #ags_xoiwcp_customer_meta_filter_op, #ags_xoiwcp_product_meta_filter_op, #hm_xoiwcp_order_item_meta_filter_1_op, #hm_xoiwcp_order_item_meta_filter_2_op').change(function() {
		var $opSelect = $(this);
		
		switch ($opSelect.val()) {
			case 'EXISTS':
			case 'NOTEXISTS':
				$opSelect.siblings('.hm_xoiwcp_filter_value_container')
					.hide()
					.val('');
				$opSelect.siblings('[id$="_value_2"]')
					.hide()
					.find('input')
					.val('');
				break;
			case 'BETWEEN':
				$opSelect.siblings('.hm_xoiwcp_filter_value_container, [id$="_value_2"]')
					.show();
				break;
			default:
				$opSelect.siblings('.hm_xoiwcp_filter_value_container')
					.show();
				$opSelect.siblings('[id$="_value_2"]')
					.hide()
					.find('input')
					.val('');
		}
	}).change();

	$('#ags_xoiwcp_field_format').change(function() {
		$('.ags_xoiwcp_format_options').hide();
		$('#ags_xoiwcp_format_options_' + $(this).val()).show();
	});
	$('#ags_xoiwcp_field_format').change();

	$('#ags_xoiwcp_product_meta_filter_op').change(function() {
		var isBetween = ( $(this).val() == 'BETWEEN' ),
			$metaFilterValue2 = $('#ags_xoiwcp_product_meta_filter_value_2'),
			$valuePreset = $metaFilterValue2.siblings('.hm-xoiwcp-value-preset:first');

		if ( isBetween && $valuePreset.val() ) {
			$valuePreset.val('').change();
		}

		$metaFilterValue2.toggle(isBetween);
		$valuePreset.toggle(!isBetween);

	});
	$('#ags_xoiwcp_product_meta_filter_op').change();


	ags_xoiwcp_update_sort_options();

	$('.hm-xoiwcp-value-preset')
		.each(function() {
			var $preset = $(this),
				forFieldName = $preset.data('hm-xoiwcp-for');

			if (forFieldName) {
				var $forField = $('[name="' + forFieldName + '"]');
				if ($forField.length) {

					$preset.change(function() {
						var presetValue = $preset.val();
						$forField
							.val(presetValue)
							.toggle(!presetValue);
					});

					$preset
						.children( '[value="' + $forField.val() + '"]:first' )
						.attr('selected', true);
					
					if ($preset.val()) {
						$preset.change();
					}


				}
			}
		});
		
	$('.ags-xoiwcp-order-meta-filter-cast').on('change', function() {
		$(this).next().toggle( $(this).val() == 'date' );
	}).trigger('change');
	
	$('#ags-xoiwcp-settings-tabs-content .ags-xoiwcp-select-other')
		.append($('<option>').html('Other...').addClass('ags-xoiwcp-select-other-option'))
		.closest('select')
		.change(function() {
			var $this = $(this);
			var $selectedOtherOption = $this.find('option.ags-xoiwcp-select-other-option:selected')
			if ($this.hasClass('ags-xoiwcp-select-other-selected')) {
					$this.removeClass('ags-xoiwcp-select-other-selected').siblings('.ags-xoiwcp-select-other-field').remove();
					$selectedOtherOption.html('Other...');
			}
			if ($selectedOtherOption.length) {
				var selectName = $this.attr('name');
				var $optGroup = $selectedOtherOption.closest('optgroup');
				var $otherField = $('<input>')
									.attr({placeholder: '(enter value)', type: 'text'})
									.addClass('ags-xoiwcp-select-other-field')
									.change(function() {
										var $this = $(this);
										var thisValue = $(this).val();
										var otherFieldPrefix = $optGroup.data('ags-xoiwcp-other-field-prefix');
										$selectedOtherOption.val((otherFieldPrefix ? otherFieldPrefix : '') + thisValue);
										
										// Special handling for the Group By fields
										if ($this.siblings('#hm_psr_field_groupby').length) {
											$('#hm_psr_report_fields .hm_psr_groupby_field .hm_psr_field_name').val(thisValue);
										} else if ($this.siblings('#hm_psr_field_groupby2').length) {
											$('#hm_psr_report_fields .hm_psr_groupby_field2 .hm_psr_field_name').val(thisValue);
										} else if ($this.siblings('#hm_psr_field_groupby3').length) {
											$('#hm_psr_report_fields .hm_psr_groupby_field3 .hm_psr_field_name').val(thisValue);
										}
									});
				
				$selectedOtherOption.text(($optGroup.length ? $optGroup.attr('label') + ' - ' : '') + 'Other:');
				
				$this
					.addClass('ags-xoiwcp-select-other-selected')
					.after($otherField);
			}
		});
	
	$('.ags-xoiwcp-date-dynamic-toggle').click(function() {
		var $this = $(this);
		var $dynamicField = $this.siblings('.ags-xoiwcp-date-dynamic-field');
		if ($dynamicField.hasClass('hidden')) {
			$dynamicField.siblings('input:first').prop('disabled', true);
			$dynamicField.val($dynamicField.hasClass('ags-xoiwcp-date-dynamic-field-with-format') ? 'now|Y-m-d H:i:s' : 'today').removeClass('hidden').change();
			$this.text('fixed value');
		} else {
			$dynamicField.addClass('hidden').val('');
			$dynamicField.siblings('input:first').prop('disabled', false);
			$this.text('dynamic date');
		}
	});
	
	$('.ags-xoiwcp-date-dynamic-field').change(function() {
		var $this = $(this);
		$.post(ajaxurl, {
			'action': 'hm_psr_calc_dynamic_date',
			'date': $this.val(),
			'allowFormat': $this.hasClass('ags-xoiwcp-date-dynamic-field-with-format'),
			'use_wp_date': $('#ags_xoiwcp_use_wp_date:checked:first').length
		}, function(result) {
			if (result.success && result.data) {
				$this.siblings('input:first').val(result.data);
			} else {
				alert('Unable to calculate date from expression: ' + $this.val());
			}
		}).fail(function() {
			alert('Unable to calculate date from expression: ' + $this.val());
		});
	});
	
	$('#ags_xoiwcp_use_wp_date').change(function() {
		$('.ags-xoiwcp-date-dynamic-field:not(.hidden)').change();
	});
	
	function validateTimeField() {
		var $field = $(this);
		var validationResult = ags_xoiwcp_validate_time( $field.val(), $field.attr('id') === 'ags_xoiwcp_field_report_end_time' );
		$field.siblings('.ags-xoiwcp-validation-result').remove();
		if (validationResult !== true) {
			$('<p>').addClass('ags-xoiwcp-validation-result ags-xoiwcp-validation-' + validationResult[0]).text(validationResult[1]).appendTo( $field.parent() );
		}
	}
	
	$('#ags_xoiwcp_field_report_start_time, #ags_xoiwcp_field_report_end_time').on('change', validateTimeField).each(validateTimeField);
});



function ags_xoiwcp_validate_time(timeStr, withExclusivityWarning) {
	var timeWords = timeStr.trim().split(' ').filter( function(word) {
		return word.trim().length > 0;
	} );
	var genericFailureMessage = ['error', 'This time format is not recognized. Please enter the time in either 12-hour format (12:00 AM) or 24 hour format (00:00), optionally including seconds.'];
	if (!timeWords.length || timeWords.length > 2 || (timeWords.length == 2 && ['am', 'pm'].indexOf(timeWords[1].toLowerCase()) === -1)) {
		return genericFailureMessage;
	}
	
	var is24Hour = timeWords.length === 1;
	
	var timeValues = timeWords[0].split(':');
	if (timeValues.length < 2 || timeValues.length > 3) {
		return genericFailureMessage;
	}
	
	if (parseInt(timeValues[0]) != timeValues[0] || timeValues[0].length > 2 || parseInt(timeValues[0]) < 0 || parseInt(timeValues[0]) > 23) {
		return ['error', 'This time has an invalid hour component. Please specify an hour between 1 and 12 (for 12-hour time) or 0 and 23 (for 24-hour time). Single digit hours may have at most one leading zero.'];
	}
	
	timeValues[0] = parseInt(timeValues[0]);
	if (!is24Hour && (timeValues[0] === 0 || timeValues[0] > 12)) {
		return ['error', 'This time uses 24 hour format with AM or PM. Please either remove AM or PM, or specify the hours in 12-hour format instead.'];
	}
	
	if (parseInt(timeValues[1]) != timeValues[1] || timeValues[1].length != 2 || parseInt(timeValues[1]) < 0 || parseInt(timeValues[1]) > 59) {
		return ['error', 'This time has an invalid minute component. Please specify a minute between 0 and 59. Single digit minutes must have one leading zero (for example, 05).'];
	}
	
	if (timeValues.length === 3 && (parseInt(timeValues[2]) != timeValues[2] || timeValues[2].length != 2 || parseInt(timeValues[2]) < 0 || parseInt(timeValues[2]) > 59)) {
		return ['error', 'This time has an invalid second component. Please specify a second between 0 and 59. Single digit seconds must have one leading zero (for example, 05).'];
	}
	
	// Handle some valid time values that may be user error
	if (withExclusivityWarning && parseInt(timeValues[1]) === 59 && (timeValues.length !== 3 || !parseInt(timeValues[2]) || parseInt(timeValues[2]) === 59)) {
		return ['warning', 'Keep in mind that this time value is exclusive, so the report will include data before (but not including) this time. For example, to report to the end of a day, don\'t specify time 12:59 PM (or 23:59) on that day; instead, specify time 12:00 AM (or 0:00) on the next day.'];
	}
	
	return true;
}


function ags_xoiwcp_add_custom_field() {
	var $fieldsSelect = jQuery('#ags_xoiwcp_custom_field');
	var isOtherOption = jQuery('#ags_xoiwcp_custom_field option:selected:first').hasClass('ags-xoiwcp-select-other-option');
	if (isOtherOption) {
		var otherFieldName = $fieldsSelect.siblings('.ags-xoiwcp-select-other-field:first').val();
		if (!otherFieldName.length) {
			return;
		}
	}
	
	var customFieldBox = jQuery('#ags_xoiwcp_report_fields > .ui-sortable-handle').last().clone();
	customFieldBox.children('.ags_xoiwcp_field_cb').prop('checked', true).attr('value', $fieldsSelect.val()).change();
	customFieldBox.children('.ags_xoiwcp_total_field').removeClass('no-total').children('input').prop('checked', false).attr('value', $fieldsSelect.val());
	customFieldBox.children('input[type="text"]').attr('name', 'field_names[' + $fieldsSelect.val() + ']').val( isOtherOption ? otherFieldName : jQuery('#ags_xoiwcp_custom_field option:selected:first').text() );
	jQuery('#ags_xoiwcp_report_fields').append(customFieldBox);
	
	if (isOtherOption) {
		$fieldsSelect.val($fieldsSelect.find('option:first').val()).change();
	}

	ags_xoiwcp_update_sort_options();
}


// hm-product-sales-report-pro/js/hm-product-sales-report.js
function ags_xoiwcp_update_sort_options() {
	var $select = jQuery('#ags_xoiwcp_field_orderby');
	var currentValue = $select.val();
	$select.empty();

	jQuery('#ags_xoiwcp_report_fields .ags_xoiwcp_field_cb:checked').each(function() {
		var $field = jQuery(this);
		var fieldId = $field.val();
		jQuery('<option>')
			.attr('value', fieldId)
			.text($field.siblings('input[type="text"]').val())
			.attr('selected', fieldId == currentValue)
			.appendTo($select);
	});
}
