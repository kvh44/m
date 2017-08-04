$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
    
    if($('#deleteUser').length > 0){
        $('#deleteUser').click(function(e) {
            e.preventDefault(); 
            // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            var title =  "Confirm Delete User?";
            var text = "If you click 'OK', you will delete the User";
            var type = "warning";
            var showCancelButton = true;
            warnBeforeRedirect(linkURL, title, text, type, showCancelButton);
        });
    }
});