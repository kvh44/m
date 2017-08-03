

function warnBeforeDelete(linkURL, title = "Confirm Delete?", text = "If you click 'OK', you will delete it", type = "warning", showCancelButton = true) {
    swal({
      title: title, 
      text: text, 
      type: type,
      showCancelButton: showCancelButton
    }, function() {
      window.location.href = linkURL;
    });
}