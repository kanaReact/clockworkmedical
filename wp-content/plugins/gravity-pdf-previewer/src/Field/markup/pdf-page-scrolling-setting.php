<li class="pdf_page_scrolling_setting field_setting">
	<label for="pdf_page_scrolling" class="section_label">
		<?php esc_html_e( 'Page Scrolling', 'gravity-pdf-previewer' ); ?>
		<?php gform_tooltip( 'pdf_page_scrolling_setting' ); ?>
	</label>

	<select id="pdf_page_scrolling" onchange="SetPdfFieldProperty('pdfpagescrolling', this.value)" style="min-width:250px">
		<option value="vertical"><?= esc_html__( 'Vertical', 'gravity-pdf-previewer' ); ?></option>
		<option value="horizontal"><?= esc_html__( 'Horizontal', 'gravity-pdf-previewer' ); ?></option>
	</select>
</li>
