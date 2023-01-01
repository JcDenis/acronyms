/*global dotclear, CKEDITOR */
'use strict';

{
	CKEDITOR.plugins.add('acronym', {
		requires: 'dialog',
		init: function(editor) {
			editor.addCommand('acronymCommand',
					  new CKEDITOR.dialogCommand('acronymDialog', { allowedContent: 'abbr[title,id]' })
					 );

			CKEDITOR.dialog.add('acronymDialog', this.path+'dialogs/popup.js');

			editor.ui.addButton("Acronym", {
				label: dotclear.getData('editor_acronyms').title,
				icon: this.path.replace('cke-addon/', '')+'icon.png',
				command: 'acronymCommand'
			});
		}
	});
}
