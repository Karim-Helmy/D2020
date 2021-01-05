var initSample = (function () {
  var wysiwygareaAvailable = isWysiwygareaAvailable(),
    isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

  return function () {
    var editorElement = CKEDITOR.document.getById('editor');

    // :(((
    if (isBBCodeBuiltIn) {
      editorElement.setHtml(
        'Hello world!\n\n' +
        'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
      );
    }

    // Depending on the wysiwygarea plugin availability initialize classic or inline editor.
    if (wysiwygareaAvailable) {
      CKEDITOR.replace('editor');
    } else {
      editorElement.setAttribute('contenteditable', 'true');
      CKEDITOR.inline('editor');

      // TODO we can consider displaying some info box that
      // without wysiwygarea the classic editor may not work.
    }
  };

  function isWysiwygareaAvailable() {
    // If in development mode, then the wysiwygarea must be available.
    // Split REV into two strings so builder does not replace it :D.
    if (CKEDITOR.revision == ('%RE' + 'V%')) {
      return true;
    }

    return !!CKEDITOR.plugins.get('wysiwygarea');
  }
})();