<?php

namespace GFPDF\Plugins\Previewer\Field;

use GFPDF\Helper\Helper_Interface_Filters;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.1
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SkipPdfPreviewerField
 *
 * @package GFPDF\Plugins\Previewer\Field
 */
class SkipPdfPreviewerField {

	/**
	 * Initialise our module
	 *
	 * @since 1.1
	 */
	public function init() {
		add_filter( 'gfpdf_field_middleware', [ $this, 'skip_previewer_field_in_pdf' ], 10, 2 );
		add_filter( 'gfpdf_blacklisted_fields', [ $this, 'blacklist_previewer_field' ] );
	}

	/**
	 * Disable the PDF Previewer field from showing up Core and Universal templates
	 *
	 * @param bool     $action
	 * @param GF_Field $field
	 *
	 * @return bool
	 *
	 * @since 1.0
	 */
	public function skip_previewer_field_in_pdf( $action, $field ) {
		if ( $action === false ) {
			if ( $field->type === 'pdfpreview' ) {
				$action = true;
			}
		}

		return $action;
	}

	/**
	 * Add field to PDF blacklist, so it won't be rendered in the PDF or settings
	 *
	 * @param array $blacklist
	 *
	 * @return array
	 *
	 * @since 3.2.1
	 */
	public function blacklist_previewer_field( $blacklist ) {
		$blacklist[] = 'pdfpreview';

		return $blacklist;
	}
}
