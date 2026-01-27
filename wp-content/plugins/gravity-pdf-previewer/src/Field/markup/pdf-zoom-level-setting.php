<li class="pdf_zoom_level_setting field_setting">
	<label for="pdf_zoom_level" class="section_label">
		<?php esc_html_e( 'Zoom Level', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_zoom_level_setting' ); ?>
	</label>

	<select id="pdf_zoom_level" onchange="SetPdfFieldProperty('pdfzoomlevel', this.value)" style="min-width:250px">
		<option value="page-width"><?= esc_html__( 'Page Width', 'gravity-pdf-previewer' ); ?></option>
		<option value="page-fit"><?= esc_html__( 'Page Fit', 'gravity-pdf-previewer' ); ?></option>
		<option value="page-actual"><?= esc_html__( 'Actual Size', 'gravity-pdf-previewer' ); ?></option>
		<option value="0.5"><?= esc_html__( '50%', 'gravity-pdf-previewer' ); ?></option>
		<option value="0.75"><?= esc_html__( '75%', 'gravity-pdf-previewer' ); ?></option>
		<option value="1"><?= esc_html__( '100%', 'gravity-pdf-previewer' ); ?></option>
		<option value="1.25"><?= esc_html__( '125%', 'gravity-pdf-previewer' ); ?></option>
		<option value="1.5"><?= esc_html__( '150%', 'gravity-pdf-previewer' ); ?></option>
		<option value="2"><?= esc_html__( '200%', 'gravity-pdf-previewer' ); ?></option>
		<option value="3"><?= esc_html__( '300%', 'gravity-pdf-previewer' ); ?></option>
		<option value="4"><?= esc_html__( '400%', 'gravity-pdf-previewer' ); ?></option>
	</select>
</li>
