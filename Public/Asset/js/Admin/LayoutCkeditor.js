$(function() {
  function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    var match = window.location.search.match(reParam);
    return match && match.length > 1 ? match[1] : null;
  }
  $('#imageBrowse > *[data-bgurl]').click(function() {
    var funcNum = getUrlParam('CKEditorFuncNum'),
        url = $(this).data('bgurl');
    
    window.opener.CKEDITOR.tools.callFunction(funcNum, url, function() {
      var dialog = this.getDialog();

      if (dialog.getName() == 'image') {
        var element = dialog.getContentElement('info', 'txtAlt');
        if (element) element.setValue('');
      }
      return url && url.length ? true : false;
    });
    window.close();
  });
});