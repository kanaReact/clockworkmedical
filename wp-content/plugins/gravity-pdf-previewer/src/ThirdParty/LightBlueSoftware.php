<?php

namespace GFPDF\Plugins\Previewer\ThirdParty;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       3.0.1
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fix compatibility problem with Light Blue GF add-on
 *
 * @see https://wordpress.org/plugins/gravity-forms-light-blue-api-add-on/
 */
class LightBlueSoftware {

	/**
	 * @since 3.0.1
	 */
	public function init() {
		if ( class_exists( '\GFLightBlueAPI' ) ) {
			add_action( 'gfpdf_previewer_start_pdf_generation', [ $this, 'remove_pre_submission_hook' ] );
		}
	}

	/**
	 * Prevents this plugin sending an API request when the Previewer is generated
	 *
	 * @return void
	 *
	 * @since 3.0.1
	 */
	public function remove_pre_submission_hook() {
		remove_action( 'gform_pre_submission', [ 'GFLightBlueAPI', 'lb_gform_pre_submission' ] );
	}
}
