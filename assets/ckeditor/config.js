/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.filebrowserBrowseUrl = 			'/assets/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
	   config.filebrowserImageBrowseUrl = 	'/assets/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
	   config.filebrowserFlashBrowseUrl = 	'/assets/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
	   config.filebrowserUploadUrl = 		'/assets/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
	   config.filebrowserImageUploadUrl = 	'/assets/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
	   config.filebrowserFlashUploadUrl = 	'/assets/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';

	   config.toolbar = 'Full';
 
config.toolbar_Full =
[
	{ name: 'document', items : [ 'Source' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace' ] },
	// { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
 //        'HiddenField' ] },
	// '/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'tools', items : [ 'Maximize' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','PageBreak','Iframe' ] },
	// '/',
	// { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] }
];
 
config.toolbar_Basic =
[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];
};
