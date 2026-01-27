<?php

namespace GFPDF\Plugins\Previewer\API;

class Token {

	protected $pdf_path;

	public function __construct( $pdf_path ) {
		$this->pdf_path = $pdf_path;
	}

	public function create( $data ) {
		$token = implode( '|', $data );

		$secret_key = \GPDFAPI::get_plugin_option( 'signed_secret_token', '' );

		/* If no secret key exists, generate it */
		if ( empty( $secret_key ) ) {
			$secret_key = wp_generate_password( 64 );
			\GPDFAPI::update_plugin_option( 'signed_secret_token', $secret_key );
		}

		return \GFCommon::openssl_encrypt( $token, $secret_key );
	}

	/**
	 * Decode token and verify the data is valid
	 *
	 * @param string $token
	 *
	 * @return array
	 *
	 * @since 2.0
	 */
	public function validate( $token ) {
		$token       = \GFCommon::openssl_decrypt( $token, \GPDFAPI::get_plugin_option( 'signed_secret_token', '' ) );
		$token_array = explode( '|', $token );

		if ( count( $token_array ) !== 2 ) {
			throw new \BadMethodCallException( 'Invalid Request' );
		}

		$tmp_id   = preg_replace( '/[^A-Za-z0-9]/', '', $token_array[0] );
		$download = (int) $token_array[1];

		if ( ! is_file( $this->pdf_path . "{$tmp_id}/document.pdf" ) ) {
			throw new \InvalidArgumentException( 'Invalid Request' );
		}

		return [ $tmp_id, $download ];
	}
}
