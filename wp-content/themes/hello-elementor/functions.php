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

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
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
	 *
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
	 *
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
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();

add_filter('woocommerce_login_redirect', function(){ 
    return admin_url(); 
}, 99);


/**** clockwork-meetings-api ****/

// Single Meeting Endpoint (with speaker ACF fields)
add_action( 'rest_api_init', function() {
    register_rest_route( 'clockwork/v1', '/meetings/(?P<id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'clockwork_get_single_meeting',
        'permission_callback' => '__return_true'
    ) );
});



function extract_all_tabs_from_html( $html ) {
    $tabs_data = array();
    
    // Create DOMDocument to parse HTML
    $dom = new DOMDocument();
    libxml_use_internal_errors( true );
    $dom->loadHTML( '<?xml encoding="UTF-8">' . $html );
    libxml_clear_errors();
    
    $xpath = new DOMXPath( $dom );
    
    // Tab containers find karo (role="tabpanel" wale divs)
    $tab_panels = $xpath->query( "//div[@role='tabpanel']" );
    
    foreach ( $tab_panels as $panel ) {
        // Tab ka heading find karo
        $heading = $xpath->query( ".//h2[contains(@class, 'elementor-heading-title')]", $panel )->item( 0 );
        
        if ( !$heading ) {
            continue;
        }
        
        $tab_title = trim( $heading->textContent );
        
        // Agar heading empty ho to skip karo
        if ( empty( $tab_title ) ) {
            continue;
        }
        
        // Tab key banao (heading se)
        $tab_key = sanitize_title( $tab_title );
        
        // Is tab ke under sab sponsors/items find karo
        $items = array();
        
        // Grid containers find karo
        $grid_containers = $xpath->query( ".//div[contains(@class, 'e-grid')]//div[contains(@class, 'e-con-full')]", $panel );
        
        foreach ( $grid_containers as $container ) {
            // Image aur link find karo
            $link = $xpath->query( ".//a[@target='_blank']", $container )->item( 0 );
            $image = $xpath->query( ".//img", $container )->item( 0 );
            
            if ( $link && $image ) {
                $url = $link->getAttribute( 'href' );
                $img_src = $image->getAttribute( 'src' );
                $img_alt = $image->getAttribute( 'alt' );
                
                // Agar URL ya image missing ho to skip karo
                if ( empty( $url ) || empty( $img_src ) ) {
                    continue;
                }
                
                $items[] = array(
                    'name'  => !empty( $img_alt ) ? $img_alt : 'Sponsor',
                    'url'   => $url,
                    'image' => $img_src,
                    'type'  => $tab_key
                );
            }
        }
        
        // Agar items mil gaye to tabs_data me add karo
        if ( !empty( $items ) ) {
            $tabs_data[ $tab_key ] = array(
                'title'  => $tab_title,
                'items'  => $items,
                'count'  => count( $items )
            );
        }
    }
    
    return $tabs_data;
}








function parse_sponsors_from_html($html_content) {
    $sponsors = [];
    $current_tier = null;
    $temp_link = null; // Stores the link URL until the corresponding image is found

    // Split the content back into lines 
    $lines = explode("\n", $html_content);
    $lines = array_map('trim', $lines);
    $lines = array_filter($lines);


    foreach ($lines as $line) {
        
        // 1. Check for Sponsor Tier Header (Look for text followed by a closing heading tag </h[1-6]>)
        // This regex captures the text content (group 1) that appears right before a closing heading tag.
        if (preg_match('/(.*?)\s*<\/h[1-6]>/i', $line, $matches_header)) {
            
            $raw_tier_name = trim($matches_header[1]);
            
            // If the opening tag was on the same line, remove it too.
            $raw_tier_name = preg_replace('/<h[1-6].*?>/i', '', $raw_tier_name);
            
            if (!empty($raw_tier_name)) {
                // Normalize the tier name for use as an array key (e.g., 'Premium Plus Sponsors' -> 'premium_plus_sponsors')
                $key = strtolower(str_replace(' ', '_', $raw_tier_name));
                $key = preg_replace('/_+/', '_', $key); // Collapse multiple underscores
                
                $current_tier = $key;
                $temp_link = null; // Reset link when a new tier starts
                
                // Ensure the tier array exists
                if (!isset($sponsors[$current_tier])) {
                    $sponsors[$current_tier] = [];
                }
                
                continue; // Skip the rest of the loop for this line (it was a header)
            }
        }
        
        // Must have a current tier defined to proceed with links/images
        if (!$current_tier) {
            continue;
        }

        // 2. Check for Link Tag <a>
        if (strpos($line, '<a href=') !== false) {
            // Extract the href URL
            if (preg_match('/href=["\']([^"\']+)["\']/', $line, $matches)) {
                $temp_link = $matches[1];
            }
        }

        // 3. Check for Image Tag <img>
        if (strpos($line, '<img') !== false) {
            
            $img_url = preg_match('/src=["\']([^"\']+)["\']/', $line, $matches_src) ? $matches_src[1] : '';
            $title = preg_match('/alt=["\']([^"\']*)["\']/', $line, $matches_alt) ? $matches_alt[1] : '';
            
            if ($img_url) {
                
                // Fallback title if alt is empty, use filename
                if (empty($title)) {
                    $path = parse_url($img_url, PHP_URL_PATH);
                    $filename = basename($path);
                    $title = pathinfo($filename, PATHINFO_FILENAME);
                    // Basic cleanup for filename title
                    $title = ucwords(str_replace(['-', '_', '.'], ' ', $title));
                }

                $sponsor_details = [
                    'title'     => $title,
                    'image_url' => $img_url,
                    // Use the temporary link captured just before this image
                    'link_url'  => $temp_link, 
                ];

                $sponsors[$current_tier][] = $sponsor_details;

                // Reset temporary link after usage
                $temp_link = null; 
            }
        }
    }

    return $sponsors;
}

function clockwork_get_single_meeting( $request ) {

 //$id = intval( $request['id'] );  

 $id = $request->get_param('id');

    $product = wc_get_product( $id );

 

$desc = $product->get_description();
// 1️⃣ Remove <style> and <script> blocks
$desc = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $desc);
$desc = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $desc);

// 2️⃣ IMP: Add a newline after every closing HTML tag (>)
// Yeh zaroori hai kyunki Elementor kabhi-kabhi pura HTML ek line mein deta hai.
$desc = str_replace('>', ">\n", $desc);

// 3️⃣ Split by new line
$lines = explode("\n", $desc);

// 4️⃣ Trim every line (remove side spaces)
$lines = array_map('trim', $lines);

// 5️⃣ Remove completely empty lines
$lines = array_filter($lines, function($value) {
    return $value !== '';
});

// 6️⃣ Remove Duplicate Lines
$lines = array_unique($lines);

// 7️⃣ Join back together
$desc = implode("\n", $lines);



//echo "<pre>"; print_r($desc); exit;

//echo "<pre>"; print_r($desc); exit;

$sponsor_data = parse_sponsors_from_html($desc);

    // Get metadata
    $start_date     = get_post_meta( $id, 'meeting_start_date', true );
    $end_date       = get_post_meta( $id, 'meeting_end_date', true );
    $sku            = $product->get_sku();
    $stock_status   = $product->get_stock_status();
    $stock_quantity = $product->get_stock_quantity();


    

    //echo "<pre>"; print_r($convenors); exit;

    // 🔹 Fetch Speaker Data from ACF
    $speakers = get_field( 'speakers', $id );

    $speaker_data = array();

    if ( $speakers ) {
        if ( is_array( $speakers ) ) {
            foreach ( $speakers as $speaker ) {
                $speaker_id = is_object( $speaker ) ? $speaker->ID : $speaker;

                // ✅ Get ACF Fields
                $speaker_image_field = get_field( 'speaker-image', $speaker_id );
                $speaker_image = '';
                if ( $speaker_image_field ) {
                    $speaker_image = is_array( $speaker_image_field ) ? $speaker_image_field['url'] : $speaker_image_field;
                }

                $bio_teaser = get_field( 'bio_teaser', $speaker_id );
                $biography  = get_field( 'biography', $speaker_id );

                $speaker_data[] = array(
                    'id'          => $speaker_id,
                    'name'        => get_the_title( $speaker_id ),
                    'image'       => $speaker_image,
                    'bio_teaser'  => wp_strip_all_tags( $bio_teaser ),
                    //'biography'   => wp_strip_all_tags( $biography ),
                );
            }
        } else {
            // Single speaker object
            $speaker_id = is_object( $speakers ) ? $speakers->ID : $speakers;

            $speaker_image_field = get_field( 'speaker-image', $speaker_id );
            $speaker_image = '';
            if ( $speaker_image_field ) {
                $speaker_image = is_array( $speaker_image_field ) ? $speaker_image_field['url'] : $speaker_image_field;
            }

            $bio_teaser = get_field( 'bio_teaser', $speaker_id );
            $biography  = get_field( 'biography', $speaker_id );

            $speaker_data[] = array(
                'id'          => $speaker_id,
                'name'        => get_the_title( $speaker_id ),
                'image'       => $speaker_image,
                'bio_teaser'  => wp_strip_all_tags( $bio_teaser ),
                //'biography'   => wp_strip_all_tags( $biography ),
            );
        }
    }

    // 🔹 Fetch Convenors Data from ACF
	$convenors = get_field('meeting_convenors', $id); // ACF field name
	$convenor_data = array();

	if ($convenors) {
	    if (is_array($convenors)) {
	        foreach ($convenors as $convenor) {

	            $convenor_id = is_object($convenor) ? $convenor->ID : $convenor;

	            // Image
	            $convenor_image_field = get_field('speaker-image', $convenor_id);
	            $convenor_image = '';
	            if ($convenor_image_field) {
	                $convenor_image = is_array($convenor_image_field)
	                    ? $convenor_image_field['url']
	                    : $convenor_image_field;
	            }

	            $bio_teaser = get_field('bio_teaser', $convenor_id);
	            $biography  = get_field('biography', $convenor_id);

	            // Push data
	            $convenor_data[] = array(
	                'id'         => $convenor_id,
	                'name'       => get_the_title($convenor_id),
	                'image'      => $convenor_image,
	                'bio_teaser' => wp_strip_all_tags($bio_teaser),
	                //'biography'  => wp_strip_all_tags($biography),
	            );
	        }

	    } else {
	        // Only one convenor
	        $convenor_id = is_object($convenors) ? $convenors->ID : $convenors;

	        $convenor_image_field = get_field('speaker-image', $convenor_id);
	        $convenor_image = is_array($convenor_image_field)
	            ? $convenor_image_field['url']
	            : $convenor_image_field;

	        $bio_teaser = get_field('bio_teaser', $convenor_id);
	        $biography  = get_field('biography', $convenor_id);

	        $convenor_data[] = array(
	            'id'         => $convenor_id,
	            'name'       => get_the_title($convenor_id),
	            'image'      => $convenor_image,
	            'bio_teaser' => wp_strip_all_tags($bio_teaser),
	            //'biography'  => wp_strip_all_tags($biography),
	        );
	    }
	}


     //$timetable = parse_html_timetable($desc);

    $meeting = array(
        'id'             => $id,
        'name'           => $product->get_name(),
        'date'           => $start_date ? $start_date : 'null',
        'date_end'       => $end_date ? $end_date : null,
        'price'          => $product->get_price(),
        // 'price_html'     => $product->get_price_html(),
        'url'            => get_permalink( $id ),
        'product_id'     => $id,
        'sku'            => $sku,
        'stock_status'   => $stock_status,
        'stock_quantity' => intval( $stock_quantity ),
        'image'          => wp_get_attachment_url( $product->get_image_id() ),
        'is_in_stock'    => $product->is_in_stock(),
        'speaker'        => $speaker_data,
        'meeting_convenors' => $convenor_data,
        'overview'       => $overview,
        'sponsors'       => $sponsor_data,
    );

    return rest_ensure_response( array(
        'success' => true,
        'message' => 'Single Clockwork Meeting',
        'data'    => $meeting
    ) );
}

/**** customer-api ****/

/**** http://localhost/wordpress_sites/wp-json/custom-api/v1/customers ****/

/*******************************
 *  API: CUSTOMERS
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/customers', [
        'methods'  => 'GET',
        'callback' => 'get_customer_users',
        'permission_callback' => '__return_true',
    ]);
});


/*******************************
 * Helper: Get User Memberships
 *******************************/
function get_user_active_memberships($user_id) {

    if (!function_exists('wc_memberships_get_user_memberships')) {
        return [];
    }

    $active_list = [];
    $memberships = wc_memberships_get_user_memberships($user_id);

    foreach ($memberships as $membership) {
        if ($membership->is_active()) {
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


/*******************************
 *  CUSTOMERS API + Memberships
 *******************************/
function get_customer_users() {

    $args = [
        'role'    => 'customer',
        'orderby' => 'registered',
        'order'   => 'DESC',
    ];

    $users = get_users($args);
    $data = [];

    foreach ($users as $user) {

        // ← MEMBERSHIP DATA
        $memberships = get_user_active_memberships($user->ID);

        $data[] = [
            'ID'                 => $user->ID,
            'username'           => $user->user_login,
            'email'              => $user->user_email,
            'name'               => $user->display_name,
            'registered'         => $user->user_registered,
            'active_memberships' => $memberships,
        ];
    }

    return [
        'total_customers' => count($users),
        'customers'       => $data
    ];
}


/*******************************
 *  API: SUBSCRIBERS + Memberships
 *******************************/

/**** http://localhost/wordpress_sites/wp-json/custom-api/v1/subscribers ****/

add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/subscribers', [
        'methods'  => 'GET',
        'callback' => 'get_subscriber_users',
        'permission_callback' => '__return_true',
    ]);
});


function get_subscriber_users() {

    $args = [
        'role'    => 'subscriber',
        'orderby' => 'registered',
        'order'   => 'DESC',
    ];

    $users = get_users($args);
    $data = [];

    foreach ($users as $user) {

        $memberships = get_user_active_memberships($user->ID);

        $data[] = [
            'ID'                 => $user->ID,
            'username'           => $user->user_login,
            'email'              => $user->user_email,
            'name'               => $user->display_name,
            'registered'         => $user->user_registered,
            'active_memberships' => $memberships,
        ];
    }

    return [
        'total_subscribers' => count($users),
        'subscribers'       => $data,
    ];
}


/*******************************
 *  API: EXHIBITORS + Memberships
 *******************************/

/**** http://localhost/wordpress_sites/wp-json/custom-api/v1/exhibitors ****/

add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/exhibitors', [
        'methods'  => 'GET',
        'callback' => 'get_exhibitor_users',
        'permission_callback' => '__return_true',
    ]);
});


function get_exhibitor_users() {

    // Assuming 'exhibitor' is the custom role for exhibitors
    $args = [
        'role'    => 'cm_exhibitor', // Change this to your actual exhibitor role if it's different
        'orderby' => 'registered',
        'order'   => 'DESC',
    ];

    $users = get_users($args);
    $data = [];

    foreach ($users as $user) {

        $memberships = get_user_active_memberships($user->ID);

        $data[] = [
            'ID'                 => $user->ID,
            'username'           => $user->user_login,
            'email'              => $user->user_email,
            'name'               => $user->display_name,
            'registered'         => $user->user_registered,
            'active_memberships' => $memberships,
        ];
    }

    return [
        'total_exhibitors' => count($users),
        'exhibitors'       => $data,
    ];
}

/************ CLOCKWORK MOBILE APP - AUTHENTICATION APIs ********************/

/**
 * Generate a simple auth token for mobile app
 * Uses WordPress application passwords or custom token
 */
function clockwork_generate_auth_token($user_id) {
    $token = wp_generate_password(64, false);
    $token_hash = wp_hash($token);

    // Store token with expiry (30 days)
    $expiry = time() + (30 * DAY_IN_SECONDS);
    update_user_meta($user_id, 'clockwork_auth_token', $token_hash);
    update_user_meta($user_id, 'clockwork_auth_token_expiry', $expiry);

    return $token;
}

/**
 * Validate auth token
 */
function clockwork_validate_auth_token($token) {
    if (empty($token)) {
        return false;
    }

    $token_hash = wp_hash($token);

    $users = get_users(array(
        'meta_key'   => 'clockwork_auth_token',
        'meta_value' => $token_hash,
        'number'     => 1
    ));

    if (empty($users)) {
        return false;
    }

    $user = $users[0];
    $expiry = get_user_meta($user->ID, 'clockwork_auth_token_expiry', true);

    if ($expiry && time() > $expiry) {
        // Token expired
        delete_user_meta($user->ID, 'clockwork_auth_token');
        delete_user_meta($user->ID, 'clockwork_auth_token_expiry');
        return false;
    }

    return $user;
}

/**
 * Get user from Authorization header
 */
function clockwork_get_user_from_token($request) {
    $auth_header = $request->get_header('Authorization');

    if (empty($auth_header)) {
        return new WP_Error('no_token', 'Authorization token is required', array('status' => 401));
    }

    // Remove "Bearer " prefix
    $token = str_replace('Bearer ', '', $auth_header);

    $user = clockwork_validate_auth_token($token);

    if (!$user) {
        return new WP_Error('invalid_token', 'Invalid or expired token', array('status' => 401));
    }

    return $user;
}

/**
 * Format user data for API response
 */
function clockwork_format_user_data($user, $include_memberships = true) {
    $user_role = isset($user->roles[0]) ? $user->roles[0] : '';

    $data = array(
        'id'         => $user->ID,
        'username'   => $user->user_login,
        'email'      => $user->user_email,
        'first_name' => $user->first_name,
        'last_name'  => $user->last_name,
        'name'       => trim($user->first_name . ' ' . $user->last_name),
        'registered' => $user->user_registered,
        'role'       => $user_role,
    );

    if ($include_memberships) {
        $data['active_memberships'] = get_user_active_memberships($user->ID);
    }

    return $data;
}


/*******************************
 *  API: USER REGISTER
 *  POST /clockwork/v1/register
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/register', array(
        'methods'  => 'POST',
        'callback' => 'clockwork_register_user_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_register_user_api($request) {
    $params = $request->get_json_params();

    $email      = sanitize_email($params['email'] ?? '');
    $password   = $params['password'] ?? '';
    $first_name = sanitize_text_field($params['first_name'] ?? '');
    $last_name  = sanitize_text_field($params['last_name'] ?? '');
    $username   = sanitize_user($params['username'] ?? '');

    // Validation
    if (empty($email)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Email is required'
        ), 400);
    }

    if (!is_email($email)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Invalid email format'
        ), 400);
    }

    if (empty($password)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Password is required'
        ), 400);
    }

    if (strlen($password) < 6) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Password must be at least 6 characters'
        ), 400);
    }

    // Check if email already exists
    if (email_exists($email)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Email already registered'
        ), 409);
    }

    // Generate username from email if not provided
    if (empty($username)) {
        $username = sanitize_user(current(explode('@', $email)));
        $base_username = $username;
        $counter = 1;
        while (username_exists($username)) {
            $username = $base_username . $counter;
            $counter++;
        }
    } else {
        // Check if username exists
        if (username_exists($username)) {
            return new WP_REST_Response(array(
                'status'  => 'error',
                'message' => 'Username already taken'
            ), 409);
        }
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $user_id->get_error_message()
        ), 400);
    }

    // Update user meta
    wp_update_user(array(
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ));

    // Set default role as customer (for WooCommerce)
    $user = new WP_User($user_id);
    $user->set_role('customer');

    // Generate auth token
    $token = clockwork_generate_auth_token($user_id);

    // Get user data
    $user = get_user_by('ID', $user_id);

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Registration successful',
        'data'    => clockwork_format_user_data($user),
        'token'   => $token
    ), 201);
}


/*******************************
 *  API: USER LOGIN
 *  POST /clockwork/v1/login
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/login', array(
        'methods'  => 'POST',
        'callback' => 'clockwork_login_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_login_api($request) {
    $params   = $request->get_json_params();
    $email    = sanitize_email($params['email'] ?? '');
    $password = $params['password'] ?? '';
    $roleReq  = sanitize_text_field($params['role'] ?? '');

    if (empty($email) || empty($password)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Email and password are required'
        ), 400);
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Invalid email or password'
        ), 401);
    }

    if (!wp_check_password($password, $user->user_pass, $user->ID)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Invalid email or password'
        ), 401);
    }

    $user_role = isset($user->roles[0]) ? $user->roles[0] : '';

    if (!empty($roleReq) && $roleReq !== $user_role) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Access denied for this role'
        ), 403);
    }

    // Generate auth token
    $token = clockwork_generate_auth_token($user->ID);

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Login successful',
        'data'    => clockwork_format_user_data($user),
        'token'   => $token
    ), 200);
}


/*******************************
 *  API: USER LOGOUT
 *  POST /clockwork/v1/logout
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/logout', array(
        'methods'  => 'POST',
        'callback' => 'clockwork_logout_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_logout_api($request) {
    $user = clockwork_get_user_from_token($request);

    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $user->get_error_message()
        ), $user->get_error_data()['status'] ?? 401);
    }

    // Delete auth token
    delete_user_meta($user->ID, 'clockwork_auth_token');
    delete_user_meta($user->ID, 'clockwork_auth_token_expiry');

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Logged out successfully'
    ), 200);
}


/*******************************
 *  API: GET PROFILE
 *  GET /clockwork/v1/profile
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/profile', array(
        'methods'  => 'GET',
        'callback' => 'clockwork_get_profile_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_get_profile_api($request) {
    $user = clockwork_get_user_from_token($request);

    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $user->get_error_message()
        ), $user->get_error_data()['status'] ?? 401);
    }

    // Get additional user meta
    $user_data = clockwork_format_user_data($user);

    // Add billing/shipping info if available (WooCommerce)
    $user_data['billing'] = array(
        'phone'    => get_user_meta($user->ID, 'billing_phone', true),
        'address'  => get_user_meta($user->ID, 'billing_address_1', true),
        'address2' => get_user_meta($user->ID, 'billing_address_2', true),
        'city'     => get_user_meta($user->ID, 'billing_city', true),
        'state'    => get_user_meta($user->ID, 'billing_state', true),
        'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
        'country'  => get_user_meta($user->ID, 'billing_country', true),
    );

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Profile retrieved successfully',
        'data'    => $user_data
    ), 200);
}


/*******************************
 *  API: UPDATE PROFILE
 *  PUT /clockwork/v1/profile
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/profile', array(
        'methods'  => 'PUT',
        'callback' => 'clockwork_update_profile_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_update_profile_api($request) {
    $user = clockwork_get_user_from_token($request);

    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $user->get_error_message()
        ), $user->get_error_data()['status'] ?? 401);
    }

    $params = $request->get_json_params();

    $update_data = array('ID' => $user->ID);

    // Update basic info
    if (isset($params['first_name'])) {
        $update_data['first_name'] = sanitize_text_field($params['first_name']);
    }
    if (isset($params['last_name'])) {
        $update_data['last_name'] = sanitize_text_field($params['last_name']);
    }
    if (isset($params['email'])) {
        $new_email = sanitize_email($params['email']);
        if (!is_email($new_email)) {
            return new WP_REST_Response(array(
                'status'  => 'error',
                'message' => 'Invalid email format'
            ), 400);
        }
        // Check if email is already used by another user
        $existing_user = get_user_by('email', $new_email);
        if ($existing_user && $existing_user->ID !== $user->ID) {
            return new WP_REST_Response(array(
                'status'  => 'error',
                'message' => 'Email already in use'
            ), 409);
        }
        $update_data['user_email'] = $new_email;
    }

    // Update user
    $result = wp_update_user($update_data);

    if (is_wp_error($result)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $result->get_error_message()
        ), 400);
    }

    // Update billing info (WooCommerce)
    if (isset($params['billing'])) {
        $billing = $params['billing'];
        if (isset($billing['phone'])) {
            update_user_meta($user->ID, 'billing_phone', sanitize_text_field($billing['phone']));
        }
        if (isset($billing['address'])) {
            update_user_meta($user->ID, 'billing_address_1', sanitize_text_field($billing['address']));
        }
        if (isset($billing['address2'])) {
            update_user_meta($user->ID, 'billing_address_2', sanitize_text_field($billing['address2']));
        }
        if (isset($billing['city'])) {
            update_user_meta($user->ID, 'billing_city', sanitize_text_field($billing['city']));
        }
        if (isset($billing['state'])) {
            update_user_meta($user->ID, 'billing_state', sanitize_text_field($billing['state']));
        }
        if (isset($billing['postcode'])) {
            update_user_meta($user->ID, 'billing_postcode', sanitize_text_field($billing['postcode']));
        }
        if (isset($billing['country'])) {
            update_user_meta($user->ID, 'billing_country', sanitize_text_field($billing['country']));
        }
    }

    // Get updated user
    $updated_user = get_user_by('ID', $user->ID);
    $user_data = clockwork_format_user_data($updated_user);

    // Add billing info
    $user_data['billing'] = array(
        'phone'    => get_user_meta($user->ID, 'billing_phone', true),
        'address'  => get_user_meta($user->ID, 'billing_address_1', true),
        'address2' => get_user_meta($user->ID, 'billing_address_2', true),
        'city'     => get_user_meta($user->ID, 'billing_city', true),
        'state'    => get_user_meta($user->ID, 'billing_state', true),
        'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
        'country'  => get_user_meta($user->ID, 'billing_country', true),
    );

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Profile updated successfully',
        'data'    => $user_data
    ), 200);
}


/*******************************
 *  API: CHANGE PASSWORD
 *  POST /clockwork/v1/change-password
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/change-password', array(
        'methods'  => 'POST',
        'callback' => 'clockwork_change_password_api',
        'permission_callback' => '__return_true'
    ));
});

function clockwork_change_password_api($request) {
    $user = clockwork_get_user_from_token($request);

    if (is_wp_error($user)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => $user->get_error_message()
        ), $user->get_error_data()['status'] ?? 401);
    }

    $params = $request->get_json_params();

    $current_password = $params['current_password'] ?? '';
    $new_password     = $params['new_password'] ?? '';

    if (empty($current_password) || empty($new_password)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Current password and new password are required'
        ), 400);
    }

    if (strlen($new_password) < 6) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'New password must be at least 6 characters'
        ), 400);
    }

    // Verify current password
    if (!wp_check_password($current_password, $user->user_pass, $user->ID)) {
        return new WP_REST_Response(array(
            'status'  => 'error',
            'message' => 'Current password is incorrect'
        ), 401);
    }

    // Update password
    wp_set_password($new_password, $user->ID);

    // Generate new token (old token becomes invalid after password change)
    $token = clockwork_generate_auth_token($user->ID);

    return new WP_REST_Response(array(
        'status'  => 'success',
        'message' => 'Password changed successfully',
        'token'   => $token
    ), 200);
}


/*******************************
 *  LEGACY: USER LOGIN (kept for backward compatibility)
 *  POST /clockwork/v1/wp-login
 *******************************/
add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/wp-login', array(
        'methods'  => 'POST',
        'callback' => 'wp_custom_login_api',
        'permission_callback' => '__return_true'
    ));
});

function wp_custom_login_api($request) {
    $params   = $request->get_json_params();
    $email    = sanitize_email($params['email'] ?? '');
    $password = $params['password'] ?? '';
    $roleReq  = $params['role'] ?? '';

    if (empty($email) || empty($password)) {
        return array(
            'status'  => 'error',
            'message' => 'Email or password missing'
        );
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return array(
            'status'  => 'error',
            'message' => 'User does not exist'
        );
    }

    if (!wp_check_password($password, $user->user_pass, $user->ID)) {
        return array(
            'status'  => 'error',
            'message' => 'Invalid password'
        );
    }

    $user_role = isset($user->roles[0]) ? $user->roles[0] : '';

    if (!empty($roleReq) && $roleReq !== $user_role) {
        return array(
            'status'  => 'error',
            'message' => 'User role does not match'
        );
    }

    // Generate auth token for mobile app
    $token = clockwork_generate_auth_token($user->ID);

    return [
        "status"  => "success",
        "message" => "Login successful",
        "data"    => clockwork_format_user_data($user),
        "token"   => $token
    ];
}




/**** result ****/

/*****
http://localhost/wordpress_sites/wp-json/clockwork/v1/wp-login


post : {
  "email": "mohitvamjashopify100@gmail.com",
  "password": "test@123",
  "role": "customer"
}


result {
	{
    "status": "success",
    "message": "Login successful",
    "data": {
        "ID": 2491,
        "username": "vamja",
        "email": "mohitvamjashopify10@gmail.com",
        "name": "vamja mohit",
        "registered": "2025-11-21 05:32:36",
        "role": "customer",
        "active_memberships": []
    }
}

}


 *****/



/**** forgot password ****/

add_action('rest_api_init', function () {
    register_rest_route('clockwork/v1', '/forgot-password', array(
        'methods'  => 'POST',
        'callback' => 'wp_forgot_password_api',
        'permission_callback' => '__return_true'
    ));
});

function wp_forgot_password_api($request) {

    $email = sanitize_email($request->get_param('email'));

    if (empty($email)) {
        return array(
            'status' => 'error',
            'message' => 'Email is required'
        );
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        return array(
            'status' => 'error',
            'message' => 'No user found with this email'
        );
    }

    $user_id  = $user->ID;
    $username = $user->user_login;
    $name     = trim($user->first_name . ' ' . $user->last_name);
    $role     = isset($user->roles[0]) ? $user->roles[0] : '';

    $key = get_password_reset_key($user);
    if (is_wp_error($key)) {
        return array(
            'status' => 'error',
            'message' => 'Could not generate reset key'
        );
    }

    $reset_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');

    // Send reset email
    $subject = 'Reset Your Password';
    $message = "Hi $name,\n\n";
    $message .= "You requested a password reset. Click the link below:\n\n";
    $message .= $reset_link . "\n\n";
    $message .= "If you didn't request this, please ignore this email.\n";

    wp_mail($email, $subject, $message);

    // Format data EXACTLY like you want
    $customData = [
        "ID: $user_id",
        "username: $username",
        "name: $name",
        "email: $email",
        "role: $role",
        "reset_link: $reset_link"
    ];

    return array(
        'status' => 'success',
        'message' => 'Password reset email sent',
        'data' => $customData
    );
}


/**** result 
POST https://yourwebsite.com/wp-json/clockwork/v1/forgot-password
Request Body (JSON):
{
    "email": "user@example.com"
}

Response Example:

{
  "status": "success",
  "message": "Password reset email sent",
  "data": {
    "ID": 123,
    "username": "john_doe",
    "name": "John Doe",
    "email": "user@example.com",
    "role": "customer",
    "reset_link": "https://yourwebsite.com/wp-login.php?action=rp&key=...",
    "active_memberships": [
        {
            "membership_id": 1,
            "plan_name": "Premium Plan",
            "start_date": "2025-01-01 00:00:00",
            "end_date": "2025-12-31 23:59:59",
            "status": "active"
        }
    ]
  }
}


****/

/***** meetings data *****/

/***** 
Get : method
http://localhost/wordpress_sites/wp-json/clockwork/v1/meetings?page=1

*****/

add_action( 'rest_api_init', function() {
    register_rest_route( 'clockwork/v1', '/meetings', array(
        'methods'  => 'GET',
        'callback' => 'clockwork_get_meetings_data_pagination',
        'permission_callback' => '__return_true'
    ) );
});


function clockwork_get_meetings_data_pagination( WP_REST_Request $request ) {

    // Get pagination (per_page always default = 5)
    $page     = intval( $request->get_param('page') ) ?: 1;
    $per_page = 5; // fixed default, no need to pass from URL

    // WP Query
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'meetings'
            )
        )
    );

    $products_query = new WP_Query($args);
    $meetings = array();

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product = wc_get_product(get_the_ID());

            $start_date     = get_post_meta(get_the_ID(), 'meeting_start_date', true);
            $end_date       = get_post_meta(get_the_ID(), 'meeting_end_date', true);
            $sku            = $product ? $product->get_sku() : '';
            $stock_status   = $product ? $product->get_stock_status() : 'outofstock';
            $stock_quantity = $product ? $product->get_stock_quantity() : 0;

            $meetings[] = array(
                'id'             => get_the_ID(),
                'name'           => get_the_title(),
                'date'           => $start_date ? $start_date : 'null',
                'date_end'       => $end_date ?: null,
                'price'          => $product ? $product->get_price() : '0',
                // 'price_html'     => $product ? $product->get_price_html() : '£0.00',
                'url'            => get_the_permalink(),
                'product_id'     => get_the_ID(),
                'sku'            => $sku,
                'stock_status'   => $stock_status,
                'stock_quantity' => intval($stock_quantity),
                'description'    => get_the_excerpt(),
                'image'          => get_the_post_thumbnail_url()
            );
        }
        wp_reset_postdata();
    }

    return rest_ensure_response(array(
        'success'       => true,
        'message'       => 'Clockwork Medical Meetings',
        'current_page'  => $page,
        'per_page'      => $per_page,     // Always 5
        'total_items'   => $products_query->found_posts,
        'total_pages'   => $products_query->max_num_pages,
        'data'          => $meetings
    ));
}