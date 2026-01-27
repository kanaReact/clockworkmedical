<li class="pdf_security_setting field_setting">
	<label class="section_label"><?php esc_html_e( 'Security', 'gravity-pdf-previewer' ); ?></label>

	<div>
		<input type="checkbox"
			   id="pdf-right-click-protection-setting"
			   onclick="SetPdfFieldProperty('pdfrightclickprotection', this.checked)"/>

		<label for="pdf-right-click-protection-setting" class="inline">
			<?php esc_html_e( 'Disable right-click protection', 'gravity-pdf-previewer' ); ?>
			<?php gform_tooltip( 'pdf_right_click_protection_setting' ); ?>
		</label>
	</div>

	<div style="margin-top: 0.9375rem">
		<input type="checkbox"
			   id="pdf-text-copying-protection-setting"
			   onclick="SetPdfFieldProperty('pdftextcopyingprotection', this.checked)"/>

		<label for="pdf-text-copying-protection-setting" class="inline">
			<?php esc_html_e( 'Disable text-copying protection', 'gravity-pdf-previewer' ); ?>
			<?php gform_tooltip( 'pdf_text_copying_protection_setting' ); ?>
		</label>
	</div>
</li>
