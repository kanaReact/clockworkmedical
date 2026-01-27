<?php

namespace GFPDF\Plugins\Previewer\Field;

use Exception;
use GF_Fields;
use GFPDF\Helper\Helper_Interface_Actions;
use GFPDF\Helper\Helper_Trait_Logger;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       0.1
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RegisterPreviewerField
 *
 * @package GFPDF\Plugins\Previewer\Field
 */
class RegisterPreviewerField implements Helper_Interface_Actions {

	/*
	 * Add logging support
	 *
	 * @since 0.2
	 */
	use Helper_Trait_Logger;

	/**
	 * Initialise our module
	 *
	 * @since 0.1
	 */
	public function init() {
		try {
			GF_Fields::register( new GFormFieldPreviewer() );
		} catch ( Exception $e ) {
			$this->get_logger()->error(
				'Could not register Previewer field with Gravity Forms',
				[
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				]
			);
		}

		$this->add_actions();
	}

	/**
	 * @since 0.1
	 */
	public function add_actions() {
		add_action( 'gform_enqueue_scripts', [ $this, 'gravityform_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'form_editor_assets' ] );
		add_action( 'enqueue_block_assets', [ $this, 'form_editor_assets' ] );
	}

	/**
	 * Load our Previewer script/styles when our custom field is in the form
	 *
	 * @param array $form
	 *
	 * @since 0.1
	 */
	public function gravityform_scripts( $form ) {
		/* Only include where our preview field is detected */
		if ( $this->has_previewer_field( $form ) && ! wp_script_is( 'gfpdf_previewer' ) ) {

			$this->get_logger()->notice( 'Including Previewer scripts and styles' );

			$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? time() : GFPDF_PDF_PREVIEWER_VERSION;

			/* Add our custom JS */
			wp_enqueue_script(
				'gfpdf_previewer',
				plugin_dir_url( GFPDF_PDF_PREVIEWER_FILE ) . 'dist/previewer.min.js',
				[ 'jquery' ],
				$version,
				true
			);

			wp_localize_script(
				'gfpdf_previewer',
				'PdfPreviewerConstants',
				[
					'pdfWorkerUrl'              => plugin_dir_url( GFPDF_PDF_PREVIEWER_FILE ) . 'dist/viewer/build/pdf.worker.js?ver=' . $version,
					'cmapsUrl'                  => plugin_dir_url( GFPDF_PDF_PREVIEWER_FILE ) . 'dist/viewer/web/cmaps/',
					'pdfGeneratorEndpoint'      => rest_url( 'gravity-pdf-previewer/v1/generator/' ),
					'pdfViewerCss'              => plugin_dir_url( GFPDF_PDF_PREVIEWER_FILE ) . 'dist/previewer.min.css',
					'apiNonce'                  => wp_create_nonce( 'wp_rest' ),

					'svgLoadingTitle'           => esc_html__( 'Loading spinner', 'gravity-pdf-previewer' ),
					'refreshTitle'              => esc_html__( 'Refresh', 'gravity-pdf-previewer' ),
					'loadingMessage'            => esc_html__( 'Loading PDF Preview', 'gravity-pdf-previewer' ),
					'canvasLoadingSpinner'      => esc_html__( 'Loading…', 'gravity-pdf-previewer' ),
					'errorMessage'              => esc_html__( 'There was a problem loading the preview.', 'gravity-pdf-previewer' ),

					'previousButtonTitle'       => esc_html__( 'Previous Page', 'gravity-pdf-previewer' ),
					'nextButtonTitle'           => esc_html__( 'Next Page', 'gravity-pdf-previewer' ),
					'pageNumberInputFieldTitle' => esc_html__( 'Set Page Number. Maximum value %s', 'gravity-pdf-previewer' ),
					'pageNumberTotals'          => esc_html__( 'of %s', 'gravity-pdf-previewer' ),
					'zoomOutButtonTitle'        => esc_html__( 'Zoom Out', 'gravity-pdf-previewer' ),
					'zoomInButtonTitle'         => esc_html__( 'Zoom In', 'gravity-pdf-previewer' ),
					'selectBoxTitle'            => esc_html__( 'Zoom level', 'gravity-pdf-previewer' ),
					'selectBoxOptionActualSize' => esc_html__( 'Actual Size', 'gravity-pdf-previewer' ),
					'selectBoxOptionPageFit'    => esc_html__( 'Page Fit', 'gravity-pdf-previewer' ),
					'selectBoxOptionPageWidth'  => esc_html__( 'Page Width', 'gravity-pdf-previewer' ),
					'selectBoxOption50'         => esc_html__( '50%', 'gravity-pdf-previewer' ),
					'selectBoxOption75'         => esc_html__( '75%', 'gravity-pdf-previewer' ),
					'selectBoxOption100'        => esc_html__( '100%', 'gravity-pdf-previewer' ),
					'selectBoxOption125'        => esc_html__( '125%', 'gravity-pdf-previewer' ),
					'selectBoxOption150'        => esc_html__( '150%', 'gravity-pdf-previewer' ),
					'selectBoxOption200'        => esc_html__( '200%', 'gravity-pdf-previewer' ),
					'selectBoxOption300'        => esc_html__( '300%', 'gravity-pdf-previewer' ),
					'selectBoxOption400'        => esc_html__( '400%', 'gravity-pdf-previewer' ),
					'downloadButtonTitle'       => esc_html__( 'Download', 'gravity-pdf-previewer' ),
					'refreshButtonTitle'        => esc_html__( 'Refresh', 'gravity-pdf-previewer' ),
					'pdfPageLabel'              => esc_html__( 'PDF Page %s', 'gravity-pdf-previewer' ),
					'viewerDescription'         => esc_html__( 'Inline PDF Viewer', 'gravity-pdf-previewer' ),
				]
			);
		}
	}

	/**
	 * Checks if our preview field is present in the form
	 *
	 * @param array $form
	 *
	 * @return bool
	 *
	 * @since 0.1
	 */
	public function has_previewer_field( $form ) {
		if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
			foreach ( $form['fields'] as $field ) {
				if ( $field->type === 'pdfpreview' ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Load the Previewer CSS in the Block and Gravity Forms Editors
	 *
	 * @return void
	 *
	 * @since 4.1
	 */
	public function form_editor_assets() {

		/* Only run on admin pages */
		if ( ! is_admin() ) {
			return;
		}

		/* Load in the GF Editor or the Block Editor */
		$current_screen = get_current_screen();

		if (
			\GFForms::get_page() !== 'form_editor' &&
			( ! method_exists( $current_screen, 'is_block_editor' ) || ! $current_screen->is_block_editor() )
		) {
			return;
		}

		$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? time() : GFPDF_PDF_PREVIEWER_VERSION;

		/* Add our custom CSS */
		wp_enqueue_style(
			'gfpdf_previewer_css',
			plugin_dir_url( GFPDF_PDF_PREVIEWER_FILE ) . 'dist/previewer.min.css',
			[],
			$version
		);
	}
}
