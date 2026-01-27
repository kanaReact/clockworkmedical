<?php
/**
 * @var string $html_id
 * @var array  $settings
 */

$theme          = $settings['theme'] === 'dark' ? 'dark-mode' : '';
$watermark_text = $settings['watermarkText'];

/* fake the scale */
$scale = 1.33;
$zoom  = $settings['zoomLevel'];
switch ( $zoom ) {
	case 'page-width':
		$scale = 1.25;
		break;

	case 'page-fit':
		$scale = 0.5;
		break;

	case 'page-actual':
		// do nothing
		break;

	default:
		$scale *= $zoom;
}

$css_variables = [
	'--gpdf-prev-viewer-container-height' => $settings['height'] . 'px',
	'--gpdf-prev-download'                => $settings['download'] === true ? 'block' : 'none',
	'--gpdf-prev-page-width'              => 600,
	'--gpdf-prev-page-height'             => 850,
	'--gpdf-prev-scale-factor'            => $scale,
	'--gpdf-prev-watermark'               => $settings['watermark'] ? 'block' : 'none',
];

$css_variables_prepared_for_style_attribute = '';
foreach ( $css_variables as $key => $value ) {
	$css_variables_prepared_for_style_attribute .= $key . ': ' . $value . ';';
}

?>

<div
		id="<?= esc_attr( 'gpdf_' . $html_id ); ?>"
		class="gpdf-previewer-wrapper gform-theme__no-reset--children <?php echo esc_attr( $theme ); ?>"
		style="<?php echo esc_attr( $css_variables_prepared_for_style_attribute ); ?> min-height: var(--gpdf-prev-viewer-container-height);"
>
	<section class="main-wrapper">
		<div class="screen-reader-text"><?php esc_html_e( 'A preview of the Gravity PDF document viewer', 'gravity-pdf-previewer' ); ?></div>

		<div style="height: var(--gpdf-prev-viewer-container-height);" class="outer-container">
			<div class="toolbar">
				<div class="toolbar-container">
					<div class="toolbar-viewer">
						<div class="toolbar-viewer-left">
							<div class="split-toolbar-button hidden-medium-small-view">
								<button type="button" title="<?php esc_attr_e( 'Previous Page', 'gravity-pdf-previewer' ); ?>" class="toolbar-button previous gform-theme-no-framework" disabled>
									<span class="screen-reader-text"><?php esc_html_e( 'Previous Page', 'gravity-pdf-previewer' ); ?></span>
								</button>

								<div class="split-toolbar-button-separator"></div>

								<button type="button" title="<?php esc_attr_e( 'Next Page', 'gravity-pdf-previewer' ); ?>" class="toolbar-button next gform-theme-no-framework" disabled>
									<span class="screen-reader-text"><?php esc_html_e( 'Next Page', 'gravity-pdf-previewer' ); ?></span>
								</button>
							</div>

							<input title="<?php printf( esc_attr__( 'Set Page Number. Maximum value %s', 'gravity-pdf-previewer' ), '1' ); ?>"
								   aria-label="<?php printf( esc_attr__( 'Set Page Number. Maximum value %s', 'gravity-pdf-previewer' ), '1' ); ?>"
								   class="toolbar-field page-number gform-theme-no-framework"
								   value="1">
						</div>

						<div class="toolbar-viewer-right hidden-xx-small-view">
							<div class="split-toolbar-button">
								<button type="button" title="<?php esc_attr_e( 'Download', 'gravity-pdf-previewer' ); ?>" class="toolbar-button download gform-theme-no-framework" disabled style="display: var(--gpdf-prev-download);">
									<span class="screen-reader-text"><?php esc_html_e( 'Download', 'gravity-pdf-previewer' ); ?></span>
								</button>

								<button type="button" title="<?php esc_attr_e( 'Refresh', 'gravity-pdf-previewer' ); ?>" class="toolbar-button refresh gform-theme-no-framework" disabled>
									<span class="screen-reader-text"><?php esc_html_e( 'Refresh', 'gravity-pdf-previewer' ); ?></span>
								</button>
							</div>
						</div>

						<div class="toolbar-viewer-middle hidden-x-small-view">
							<div class="split-toolbar-button">
								<button type="button" title="<?php esc_attr_e( 'Zoom Out', 'gravity-pdf-previewer' ); ?>" class="toolbar-button zoom-out gform-theme-no-framework" disabled>
									<span class="screen-reader-text"><?php esc_html_e( 'Zoom Out', 'gravity-pdf-previewer' ); ?></span>
								</button>

								<div class="split-toolbar-button-separator"></div>

								<button type="button" title="<?php esc_attr_e( 'Zoom In', 'gravity-pdf-previewer' ); ?>" class="toolbar-button zoom-in gform-theme-no-framework" disabled>
									<span class="screen-reader-text"><?php esc_html_e( 'Zoom In', 'gravity-pdf-previewer' ); ?></span>
								</button>
							</div>

							<span class="dropdown-toolbar-button hidden-small-view">
								<select aria-label="<?php esc_attr_e( 'Zoom level', 'gravity-pdf-previewer' ); ?>" class="scale-select gform-theme-no-framework">
									<option value="page-actual" disabled><?php esc_html_e( 'Actual Size', 'gravity-pdf-previewer' ); ?></option>
									<option value="page-fit" disabled><?php esc_html_e( 'Page Fit', 'gravity-pdf-previewer' ); ?></option>
									<option value="page-width" disabled><?php esc_html_e( 'Page Width', 'gravity-pdf-previewer' ); ?></option>
									<option value="0.5" disabled><?php esc_html_e( '50%', 'gravity-pdf-previewer' ); ?></option>
									<option value="0.75" disabled><?php esc_html_e( '75%', 'gravity-pdf-previewer' ); ?></option>
									<option value="1" disabled><?php esc_html_e( '100%', 'gravity-pdf-previewer' ); ?></option>
									<option value="1.25" disabled><?php esc_html_e( '125%', 'gravity-pdf-previewer' ); ?></option>
									<option value="1.5" disabled><?php esc_html_e( '150%', 'gravity-pdf-previewer' ); ?></option>
									<option value="2" disabled><?php esc_html_e( '200%', 'gravity-pdf-previewer' ); ?></option>
									<option value="3" disabled><?php esc_html_e( '300%', 'gravity-pdf-previewer' ); ?></option>
									<option value="4" disabled><?php esc_html_e( '400%', 'gravity-pdf-previewer' ); ?></option>
								</select>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div tabindex="0" class="viewer-container">
				<div class="pdf-viewer" style="">
					<div class="page"
						 data-page-number="1"
						 role="region"
						 data-l10n-id="pdfjs-page-landmark"
						 style="width: round(var(--gpdf-prev-scale-factor) * calc(var(--gpdf-prev-page-width) * 1px), 1px); height: round(var(--gpdf-prev-scale-factor) * calc(var(--gpdf-prev-page-height) * 1px), 1px);"
						 data-loaded="true"
						 aria-label="<?php printf( esc_attr__( 'Page %s', 'gravity-pdf-previewer' ), '1' ); ?>">
						<div class="canvasWrapper" style="padding: 10%; text-align: left">
							<div style="font-weight; 700; font-size: calc(175% * var(--gpdf-prev-scale-factor))">
								<?php esc_html_e( 'Gravity PDF', 'gravity-pdf-previewer' ); ?>
							</div>

							<div style="font-weight; 500; font-size: calc(150% * var(--gpdf-prev-scale-factor)); margin-top: 4%">
								<?php esc_html_e( 'Previewer Field Example', 'gravity-pdf-previewer' ); ?>
							</div>

							<p style="font-size: calc(100% * var(--gpdf-prev-scale-factor));">
								<?php echo esc_html__( 'This is a content placeholder. The paper size, orientation, fonts, watermark, and layout will vary (as defined in your PDF settings).', 'gravity-pdf-previewer' ); ?>
							</p>
						</div>

						<div class="pdf-page-watermark" style="display: var(--gpdf-prev-watermark); position: absolute; left: 0; right: 0; bottom: 45%; transform: rotate(-55deg); font-size: calc(600% * var(--gpdf-prev-scale-factor)); font-weight: 500; color: rgba(0, 0, 0, 0.1); white-space: nowrap;">
							<?php echo esc_html( $watermark_text ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>