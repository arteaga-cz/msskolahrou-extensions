class DynamicSelectFieldHandler extends elementorModules.editor.utils.Module {
	onInit() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/dynamic_select', this.renderField.bind(this), 10, 4 );
	}

	renderField( inputField, item, i, settings ) {
		const itemClasses = item.css_classes || '',
			required = item.required ? 'required' : '',
			fieldName = 'form_field_',
			inputSize = settings.input_size || 'sm';

		let fieldHtml = `<div class="elementor-field elementor-select-wrapper ${itemClasses}">`;
		fieldHtml += `<select class="elementor-field-textual elementor-field elementor-size-${inputSize}" name="${fieldName}" id="form_field_${i}" ${required}>`;
		fieldHtml += '<option>Option 1</option>';
		fieldHtml += '<option>Option 2</option>';
		fieldHtml += '</select>';
		fieldHtml += '</div>';

		return fieldHtml;
	}
}

jQuery( window ).on( 'elementor/init', () => {
	new DynamicSelectFieldHandler();
} );
