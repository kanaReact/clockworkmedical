<li class="pdf_watermark_setting field_setting">
	<label class="section_label"><?php esc_html_e( 'Watermark', 'gravity-pdf-previewer' ); ?></label>
	<input type="checkbox"
		   id="pdf-watermark-setting"
		   onclick="SetPdfFieldProperty('pdfwatermarktoggle', this.checked)"/>

	<label for="pdf-watermark-setting" class="inline">
		<?php esc_html_e( 'Enable a text watermark in the PDF', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_watermark_setting' ); ?>
	</label>

	<div id="pdf_watermark_container" style="margin-top: 0.9375rem">
		<div>
			<label class="inline" for="pdf_watermark_text">
				<?php esc_html_e( 'Watermark Text', 'gravity-pdf-previewer' ); ?>
			</label>

			<input id="pdf_watermark_text" size="35" type="text"
				   onchange="SetPdfFieldProperty('pdfwatermarktext', this.value)"/>
		</div>

		<div style="padding-top: 5px">
			<label class="inline" for="pdf_watermark_font">
				<?php esc_html_e( 'Watermark Font', 'gravity-pdf-previewer' ); ?>
			</label>

			<select id="pdf_watermark_font"
					onchange="SetPdfFieldProperty('pdfwatermarkfont', this.value)"
					style="min-width:250px">

				<?php foreach ( $font_stack as $group => $fonts ): ?>
					<optgroup label="<?php echo esc_attr( $group ); ?>">
						<?php foreach ( $fonts as $font_id => $font_name ): ?>
							<option value="<?php echo esc_attr( $font_id ); ?>">
								<?php echo esc_html( $font_name ); ?>
							</option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</li>
