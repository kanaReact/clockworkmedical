<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.5' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
    $content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
    /**
     * Set up theme support.
     *
     * @return void
     */
    function hello_elementor_setup() {
        if ( is_admin() ) {
            hello_maybe_update_theme_version_in_db();
        }

        if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
            register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
            register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
        }

        if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
            add_post_type_support( 'page', 'excerpt' );
        }

        if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'automatic-feed-links' );
            add_theme_support( 'title-tag' );
            add_theme_support(
                'html5',
                [
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'script',
                    'style',
                    'navigation-widgets',
                ]
            );
            add_theme_support(
                'custom-logo',
                [
                    'height'      => 100,
                    'width'       => 350,
                    'flex-height' => true,
                    'flex-width'  => true,
                ]
            );
            add_theme_support( 'align-wide' );
            add_theme_support( 'responsive-embeds' );

            // Editor Styles
            add_theme_support( 'editor-styles' );
            add_editor_style( 'assets/css/editor-styles.css' );

            // WooCommerce Support
            if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
                add_theme_support( 'woocommerce' );
                add_theme_support( 'wc-product-gallery-zoom' );
                add_theme_support( 'wc-product-gallery-lightbox' );
                add_theme_support( 'wc-product-gallery-slider' );
            }
        }
    }
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
    $theme_version_option_name = 'hello_theme_version';
    $hello_theme_db_version = get_option( $theme_version_option_name );

    if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
        update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
    }
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
    /**
     * Check whether to display header footer.
     *
     * @return bool
     */
    function hello_elementor_display_header_footer() {
        $hello_elementor_header_footer = true;
        return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
    }
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
    /**
     * Theme Scripts & Styles.
     *
     * @return void
     */
    function hello_elementor_scripts_styles() {
        if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
            wp_enqueue_style(
                'hello-elementor',
                HELLO_THEME_STYLE_URL . 'reset.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
            wp_enqueue_style(
                'hello-elementor-theme-style',
                HELLO_THEME_STYLE_URL . 'theme.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }

        if ( hello_elementor_display_header_footer() ) {
            wp_enqueue_style(
                'hello-elementor-header-footer',
                HELLO_THEME_STYLE_URL . 'header-footer.css',
                [],
                HELLO_ELEMENTOR_VERSION
            );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
    /**
     * Register Elementor Locations.
     *
     * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
     * @return void
     */
    function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
        if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
            $elementor_theme_manager->register_all_core_location();
        }
    }
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
    /**
     * Set default content width.
     *
     * @return void
     */
    function hello_elementor_content_width() {
        $GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
    }
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
    /**
     * Add description meta tag with excerpt text.
     *
     * @return void
     */
    function hello_elementor_add_description_meta_tag() {
        if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
            return;
        }

        if ( ! is_singular() ) {
            return;
        }

        $post = get_queried_object();
        if ( empty( $post->post_excerpt ) ) {
            return;
        }

        echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
    // Customizer controls
    function hello_elementor_customizer() {
        if ( ! is_customize_preview() ) {
            return;
        }

        if ( ! hello_elementor_display_header_footer() ) {
            return;
        }

        require get_template_directory() . '/includes/customizer-functions.php';
    }
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
    /**
     * Check whether to display the page title.
     *
     * @param bool $val default value.
     * @return bool
     */
    function hello_elementor_check_hide_title( $val ) {
        if ( defined( 'ELEMENTOR_VERSION' ) ) {
            $current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
            if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
                $val = false;
            }
        }
        return $val;
    }
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC: Prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
    function hello_elementor_body_open() {
        wp_body_open();
    }
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();

// Redirect to admin after WooCommerce login
add_filter( 'woocommerce_login_redirect', function() {
    return admin_url();
}, 99 );


/*******************************************************************************
 * CLOCKWORK MEDICAL - REST API
 *
 * API Endpoints for Mobile Application
 * Documentation: API-DOCUMENTATION.md
 ******************************************************************************/

/**
 * Register all REST API routes in one place
 */
add_action( 'rest_api_init', 'clockwork_register_rest_routes' );

function clockwork_register_rest_routes() {
    $namespace_v1 = 'clockwork/v1';
    $namespace_custom = 'custom-api/v1';

    // Authentication Endpoints
    register_rest_route( $namespace_v1, '/register', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_register_user_api',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/login', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_login_api',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/wp-login', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_legacy_login_api',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/logout', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_logout_api',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/forgot-password', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_forgot_password_api',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/change-password', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_change_password_api',
        'permission_callback' => '__return_true',
    ]);

    // Profile Endpoints
    register_rest_route( $namespace_v1, '/profile', [
        [
            'methods'             => 'GET',
            'callback'            => 'clockwork_get_profile_api',
            'permission_callback' => '__return_true',
        ],
        [
            'methods'             => 'PUT',
            'callback'            => 'clockwork_update_profile_api',
            'permission_callback' => '__return_true',
        ],
    ]);

    // Meetings Endpoints
    register_rest_route( $namespace_v1, '/meetings', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meetings_list',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_single_meeting',
        'permission_callback' => '__return_true',
    ]);

    // Meeting Tab Endpoints
    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/overview', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_overview',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/register', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_register',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/timetable', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_timetable',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/speakers', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_speakers',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/venue', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_venue',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route( $namespace_v1, '/meetings/(?P<id>\d+)/sponsors', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_meeting_sponsors',
        'permission_callback' => '__return_true',
    ]);

    // Contact Form Endpoint
    register_rest_route( $namespace_v1, '/contact', [
        'methods'             => 'POST',
        'callback'            => 'clockwork_submit_contact_form',
        'permission_callback' => '__return_true',
    ]);

    // User List Endpoints (Admin)
    register_rest_route( $namespace_custom, '/customers', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_users_by_role',
        'permission_callback' => '__return_true',
        'args'                => [ 'role' => [ 'default' => 'customer' ] ],
    ]);

    register_rest_route( $namespace_custom, '/subscribers', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_users_by_role',
        'permission_callback' => '__return_true',
        'args'                => [ 'role' => [ 'default' => 'subscriber' ] ],
    ]);

    register_rest_route( $namespace_custom, '/exhibitors', [
        'methods'             => 'GET',
        'callback'            => 'clockwork_get_users_by_role',
        'permission_callback' => '__return_true',
        'args'                => [ 'role' => [ 'default' => 'cm_exhibitor' ] ],
    ]);
}


/*******************************************************************************
 * HELPER FUNCTIONS
 ******************************************************************************/

/**
 * Generate authentication token for user
 *
 * @param int $user_id User ID
 * @return string Token
 */
function clockwork_generate_auth_token( $user_id ) {
    $token = wp_generate_password( 64, false );
    $token_hash = wp_hash( $token );
    $expiry = time() + ( 30 * DAY_IN_SECONDS );

    update_user_meta( $user_id, 'clockwork_auth_token', $token_hash );
    update_user_meta( $user_id, 'clockwork_auth_token_expiry', $expiry );

    return $token;
}

/**
 * Validate authentication token
 *
 * @param string $token Token to validate
 * @return WP_User|false User object or false
 */
function clockwork_validate_auth_token( $token ) {
    if ( empty( $token ) ) {
        return false;
    }

    $token_hash = wp_hash( $token );

    $users = get_users([
        'meta_key'   => 'clockwork_auth_token',
        'meta_value' => $token_hash,
        'number'     => 1,
    ]);

    if ( empty( $users ) ) {
        return false;
    }

    $user = $users[0];
    $expiry = get_user_meta( $user->ID, 'clockwork_auth_token_expiry', true );

    if ( $expiry && time() > $expiry ) {
        delete_user_meta( $user->ID, 'clockwork_auth_token' );
        delete_user_meta( $user->ID, 'clockwork_auth_token_expiry' );
        return false;
    }

    return $user;
}

/**
 * Get user from Authorization header
 *
 * @param WP_REST_Request $request Request object
 * @return WP_User|WP_Error User object or error
 */
function clockwork_get_user_from_token( $request ) {
    $auth_header = $request->get_header( 'Authorization' );

    if ( empty( $auth_header ) ) {
        return new WP_Error( 'no_token', 'Authorization token is required', [ 'status' => 401 ] );
    }

    $token = str_replace( 'Bearer ', '', $auth_header );
    $user = clockwork_validate_auth_token( $token );

    if ( ! $user ) {
        return new WP_Error( 'invalid_token', 'Invalid or expired token', [ 'status' => 401 ] );
    }

    return $user;
}

/**
 * Get user's active memberships
 *
 * @param int $user_id User ID
 * @return array Active memberships
 */
function clockwork_get_user_memberships( $user_id ) {
    if ( ! function_exists( 'wc_memberships_get_user_memberships' ) ) {
        return [];
    }

    $active_list = [];
    $memberships = wc_memberships_get_user_memberships( $user_id );

    foreach ( $memberships as $membership ) {
        if ( $membership->is_active() ) {
            $active_list[] = [
                'membership_id' => $membership->get_id(),
                'plan_name'     => $membership->get_plan()->get_name(),
                'start_date'    => $membership->get_start_date(),
                'end_date'      => $membership->get_end_date(),
                'status'        => $membership->get_status(),
            ];
        }
    }

    return $active_list;
}

/**
 * Format user data for API response
 *
 * @param WP_User $user User object
 * @param bool $include_memberships Include membership data
 * @return array Formatted user data
 */
function clockwork_format_user_data( $user, $include_memberships = true ) {
    $data = [
        'id'         => $user->ID,
        'username'   => $user->user_login,
        'email'      => $user->user_email,
        'first_name' => $user->first_name,
        'last_name'  => $user->last_name,
        'name'       => trim( $user->first_name . ' ' . $user->last_name ),
        'registered' => $user->user_registered,
        'role'       => $user->roles[0] ?? '',
    ];

    if ( $include_memberships ) {
        $data['active_memberships'] = clockwork_get_user_memberships( $user->ID );
    }

    return $data;
}

/**
 * Get user billing information
 *
 * @param int $user_id User ID
 * @return array Billing data
 */
function clockwork_get_user_billing( $user_id ) {
    return [
        'phone'    => get_user_meta( $user_id, 'billing_phone', true ),
        'address'  => get_user_meta( $user_id, 'billing_address_1', true ),
        'address2' => get_user_meta( $user_id, 'billing_address_2', true ),
        'city'     => get_user_meta( $user_id, 'billing_city', true ),
        'state'    => get_user_meta( $user_id, 'billing_state', true ),
        'postcode' => get_user_meta( $user_id, 'billing_postcode', true ),
        'country'  => get_user_meta( $user_id, 'billing_country', true ),
    ];
}

/**
 * Update user billing information
 *
 * @param int $user_id User ID
 * @param array $billing Billing data
 */
function clockwork_update_user_billing( $user_id, $billing ) {
    $fields = [
        'phone'    => 'billing_phone',
        'address'  => 'billing_address_1',
        'address2' => 'billing_address_2',
        'city'     => 'billing_city',
        'state'    => 'billing_state',
        'postcode' => 'billing_postcode',
        'country'  => 'billing_country',
    ];

    foreach ( $fields as $key => $meta_key ) {
        if ( isset( $billing[ $key ] ) ) {
            update_user_meta( $user_id, $meta_key, sanitize_text_field( $billing[ $key ] ) );
        }
    }
}

/**
 * Create API error response
 *
 * @param string $message Error message
 * @param int $status HTTP status code
 * @return WP_REST_Response
 */
function clockwork_error_response( $message, $status = 400 ) {
    return new WP_REST_Response( [
        'status'  => 'error',
        'message' => $message,
    ], $status );
}

/**
 * Create API success response
 *
 * @param string $message Success message
 * @param array $data Response data
 * @param int $status HTTP status code
 * @return WP_REST_Response
 */
function clockwork_success_response( $message, $data = [], $status = 200 ) {
    $response = [
        'status'  => 'success',
        'message' => $message,
    ];

    if ( ! empty( $data ) ) {
        $response = array_merge( $response, $data );
    }

    return new WP_REST_Response( $response, $status );
}

/**
 * Get person data (speaker/convenor) from ACF
 *
 * @param mixed $person Person post object or ID
 * @return array Person data
 */
function clockwork_get_person_data( $person ) {
    $person_id = is_object( $person ) ? $person->ID : $person;

    $image_field = get_field( 'speaker-image', $person_id );
    $image = '';
    if ( $image_field ) {
        $image = is_array( $image_field ) ? $image_field['url'] : $image_field;
    }

    return [
        'id'         => $person_id,
        'name'       => get_the_title( $person_id ),
        'image'      => $image,
        'bio_teaser' => wp_strip_all_tags( get_field( 'bio_teaser', $person_id ) ),
    ];
}

/**
 * Get product variations for variable products
 *
 * @param WC_Product $product Product object
 * @return array Variations data
 */
function clockwork_get_product_variations( $product ) {
    if ( ! $product->is_type( 'variable' ) ) {
        return [];
    }

    $variations = [];
    $variation_ids = $product->get_children();

    foreach ( $variation_ids as $variation_id ) {
        $variation = wc_get_product( $variation_id );
        if ( ! $variation || ! $variation->exists() ) {
            continue;
        }

        $attributes = $variation->get_variation_attributes();
        $variation_data = [
            'id'              => $variation_id,
            'sku'             => $variation->get_sku(),
            'price'           => $variation->get_price(),
            'regular_price'   => $variation->get_regular_price(),
            'sale_price'      => $variation->get_sale_price() ?: null,
            'is_in_stock'     => $variation->is_in_stock(),
            'stock_quantity'  => $variation->get_stock_quantity(),
            'stock_status'    => $variation->get_stock_status(),
            'attributes'      => [],
            'description'     => $variation->get_description(),
            'image'           => wp_get_attachment_url( $variation->get_image_id() ),
        ];

        // Format attributes nicely
        foreach ( $attributes as $attr_name => $attr_value ) {
            $attr_label = wc_attribute_label( str_replace( 'attribute_', '', $attr_name ), $product );
            $variation_data['attributes'][] = [
                'name'  => $attr_label,
                'slug'  => $attr_name,
                'value' => $attr_value,
            ];
        }

        $variations[] = $variation_data;
    }

    return $variations;
}

/**
 * Get product attributes
 *
 * @param WC_Product $product Product object
 * @return array Attributes data
 */
function clockwork_get_product_attributes( $product ) {
    $attributes = [];
    $product_attributes = $product->get_attributes();

    foreach ( $product_attributes as $attr_name => $attribute ) {
        $attr_data = [
            'name'    => wc_attribute_label( $attr_name, $product ),
            'slug'    => $attr_name,
            'options' => [],
        ];

        if ( $attribute->is_taxonomy() ) {
            $terms = wc_get_product_terms( $product->get_id(), $attr_name, [ 'fields' => 'all' ] );
            foreach ( $terms as $term ) {
                $attr_data['options'][] = [
                    'id'    => $term->term_id,
                    'name'  => $term->name,
                    'slug'  => $term->slug,
                ];
            }
        } else {
            $options = $attribute->get_options();
            foreach ( $options as $option ) {
                $attr_data['options'][] = [
                    'id'    => sanitize_title( $option ),
                    'name'  => $option,
                    'slug'  => sanitize_title( $option ),
                ];
            }
        }

        $attributes[] = $attr_data;
    }

    return $attributes;
}

/**
 * Extract Gravity Form ID from Elementor content
 *
 * @param int $post_id Post ID
 * @return int|null Gravity Form ID or null if not found
 */
function clockwork_get_gravity_form_id_from_elementor( $post_id ) {
    $elementor_data = get_post_meta( $post_id, '_elementor_data', true );

    if ( empty( $elementor_data ) ) {
        return null;
    }

    if ( is_string( $elementor_data ) ) {
        $elementor_data = json_decode( $elementor_data, true );
    }

    if ( ! is_array( $elementor_data ) ) {
        return null;
    }

    // Recursively search for gravityform shortcode in Register tab
    $form_id = null;
    clockwork_find_gravity_form_recursive( $elementor_data, $form_id, '' );

    return $form_id;
}

/**
 * Recursively find Gravity Form shortcode in Elementor elements
 *
 * @param array $elements Elementor elements
 * @param int|null &$form_id Reference to form ID
 * @param string $current_tab Current tab title
 */
function clockwork_find_gravity_form_recursive( $elements, &$form_id, $current_tab ) {
    foreach ( $elements as $element ) {
        $tab_title = $current_tab;

        // Check if this is a tab with title
        if ( isset( $element['settings']['n_tab_title'] ) ) {
            $tab_title = $element['settings']['n_tab_title'];
        }

        // Check for shortcode widget with gravityform
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'shortcode' ) {
            $shortcode = $element['settings']['shortcode'] ?? '';
            // Check if it's in Register tab (or no tab context)
            $is_register_tab = empty( $tab_title ) || stripos( $tab_title, 'register' ) !== false;

            if ( $is_register_tab && preg_match( '/\[gravityform[^\]]*id=["\']?(\d+)["\']?/i', $shortcode, $matches ) ) {
                $form_id = intval( $matches[1] );
                return;
            }
        }

        // Recurse into nested elements
        if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
            clockwork_find_gravity_form_recursive( $element['elements'], $form_id, $tab_title );
            if ( $form_id !== null ) {
                return;
            }
        }
    }
}

/**
 * Get Gravity Form fields as structured array
 *
 * @param int $form_id Gravity Form ID
 * @return array Form fields
 */
function clockwork_get_gravity_form_fields( $form_id ) {
    $fields = [];

    if ( ! class_exists( 'GFAPI' ) ) {
        return $fields;
    }

    $form = GFAPI::get_form( $form_id );
    if ( ! $form || empty( $form['fields'] ) ) {
        return $fields;
    }

    foreach ( $form['fields'] as $field ) {
        $field_data = [
            'id'          => (string) $field->id,
            'label'       => $field->label,
            'type'        => clockwork_map_gravity_field_type( $field->type ),
            'required'    => (bool) $field->isRequired,
            'description' => $field->description ?? '',
            'placeholder' => $field->placeholder ?? '',
            'choices'     => [],
        ];

        // Handle name field with multiple inputs
        if ( $field->type === 'name' && ! empty( $field->inputs ) ) {
            $field_data['type'] = 'name';
            $field_data['inputs'] = [];
            foreach ( $field->inputs as $input ) {
                if ( ! isset( $input['isHidden'] ) || ! $input['isHidden'] ) {
                    $field_data['inputs'][] = [
                        'id'          => (string) $input['id'],
                        'label'       => $input['label'],
                        'placeholder' => $input['placeholder'] ?? '',
                    ];
                }
            }
        }

        // Handle fields with choices (select, radio, checkbox, multiselect)
        if ( ! empty( $field->choices ) ) {
            foreach ( $field->choices as $choice ) {
                $choice_data = [
                    'label' => $choice['text'],
                    'value' => $choice['value'] ?? $choice['text'],
                ];
                if ( ! empty( $choice['price'] ) ) {
                    $choice_data['price'] = floatval( str_replace( ['$', '£', '€', ','], '', $choice['price'] ) );
                }
                $field_data['choices'][] = $choice_data;
            }
        }

        // Handle address field with multiple inputs
        if ( $field->type === 'address' && ! empty( $field->inputs ) ) {
            $field_data['inputs'] = [];
            foreach ( $field->inputs as $input ) {
                if ( ! isset( $input['isHidden'] ) || ! $input['isHidden'] ) {
                    $field_data['inputs'][] = [
                        'id'          => (string) $input['id'],
                        'label'       => $input['label'],
                        'placeholder' => $input['placeholder'] ?? '',
                    ];
                }
            }
        }

        $fields[] = $field_data;
    }

    return $fields;
}

/**
 * Map Gravity Forms field type to a simplified type
 *
 * @param string $gf_type Gravity Forms field type
 * @return string Simplified type
 */
function clockwork_map_gravity_field_type( $gf_type ) {
    $type_map = [
        'text'        => 'text',
        'textarea'    => 'textarea',
        'select'      => 'select',
        'multiselect' => 'multiselect',
        'number'      => 'number',
        'checkbox'    => 'checkbox',
        'radio'       => 'radio',
        'name'        => 'name',
        'email'       => 'email',
        'phone'       => 'phone',
        'address'     => 'address',
        'website'     => 'url',
        'date'        => 'date',
        'time'        => 'time',
        'fileupload'  => 'file',
        'hidden'      => 'hidden',
        'html'        => 'html',
        'section'     => 'section',
        'page'        => 'page',
    ];

    return $type_map[ $gf_type ] ?? $gf_type;
}

/**
 * Get Extra Product Options from TM EPO plugin
 *
 * @param int $product_id Product ID
 * @return array Extra product options
 */
function clockwork_get_extra_product_options( $product_id ) {
    $options = [];

    // Check if THEMECOMPLETE_EPO function exists
    if ( ! function_exists( 'THEMECOMPLETE_EPO' ) ) {
        return $options;
    }

    try {
        $epo = THEMECOMPLETE_EPO();
        if ( ! method_exists( $epo, 'get_product_tm_epos' ) ) {
            return $options;
        }

        $epo_data = $epo->get_product_tm_epos( $product_id, '', false, false );

        if ( empty( $epo_data ) ) {
            return $options;
        }

        // Process global options - TM EPO stores data in a nested builder structure
        if ( ! empty( $epo_data['global'] ) && is_array( $epo_data['global'] ) ) {
            foreach ( $epo_data['global'] as $priority => $priority_sections ) {
                if ( ! is_array( $priority_sections ) ) {
                    continue;
                }
                foreach ( $priority_sections as $product_id_key => $product_data ) {
                    if ( ! is_array( $product_data ) || ! isset( $product_data['sections'] ) ) {
                        continue;
                    }

                    // Loop through sections
                    foreach ( $product_data['sections'] as $section ) {
                        if ( ! is_array( $section ) || ! isset( $section['elements'] ) ) {
                            continue;
                        }

                        $section_label = $section['label'] ?? '';

                        // Loop through elements in section
                        foreach ( $section['elements'] as $element ) {
                            if ( ! is_array( $element ) || ! isset( $element['builder'] ) ) {
                                continue;
                            }

                            $builder = $element['builder'];
                            $parsed_options = clockwork_parse_epo_builder( $builder );

                            foreach ( $parsed_options as $opt ) {
                                // Fallback to section label if element label is empty
                                if ( empty( $opt['label'] ) && ! empty( $section_label ) ) {
                                    $opt['label'] = $section_label;
                                }
                                $options[] = $opt;
                            }
                        }
                    }
                }
            }
        }

        // Process local options
        if ( ! empty( $epo_data['local'] ) && is_array( $epo_data['local'] ) ) {
            foreach ( $epo_data['local'] as $section ) {
                if ( ! is_array( $section ) ) {
                    continue;
                }
                
                $section_label = $section['label'] ?? '';

                if ( isset( $section['elements'] ) && is_array( $section['elements'] ) ) {
                    foreach ( $section['elements'] as $element ) {
                        if ( isset( $element['builder'] ) ) {
                            $parsed = clockwork_parse_epo_builder( $element['builder'] );
                            foreach ( $parsed as $opt ) {
                                // Fallback to section label if element label is empty
                                if ( empty( $opt['label'] ) && ! empty( $section_label ) ) {
                                    $opt['label'] = $section_label;
                                }
                                $options[] = $opt;
                            }
                        }
                    }
                }
            }
        }

    } catch ( Exception $e ) {
        // Silently fail
    }

    // Filter options by label
    $final_options = [];
    $seen_labels = [];

    foreach ( $options as $opt ) {
        $label = isset( $opt['label'] ) ? trim( $opt['label'] ) : '';

        // Skip if label is empty
        if ( empty( $label ) ) {
            continue;
        }

        // Skip if label already seen
        if ( isset( $seen_labels[ $label ] ) ) {
            continue;
        }

        $seen_labels[ $label ] = true;
        $final_options[] = $opt;
    }

    return $final_options;
}

/**
 * Parse TM EPO builder structure to extract options
 * The builder stores element data as indexed arrays where each index represents an element
 *
 * @param array $builder EPO builder data
 * @return array Parsed options
 */
function clockwork_parse_epo_builder( $builder ) {
    $options = [];

    if ( ! is_array( $builder ) || ! isset( $builder['element_type'] ) ) {
        return $options;
    }

    $element_types = $builder['element_type'];
    if ( ! is_array( $element_types ) ) {
        return $options;
    }

    // Track the index for each element type (e.g., radiobuttons[0], radiobuttons[1], checkboxes[0])
    $type_counts = [];

    // Loop through each element by index
    foreach ( $element_types as $idx => $element_type ) {
        $type_prefix = $element_type; // e.g., 'radiobuttons', 'checkboxes', 'select'

        // Determine the type-specific index
        if ( ! isset( $type_counts[ $element_type ] ) ) {
            $type_counts[ $element_type ] = 0;
        }
        $type_idx = $type_counts[ $element_type ];

        // Get the header/label for this element using the type index
        $title_key = $type_prefix . '_header_title';
        $subtitle_key = $type_prefix . '_header_subtitle';
        $required_key = $type_prefix . '_required';
        $uniqid_key = $type_prefix . '_uniqid';

        $label = '';
        if ( isset( $builder[ $title_key ][ $type_idx ] ) ) {
            $label = $builder[ $title_key ][ $type_idx ];
        }

        $description = '';
        if ( isset( $builder[ $subtitle_key ][ $type_idx ] ) ) {
            $description = wp_strip_all_tags( $builder[ $subtitle_key ][ $type_idx ] );
        }

        $required = false;
        if ( isset( $builder[ $required_key ][ $type_idx ] ) ) {
            $required = ! empty( $builder[ $required_key ][ $type_idx ] );
        }

        $uniqid = '';
        if ( isset( $builder[ $uniqid_key ][ $type_idx ] ) ) {
            $uniqid = $builder[ $uniqid_key ][ $type_idx ];
        }

        // Get choices for this element using the type index
        $choices = [];
        $options_title_key = 'multiple_' . $type_prefix . '_options_title';
        $options_value_key = 'multiple_' . $type_prefix . '_options_value';
        $options_price_key = 'multiple_' . $type_prefix . '_options_price';
        $options_price_type_key = 'multiple_' . $type_prefix . '_options_price_type';
        $options_sale_price_key = 'multiple_' . $type_prefix . '_options_sale_price';

        if ( isset( $builder[ $options_title_key ][ $type_idx ] ) && is_array( $builder[ $options_title_key ][ $type_idx ] ) ) {
            $titles = $builder[ $options_title_key ][ $type_idx ];
            $values = $builder[ $options_value_key ][ $type_idx ] ?? [];
            $prices = $builder[ $options_price_key ][ $type_idx ] ?? [];
            $price_types = $builder[ $options_price_type_key ][ $type_idx ] ?? [];
            $sale_prices = $builder[ $options_sale_price_key ][ $type_idx ] ?? [];

            foreach ( $titles as $opt_idx => $opt_title ) {
                $choice = [
                    'label'       => $opt_title,
                    'value'       => $values[ $opt_idx ] ?? $opt_title,
                    'price'       => floatval( $prices[ $opt_idx ] ?? 0 ),
                    'price_type'  => ! empty( $price_types[ $opt_idx ] ) ? $price_types[ $opt_idx ] : 'fixed',
                    'sale_price'  => ! empty( $sale_prices[ $opt_idx ] ) ? floatval( $sale_prices[ $opt_idx ] ) : null,
                ];
                $choices[] = $choice;
            }
        }

        // Map element types to a cleaner type name
        $type_map = [
            'radiobuttons' => 'radio',
            'checkboxes'   => 'checkbox',
            'selectbox'    => 'select',
            'select'       => 'select',
            'textfield'    => 'text',
            'textarea'     => 'textarea',
        ];
        $clean_type = $type_map[ $element_type ] ?? $element_type;

        $option = [
            'id'          => $uniqid ?: uniqid( 'epo_' ),
            'label'       => $label,
            'description' => $description,
            'type'        => $clean_type,
            'required'    => $required,
            'choices'     => $choices,
        ];

        $options[] = $option;

        // Increment the type counter
        $type_counts[ $element_type ]++;
    }

    return $options;
}

/**
 * Parse Elementor data to extract tab content
 *
 * @param int $post_id Post ID
 * @return array Tabs data with title and content
 */
function clockwork_parse_elementor_tabs( $post_id ) {
    $elementor_data = get_post_meta( $post_id, '_elementor_data', true );

    if ( empty( $elementor_data ) ) {
        return [];
    }

    // Decode if it's a string
    if ( is_string( $elementor_data ) ) {
        $elementor_data = json_decode( $elementor_data, true );
    }

    if ( ! is_array( $elementor_data ) ) {
        return [];
    }

    $tabs = [];
    clockwork_extract_tabs_recursive( $elementor_data, $tabs );

    return $tabs;
}

/**
 * Recursively extract tabs content from Elementor data
 *
 * @param array $elements Elementor elements
 * @param array &$tabs Reference to tabs array
 */
function clockwork_extract_tabs_recursive( $elements, &$tabs ) {
    foreach ( $elements as $element ) {
        // Check for nested-tabs widget (Elementor Pro)
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'nested-tabs' ) {
            if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
                foreach ( $element['elements'] as $index => $tab_container ) {
                    $tab_title = '';
                    $tab_content = '';
                    $tab_id = '';

                    // Get tab title from settings
                    if ( isset( $tab_container['settings']['n_tab_title'] ) ) {
                        $tab_title = $tab_container['settings']['n_tab_title'];
                    } elseif ( isset( $tab_container['settings']['_title'] ) ) {
                        $tab_title = $tab_container['settings']['_title'];
                    }

                    // Generate tab ID from title
                    $tab_id = sanitize_title( $tab_title );

                    // Extract content from tab container elements
                    if ( isset( $tab_container['elements'] ) && is_array( $tab_container['elements'] ) ) {
                        $tab_content = clockwork_extract_content_from_elements( $tab_container['elements'] );
                    }

                    // Generate summary text from content
                    $summary_text = clockwork_generate_tab_summary( $tab_content );

                    if ( ! empty( $tab_title ) ) {
                        $tabs[] = [
                            'id'       => $tab_id,
                            'title'    => $tab_title,
                            'summary'  => $summary_text,
                            'content'  => $tab_content,
                            'order'    => $index + 1,
                        ];
                    }
                }
            }
        }

        // Check for classic tabs widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'tabs' ) {
            if ( isset( $element['settings']['tabs'] ) && is_array( $element['settings']['tabs'] ) ) {
                foreach ( $element['settings']['tabs'] as $index => $tab ) {
                    $tab_title = $tab['tab_title'] ?? '';
                    $tab_content_html = $tab['tab_content'] ?? '';
                    $tab_id = sanitize_title( $tab_title );

                    // Clean content for classic tabs
                    $clean_text = clockwork_html_to_text( $tab_content_html );
                    $tab_content = [];
                    if ( ! empty( trim( $clean_text ) ) ) {
                        $tab_content[] = [
                            'type' => 'text',
                            'text' => $clean_text,
                        ];
                    }

                    if ( ! empty( $tab_title ) ) {
                        $tabs[] = [
                            'id'       => $tab_id,
                            'title'    => $tab_title,
                            'summary'  => $clean_text,
                            'content'  => $tab_content,
                            'order'    => $index + 1,
                        ];
                    }
                }
            }
        }

        // Recurse into nested elements
        if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
            clockwork_extract_tabs_recursive( $element['elements'], $tabs );
        }
    }
}

/**
 * Generate a plain text summary from tab content array
 *
 * @param array $content_parts Content parts array
 * @return string Plain text summary
 */
function clockwork_generate_tab_summary( $content_parts ) {
    if ( ! is_array( $content_parts ) ) {
        return is_string( $content_parts ) ? clockwork_html_to_text( $content_parts ) : '';
    }

    $summary_parts = [];

    foreach ( $content_parts as $part ) {
        if ( ! is_array( $part ) ) {
            continue;
        }

        $type = $part['type'] ?? '';

        switch ( $type ) {
            case 'heading':
                $text = trim( $part['text'] ?? '' );
                if ( ! empty( $text ) ) {
                    $summary_parts[] = "\n" . $text . "\n";
                }
                break;

            case 'text':
                $text = trim( $part['text'] ?? '' );
                if ( ! empty( $text ) ) {
                    $summary_parts[] = $text;
                }
                break;

            case 'list':
                if ( ! empty( $part['items'] ) && is_array( $part['items'] ) ) {
                    $list_items = [];
                    foreach ( $part['items'] as $item ) {
                        $item = trim( $item );
                        if ( ! empty( $item ) ) {
                            $list_items[] = "• " . $item;
                        }
                    }
                    if ( ! empty( $list_items ) ) {
                        $summary_parts[] = implode( "\n", $list_items );
                    }
                }
                break;

            // Skip buttons, images, videos in summary
        }
    }

    $summary = implode( "\n\n", $summary_parts );

    // Clean up multiple newlines
    $summary = preg_replace( '/\n{3,}/', "\n\n", $summary );

    // Clean up multiple spaces
    $summary = preg_replace( '/[ \t]+/', ' ', $summary );

    return trim( $summary );
}

/**
 * Clean HTML content - remove unnecessary tags, classes, and styles
 *
 * @param string $html HTML content
 * @return string Clean text
 */
function clockwork_clean_html( $html ) {
    // Remove style tags and their content
    $html = preg_replace( '/<style\b[^>]*>.*?<\/style>/is', '', $html );

    // Remove script tags and their content
    $html = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $html );

    // Remove all class and style attributes
    $html = preg_replace( '/\s*(class|style|data-[a-z-]+)="[^"]*"/i', '', $html );

    // Remove span tags but keep content
    $html = preg_replace( '/<\/?span[^>]*>/i', '', $html );

    // Convert bullet points
    $html = str_replace( '•', '- ', $html );

    // Clean up whitespace
    $html = preg_replace( '/\s+/', ' ', $html );

    return trim( $html );
}

/**
 * Convert HTML to plain text
 *
 * @param string $html HTML content
 * @return string Plain text
 */
function clockwork_html_to_text( $html ) {
    // First clean the HTML
    $html = clockwork_clean_html( $html );

    // Convert <br> to newlines
    $html = preg_replace( '/<br\s*\/?>/i', "\n", $html );

    // Convert </p> to double newlines
    $html = preg_replace( '/<\/p>/i', "\n\n", $html );

    // Convert list items to bullet points
    $html = preg_replace( '/<li[^>]*>/i', "• ", $html );
    $html = preg_replace( '/<\/li>/i', "\n", $html );

    // Strip remaining tags
    $text = wp_strip_all_tags( $html );

    // Clean up multiple newlines
    $text = preg_replace( '/\n{3,}/', "\n\n", $text );

    // Clean up whitespace
    $text = preg_replace( '/[ \t]+/', ' ', $text );

    return trim( $text );
}

/**
 * Extract text content from Elementor elements
 *
 * @param array $elements Elementor elements
 * @return array Combined content
 */
function clockwork_extract_content_from_elements( $elements ) {
    $content_parts = [];

    foreach ( $elements as $element ) {
        // Text editor widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'text-editor' ) {
            if ( isset( $element['settings']['editor'] ) ) {
                $html = $element['settings']['editor'];
                $text = clockwork_html_to_text( $html );
                if ( ! empty( trim( $text ) ) ) {
                    $content_parts[] = [
                        'type' => 'text',
                        'text' => $text,
                    ];
                }
            }
        }

        // Heading widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'heading' ) {
            if ( isset( $element['settings']['title'] ) ) {
                $tag = $element['settings']['header_size'] ?? 'h2';
                $title = wp_strip_all_tags( $element['settings']['title'] );
                if ( ! empty( trim( $title ) ) ) {
                    $content_parts[] = [
                        'type'  => 'heading',
                        'level' => intval( str_replace( 'h', '', $tag ) ),
                        'text'  => trim( $title ),
                    ];
                }
            }
        }

        // Image widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'image' ) {
            if ( isset( $element['settings']['image']['url'] ) ) {
                $content_parts[] = [
                    'type'    => 'image',
                    'url'     => $element['settings']['image']['url'],
                    'alt'     => $element['settings']['image']['alt'] ?? '',
                ];
            }
        }

        // Button widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'button' ) {
            $btn_text = wp_strip_all_tags( $element['settings']['text'] ?? '' );
            $btn_url = $element['settings']['link']['url'] ?? '';
            if ( ! empty( $btn_text ) && ! empty( $btn_url ) ) {
                $content_parts[] = [
                    'type' => 'button',
                    'text' => $btn_text,
                    'url'  => $btn_url,
                ];
            }
        }

        // Icon list widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'icon-list' ) {
            if ( isset( $element['settings']['icon_list'] ) ) {
                $items = [];
                foreach ( $element['settings']['icon_list'] as $item ) {
                    $item_text = wp_strip_all_tags( $item['text'] ?? '' );
                    if ( ! empty( trim( $item_text ) ) ) {
                        $items[] = trim( $item_text );
                    }
                }
                if ( ! empty( $items ) ) {
                    $content_parts[] = [
                        'type'  => 'list',
                        'items' => $items,
                    ];
                }
            }
        }

        // Video widget
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'video' ) {
            $video_type = $element['settings']['video_type'] ?? 'youtube';
            $video_url = '';
            if ( $video_type === 'youtube' && ! empty( $element['settings']['youtube_url'] ) ) {
                $video_url = $element['settings']['youtube_url'];
            } elseif ( $video_type === 'vimeo' && ! empty( $element['settings']['vimeo_url'] ) ) {
                $video_url = $element['settings']['vimeo_url'];
            }
            if ( ! empty( $video_url ) ) {
                $content_parts[] = [
                    'type' => 'video',
                    'url'  => $video_url,
                ];
            }
        }

        // Spacer widget - skip
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'spacer' ) {
            continue;
        }

        // Divider widget - skip (not useful for mobile)
        if ( isset( $element['widgetType'] ) && $element['widgetType'] === 'divider' ) {
            continue;
        }

        // WooCommerce widgets - skip (handled separately in registration)
        if ( isset( $element['widgetType'] ) && strpos( $element['widgetType'], 'woocommerce' ) !== false ) {
            continue;
        }

        // Recurse into nested elements (containers, columns, sections)
        if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
            $nested_content = clockwork_extract_content_from_elements( $element['elements'] );
            if ( ! empty( $nested_content ) ) {
                $content_parts = array_merge( $content_parts, $nested_content );
            }
        }
    }

    return $content_parts;
}

/**
 * Parse timetable content into structured schedule format
 *
 * @param array $content_parts Raw content parts from Elementor
 * @return array Structured timetable with days, sessions, and items
 */
function clockwork_parse_timetable_content( $content_parts ) {
    $schedule = [];
    $current_day = null;
    $current_session = null;
    $pending_time = null;

    foreach ( $content_parts as $part ) {
        $type = $part['type'] ?? '';
        $text = $part['text'] ?? '';

        // Skip empty content
        if ( empty( trim( $text ) ) ) {
            continue;
        }

        // Check for day header (e.g., "Thursday 19th June", "Day 1", "Friday 20th June")
        if ( $type === 'heading' ) {
            // Check if it's a day header
            $is_day = preg_match( '/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday|Day\s*\d)/i', $text );

            if ( $is_day ) {
                // Save current day if exists
                if ( $current_day !== null ) {
                    // Save current session to current day if exists
                    if ( $current_session !== null ) {
                        $current_day['sessions'][] = $current_session;
                        $current_session = null;
                    }
                    $schedule[] = $current_day;
                }

                $current_day = [
                    'day_title' => html_entity_decode( trim( $text ), ENT_QUOTES, 'UTF-8' ),
                    'items'     => [],
                    'sessions'  => [],
                ];
                $pending_time = null;
                continue;
            }

            // Check if it's a timetable title (skip it)
            if ( preg_match( '/timetable/i', $text ) ) {
                continue;
            }

            // Other headings might be section titles
            if ( $current_day === null ) {
                $current_day = [
                    'day_title' => 'Schedule',
                    'items'     => [],
                    'sessions'  => [],
                ];
            }
        }

        // Check for session header (e.g., "Session 1 - The MDT\n10:30 - 12:00\nModerators: ...")
        $is_session = preg_match( '/^Session\s*\d/i', $text );
        if ( $is_session || ( $type === 'text' && preg_match( '/^Session\s*\d/i', $text ) ) ) {
            // Save previous session if exists
            if ( $current_session !== null && $current_day !== null ) {
                $current_day['sessions'][] = $current_session;
            }

            // Parse session details
            $lines = preg_split( '/\r?\n/', $text );
            $session_title = html_entity_decode( trim( $lines[0] ?? '' ), ENT_QUOTES, 'UTF-8' );
            $session_time = '';
            $session_moderators = '';

            foreach ( $lines as $idx => $line ) {
                if ( $idx === 0 ) continue;
                $line = trim( $line );
                if ( preg_match( '/^\d{1,2}[:.]\d{2}\s*[-–]\s*\d{1,2}[:.]\d{2}/', $line ) ) {
                    $session_time = $line;
                } elseif ( preg_match( '/^Moderator/i', $line ) ) {
                    $session_moderators = html_entity_decode( $line, ENT_QUOTES, 'UTF-8' );
                }
            }

            $current_session = [
                'title'      => $session_title,
                'time'       => $session_time,
                'moderators' => $session_moderators,
                'items'      => [],
            ];

            if ( $current_day === null ) {
                $current_day = [
                    'day_title' => 'Schedule',
                    'items'     => [],
                    'sessions'  => [],
                ];
            }
            continue;
        }

        // Check for time entry (e.g., "10:00", "10:30")
        $is_time = preg_match( '/^(\d{1,2})[:.](\d{2})$/', trim( $text ) );
        if ( $is_time ) {
            $pending_time = trim( $text );
            continue;
        }

        // Regular content - associate with pending time if available
        if ( $current_day === null ) {
            $current_day = [
                'day_title' => 'Schedule',
                'items'     => [],
                'sessions'  => [],
            ];
        }

        // Parse text that may contain multiple lines
        $lines = preg_split( '/\r?\n/', $text );
        $title = html_entity_decode( trim( $lines[0] ?? '' ), ENT_QUOTES, 'UTF-8' );
        $description = count( $lines ) > 1 ? html_entity_decode( trim( implode( "\n", array_slice( $lines, 1 ) ) ), ENT_QUOTES, 'UTF-8' ) : '';

        $item = [
            'time'        => $pending_time ?: '',
            'title'       => $title,
            'description' => $description,
        ];

        // Add to current session or day
        if ( $current_session !== null ) {
            $current_session['items'][] = $item;
        } else {
            $current_day['items'][] = $item;
        }

        $pending_time = null;
    }

    // Save final session and day
    if ( $current_session !== null && $current_day !== null ) {
        $current_day['sessions'][] = $current_session;
    }
    if ( $current_day !== null ) {
        $schedule[] = $current_day;
    }

    return $schedule;
}

/**
 * Parse sponsors from HTML content
 *
 * @param string $html_content HTML content
 * @return array Sponsors data
 */
function clockwork_parse_sponsors_from_html( $html_content ) {
    $sponsors = [];
    $current_tier = null;
    $temp_link = null;

    $lines = explode( "\n", $html_content );
    $lines = array_map( 'trim', $lines );
    $lines = array_filter( $lines );

    foreach ( $lines as $line ) {
        // Check for sponsor tier header
        if ( preg_match( '/(.*?)\s*<\/h[1-6]>/i', $line, $matches_header ) ) {
            $raw_tier_name = trim( $matches_header[1] );
            $raw_tier_name = preg_replace( '/<h[1-6].*?>/i', '', $raw_tier_name );

            if ( ! empty( $raw_tier_name ) ) {
                $key = strtolower( str_replace( ' ', '_', $raw_tier_name ) );
                $key = preg_replace( '/_+/', '_', $key );
                $current_tier = $key;
                $temp_link = null;

                if ( ! isset( $sponsors[ $current_tier ] ) ) {
                    $sponsors[ $current_tier ] = [];
                }
                continue;
            }
        }

        if ( ! $current_tier ) {
            continue;
        }

        // Check for link
        if ( strpos( $line, '<a href=' ) !== false ) {
            if ( preg_match( '/href=["\']([^"\']+)["\']/', $line, $matches ) ) {
                $temp_link = $matches[1];
            }
        }

        // Check for image
        if ( strpos( $line, '<img' ) !== false ) {
            $img_url = preg_match( '/src=["\']([^"\']+)["\']/', $line, $matches_src ) ? $matches_src[1] : '';
            $title = preg_match( '/alt=["\']([^"\']*)["\']/', $line, $matches_alt ) ? $matches_alt[1] : '';

            if ( $img_url ) {
                if ( empty( $title ) ) {
                    $path = parse_url( $img_url, PHP_URL_PATH );
                    $filename = basename( $path );
                    $title = pathinfo( $filename, PATHINFO_FILENAME );
                    $title = ucwords( str_replace( [ '-', '_', '.' ], ' ', $title ) );
                }

                $sponsors[ $current_tier ][] = [
                    'title'     => $title,
                    'image_url' => $img_url,
                    'link_url'  => $temp_link,
                ];

                $temp_link = null;
            }
        }
    }

    return $sponsors;
}


/*******************************************************************************
 * AUTHENTICATION API ENDPOINTS
 ******************************************************************************/

/**
 * POST /clockwork/v1/register
 * Register new user
 */
function clockwork_register_user_api( $request ) {
    $params = $request->get_json_params();

    $email      = sanitize_email( $params['email'] ?? '' );
    $password   = $params['password'] ?? '';
    $first_name = sanitize_text_field( $params['first_name'] ?? '' );
    $last_name  = sanitize_text_field( $params['last_name'] ?? '' );
    $username   = sanitize_user( $params['username'] ?? '' );

    // Validation
    if ( empty( $email ) ) {
        return clockwork_error_response( 'Email is required', 400 );
    }

    if ( ! is_email( $email ) ) {
        return clockwork_error_response( 'Invalid email format', 400 );
    }

    if ( empty( $password ) ) {
        return clockwork_error_response( 'Password is required', 400 );
    }

    if ( strlen( $password ) < 6 ) {
        return clockwork_error_response( 'Password must be at least 6 characters', 400 );
    }

    if ( email_exists( $email ) ) {
        return clockwork_error_response( 'Email already registered', 409 );
    }

    // Generate username from email if not provided
    if ( empty( $username ) ) {
        $username = sanitize_user( current( explode( '@', $email ) ) );
        $base_username = $username;
        $counter = 1;
        while ( username_exists( $username ) ) {
            $username = $base_username . $counter;
            $counter++;
        }
    } elseif ( username_exists( $username ) ) {
        return clockwork_error_response( 'Username already taken', 409 );
    }

    // Create user
    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        return clockwork_error_response( $user_id->get_error_message(), 400 );
    }

    // Update user meta
    wp_update_user([
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ]);

    // Set default role as customer
    $user = new WP_User( $user_id );
    $user->set_role( 'customer' );

    $token = clockwork_generate_auth_token( $user_id );
    $user = get_user_by( 'ID', $user_id );

    return clockwork_success_response( 'Registration successful', [
        'data'  => clockwork_format_user_data( $user ),
        'token' => $token,
    ], 201 );
}

/**
 * POST /clockwork/v1/login
 * User login
 */
function clockwork_login_api( $request ) {
    $params   = $request->get_json_params();
    $email    = sanitize_email( $params['email'] ?? '' );
    $password = $params['password'] ?? '';
    $role_req = sanitize_text_field( $params['role'] ?? '' );

    if ( empty( $email ) || empty( $password ) ) {
        return clockwork_error_response( 'Email and password are required', 400 );
    }

    $user = get_user_by( 'email', $email );

    if ( ! $user || ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
        return clockwork_error_response( 'Invalid email or password', 401 );
    }

    $user_role = $user->roles[0] ?? '';

    if ( ! empty( $role_req ) && $role_req !== $user_role ) {
        return clockwork_error_response( 'Access denied for this role', 403 );
    }

    $token = clockwork_generate_auth_token( $user->ID );

    return clockwork_success_response( 'Login successful', [
        'data'  => clockwork_format_user_data( $user ),
        'token' => $token,
    ] );
}

/**
 * POST /clockwork/v1/wp-login (Legacy)
 * Legacy login endpoint for backward compatibility
 */
function clockwork_legacy_login_api( $request ) {
    $params   = $request->get_json_params();
    $email    = sanitize_email( $params['email'] ?? '' );
    $password = $params['password'] ?? '';
    $role_req = $params['role'] ?? '';

    if ( empty( $email ) || empty( $password ) ) {
        return [ 'status' => 'error', 'message' => 'Email or password missing' ];
    }

    $user = get_user_by( 'email', $email );

    if ( ! $user ) {
        return [ 'status' => 'error', 'message' => 'User does not exist' ];
    }

    if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
        return [ 'status' => 'error', 'message' => 'Invalid password' ];
    }

    $user_role = $user->roles[0] ?? '';

    if ( ! empty( $role_req ) && $role_req !== $user_role ) {
        return [ 'status' => 'error', 'message' => 'User role does not match' ];
    }

    $token = clockwork_generate_auth_token( $user->ID );

    return [
        'status'  => 'success',
        'message' => 'Login successful',
        'data'    => clockwork_format_user_data( $user ),
        'token'   => $token,
    ];
}

/**
 * POST /clockwork/v1/logout
 * User logout
 */
function clockwork_logout_api( $request ) {
    $user = clockwork_get_user_from_token( $request );

    if ( is_wp_error( $user ) ) {
        return clockwork_error_response( $user->get_error_message(), $user->get_error_data()['status'] ?? 401 );
    }

    delete_user_meta( $user->ID, 'clockwork_auth_token' );
    delete_user_meta( $user->ID, 'clockwork_auth_token_expiry' );

    return clockwork_success_response( 'Logged out successfully' );
}

/**
 * POST /clockwork/v1/forgot-password
 * Request password reset
 */
function clockwork_forgot_password_api( $request ) {
    $email = sanitize_email( $request->get_param( 'email' ) );

    if ( empty( $email ) ) {
        return [ 'status' => 'error', 'message' => 'Email is required' ];
    }

    $user = get_user_by( 'email', $email );

    if ( ! $user ) {
        return [ 'status' => 'error', 'message' => 'No user found with this email' ];
    }

    $key = get_password_reset_key( $user );
    if ( is_wp_error( $key ) ) {
        return [ 'status' => 'error', 'message' => 'Could not generate reset key' ];
    }

    $name = trim( $user->first_name . ' ' . $user->last_name );
    $reset_link = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' );

    // Send reset email
    $subject = 'Reset Your Password';
    $message = "Hi $name,\n\n";
    $message .= "You requested a password reset. Click the link below:\n\n";
    $message .= $reset_link . "\n\n";
    $message .= "If you didn't request this, please ignore this email.\n";

    wp_mail( $email, $subject, $message );

    return [
        'status'  => 'success',
        'message' => 'Password reset email sent',
        'data'    => [
            'id'         => $user->ID,
            'username'   => $user->user_login,
            'name'       => $name,
            'email'      => $email,
            'role'       => $user->roles[0] ?? '',
            'reset_link' => $reset_link,
        ],
    ];
}

/**
 * POST /clockwork/v1/change-password
 * Change user password
 */
function clockwork_change_password_api( $request ) {
    $user = clockwork_get_user_from_token( $request );

    if ( is_wp_error( $user ) ) {
        return clockwork_error_response( $user->get_error_message(), $user->get_error_data()['status'] ?? 401 );
    }

    $params = $request->get_json_params();
    $current_password = $params['current_password'] ?? '';
    $new_password = $params['new_password'] ?? '';

    if ( empty( $current_password ) || empty( $new_password ) ) {
        return clockwork_error_response( 'Current password and new password are required', 400 );
    }

    if ( strlen( $new_password ) < 6 ) {
        return clockwork_error_response( 'New password must be at least 6 characters', 400 );
    }

    if ( ! wp_check_password( $current_password, $user->user_pass, $user->ID ) ) {
        return clockwork_error_response( 'Current password is incorrect', 401 );
    }

    wp_set_password( $new_password, $user->ID );
    $token = clockwork_generate_auth_token( $user->ID );

    return clockwork_success_response( 'Password changed successfully', [ 'token' => $token ] );
}


/*******************************************************************************
 * PROFILE API ENDPOINTS
 ******************************************************************************/

/**
 * GET /clockwork/v1/profile
 * Get user profile
 */
function clockwork_get_profile_api( $request ) {
    $user = clockwork_get_user_from_token( $request );

    if ( is_wp_error( $user ) ) {
        return clockwork_error_response( $user->get_error_message(), $user->get_error_data()['status'] ?? 401 );
    }

    $user_data = clockwork_format_user_data( $user );
    $user_data['billing'] = clockwork_get_user_billing( $user->ID );

    return clockwork_success_response( 'Profile retrieved successfully', [ 'data' => $user_data ] );
}

/**
 * PUT /clockwork/v1/profile
 * Update user profile
 */
function clockwork_update_profile_api( $request ) {
    $user = clockwork_get_user_from_token( $request );

    if ( is_wp_error( $user ) ) {
        return clockwork_error_response( $user->get_error_message(), $user->get_error_data()['status'] ?? 401 );
    }

    $params = $request->get_json_params();
    $update_data = [ 'ID' => $user->ID ];

    if ( isset( $params['first_name'] ) ) {
        $update_data['first_name'] = sanitize_text_field( $params['first_name'] );
    }

    if ( isset( $params['last_name'] ) ) {
        $update_data['last_name'] = sanitize_text_field( $params['last_name'] );
    }

    if ( isset( $params['email'] ) ) {
        $new_email = sanitize_email( $params['email'] );

        if ( ! is_email( $new_email ) ) {
            return clockwork_error_response( 'Invalid email format', 400 );
        }

        $existing_user = get_user_by( 'email', $new_email );
        if ( $existing_user && $existing_user->ID !== $user->ID ) {
            return clockwork_error_response( 'Email already in use', 409 );
        }

        $update_data['user_email'] = $new_email;
    }

    $result = wp_update_user( $update_data );

    if ( is_wp_error( $result ) ) {
        return clockwork_error_response( $result->get_error_message(), 400 );
    }

    if ( isset( $params['billing'] ) ) {
        clockwork_update_user_billing( $user->ID, $params['billing'] );
    }

    $updated_user = get_user_by( 'ID', $user->ID );
    $user_data = clockwork_format_user_data( $updated_user );
    $user_data['billing'] = clockwork_get_user_billing( $user->ID );

    return clockwork_success_response( 'Profile updated successfully', [ 'data' => $user_data ] );
}


/*******************************************************************************
 * MEETINGS API ENDPOINTS
 ******************************************************************************/

/**
 * GET /clockwork/v1/meetings
 * Get paginated meetings list
 */
function clockwork_get_meetings_list( $request ) {
    $page = max( 1, intval( $request->get_param( 'page' ) ) );
    $per_page = 10;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'tax_query'      => [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'meetings',
        ]],
    ];

    $query = new WP_Query( $args );
    $meetings = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $product = wc_get_product( get_the_ID() );

            if ( ! $product ) {
                continue;
            }

            $post_id = get_the_ID();

            // Build start time string
            $start_hour = get_post_meta( $post_id, 'WooCommerceEventsHour', true );
            $start_minutes = get_post_meta( $post_id, 'WooCommerceEventsMinutes', true );
            $start_period = get_post_meta( $post_id, 'WooCommerceEventsPeriod', true );
            $start_time = null;
            if ( $start_hour && $start_minutes ) {
                $start_time = $start_hour . ':' . $start_minutes . ( $start_period ? ' ' . $start_period : '' );
            }

            // Build end time string
            $end_hour = get_post_meta( $post_id, 'WooCommerceEventsHourEnd', true );
            $end_minutes = get_post_meta( $post_id, 'WooCommerceEventsMinutesEnd', true );
            $end_period = get_post_meta( $post_id, 'WooCommerceEventsEndPeriod', true );
            $end_time = null;
            if ( $end_hour && $end_minutes ) {
                $end_time = $end_hour . ':' . $end_minutes . ( $end_period ? ' ' . $end_period : '' );
            }

            $meetings[] = [
                'id'             => $post_id,
                'name'           => get_the_title(),
                'date'           => get_post_meta( $post_id, 'WooCommerceEventsDate', true ) ?: null,
                'date_end'       => get_post_meta( $post_id, 'WooCommerceEventsEndDate', true ) ?: null,
                'time_start'     => $start_time,
                'time_end'       => $end_time,
                'timezone'       => get_post_meta( $post_id, 'WooCommerceEventsTimeZone', true ) ?: null,
                'location'       => get_post_meta( $post_id, 'WooCommerceEventsLocation', true ) ?: null,
                'price'          => $product->get_price(),
                'currency'       => get_woocommerce_currency(),
                'url'            => get_the_permalink(),
                'product_id'     => $post_id,
                'sku'            => $product->get_sku(),
                'stock_status'   => $product->get_stock_status(),
                'stock_quantity' => intval( $product->get_stock_quantity() ),
                'is_in_stock'    => $product->is_in_stock(),
                'description'    => get_the_excerpt(),
                'image'          => get_the_post_thumbnail_url(),
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response([
        'success'      => true,
        'message'      => 'Clockwork Medical Meetings',
        'current_page' => $page,
        'per_page'     => $per_page,
        'total_items'  => $query->found_posts,
        'total_pages'  => $query->max_num_pages,
        'data'         => $meetings,
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}
 * Get single meeting details
 */
function clockwork_get_single_meeting( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Process description for sponsors
    $desc = $product->get_description();
    $desc = preg_replace( '/<style\b[^>]*>.*?<\/style>/is', '', $desc );
    $desc = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $desc );
    $desc = str_replace( '>', ">\n", $desc );

    $lines = explode( "\n", $desc );
    $lines = array_map( 'trim', $lines );
    $lines = array_filter( $lines, fn( $v ) => $v !== '' );
    $lines = array_unique( $lines );
    $desc = implode( "\n", $lines );

    $sponsor_data = clockwork_parse_sponsors_from_html( $desc );

    // Get speakers
    $speakers = get_field( 'speakers', $id );
    $speaker_data = [];

    if ( $speakers ) {
        $speakers = is_array( $speakers ) ? $speakers : [ $speakers ];
        foreach ( $speakers as $speaker ) {
            $speaker_data[] = clockwork_get_person_data( $speaker );
        }
    }

    // Get convenors
    $convenors = get_field( 'meeting_convenors', $id );
    $convenor_data = [];

    if ( $convenors ) {
        $convenors = is_array( $convenors ) ? $convenors : [ $convenors ];
        foreach ( $convenors as $convenor ) {
            $convenor_data[] = clockwork_get_person_data( $convenor );
        }
    }

    // Build start time string
    $start_hour = get_post_meta( $id, 'WooCommerceEventsHour', true );
    $start_minutes = get_post_meta( $id, 'WooCommerceEventsMinutes', true );
    $start_period = get_post_meta( $id, 'WooCommerceEventsPeriod', true );
    $start_time = null;
    if ( $start_hour && $start_minutes ) {
        $start_time = $start_hour . ':' . $start_minutes . ( $start_period ? ' ' . $start_period : '' );
    }

    // Build end time string
    $end_hour = get_post_meta( $id, 'WooCommerceEventsHourEnd', true );
    $end_minutes = get_post_meta( $id, 'WooCommerceEventsMinutesEnd', true );
    $end_period = get_post_meta( $id, 'WooCommerceEventsEndPeriod', true );
    $end_time = null;
    if ( $end_hour && $end_minutes ) {
        $end_time = $end_hour . ':' . $end_minutes . ( $end_period ? ' ' . $end_period : '' );
    }

    // Get gallery images
    $gallery_ids = $product->get_gallery_image_ids();
    $gallery_images = [];
    foreach ( $gallery_ids as $gallery_id ) {
        $gallery_images[] = wp_get_attachment_url( $gallery_id );
    }

    // Get product categories
    $categories = wp_get_post_terms( $id, 'product_cat', [ 'fields' => 'names' ] );

    // Parse Elementor tabs content
    $elementor_tabs = clockwork_parse_elementor_tabs( $id );

    // Get all ACF fields for the product
    $all_acf_fields = [];
    if ( function_exists( 'get_fields' ) ) {
        $fields = get_fields( $id );
        if ( $fields ) {
            $all_acf_fields = $fields;
        }
    }

    // Get product type
    $product_type = $product->get_type();

    // Get variations (for variable products)
    $variations = clockwork_get_product_variations( $product );

    // Get product attributes
    $attributes = clockwork_get_product_attributes( $product );

    // Get extra product options (TM EPO plugin)
    $extra_options = clockwork_get_extra_product_options( $id );

    // Build registration info with variations and options
    $registration = [
        'product_type'    => $product_type,
        'price'           => $product->get_price(),
        'regular_price'   => $product->get_regular_price(),
        'sale_price'      => $product->get_sale_price() ?: null,
        'currency'        => get_woocommerce_currency(),
        'currency_symbol' => get_woocommerce_currency_symbol(),
        'is_in_stock'     => $product->is_in_stock(),
        'stock_status'    => $product->get_stock_status(),
        'stock_quantity'  => intval( $product->get_stock_quantity() ),
        'variations'      => $variations,
        'attributes'      => $attributes,
        'extra_options'   => $extra_options,
    ];

    // Build venue info
    $venue = [
        'location'    => get_post_meta( $id, 'WooCommerceEventsLocation', true ) ?: null,
        'gps'         => get_post_meta( $id, 'WooCommerceEventsGPS', true ) ?: null,
        'google_maps' => get_post_meta( $id, 'WooCommerceEventsGoogleMaps', true ) ?: null,
        'directions'  => get_post_meta( $id, 'WooCommerceEventsDirections', true ) ?: null,
    ];

    $meeting = [
        'id'                => $id,
        'name'              => $product->get_name(),
        'slug'              => $product->get_slug(),
        'product_type'      => $product_type,
        'date'              => get_post_meta( $id, 'WooCommerceEventsDate', true ) ?: null,
        'date_end'          => get_post_meta( $id, 'WooCommerceEventsEndDate', true ) ?: null,
        'time_start'        => $start_time,
        'time_end'          => $end_time,
        'timezone'          => get_post_meta( $id, 'WooCommerceEventsTimeZone', true ) ?: null,
        'location'          => get_post_meta( $id, 'WooCommerceEventsLocation', true ) ?: null,
        'price'             => $product->get_price(),
        'regular_price'     => $product->get_regular_price(),
        'sale_price'        => $product->get_sale_price() ?: null,
        'currency'          => get_woocommerce_currency(),
        'currency_symbol'   => get_woocommerce_currency_symbol(),
        'url'               => get_permalink( $id ),
        'product_id'        => $id,
        'sku'               => $product->get_sku(),
        'stock_status'      => $product->get_stock_status(),
        'stock_quantity'    => intval( $product->get_stock_quantity() ),
        'image'             => wp_get_attachment_url( $product->get_image_id() ),
        'gallery_images'    => $gallery_images,
        'is_in_stock'       => $product->is_in_stock(),
        'short_description' => $product->get_short_description(),
        'categories'        => $categories,
        'tabs'              => $elementor_tabs,
        'registration'      => $registration,
        'venue'             => $venue,
        'speakers'          => $speaker_data,
        'convenors'         => $convenor_data,
        'sponsors'          => $sponsor_data,
        'acf_fields'        => $all_acf_fields,
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Single Clockwork Meeting',
        'data'    => $meeting,
    ]);
}


/*******************************************************************************
 * MEETING TAB API ENDPOINTS
 ******************************************************************************/

/**
 * GET /clockwork/v1/meetings/{id}/overview
 * Get meeting overview tab content
 */
function clockwork_get_meeting_overview( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Parse Elementor tabs and find Overview tab
    $elementor_tabs = clockwork_parse_elementor_tabs( $id );
    $overview_content = null;

    foreach ( $elementor_tabs as $tab ) {
        $tab_id = strtolower( $tab['id'] ?? '' );
        $tab_title = strtolower( $tab['title'] ?? '' );
        if ( $tab_id === 'overview' || strpos( $tab_title, 'overview' ) !== false ) {
            $overview_content = $tab;
            break;
        }
    }

    // Get convenors from ACF field
    $convenors = get_field( 'meeting_convenors', $id );
    $convenor_data = [];

    if ( $convenors ) {
        $convenors = is_array( $convenors ) ? $convenors : [ $convenors ];
        foreach ( $convenors as $convenor ) {
            $person = clockwork_get_person_data( $convenor );
            $convenor_id = is_object( $convenor ) ? $convenor->ID : $convenor;
            $person['bio'] = html_entity_decode( wp_strip_all_tags( get_field( 'biography', $convenor_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['bio_teaser'] = html_entity_decode( wp_strip_all_tags( get_field( 'bio_teaser', $convenor_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['designation'] = html_entity_decode( wp_strip_all_tags( get_field( 'designation', $convenor_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['organization'] = html_entity_decode( wp_strip_all_tags( get_field( 'place', $convenor_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $convenor_data[] = $person;
        }
    }

    // Basic meeting info for context
    $overview = [
        'id'                => $id,
        'name'              => $product->get_name(),
        'short_description' => wp_strip_all_tags( $product->get_short_description() ),
        'date'              => get_post_meta( $id, 'WooCommerceEventsDate', true ) ?: null,
        'date_end'          => get_post_meta( $id, 'WooCommerceEventsEndDate', true ) ?: null,
        'location'          => get_post_meta( $id, 'WooCommerceEventsLocation', true ) ?: null,
        'convenors'         => $convenor_data,
        'content'           => $overview_content ? $overview_content['content'] : [],
        'summary'           => $overview_content ? $overview_content['summary'] : '',
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Overview',
        'data'    => $overview,
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}/register
 * Get meeting registration options (Gravity Form fields, or fallback to TM EPO options)
 */
function clockwork_get_meeting_register( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Get product type
    $product_type = $product->get_type();

    // Get variations (for variable products)
    $variations = clockwork_get_product_variations( $product );

    // Get product attributes
    $attributes = clockwork_get_product_attributes( $product );

    // Check for Gravity Form in Register tab first
    $gravity_form_id = clockwork_get_gravity_form_id_from_elementor( $id );
    $form_fields = [];
    $form_source = 'none';

    if ( $gravity_form_id ) {
        // Get Gravity Form fields
        $form_fields = clockwork_get_gravity_form_fields( $gravity_form_id );
        if ( ! empty( $form_fields ) ) {
            $form_source = 'gravity_forms';
        }
    }

    // Fallback to TM EPO options if no Gravity Form found
    if ( empty( $form_fields ) ) {
        $form_fields = clockwork_get_extra_product_options( $id );
        if ( ! empty( $form_fields ) ) {
            $form_source = 'tm_epo';
        }
    }

    // Parse Elementor tabs and find Register tab for additional content
    $elementor_tabs = clockwork_parse_elementor_tabs( $id );
    $register_content = null;

    foreach ( $elementor_tabs as $tab ) {
        $tab_id = strtolower( $tab['id'] ?? '' );
        $tab_title = strtolower( $tab['title'] ?? '' );
        if ( $tab_id === 'register' || strpos( $tab_title, 'register' ) !== false ) {
            $register_content = $tab;
            break;
        }
    }

    $registration = [
        'id'              => $id,
        'name'            => $product->get_name(),
        'product_type'    => $product_type,
        'price'           => $product->get_price(),
        'regular_price'   => $product->get_regular_price(),
        'sale_price'      => $product->get_sale_price() ?: null,
        'currency'        => get_woocommerce_currency(),
        'currency_symbol' => get_woocommerce_currency_symbol(),
        'is_in_stock'     => $product->is_in_stock(),
        'stock_status'    => $product->get_stock_status(),
        'stock_quantity'  => intval( $product->get_stock_quantity() ),
        'sku'             => $product->get_sku(),
        'url'             => get_permalink( $id ),
        'variations'      => $variations,
        'attributes'      => $attributes,
        'form_source'     => $form_source,
        'form_id'         => $gravity_form_id,
        'form_fields'     => $form_fields,
        'tab_content'     => $register_content ? $register_content['content'] : [],
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Registration Options',
        'data'    => $registration,
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}/timetable
 * Get meeting timetable/schedule
 */
function clockwork_get_meeting_timetable( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Parse Elementor tabs and find Timetable tab
    $elementor_tabs = clockwork_parse_elementor_tabs( $id );
    $timetable_content = null;

    foreach ( $elementor_tabs as $tab ) {
        $tab_id = strtolower( $tab['id'] ?? '' );
        $tab_title = strtolower( $tab['title'] ?? '' );
        if ( $tab_id === 'timetable' || strpos( $tab_title, 'timetable' ) !== false
            || strpos( $tab_title, 'schedule' ) !== false || strpos( $tab_title, 'agenda' ) !== false ) {
            $timetable_content = $tab;
            break;
        }
    }

    // Build start time string
    $start_hour = get_post_meta( $id, 'WooCommerceEventsHour', true );
    $start_minutes = get_post_meta( $id, 'WooCommerceEventsMinutes', true );
    $start_period = get_post_meta( $id, 'WooCommerceEventsPeriod', true );
    $start_time = null;
    if ( $start_hour && $start_minutes ) {
        $start_time = $start_hour . ':' . $start_minutes . ( $start_period ? ' ' . $start_period : '' );
    }

    // Build end time string
    $end_hour = get_post_meta( $id, 'WooCommerceEventsHourEnd', true );
    $end_minutes = get_post_meta( $id, 'WooCommerceEventsMinutesEnd', true );
    $end_period = get_post_meta( $id, 'WooCommerceEventsEndPeriod', true );
    $end_time = null;
    if ( $end_hour && $end_minutes ) {
        $end_time = $end_hour . ':' . $end_minutes . ( $end_period ? ' ' . $end_period : '' );
    }

    // Parse content into structured schedule
    $raw_content = $timetable_content ? $timetable_content['content'] : [];
    $schedule = clockwork_parse_timetable_content( $raw_content );

    $timetable = [
        'id'          => $id,
        'name'        => $product->get_name(),
        'date'        => get_post_meta( $id, 'WooCommerceEventsDate', true ) ?: null,
        'date_end'    => get_post_meta( $id, 'WooCommerceEventsEndDate', true ) ?: null,
        'time_start'  => $start_time,
        'time_end'    => $end_time,
        'timezone'    => get_post_meta( $id, 'WooCommerceEventsTimeZone', true ) ?: null,
        'schedule'    => $schedule,
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Timetable',
        'data'    => $timetable,
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}/speakers
 * Get meeting speakers and convenors
 */
function clockwork_get_meeting_speakers( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Get speakers from ACF field
    $speakers = get_field( 'speakers', $id );
    $speaker_data = [];

    if ( $speakers ) {
        $speakers = is_array( $speakers ) ? $speakers : [ $speakers ];
        foreach ( $speakers as $speaker ) {
            $person = clockwork_get_person_data( $speaker );
            // Get additional speaker fields
            $speaker_id = is_object( $speaker ) ? $speaker->ID : $speaker;
            $person['bio'] = html_entity_decode( wp_strip_all_tags( get_field( 'biography', $speaker_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['bio_teaser'] = html_entity_decode( wp_strip_all_tags( get_field( 'bio_teaser', $speaker_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['designation'] = html_entity_decode( wp_strip_all_tags( get_field( 'designation', $speaker_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $person['organization'] = html_entity_decode( wp_strip_all_tags( get_field( 'place', $speaker_id ) ?: '' ), ENT_QUOTES, 'UTF-8' );
            $speaker_data[] = $person;
        }
    }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Speakers',
        'data'    => [
            'id'       => $id,
            'name'     => $product->get_name(),
            'speakers' => $speaker_data,
        ],
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}/venue
 * Get meeting venue details
 */
function clockwork_get_meeting_venue( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Get venue info from FooEvents meta
    $location = get_post_meta( $id, 'WooCommerceEventsLocation', true ) ?: null;
    $gps = get_post_meta( $id, 'WooCommerceEventsGPS', true ) ?: null;
    $google_maps = get_post_meta( $id, 'WooCommerceEventsGoogleMaps', true ) ?: null;
    $directions = get_post_meta( $id, 'WooCommerceEventsDirections', true ) ?: null;

    // Parse GPS coordinates if available
    $latitude = null;
    $longitude = null;
    if ( $gps ) {
        $coords = explode( ',', $gps );
        if ( count( $coords ) === 2 ) {
            $latitude = floatval( trim( $coords[0] ) );
            $longitude = floatval( trim( $coords[1] ) );
        }
    }

    // Parse Elementor tabs and find Venue tab for additional content
    $elementor_tabs = clockwork_parse_elementor_tabs( $id );
    $venue_content = null;

    foreach ( $elementor_tabs as $tab ) {
        $tab_id = strtolower( $tab['id'] ?? '' );
        $tab_title = strtolower( $tab['title'] ?? '' );
        if ( $tab_id === 'venue' || strpos( $tab_title, 'venue' ) !== false
            || strpos( $tab_title, 'location' ) !== false ) {
            $venue_content = $tab;
            break;
        }
    }

    // Extract transport info, postcode and what3words from tab content
    $transport = [
        'parking' => '',
        'train'   => '',
        'airport' => '',
    ];
    $postcode = '';
    $what3words = '';

    $tab_content = $venue_content ? $venue_content['content'] : [];

    foreach ( $tab_content as $item ) {
        if ( ( $item['type'] ?? '' ) === 'text' && ! empty( $item['text'] ) ) {
            $text = html_entity_decode( $item['text'], ENT_QUOTES, 'UTF-8' );
            $text_lower = strtolower( $text );

            // Check what this text block is primarily about
            $has_parking = stripos( $text, 'parking' ) !== false || stripos( $text, 'charger' ) !== false;
            $has_train = stripos( $text, 'train' ) !== false || stripos( $text, 'station' ) !== false;
            $has_airport = stripos( $text, 'airport' ) !== false;

            // Dedicated train text block (mentions train but not parking in a general highlights way)
            if ( $has_train && ! $has_airport && stripos( $text, 'Pavilions are' ) !== false ) {
                $transport['train'] = trim( preg_replace( '/\s+/', ' ', $text ) );
            }

            // Dedicated airport text block
            if ( $has_airport && stripos( $text, 'closest airport' ) !== false ) {
                $transport['airport'] = trim( preg_replace( '/\s+/', ' ', $text ) );
            }

            // Extract parking sentences
            if ( $has_parking ) {
                $sentences = preg_split( '/(?<=[.!?])\s+|\n/', $text );
                foreach ( $sentences as $sentence ) {
                    $sentence = trim( $sentence );
                    if ( ( stripos( $sentence, 'parking' ) !== false || stripos( $sentence, 'charger' ) !== false )
                        && strlen( $sentence ) > 15
                        && stripos( $transport['parking'], $sentence ) === false ) {
                        $transport['parking'] .= ( ! empty( $transport['parking'] ) ? ' ' : '' ) . $sentence;
                    }
                }
            }

            // Extract postcode (UK format)
            if ( empty( $postcode ) && preg_match( '/\b([A-Z]{1,2}\d{1,2}[A-Z]?\s*\d[A-Z]{2})\b/i', $text, $matches ) ) {
                $postcode = strtoupper( trim( $matches[1] ) );
            }

            // Extract What3Words (three words separated by dots)
            if ( empty( $what3words ) && preg_match( '/\b([a-z]+\.[a-z]+\.[a-z]+)\b/i', $text, $matches ) ) {
                $what3words = strtolower( trim( $matches[1] ) );
            }
        }
    }

    // Filter out transport-related items from tab_content
    $filtered_content = [];
    foreach ( $tab_content as $item ) {
        if ( ( $item['type'] ?? '' ) === 'text' && ! empty( $item['text'] ) ) {
            $text = $item['text'];
            // Skip dedicated transport text blocks
            if ( stripos( $text, 'Pavilions are' ) !== false && stripos( $text, 'train station' ) !== false ) {
                continue;
            }
            if ( stripos( $text, 'closest airport' ) !== false ) {
                continue;
            }
        }
        $filtered_content[] = $item;
    }

    $venue = [
        'id'           => $id,
        'name'         => $product->get_name(),
        'location'     => $location,
        'postcode'     => $postcode,
        'what3words'   => $what3words,
        'gps'          => $gps,
        'latitude'     => $latitude,
        'longitude'    => $longitude,
        'google_maps'  => $google_maps,
        'directions'   => $directions,
        'transport'    => $transport,
        'tab_content'  => $filtered_content,
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Venue',
        'data'    => $venue,
    ]);
}

/**
 * GET /clockwork/v1/meetings/{id}/sponsors
 * Get meeting sponsors
 */
function clockwork_get_meeting_sponsors( $request ) {
    $id = intval( $request->get_param( 'id' ) );
    $product = wc_get_product( $id );

    if ( ! $product ) {
        return clockwork_error_response( 'Meeting not found', 404 );
    }

    // Process description for sponsors
    $desc = $product->get_description();
    $desc = preg_replace( '/<style\b[^>]*>.*?<\/style>/is', '', $desc );
    $desc = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $desc );
    $desc = str_replace( '>', ">\n", $desc );

    $lines = explode( "\n", $desc );
    $lines = array_map( 'trim', $lines );
    $lines = array_filter( $lines, fn( $v ) => $v !== '' );
    $lines = array_unique( $lines );
    $desc = implode( "\n", $lines );

    $sponsor_data = clockwork_parse_sponsors_from_html( $desc );

    // Filter to only include actual sponsor tiers (entries with sponsor data)
    $filtered_sponsors = [];
    foreach ( $sponsor_data as $tier => $sponsors ) {
        // Only include tiers that have sponsors and look like sponsor tiers
        if ( ! empty( $sponsors ) && (
            stripos( $tier, 'sponsor' ) !== false ||
            stripos( $tier, 'partner' ) !== false ||
            stripos( $tier, 'supporter' ) !== false
        ) ) {
            $filtered_sponsors[ $tier ] = $sponsors;
        }
    }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Meeting Sponsors',
        'data'    => [
            'id'       => $id,
            'name'     => $product->get_name(),
            'sponsors' => $filtered_sponsors,
        ],
    ]);
}


/*******************************************************************************
 * CONTACT FORM API ENDPOINTS
 ******************************************************************************/

/**
 * POST /clockwork/v1/contact
 * Submit contact form data
 */
function clockwork_submit_contact_form( $request ) {
    // Get form fields from request
    $first_name = sanitize_text_field( $request->get_param( 'first_name' ) );
    $last_name  = sanitize_text_field( $request->get_param( 'last_name' ) );
    $phone      = sanitize_text_field( $request->get_param( 'phone' ) );
    $email      = sanitize_email( $request->get_param( 'email' ) );
    $message    = sanitize_textarea_field( $request->get_param( 'message' ) );

    // Validate required fields
    if ( empty( $email ) ) {
        return clockwork_error_response( 'Email address is required', 400 );
    }

    if ( ! is_email( $email ) ) {
        return clockwork_error_response( 'Invalid email address', 400 );
    }

    if ( empty( $message ) ) {
        return clockwork_error_response( 'Message is required', 400 );
    }

    // Prepare email content
    $to = get_option( 'admin_email' );
    $subject = 'New Contact Form Submission - ClockWork Medical App';

    $full_name = trim( $first_name . ' ' . $last_name );
    if ( empty( $full_name ) ) {
        $full_name = 'Not provided';
    }

    $email_body = "You have received a new contact form submission from the ClockWork Medical website.\n\n";
    $email_body .= "Name: {$full_name}\n";
    $email_body .= "Email: {$email}\n";
    $email_body .= "Phone: " . ( ! empty( $phone ) ? $phone : 'Not provided' ) . "\n\n";
    $email_body .= "Message:\n{$message}\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: {$full_name} <{$email}>",
    ];

    // Send email
    $sent = wp_mail( $to, $subject, $email_body, $headers );

    // Currently commenting the mail delivery status check to avoid blocking form submissions
    // if ( ! $sent ) {
    //     return clockwork_error_response( 'Failed to send message. Please try again later.', 500 );
    // }

    return rest_ensure_response([
        'success' => true,
        'message' => 'Your message has been sent successfully. We will get back to you soon.',
    ]);
}


/*******************************************************************************
 * USER LIST API ENDPOINTS
 ******************************************************************************/

/**
 * GET /custom-api/v1/customers|subscribers|exhibitors
 * Get users by role
 */
function clockwork_get_users_by_role( $request ) {
    $role = $request->get_param( 'role' );

    // Map route to role
    $route = $request->get_route();
    if ( strpos( $route, '/exhibitors' ) !== false ) {
        $role = 'cm_exhibitor';
    } elseif ( strpos( $route, '/subscribers' ) !== false ) {
        $role = 'subscriber';
    } else {
        $role = 'customer';
    }

    $users = get_users([
        'role'    => $role,
        'orderby' => 'registered',
        'order'   => 'DESC',
    ]);

    $data = [];
    foreach ( $users as $user ) {
        $data[] = [
            'ID'                 => $user->ID,
            'username'           => $user->user_login,
            'email'              => $user->user_email,
            'name'               => $user->display_name,
            'registered'         => $user->user_registered,
            'active_memberships' => clockwork_get_user_memberships( $user->ID ),
        ];
    }

    $type_label = str_replace( 'cm_', '', $role ) . 's';

    return [
        "total_{$type_label}" => count( $users ),
        $type_label           => $data,
    ];
}
