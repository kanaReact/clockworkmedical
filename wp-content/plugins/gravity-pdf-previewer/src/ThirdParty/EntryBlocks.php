<?php

namespace GFPDF\Plugins\Previewer\ThirdParty;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.1.5
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EntryBlocks {

	public function init() {
		if ( ! class_exists( 'GP_Entry_Blocks' ) ) {
			return;
		}

		add_filter( 'gfpdf_previewer_entry_id', [ $this, 'set_entry_id' ], 10, 3 );
	}

	/**
	 * Use the correct entry ID for File Uploads when using Entry Blocks
	 *
	 * @param int|false $entry_id
	 *
	 * @return int
	 *
	 * @since 3.1.5
	 */
	public function set_entry_id( $entry_id ) {
		/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
		if ( empty( $entry_id ) && isset( $_POST['gpeb_entry_id'] ) ) {
			/* phpcs:ignore WordPress.Security.NonceVerification.Missing */
			$entry_id = (int) $_POST['gpeb_entry_id'];
		}

		return $entry_id;
	}
}
