<?php
/*
 * CKPackager - Sample Package file
 */

header :
	'/*'																			+ '\n' +
	'Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.'	+ '\n' +
	'For licensing, see LICENSE.html or http://ckeditor.com/license'				+ '\n' +
	'*/'																			+ '\n' +
	'\n',

noCheck : false,

constants :
	{
		'CKEDITOR.ELEMENT_MODE_NONE' : 0,
		'CKEDITOR.ELEMENT_MODE_REPLACE' : 1,
		'CKEDITOR.ELEMENT_MODE_APPENDTO' : 2,
		'CKEDITOR.CTRL' : 0x110000,
		'CKEDITOR.SHIFT' : 0x220000,
		'CKEDITOR.ALT' : 0x440000,
		'CKEDITOR.NODE_ELEMENT' : 1,
		'CKEDITOR.NODE_DOCUMENT' : 9,
		'CKEDITOR.NODE_TEXT' : 3,
		'CKEDITOR.NODE_COMMENT' : 8,
		'CKEDITOR.NODE_DOCUMENT_FRAGMENT' : 11,
		'CKEDITOR.POSITION_IDENTICAL' : 0,
		'CKEDITOR.POSITION_DISCONNECTED' : 1,
		'CKEDITOR.POSITION_FOLLOWING' : 2,
		'CKEDITOR.POSITION_PRECEDING' : 4,
		'CKEDITOR.POSITION_IS_CONTAINED' : 8,
		'CKEDITOR.POSITION_CONTAINS' : 16,
		'CKEDITOR.ENTER_P' : 1,
		'CKEDITOR.ENTER_BR' : 2,
		'CKEDITOR.ENTER_DIV' : 3,
		'CKEDITOR.TRISTATE_ON' : 1,
		'CKEDITOR.TRISTATE_OFF' : 2,
		'CKEDITOR.TRISTATE_DISABLED' : 0,
		'CKEDITOR.POSITION_AFTER_START' : 1,
		'CKEDITOR.POSITION_BEFORE_END' : 2,
		'CKEDITOR.POSITION_BEFORE_START' : 3,
		'CKEDITOR.POSITION_AFTER_END' : 4,
		'CKEDITOR.ENLARGE_ELEMENT' : 1,
		'CKEDITOR.ENLARGE_BLOCK_CONTENTS' : 2,
		'CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS' : 3,
		'CKEDITOR.START' : 1,
		'CKEDITOR.END' : 2,
		'CKEDITOR.STARTEND' : 3,
		'CKEDITOR.SHRINK_ELEMENT' : 1,
		'CKEDITOR.SHRINK_TEXT' : 2,
		'CKEDITOR.UI_BUTTON' : '\'button\'',
		'CKEDITOR.DIALOG_RESIZE_NONE' : 0,
		'CKEDITOR.DIALOG_RESIZE_WIDTH' : 1,
		'CKEDITOR.DIALOG_RESIZE_HEIGHT' : 2,
		'CKEDITOR.DIALOG_RESIZE_BOTH' : 3,
		'CKEDITOR.VALIDATE_OR' : 1,
		'CKEDITOR.VALIDATE_AND' : 2,
		'CKEDITOR.STYLE_BLOCK' : 1,
		'CKEDITOR.STYLE_INLINE' : 2,
		'CKEDITOR.STYLE_OBJECT' : 3,
		'CKEDITOR.UI_PANELBUTTON' : '\'panelbutton\'',
		'CKEDITOR.SELECTION_NONE' : 1,
		'CKEDITOR.SELECTION_TEXT' : 2,
		'CKEDITOR.SELECTION_ELEMENT' : 3,
		'CKEDITOR.UI_RICHCOMBO' : '\'richcombo\'',
		'CKEDITOR.UI_MENUBUTTON' : '\'menubutton\'',
		'CKEDITOR.UI_PANEL' : '\'panel\''
	},

packages :
	[
		{
			output : 'ckeditor_basic.js',
			wrap : true,
			files :
				[
					'_source/core/ckeditor_base.js',
					'_source/core/event.js',
					'_source/core/editor_basic.js',
					'_source/core/env.js',
					'_source/core/ckeditor_basic.js'
				]
		},

		{
			output : 'ckeditor.js',
			wrap : true,
			files :
				[
					'_source/core/ckeditor_base.js',
					'_source/core/event.js',
					'_source/core/editor_basic.js',
					'_source/core/env.js',
					'_source/core/ckeditor_basic.js',
					'_source/core/dom.js',
					'_source/core/tools.js',
					'_source/core/dtd.js',
					'_source/core/dom/event.js',
					'_source/core/dom/domobject.js',
					'_source/core/dom/window.js',
					'_source/core/dom/document.js',
					'_source/core/dom/node.js',
					'_source/core/dom/nodelist.js',
					'_source/core/dom/element.js',
					'_source/core/command.js',
					'_source/core/config.js',
					'_source/core/focusmanager.js',
					'_source/core/lang.js',
					'_source/core/scriptloader.js',
					'_source/core/resourcemanager.js',
					'_source/core/plugins.js',
					'_source/core/skins.js',
					'_source/core/themes.js',
					'_source/core/ui.js',
					'_source/core/editor.js',
					'_source/core/htmlparser.js',
					'_source/core/htmlparser/comment.js',
					'_source/core/htmlparser/text.js',
					'_source/core/htmlparser/cdata.js',
					'_source/core/htmlparser/fragment.js',
					'_source/core/htmlparser/element.js',
					'_source/core/htmlparser/filter.js',
					'_source/core/htmlparser/basicwriter.js',
					'_source/core/ckeditor.js',
					'_source/core/dom/comment.js',
					'_source/core/dom/elementpath.js',
					'_source/core/dom/text.js',
					'_source/core/dom/documentfragment.js',
					'_source/core/dom/walker.js',
					'_source/core/dom/range.js',
					'_source/core/dom/rangelist.js',
					'_source/core/_bootstrap.js',
					'_source/skins/kama/skin.js',
//					'_source/lang/en.js',
					'_source/plugins/about/plugin.js',
					'_source/plugins/a11yhelp/plugin.js',
					'_source/plugins/basicstyles/plugin.js',
					'_source/plugins/bidi/plugin.js',
					'_source/plugins/blockquote/plugin.js',
					'_source/plugins/button/plugin.js',
					'_source/plugins/clipboard/plugin.js',
					'_source/plugins/colorbutton/plugin.js',
					'_source/plugins/colordialog/plugin.js',
					'_source/plugins/contextmenu/plugin.js',
					'_source/plugins/dialogadvtab/plugin.js',
					'_source/plugins/div/plugin.js',
					'_source/plugins/elementspath/plugin.js',
					'_source/plugins/enterkey/plugin.js',
					'_source/plugins/entities/plugin.js',
					'_source/plugins/filebrowser/plugin.js',
					'_source/plugins/find/plugin.js',
					'_source/plugins/flash/plugin.js',
					'_source/plugins/font/plugin.js',
					'_source/plugins/format/plugin.js',
					'_source/plugins/forms/plugin.js',
					'_source/plugins/horizontalrule/plugin.js',
					'_source/plugins/htmldataprocessor/plugin.js',
					'_source/plugins/iframe/plugin.js',
					'_source/plugins/image/plugin.js',
					'_source/plugins/indent/plugin.js',
					'_source/plugins/justify/plugin.js',
					'_source/plugins/keystrokes/plugin.js',
					'_source/plugins/link/plugin.js',
					'_source/plugins/list/plugin.js',
					'_source/plugins/liststyle/plugin.js',
					'_source/plugins/maximize/plugin.js',
					'_source/plugins/newpage/plugin.js',
					'_source/plugins/pagebreak/plugin.js',
					'_source/plugins/pastefromword/plugin.js',
					'_source/plugins/pastetext/plugin.js',
					'_source/plugins/popup/plugin.js',
					'_source/plugins/preview/plugin.js',
					'_source/plugins/print/plugin.js',
					'_source/plugins/removeformat/plugin.js',
					'_source/plugins/resize/plugin.js',
					'_source/plugins/save/plugin.js',
					'_source/plugins/scayt/plugin.js',
					'_source/plugins/smiley/plugin.js',
					'_source/plugins/showblocks/plugin.js',
					'_source/plugins/showborders/plugin.js',
					'_source/plugins/sourcearea/plugin.js',
					'_source/plugins/stylescombo/plugin.js',
					'_source/plugins/table/plugin.js',
					'_source/plugins/tabletools/plugin.js',
					'_source/plugins/specialchar/plugin.js',
					'_source/plugins/tab/plugin.js',
					'_source/plugins/templates/plugin.js',
					'_source/plugins/toolbar/plugin.js',
					'_source/plugins/undo/plugin.js',
					'_source/plugins/wysiwygarea/plugin.js',
					'_source/plugins/wsc/plugin.js',
					'_source/plugins/dialog/plugin.js',
					'_source/plugins/styles/plugin.js',
					'_source/plugins/domiterator/plugin.js',
					'_source/plugins/panelbutton/plugin.js',
					'_source/plugins/floatpanel/plugin.js',
					'_source/plugins/menu/plugin.js',
					'_source/plugins/editingblock/plugin.js',
					'_source/plugins/selection/plugin.js',
					'_source/plugins/fakeobjects/plugin.js',
					'_source/plugins/richcombo/plugin.js',
					'_source/plugins/htmlwriter/plugin.js',
					'_source/plugins/menubutton/plugin.js',
					'_source/plugins/dialogui/plugin.js',
					'_source/plugins/panel/plugin.js',
					'_source/plugins/listblock/plugin.js',
					'_source/themes/default/theme.js'
				]
		}

	]
