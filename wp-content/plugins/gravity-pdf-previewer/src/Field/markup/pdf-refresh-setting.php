<li class="pdf_refresh_setting field_setting">
	<label class="section_label"><?php esc_html_e( 'Refresh', 'gravity-pdf-previewer' ); ?></label>

	<input type="checkbox"
		   id="pdf-automatic-refresh-setting"
		   onclick="SetPdfFieldProperty('pdfautomaticrefresh', this.checked)" />

	<label for="pdf-automatic-refresh-setting" class="inline">
		<?php esc_html_e( 'Disable automatic refresh', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_automatic_refresh_setting' ); ?>
	</label>
</li>
