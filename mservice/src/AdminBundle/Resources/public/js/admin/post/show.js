$(document).ready(function () {
    if($('#deletePost').length > 0){
        $('#deletePost').click(function(e) {
            e.preventDefault(); 
            // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            var title =  "Confirm Delete Post?";
            var text = "If you click 'OK', you will delete the Post";
            var type = "warning";
            var showCancelButton = true;
            warnBeforeRedirect(linkURL, title, text, type, showCancelButton);
        });
    }
});