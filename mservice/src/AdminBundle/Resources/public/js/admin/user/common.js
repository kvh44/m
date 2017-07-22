

function warnBeforeDelete(linkURL) {
    swal({
      title: "Confirm Delete?", 
      text: "If you click 'OK', you will delete it", 
      type: "warning",
      showCancelButton: true
    }, function() {
      window.location.href = linkURL;
    });
}