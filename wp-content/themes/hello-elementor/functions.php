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
    $per_page = 5;

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

            $meetings[] = [
                'id'             => get_the_ID(),
                'name'           => get_the_title(),
                'date'           => get_post_meta( get_the_ID(), 'meeting_start_date', true ) ?: null,
                'date_end'       => get_post_meta( get_the_ID(), 'meeting_end_date', true ) ?: null,
                'price'          => $product->get_price(),
                'url'            => get_the_permalink(),
                'product_id'     => get_the_ID(),
                'sku'            => $product->get_sku(),
                'stock_status'   => $product->get_stock_status(),
                'stock_quantity' => intval( $product->get_stock_quantity() ),
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

    $meeting = [
        'id'                => $id,
        'name'              => $product->get_name(),
        'date'              => get_post_meta( $id, 'meeting_start_date', true ) ?: null,
        'date_end'          => get_post_meta( $id, 'meeting_end_date', true ) ?: null,
        'price'             => $product->get_price(),
        'url'               => get_permalink( $id ),
        'product_id'        => $id,
        'sku'               => $product->get_sku(),
        'stock_status'      => $product->get_stock_status(),
        'stock_quantity'    => intval( $product->get_stock_quantity() ),
        'image'             => wp_get_attachment_url( $product->get_image_id() ),
        'is_in_stock'       => $product->is_in_stock(),
        'speaker'           => $speaker_data,
        'meeting_convenors' => $convenor_data,
        'sponsors'          => $sponsor_data,
    ];

    return rest_ensure_response([
        'success' => true,
        'message' => 'Single Clockwork Meeting',
        'data'    => $meeting,
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
