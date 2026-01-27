<?php

namespace GFPDF\Plugins\Previewer\API;

use GFPDF\Helper\Helper_Trait_Logger;
use WP_REST_Request;
use WP_REST_Response;

/**
 * @package     Gravity PDF Previewer
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       2.0
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
class PdfViewerApiResponseV2 implements CallableApiResponse {

	/*
	 * Add logging support
	 *
	 * @since 2.0
	 */
	use Helper_Trait_Logger;

	/**
	 * @var Token
	 *
	 * @since 2.0
	 */
	protected $token;

	/**
	 * @var string
	 *
	 * @since 2.0
	 */
	protected $pdf_path;

	/**
	 * PdfViewerApiResponseV2 constructor.
	 *
	 * @param Token  $token
	 * @param string $pdf_path
	 *
	 * @since 2.0
	 */
	public function __construct( Token $token, $pdf_path ) {
		$this->token    = $token;
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
	 * @since    2.0
	 */
	public function response( WP_REST_Request $request ) {
		try {
			list( $tmp_id, $download ) = $this->token->validate( $this->get_token( $request ) );

			$tmp_pdf = $this->pdf_path . $tmp_id . '/document.pdf';

			$this->get_logger()->notice(
				'Begin streaming Preview PDF',
				[
					'id'  => $tmp_id,
					'pdf' => $tmp_pdf,
				]
			);

			$access_number = $this->get_access_limit( $tmp_pdf );
			$this->stream_pdf( $tmp_pdf );
			$access_number = $this->update_access_limit( $tmp_pdf, $access_number );

			/*
			 * Some browsers will give users the cached copy of the PDF. Some will try download it from the source.
			 * When the download setting is enabled we won't delete the PDF by default. Instead, we'll rely on a crude access
			 * policy using the PDF's last accessed time, which we'll manually update.
			 *
			 * If the access policy doesn't work, the PDF will be cleaned up as part of our clean-up cron.
			 */
			if ( empty( $download ) || $access_number === 2 ) {
				$this->delete_pdf( $tmp_pdf );
			}
		} catch ( \Exception $e ) {
			return new WP_REST_Response( [ 'error' => $e->getMessage() ], 500 );
		}

		$this->end();
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return string
	 *
	 * @since 2.0
	 */
	protected function get_token( $request ) {
		$token = $request->get_param( 'token' );
		$token = str_replace( ' ', '+', $token );
		$token = rawurldecode( $token );

		return $token;
	}

	/**
	 * Get the current access limit
	 *
	 * @param string $path Full path to PDF
	 *
	 * @return int
	 * @internal We manually set the last access hour time to zero when we created the PDF
	 *
	 * @since    2.0
	 */
	protected function get_access_limit( $path ) {
		$access_limit = (int) gmdate( 'i', fileatime( $path ) );

		return ( in_array( $access_limit, [ 0, 1, 2 ], true ) ) ? $access_limit : 0;
	}

	/**
	 * Updates the access limit and stores it with the PDF file's last access time
	 *
	 * @param string $path  Full path to PDF
	 * @param int    $limit The number of times the PDF has been downloaded
	 *                      `
	 * @return int
	 *
	 * @since 2.0
	 */
	protected function update_access_limit( $path, $limit ) {
		touch( $path, time(), mktime( gmdate( 'G' ), ++$limit, 0 ) );

		return $limit;
	}


	/**
	 * Send out PDF to the client
	 *
	 * @param string $file Path to PDF file
	 *
	 * @since 2.0
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
	 * @since 2.0
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
	 * @since   2.0
	 */
	protected function end() {
		exit;
	}
}
