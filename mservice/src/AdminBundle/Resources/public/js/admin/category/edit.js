$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
    
    if($('#deleteCategory').length > 0){
        $('#deleteCategory').click(function(e) {
            e.preventDefault(); 
            // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            var title =  "Confirm Delete The Category?";
            var text = "If you click 'OK', you will delete the Category";
            var type = "warning";
            var showCancelButton = true;
            warnBeforeRedirect(linkURL, title, text, type, showCancelButton);
        });
    }
});