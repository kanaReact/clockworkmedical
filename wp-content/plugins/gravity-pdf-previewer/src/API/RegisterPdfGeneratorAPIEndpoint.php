<?php

namespace GFPDF\Plugins\Previewer\API;

use WP_REST_Server;

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
 * Class RegisterPdfGeneratorAPIEndpoint
 *
 * @package GFPDF\Plugins\Previewer\API
 */
class RegisterPdfGeneratorAPIEndpoint {

	/**
	 * @var CallableApiResponse
	 *
	 * @since 0.1
	 */
	protected $response;

	/**
	 * RegisterPdfGeneratorAPIEndpoint constructor.
	 *
	 * @param CallableApiResponse $response
	 *
	 * @since 0.1
	 */
	public function __construct( CallableApiResponse $response ) {
		$this->response = $response;
	}

	/**
	 * Initialise our module
	 *
	 * @since 0.1
	 */
	public function init() {
		$this->add_actions();
	}

	/**
	 * @since 0.1
	 */
	public function add_actions() {
		add_action( 'rest_api_init', [ $this, 'register_endpoint' ] );
		add_filter( 'gfpdf_previewer_created_entry', [ $this, 'hydrate_repeater' ], 10, 2 );
	}

	/**
	 * Register our PDF generator endpoint.
	 *
	 * @Internal The Field ID is optional and needed when you want Watermark support (or any future settings we add to the PDF Preview field)
	 *
	 * @since    0.1
	 */
	public function register_endpoint() {
		register_rest_route(
			'gravity-pdf-previewer/v1',
			'/generator/(?P<pid>[a-zA-Z0-9]+)',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this->response, 'response' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'gravity-pdf-previewer/v1',
			'/generator/(?P<pid>[a-zA-Z0-9]+)/(?P<fid>\d+)',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this->response, 'response' ],
				'permission_callback' => '__return_true',
			]
		);

		/*
		 * While v1 and v2 routes are exactly the same for backwards compatibility reasons,
		 * the viewer endpoints are not and this reduces confusion for anyone accessing the
		 * REST API directly.
		 */
		register_rest_route(
			'gravity-pdf-previewer/v2',
			'/generator/(?P<pid>[a-zA-Z0-9]+)',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this->response, 'response' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'gravity-pdf-previewer/v2',
			'/generator/(?P<pid>[a-zA-Z0-9]+)/(?P<fid>\d+)',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this->response, 'response' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * If any native repeater fields, hydrate the info in the entry
	 *
	 * @param array $entry
	 * @param array $form
	 *
	 * @return array
	 *
	 * @since 2.0
	 */
	public function hydrate_repeater( $entry, $form ) {
		$repeaters = \GFAPI::get_fields_by_type( $form, 'repeater' );
		foreach ( $repeaters as $repeater ) {
			foreach ( $repeater->fields as $sub_field ) {
				$inputs = $sub_field->get_entry_inputs();
				if ( is_array( $inputs ) ) {
					foreach ( $inputs as $input ) {
						\GFFormsModel::save_input( $form, $sub_field, $entry, [], $input['id'] );
					}
				} else {
					\GFFormsModel::save_input( $form, $sub_field, $entry, [], $sub_field->id );
				}
			}
		}

		\GFFormsModel::hydrate_repeaters( $entry, $form );

		return $entry;
	}
}
