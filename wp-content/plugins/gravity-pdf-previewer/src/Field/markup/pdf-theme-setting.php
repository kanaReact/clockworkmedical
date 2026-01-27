<li class="pdf_theme_setting field_setting">
	<label for="pdf_theme" class="section_label">
		<?php esc_html_e( 'Design', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_theme_setting' ); ?>
	</label>

	<select id="pdf_theme" onchange="SetPdfFieldProperty('pdftheme', this.value)" style="min-width:250px">
		<option value="auto"><?= esc_html__( 'Automatic', 'gravity-pdf-previewer' ); ?></option>
		<option value="light"><?= esc_html__( 'Light', 'gravity-pdf-previewer' ); ?></option>
		<option value="dark"><?= esc_html__( 'Dark', 'gravity-pdf-previewer' ); ?></option>
	</select>
</li>
