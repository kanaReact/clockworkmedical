<?php

/**
 * Plugin Name:     Gravity PDF Previewer
 * Plugin URI:      https://gravitypdf.com/shop/previewer-add-on/
 * Description:     Live-preview Gravity PDF documents when filling out Gravity Forms.
 * Author:          Blue Liquid Designs
 * Author URI:      https://blueliquiddesigns.com.au
 * Update URI:      https://gravitypdf.com
 * Text Domain:     gravity-pdf-previewer
 * Domain Path:     /languages
 * Version:         4.1.0
 * Requires PHP:    7.3
 * License:         GPLv3
 * License URI:     https://opensource.org/licenses/GPL-3.0
 */

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

/* If the plugin has been activated already, deactivate the original */
if ( defined( 'GFPDF_PDF_PREVIEWER_FILE' ) ) {
	deactivate_plugins( plugin_basename( GFPDF_PDF_PREVIEWER_FILE ), true );

	return;
}

define( 'GFPDF_PDF_PREVIEWER_FILE', __FILE__ );
define( 'GFPDF_PDF_PREVIEWER_VERSION', '4.1.0' );

/**
 * Class GPDF_Previewer_Checks
 *
 * @since 0.1
 */
if ( ! class_exists( 'GPDF_Previewer_Checks' ) ) {
	class GPDF_Previewer_Checks {

		/**
		 * Holds any blocker error messages stopping plugin running
		 *
		 * @var array
		 *
		 * @since 0.1
		 */
		private $notices = [];

		/**
		 * @var string
		 *
		 * @since 0.1
		 */
		private $required_gravitypdf_version = '6.0.0';

		/**
		 * Run our pre-checks and if it passes bootstrap the plugin
		 *
		 * @return void
		 *
		 * @since 0.1
		 */
		public function init() {

			/* Test the minimum version requirements are met */
			$this->check_gravitypdf_version();

			/* Check if any errors were thrown, enqueue them and exit early */
			if ( count( $this->notices ) > 0 ) {
				/* Only display on the plugin admin page */
				global $pagenow;
				if ( is_admin() && $pagenow === 'plugins.php' ) {
					add_action( 'admin_notices', [ $this, 'display_notices' ] );
				}

				return;
			}

			add_action(
				'gfpdf_fully_loaded',
				function() {
					require_once __DIR__ . '/src/bootstrap.php';
				}
			);
		}

		/**
		 * Check if the current version of Gravity PDF is compatible with this add-on
		 *
		 * @return bool
		 *
		 * @since 0.1
		 */
		public function check_gravitypdf_version() {

			/* Check if the Gravity PDF Minimum version requirements are met */
			if ( defined( 'PDF_EXTENDED_VERSION' ) &&
			 version_compare( PDF_EXTENDED_VERSION, $this->required_gravitypdf_version, '>=' )
			) {
				return true;
			}

			$slug    = 'gravity-forms-pdf-extended/pdf.php';
			$plugins = function_exists( 'get_plugins' ) ? get_plugins() : [];
			foreach ( $plugins as $plugin_slug => $plugin ) {
				if ( ( $plugin['TextDomain'] ?? '' ) === 'gravity-forms-pdf-extended' ) {
					$slug = $plugin_slug;
					break;
				}
			}

			if ( defined( 'PDF_EXTENDED_VERSION' ) ) {
				/* Installed, but running unsupported version */
				$action          = $this->get_upgrade_action( $slug );
				$this->notices[] = sprintf( esc_html__( '%1$s version %2$s or higher is required to use this add-on.', 'gravity-pdf-previewer' ), 'Gravity PDF', $this->required_gravitypdf_version ) . ' ' . $action;
			} elseif ( isset( $plugins[ $slug ] ) ) {
				/* Installed, but not activated */
				$action          = $this->get_activation_action( $slug );
				$this->notices[] = sprintf( esc_html__( '%s is required to use this add-on.', 'gravity-pdf-previewer' ), 'Gravity PDF' ) . ' ' . $action;
			} else {
				/* Not installed */
				if ( current_user_can( 'install_plugins' ) ) {
					$slug   = 'gravity-forms-pdf-extended';
					$url    = admin_url( 'update.php?action=install-plugin&plugin=' . rawurlencode( $slug ) );
					$url    = wp_nonce_url( $url, 'install-plugin_' . $slug );
					$action = sprintf( '<a href="%s">' . esc_html__( 'Install the plugin to continue.', 'gravity-pdf-previewer' ) . '</a>', esc_url( $url ) );
				} else {
					$url    = 'https://wordpress.org/plugins/gravity-forms-pdf-extended/';
					$action = sprintf( '<a href="%s">' . esc_html__( 'Contact your Site Administrator to install the plugin from the WordPress.org repository.', 'gravity-pdf-previewer' ) . '</a>', esc_url( $url ) );
				}

				$this->notices[] = sprintf( __( '%s is required to use this add-on.', 'gravity-pdf-previewer' ), 'Gravity PDF' ) . ' ' . $action;
			}
		}

		/**
		 * Create a plugin activation link with an appropriate message, if the user has permission
		 *
		 * @param string $slug
		 *
		 * @return string
		 *
		 * @since 4.1
		 */
		protected function get_activation_action( string $slug ): string {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return __( 'Contact your Site Administrator to active the plugin.', 'gravity-pdf-previewer' );
			}

			$url = admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $slug ) . '&plugin_status=all&paged=1&s' );
			$url = wp_nonce_url( $url, 'activate-plugin_' . $slug );

			return sprintf( '<a href="%s">' . __( 'Activate the plugin to continue.', 'gravity-pdf-previewer' ) . '</a>', esc_url( $url ) );
		}

		/**
		 * Create a plugin upgrade link with an appropriate message, if the user has permission
		 *
		 * @param string $slug
		 *
		 * @return string
		 *
		 * @since 4.1
		 */
		protected function get_upgrade_action( string $slug ): string {
			if ( ! current_user_can( 'update_plugins' ) ) {
				return __( 'Contact your Site Administrator to upgrade the plugin to the latest version.', 'gravity-pdf-previewer' );
			}

			$url = admin_url( 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $slug ) );
			$url = wp_nonce_url( $url, 'upgrade-plugin_' . $slug );

			return sprintf( '<a href="%s">' . __( 'Upgrade the plugin to continue.', 'gravity-pdf-previewer' ) . '</a>', esc_url( $url ) );
		}

		/**
		 * Helper function to easily display error messages
		 *
		 * @return void
		 *
		 * @since 0.1
		 */
		public function display_notices() {
			?>
		<div class="error">
			<p>
				<strong><?php esc_html_e( 'Gravity PDF Previewer Installation Problem', 'gravity-pdf-previewer' ); ?></strong>
			</p>

			<ul style="padding-bottom: 0.5em">
				<?php foreach ( $this->notices as $notice ): ?>
					<li style="padding-left: 20px;list-style: inside"><?php echo wp_kses_post( $notice ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
			<?php
		}
	}
}

/* Initialise the software */
add_action(
	'plugins_loaded',
	function() {
		( new GPDF_Previewer_Checks() )->init();
	}
);
