/*global dotclear, CKEDITOR */
'use strict';

{
	CKEDITOR.dialog.add('acronymDialog', function(editor) {
		return {
			title: dotclear.getData('editor_acronyms',false).title,
			minWidth: 400,
			minHeight: 150,
			contents: [
				{
					id: 'tab-acronym',
					elements: [
						{
							id: 'acronym',
							type: 'text',
							label: dotclear.getData('editor_acronyms',false).title,
							validate: CKEDITOR.dialog.validate.notEmpty('Acronym cannot be empty.'),
							setup: function(element) {
								this.setValue(element.getText());
							},
							commit: function( element ) {
								element.setText(this.getValue());
							}
						},
						{
							id: 'label',
							type: 'text',
							label: 'Label',
							setup: function(element) {
								this.setValue(element.getAttribute('title'));
							},
							commit: function( element ) {
								element.setAttribute('title', this.getValue());
							}
						}
					]
				}
			],
			// Invoked when the dialog is loaded.
			onShow: function() {
				var selection = editor.getSelection();
				var element = selection.getStartElement();
				if (element) {
					element = element.getAscendant('abbr', true);
				}

				if (!element || element.getName() != 'abbr') {
					element = editor.document.createElement( 'abbr' );
					this.insertMode = true;
				} else {
					this.insertMode = false;
				}
				this.element = element;
				if (!this.insertMode) {
					this.setupContent(this.element);
				}
			},
			onOk: function() {
				var abbr = this.element;
				this.commitContent(abbr);
				if (this.insertMode) {
					editor.insertElement(abbr);
				}
			}
		};
	});
}