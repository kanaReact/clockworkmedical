<li class="pdf_spread_setting field_setting">
	<label for="pdf_spread" class="section_label">
		<?php esc_html_e( 'Page Spread', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_spread_setting' ); ?>
	</label>

	<select id="pdf_spread" onchange="SetPdfFieldProperty('pdfspread', this.value)" style="min-width:250px">
		<option value="none"><?= esc_html__( 'No Spread', 'gravity-pdf-previewer' ); ?></option>
		<option value="odd"><?= esc_html__( 'Odd Spread', 'gravity-pdf-previewer' ); ?></option>
		<option value="even"><?= esc_html__( 'Even Spread', 'gravity-pdf-previewer' ); ?></option>
	</select>
</li>
