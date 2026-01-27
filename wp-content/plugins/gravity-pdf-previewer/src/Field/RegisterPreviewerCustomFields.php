<?php

namespace GFPDF\Plugins\Previewer\Field;

use GFPDF\Helper\Helper_Interface_Actions;
use GFPDF\Helper\Helper_Interface_Filters;
use GFPDF\Helper\Helper_Trait_Logger;
use GPDFAPI;

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
 * Class RegisterPreviewerCustomFields
 *
 * @package GFPDF\Plugins\Previewer\Field
 */
class RegisterPreviewerCustomFields implements Helper_Interface_Actions, Helper_Interface_Filters {

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
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * @since 0.1
	 */
	public function add_actions() {
		add_action( 'gform_field_standard_settings', [ $this, 'add_pdf_selector' ], 10, 2 );
		add_action( 'gform_field_standard_settings', [ $this, 'add_pdf_download_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_theme_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_zoom_level_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_page_scrolling_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_spread_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_preview_height_support' ] );
		add_action( 'gform_field_appearance_settings', [ $this, 'add_pdf_watermark_support' ] );
		add_action( 'gform_field_advanced_settings', [ $this, 'add_security_support' ] );
		add_action( 'gform_field_advanced_settings', [ $this, 'add_automatic_refresh_support' ] );
		add_action( 'gform_editor_js', [ $this, 'editor_js' ] );
	}

	/**
	 * @since 0.1
	 */
	public function add_filters() {
		add_filter( 'gform_tooltips', [ $this, 'add_tooltips' ] );
	}

	/**
	 * Add tooltip support for our new PDF Preview form editor fields
	 *
	 * @param array $tooltips
	 *
	 * @return array
	 *
	 * @since 0.1
	 */
	public function add_tooltips( $tooltips ) {
		$tooltips['pdf_selector_setting']                = '<h6>' . esc_html__( 'PDF to Preview', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Select one of the active PDFs you want the end-user to preview before the form is submitted.', 'gravity-pdf-previewer' );
		$tooltips['pdf_theme_setting']                   = '<h6>' . esc_html__( 'Design', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Select a light or dark appearance for the PDF Viewer, or set to automatic to use the device default.', 'gravity-pdf-previewer' );
		$tooltips['pdf_zoom_level_setting']              = '<h6>' . esc_html__( 'Zoom Level', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Control the default zoom level of the Viewer. Page Width sets the zoom level of the PDF to the container width. Page Fit will show the entire first page in the Viewer. Actual Size is an alias for 100%.', 'gravity-pdf-previewer' );
		$tooltips['pdf_page_scrolling_setting']          = '<h6>' . esc_html__( 'Page Scrolling', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Display the pages of a PDF vertically or horizontally in the Viewer.', 'gravity-pdf-previewer' );
		$tooltips['pdf_spread_setting']                  = '<h6>' . esc_html__( 'Page Spread', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'When enabled, the PDF pages are displayed side-by-side like a book. Choosing an Odd Spread begins page 1 on the left and page 2 on the right. An Even Spread begins with page 2 on the left and page 3 the right. This format continues until all pages are displayed.', 'gravity-pdf-previewer' );
		$tooltips['pdf_preview_height']                  = '<h6>' . esc_html__( 'Preview Height', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Set the height of the Viewer container (in pixels).', 'gravity-pdf-previewer' );
		$tooltips['pdf_watermark_setting']               = '<h6>' . esc_html__( 'Watermark', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'Add a diagonal text-based watermark to each page of the PDF and control the font type used.', 'gravity-pdf-previewer' );
		$tooltips['pdf_right_click_protection_setting']  = '<h6>' . esc_html__( 'Right-Click Protection', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'By default users cannot right click on PDF pages to access the context menu. This prevents users saving a page as an image, which some browsers support. Enabling this option turns off this protection.', 'gravity-pdf-previewer' );
		$tooltips['pdf_text_copying_protection_setting'] = '<h6>' . esc_html__( 'Text-Copying Protection', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'By default users cannot copy any text on PDF pages. Enable this option to render the text in the DOM, making it selectable for users and providing better accessibility for Screen Readers.', 'gravity-pdf-previewer' );
		$tooltips['pdf_automatic_refresh_setting']       = '<h6>' . esc_html__( 'Automatic Refresh', 'gravity-pdf-previewer' ) . '</h6>' . esc_html__( 'The PDF preview automatically refreshes when form changes are made, and the viewer is visible. Depending on the hosting provider, and size of the PDF, refreshing automatically may put too much strain on the server. Enable this option turns off this feature.', 'gravity-pdf-previewer' );

		return $tooltips;
	}

	/**
	 * Add support for a PDF selector field in the Form Editor
	 *
	 * @param init $position
	 * @param int  $form_id
	 *
	 * @since 0.1
	 */
	public function add_pdf_selector( $position, $form_id ) {
		if ( $position === 25 ) {
			$this->get_logger()->notice( 'Add PDF Selector field to form editor' );

			$pdfs              = $this->get_active_pdfs( $form_id );
			$subview           = 'PDF';
			$form_pdf_settings = network_admin_url( 'admin.php?page=gf_edit_forms&view=settings&subview=' . $subview . '&id=' . $form_id );
			include __DIR__ . '/markup/pdf-selector-setting.php';
		}
	}

	/**
	 * Return a list of active PDFs for our form
	 *
	 * @param int $form_id
	 *
	 * @return array
	 *
	 * @since 0.1
	 */
	protected function get_active_pdfs( $form_id ) {
		$pdfs = GPDFAPI::get_form_pdfs( $form_id );

		if ( is_wp_error( $pdfs ) ) {
			return [];
		}

		/* Filter the inactive PDFs */
		$pdfs = array_filter(
			$pdfs,
			function( $pdf ) {
				return $pdf['active'];
			}
		);

		$this->get_logger()->notice(
			'Active PDFs on form',
			[
				'pdfs' => $pdfs,
			]
		);

		return $pdfs;
	}

	/**
	 * Add support for PDF Download Toggle in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 0.1
	 */
	public function add_pdf_download_support( $position ) {
		if ( $position === 25 ) {
			$this->get_logger()->notice( 'Add PDF Download toggle to form editor' );
			include __DIR__ . '/markup/pdf-download-setting.php';
		}
	}

	/**
	 * Add support for a PDF Theme field in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_pdf_theme_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Theme selector to form editor' );

			include __DIR__ . '/markup/pdf-theme-setting.php';
		}
	}

	/**
	 * Add support for a PDF Page Scrolling field in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_pdf_page_scrolling_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Page Scrolling selector to form editor' );

			include __DIR__ . '/markup/pdf-page-scrolling-setting.php';
		}
	}

	/**
	 * Add support for a PDF Page Scrolling field in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_pdf_spread_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Spread selector to form editor' );

			include __DIR__ . '/markup/pdf-spread-setting.php';
		}
	}

	/**
	 * Add support for a PDF Security fields in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_pdf_zoom_level_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Zoom Level fields to form editor' );

			include __DIR__ . '/markup/pdf-zoom-level-setting.php';
		}
	}

	/**
	 * Add support for a PDF Height field in the Form Editor
	 *
	 * @param init $position
	 *
	 * @depecated 2.0 Use self::add_pdf_preview_height_support()
	 *
	 * @since     0.1
	 */
	public function add_pdf_preview_height( $position ) {
		$this->add_pdf_preview_height_support( $position );
	}

	/**
	 * Add support for a PDF Height field in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_pdf_preview_height_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Height selector to form editor' );

			include __DIR__ . '/markup/preview-height-setting.php';
		}
	}

	/**
	 * Add support for PDF Watermark fields in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 0.1
	 */
	public function add_pdf_watermark_support( $position ) {
		if ( $position === 250 ) {
			$this->get_logger()->notice( 'Add PDF Watermark fields to form editor' );

			$font_stack = GPDFAPI::get_pdf_fonts();
			include __DIR__ . '/markup/pdf-watermark-setting.php';
		}
	}

	/**
	 * Add support for a PDF Security fields in the Form Editor
	 *
	 * @param init $position
	 *
	 * @since 2.0
	 */
	public function add_security_support( $position ) {
		if ( $position === 0 ) {
			$this->get_logger()->notice( 'Add PDF Security fields to form editor' );

			include __DIR__ . '/markup/pdf-security-setting.php';
		}
	}

	/**
	 * Add support for a PDF Automatic Refresh field in the Form Editor
	 *
	 * @param int $position
	 *
	 * @since 3.3
	 */
	public function add_automatic_refresh_support( $position ) {
		if ( $position === 0 ) {
			include __DIR__ . '/markup/pdf-refresh-setting.php';
		}
	}

	/**
	 * Load our custom form editor JS to ensure our custom PDF Preview fields save and update correctly
	 *
	 * @since 0.1
	 */
	public function editor_js() {
		$this->get_logger()->notice( 'Load PDF Preview Editor Javascript' );

		?>
		<script type="text/javascript">

		  /* Setup default values for our PDF Preview field */
		  function SetDefaultValues_pdfpreview (field) {
			field['label'] = <?php echo wp_json_encode( __( 'PDF Preview', 'gravity-pdf-previewer' ) ); ?>;
			field['pdfpreviewheight'] = '600'
			field['pdfwatermarktext'] = <?php echo wp_json_encode( __( 'SAMPLE', 'gravity-pdf-previewer' ) ); ?>;
			field['pdfwatermarkfont'] = <?php echo wp_json_encode( GPDFAPI::get_plugin_option( 'default_font', 'dejavusanscondensed' ) ); ?>;

			return field
		  }

		  <?php readfile( __DIR__ . '/markup/editor.js' ); ?>
		</script>
		<?php
	}
}
