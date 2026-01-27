<?php

namespace GFPDF\Plugins\Previewer\ThirdParty;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.2.6
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NestedFormsPerk
 *
 * @package GFPDF\Plugins\Previewer\ThirdParty
 */
class NestedFormsPerk {

	/**
	 * Initiaise the class if the Nested Forms Perk is activated
	 *
	 * @since 1.2.6
	 */
	public function init() {
		if ( ! function_exists( '\gp_nested_forms' ) ) {
			return;
		}

		$this->add_action();

		add_filter( 'gfpdf_previewer_entry_id', [ $this, 'set_entry_id' ], 10, 3 );
	}

	/**
	 * Use the correct entry ID for File Uploads when using Nested Forms
	 *
	 * @param int|false $entry_id
	 *
	 * @return int
	 *
	 * @since 3.1.5
	 */
	public function set_entry_id( $entry_id ) {
		/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
		if ( empty( $entry_id ) && isset( $_POST['gpnf_entry_id'] ) ) {
			/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
			$entry_id = (int) $_POST['gpnf_entry_id'];
		}

		return $entry_id;
	}

	/**
	 * @since 1.2.6
	 */
	public function add_action() {
		add_action( 'gfpdf_previewer_start_pdf_generation', [ $this, 'remove_entry_validation_for_previewer' ] );
	}

	/**
	 * Prevent entry validation occuring on Previewer entries
	 *
	 * @since 1.2.6
	 */
	public function remove_entry_validation_for_previewer() {
		$nested_forms = \gp_nested_forms();
		remove_filter( 'gform_get_field_value', [ $nested_forms, 'handle_nested_form_field_value' ] );
	}
}
