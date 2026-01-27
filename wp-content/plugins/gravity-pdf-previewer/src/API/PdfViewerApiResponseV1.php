<?php

namespace GFPDF\Plugins\Previewer\API;

use GFPDF\Helper\Helper_Trait_Logger;
use WP_REST_Request;

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
 * Class PdfViewerApiResponseV1
 *
 * @package GFPDF\Plugins\Previewer\API
 */
class PdfViewerApiResponseV1 implements CallableApiResponse {

	/*
	 * Add logging support
	 *
	 * @since 0.2
	 */
	use Helper_Trait_Logger;

	/**
	 * @var string
	 *
	 * @since 0.1
	 */
	protected $pdf_path;

	/**
	 * PdfViewerApiResponseV1 constructor.
	 *
	 * @param string $pdf_path
	 *
	 * @since 0.1
	 */
	public function __construct( $pdf_path ) {
		$this->pdf_path = $pdf_path;
	}

	/**
	 * Locate the PDF on the server using a temporary ID and stream it to the client
	 *
	 * @Internal The temp ID is provided using the PdfGeneratorApiResponse endpoint
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 *
	 * @since    0.1
	 */
	public function response( WP_REST_Request $request ) {

		_doing_it_wrong( __METHOD__, 'The v1 API has been deprecated and the download option has been removed for security reasons. Use the v2 API.', '2.0' );
		$temp_id  = $request->get_param( 'temp_id' );
		$temp_pdf = $this->pdf_path . $temp_id . '/document.pdf';

		$this->get_logger()->notice(
			'Begin streaming Preview PDF',
			[
				'id'  => $temp_id,
				'pdf' => $temp_pdf,
			]
		);

		/* No file found. Trigger error */
		if ( ! is_file( $temp_pdf ) ) {
			$this->get_logger()->error( 'PDF Not Found' );
			return rest_ensure_response( [ 'error' => 'Requested PDF could not be found' ] );
		}

		$this->stream_pdf( $temp_pdf );
		$this->delete_pdf( $temp_pdf );

		$this->end();
	}

	/**
	 * Send out PDF to the client
	 *
	 * @param string $file Path to PDF file
	 *
	 * @since 0.1
	 */
	protected function stream_pdf( $file ) {
		/* Stream PDF */
		header( 'Content-type: application/pdf' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Accept-Ranges: none' );
		readfile( $file );
	}

	/**
	 * Remove file /folder after it has been streamed
	 *
	 * @param string $file Path to PDF file
	 *
	 * @since 0.1
	 */
	protected function delete_pdf( $file ) {
		unlink( $file );
		rmdir( dirname( $file ) );
	}

	/**
	 * Exit the process after streaming the PDF
	 *
	 * @Interal In its own method so we can easily mock it for unit testing
	 *
	 * @since   0.1
	 */
	protected function end() {
		exit;
	}
}
