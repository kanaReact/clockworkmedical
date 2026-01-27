<?php
/* Plugin Name: Theme Addons Page */

/*
See ./license.txt for copyright and licensing information applicable to this file and other files and subdirectories in the same directory.
This file contains code copied from WordPress.
This file was modified by Jonathan Hall, Anna Kurowska, and/or others.
Last modified 2020-11-23
*/

// The following line should be added to the theme
// define('AGS_Export_Order_Items_Pro_Addons_URL', 'https://wpzone.co/wp-content/uploads/product-addons/divi-ecommerce.json');

if ( defined('AGS_Export_Order_Items_Pro_Addons_URL') ) {

	class AGS_Export_Order_Items_Pro_Addons {

		static function outputList() {

			load_plugin_textdomain( 'ags-export-order-items-pro-addons', false,__DIR__.'/languages' );
			
			$addonsJson = wp_remote_retrieve_body(
				wp_remote_get(
					add_query_arg('locale', get_locale(), AGS_Export_Order_Items_Pro_Addons_URL)
				)
			);
			
			if ( $addonsJson ) {
				$addons = json_decode($addonsJson);
			}
			
			if ( empty($addons) ) {
				echo(
					'<p class="ags-settings-addons-error">'
					.esc_html__('We are unable to load the addons for this plugin right now. Please try again later!', 'ags-export-order-items-pro-addons')
					.'</p>'
				);
				return;
			}
			
			echo('<div class="ags-settings-addons-list">');
			
			foreach ($addons as $addon) {
				self::outputListItem($addon);
			}
			
			echo('</div>');
			
		}
		
		static function outputListItem($addon) { ?>
		
			<div class="ags-settings-addon">
				<div class="ags-settings-addon-info">
					<img src="<?php echo esc_url(
								empty($addon->thumbnail)
									? plugin_dir_url(__FILE__) . 'images/placeholder.jpg'
									: $addon->thumbnail
								); ?>" alt="<?php echo( esc_attr($addon->name) ); ?>" class="ags-settings-addon-img">
					<h4>
						<?php
							echo( esc_html($addon->name) );
							
							if ( !empty($addon->badges) ) {
								foreach ( $addon->badges as $badge ) {
									printf(
										'<span class="ags-settings-addon-badge" style="color: %s; background-color: %s;">%s</span>',
										esc_attr(sanitize_hex_color( $badge->textColor )),
										esc_attr(sanitize_hex_color( $badge->bgColor )),
										esc_html($badge->label)
									);
								}
							}
						?>
					</h4>
					<p><?php echo( esc_html($addon->description) ); ?></p>
				</div>
				<?php self::outputListItemLink($addon); ?>
			</div>
			
		<?php }
		
		
		static function outputListItemLink($addon) {
			
			$classes = 'ags-settings-addon-btn';
			
			switch ($addon->type) {
				
				case 'repo':
					// from (modified) wp-admin/includes/plugin-install.php
					$installed_plugin = get_plugins( '/' . $addon->target );
					if ( empty( $installed_plugin ) ) {
						$label = __('Install', 'ags-export-order-items-pro-addons');
						if ( current_user_can( 'install_plugins' ) ) {
							$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $addon->target ), 'install-plugin_' . $addon->target );
							$onclick_escaped = '';
						} else {
							$url = '#';
							$onclick_escaped = ' onclick="alert(\''.esc_js( __('You do not have sufficient permissions to install plugins. If you think this message is in error, go to Plugins > Add New and search for the plugin name.', 'ags-export-order-items-pro-addons') ).'\');return false;"';
						}
					} else {
						$label = __('Already Installed', 'ags-export-order-items-pro-addons');
						$classes .= ' ags-settings-addon-btn-disabled';
						$url = '#';
						$onclick_escaped = ' onclick="return false;"';
					}
					break;
				
				case 'link':
					$label = $addon->buttonLabel;
					$url = $addon->target;
					$onclick_escaped = '';
					break;
				
				default:
					return;
				
			}
			

			printf(
				'<a href="%s" target="_blank" class="%s"%s>%s</a>',
				esc_url($url),
				esc_attr($classes),
				$onclick_escaped, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html($label)
			);
		}
	}

	// Code after this line is for testing purposes
	/*
	add_action('admin_menu', function() {
		add_menu_page( 'Addons Test', 'Addons Test', 'manage_options', 'ags-export-order-items-pro-addons', [ 'AGS_Product_Carousel_Addons', 'outputList' ] );
	});
	*/

} // this must be at the end of the file
