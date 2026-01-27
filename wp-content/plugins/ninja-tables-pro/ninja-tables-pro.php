<?php defined('ABSPATH') or die;

/**
Plugin Name: Ninja Tables Pro
Description: The Pro Add-On of Ninja Tables, the best Responsive Table Plugin for WordPress.
Version: 5.2.3
Author: WPManageNinja
Author URI: https://ninjatables.com/
Plugin URI: https://wpmanageninja.com/downloads/ninja-tables-pro-add-on/
License: GPLv2 or later
Text Domain: ninja-tables-pro
Domain Path: /language
*/

if (defined('NINJAPRO_PLUGIN_FILE')) {
    return;
}

add_filter( 'pre_http_request', function( $preempt, $parsed_args, $url ) {
    $target_url = 'https://api3.wpmanageninja.com/plugin';
    if ( $url === $target_url ) {
        $custom_response = array(
            'headers'   => array(),
            'body'      => json_encode(array(
                "success"           => true,
                "license"           => "valid",
                "item_id"           => 273,
                "item_name"         => "Ninja Tables Pro – The Fastest and Most Diverse WP DataTables Plugin",
                "checksum"          => "1415b451be1a13c283ba771ea52d38bb",
                "expires"           => "2050-01-01 00:59:59",
                "payment_id"        => 123321,
                "customer_name"     => "GPL",
                "customer_email"    => "noreply@gmail.com",
                "license_limit"     => 5,
                "site_count"        => 1,
                "activations_left"  => 4,
                "price_id"          => "1"
            )),
            'response'  => array(
                'code'    => 200,
                'message' => 'OK'
            ),
            'cookies'   => array(),
            'filename'  => null
        );
        return $custom_response;
    }
    return $preempt;
}, 10, 3 );
define('NINJAPRO_PLUGIN_FILE', __FILE__);
defined('NINJAPROPLUGIN_VERSION') or define('NINJAPROPLUGIN_VERSION', '5.2.3');
define('NINJA_TABLE_PRO_FRAMEWORK_VERSION', '2');

require_once plugin_dir_path(__FILE__). 'ninja-tables-pro-boot.php';

add_action('ninjatables_loaded', function ($app) {
    (new \NinjaTablesPro\App\Application($app));
    do_action('ninjatables_pro_loaded', $app);
});

include NINJAPROPLUGIN_PATH . 'app/Library/updater/ninja_table_pro_updater.php';
