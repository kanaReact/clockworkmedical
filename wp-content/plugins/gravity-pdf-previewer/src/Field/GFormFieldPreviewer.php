<?php

namespace GFPDF\Plugins\Previewer\Field;

use GF_Field;

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
 * Class GFormFieldPreviewer
 *
 * @package GFPDF\Plugins\Previewer\Field
 *
 * @since   0.1
 */
class GFormFieldPreviewer extends GF_Field {

	/**
	 * @var string
	 *
	 * @since 0.1
	 */
	public $type = 'pdfpreview';

	/**
	 * Mark this is a displayOnly field to prevent display in some of the settings
	 *
	 * @var bool
	 *
	 * @since 1.1
	 */
	public $displayOnly = true;

	/**
	 * Mask this field as a HTML field to prevent public display
	 *
	 * @return string
	 *
	 * @since 1.1
	 */
	public function get_input_type() {
		return 'html';
	}

	/**
	 * Returns the HTML tag for the field container.
	 *
	 * @since 4.0
	 *
	 * @param array $form The current Form object.
	 *
	 * @return string
	 */
	public function get_field_container_tag( $form ) {
		if ( \GFCommon::is_legacy_markup_enabled( $form ) ) {
			return parent::get_field_container_tag( $form );
		}

		return 'fieldset';
	}

	/**
	 * @return string
	 *
	 * @since 0.1
	 */
	public function get_form_editor_field_title() {
		return esc_attr__( 'PDF Preview', 'gravity-pdf-previewer' );
	}

	/**
	 * Generate the Previewer HTML mark-up
	 *
	 * @param array  $form
	 * @param string $value
	 * @param null   $entry
	 *
	 * @return string
	 *
	 * @since 0.1
	 */
	public function get_field_input( $form, $value = '', $entry = null ) {
		ob_start();

		$field_id = $this->id;
		$html_id  = $this->get_first_input_id( $form );
		$pdf_id   = isset( $this->pdfpreview ) ? $this->pdfpreview : $this->get_pdf_id_if_any( $form );

		$settings = [
			'height'                => isset( $this->pdfpreviewheight ) && (int) $this->pdfpreviewheight > 0 ? (int) $this->pdfpreviewheight : 600,
			'download'              => isset( $this->pdfdownload ) ? (bool) $this->pdfdownload : false,
			'theme'                 => isset( $this->pdftheme ) ? $this->pdftheme : 'dark',
			'zoomLevel'             => isset( $this->pdfzoomlevel ) ? $this->pdfzoomlevel : 'page-width',
			'pageScrolling'         => isset( $this->pdfpagescrolling ) ? $this->pdfpagescrolling : 'vertical',
			'spread'                => isset( $this->pdfspread ) ? $this->pdfspread : 'none',
			'rightClickProtection'  => isset( $this->pdfrightclickprotection ) ? ! (bool) $this->pdfrightclickprotection : true,
			'textCopyingProtection' => isset( $this->pdftextcopyingprotection ) ? ! (bool) $this->pdftextcopyingprotection : true,
			'automaticRefresh'      => isset( $this->pdfautomaticrefresh ) ? ! (bool) $this->pdfautomaticrefresh : true,
			'watermark'             => isset( $this->pdfwatermarktoggle ) ? true : false,
			'watermarkText'         => isset( $this->pdfwatermarktext ) ? $this->pdfwatermarktext : '',
		];

		/* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$is_gb_editor = \defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];

		if ( $this->is_entry_detail() || $this->is_form_editor() || $is_gb_editor ) {
			include __DIR__ . '/markup/previewer-placeholder.php';
		} else {
			$pdf = \GPDFAPI::get_pdf( $form['id'], $pdf_id );
			if ( is_wp_error( $pdf ) ) {
				$pdf = [];
			}

			/* set RTL to true/false for use on the front-end */
			$settings['rtl'] = ! empty( $pdf['rtl'] ) && $pdf['rtl'] === 'Yes';

			include __DIR__ . '/markup/previewer-wrapper.php';
		}

		return ob_get_clean();
	}

	/**
	 * Returns the first PDF ID, if it exists
	 *
	 * @param array $form
	 *
	 * @return string
	 *
	 * @since 0.2
	 */
	protected function get_pdf_id_if_any( $form ) {
		if ( isset( $form['gfpdf_form_settings'] ) && count( $form['gfpdf_form_settings'] ) > 0 ) {
			$pdf = reset( $form['gfpdf_form_settings'] );

			return $pdf['id'];
		}

		return 0;
	}

	/**
	 * Enable supported settings for our custom field
	 *
	 * @return array
	 *
	 * @since 0.1
	 */
	public function get_form_editor_field_settings() {
		return [
			'conditional_logic_field_setting',
			'css_class_setting',
			'label_setting',
			'description_setting',
			'visibility_setting',
			'pdf_selector_setting',
			'pdf_download_setting',
			'pdf_theme_setting',
			'pdf_watermark_setting',
			'pdf_preview_height_setting',
			'pdf_page_scrolling_setting',
			'pdf_spread_setting',
			'pdf_zoom_level_setting',
			'pdf_security_setting',
			'pdf_refresh_setting',
		];
	}

	/**
	 * Add field to the Advanced group
	 *
	 * @return array
	 *
	 * @since 0.1
	 */
	public function get_form_editor_button() {
		return [
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		];
	}

	/**
	 * Returns the field's form editor icon.
	 *
	 * This could be an icon url or a gform-icon class.
	 *
	 * @since 1.2.9
	 *
	 * @return string
	 */
	public function get_form_editor_field_icon() {
		return 'dashicons-pdf';
	}
}
