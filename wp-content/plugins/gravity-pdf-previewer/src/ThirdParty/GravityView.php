<?php

namespace GFPDF\Plugins\Previewer\ThirdParty;

use GFAPI;
use GFPDF\Helper\Helper_Interface_Filters;
use GV\View;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       3.1.1
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class GravityView implements Helper_Interface_Filters {

	/**
	 * @since 3.1.1
	 */
	public function init() {
		$this->add_filters();
	}

	/**
	 * @since 3.1.1
	 */
	public function add_filters() {
		add_filter( 'gfpdf_previewer_form_pre_entry_created', [ $this, 'fix_conditional_logic' ] );
		add_filter( 'gfpdf_previewer_created_entry', [ $this, 'merge_entry_data' ], 10, 4 );
	}

	/**
	 * Disable conditional logic when Previewer first creates the entry
	 *
	 * @param array $form
	 *
	 * @return array
	 *
	 * @since 3.1.4
	 */
	public function fix_conditional_logic( $form ) {
		/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
		if ( ! isset( $_POST['is_gv_edit_entry'], $_POST['lid'] ) ) {
			return $form;
		}

		/* Only run if GravityView multi-page edit support is NOT enabled */
		/* phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores */
		if ( apply_filters( 'gravityview/features/paged-edit', false, $form ) ) {
			return $form;
		}

		foreach ( $form['fields'] as $id => $field ) {
			/* Clone the field to prevent any other references to this field object also being updated */
			if ( is_object( $field ) ) {
				$field = clone $field;
			}

			/* Remove conditional logic from all fields */
			$field['conditionalLogic'] = '';
			$form['fields'][ $id ]     = $field;
		}

		return $form;
	}

	/**
	 * To ensure accuracy in the previewer, when GravityView Preview submission occurs we'll merge the existing
	 * DB entry with the $_POST data entry
	 *
	 * @param array $raw_entry
	 * @param array $form
	 * @param array $settings
	 * @param array $input
	 *
	 * @return array
	 *
	 * @since 3.1.1
	 */
	public function merge_entry_data( $raw_entry, $form, $settings, $input ) {
		/* If not a GV Edit Entry page, ignore */
		if ( ! isset( $input['is_gv_edit_entry'], $input['lid'] ) ) {
			return $raw_entry;
		}

		/* Skip if the required GravityView classes are not found */
		if (
			! class_exists( '\GV\View' ) ||
			! class_exists( '\GravityView_Edit_Entry_Render' ) ||
			! class_exists( '\GravityView_Edit_Entry' )
		) {
			return $raw_entry;
		}

		$entry_id = (int) $input['lid'];
		if ( $entry_id === 0 ) {
			return $raw_entry;
		}

		/* Get the View ID */
		$view = $this->get_view_from_input_data( $input );
		if ( ! $view ) {
			return $raw_entry;
		}

		/* Return the POST data entry if we can't get the one from the DB */
		$db_entry = $this->get_entry( $entry_id );
		if ( is_wp_error( $db_entry ) ) {
			return $raw_entry;
		}

		/* Get the GV fields being displayed */
		$editable_fields = $this->get_edit_entry_fields( $view, $form, $raw_entry );

		return $this->entry_merge( $db_entry, $raw_entry, $form['fields'], wp_list_pluck( $editable_fields, 'id' ) );
	}

	/**
	 * Get the View ID from the $_POST data and return the View
	 *
	 * @param array
	 *
	 * @return View|null
	 * @since 3.2.3
	 */
	protected function get_view_from_input_data( $input ) {
		/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
		$keys          = array_keys( $input );
		$matching_keys = preg_grep( '/^edit_\d+_\d+_\d+$/', $keys );
		$matching_key  = isset( $matching_keys[0] ) ? $matching_keys[0] : '';

		$view_id_extraction = explode( '_', $matching_key );
		$view_id            = isset( $view_id_extraction[1] ) ? (int) $view_id_extraction[1] : 0;

		return View::by_id( $view_id );
	}

	/**
	 * Get the current entry
	 *
	 * @param int $entry_id
	 *
	 * @return array|\WP_Error
	 *
	 * @since 1.1
	 */
	protected function get_entry( $entry_id ) {
		return GFAPI::get_entry( $entry_id );
	}

	/**
	 * @param View  $view
	 * @param array $form
	 * @param array $entry
	 *
	 * @return array
	 */
	protected function get_edit_entry_fields( View $view, array $form, array $entry ) {
		$render          = new \GravityView_Edit_Entry_Render( \GravityView_Edit_Entry::getInstance() );
		$render->view_id = $view->ID;
		$render->view    = $view;
		$render->form_id = $form['id'];
		$render->entry   = $entry;

		$gv_form = $render->filter_modify_form_fields( $form );

		return isset( $gv_form['fields'] ) ? $gv_form['fields'] : [];
	}

	/**
	 * Merge in the editable fields values from the POSTed entry data to the DB entry data
	 *
	 * @param array $db_entry  The current entry information found in the DB
	 * @param array $raw_entry The newly $_POSTed entry information
	 * @param array $fields    The Gravity Form object fields
	 * @param array $editable_fields
	 *
	 * @return array
	 *
	 * @since 1.1
	 */
	protected function entry_merge( array $db_entry, array $raw_entry, array $fields, array $editable_fields ) {
		$editable_fields = array_map( 'intval', $editable_fields );

		foreach ( $fields as $field ) {
			if ( in_array( (int) $field->id, $editable_fields, true ) ) {
				$inputs = $field->get_entry_inputs();

				if ( is_array( $inputs ) ) {
					foreach ( $inputs as $input ) {
						$db_entry[ $input['id'] ] = isset( $raw_entry[ $input['id'] ] ) ? $raw_entry[ $input['id'] ] : '';
					}
				} else {
					$db_entry[ $field->id ] = isset( $raw_entry[ $field->id ] ) ? $raw_entry[ $field->id ] : '';
				}
			}
		}

		return $db_entry;
	}
}
