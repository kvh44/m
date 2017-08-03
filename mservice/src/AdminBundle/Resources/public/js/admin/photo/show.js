$(document).ready(function () {
    if($('#deletePhoto').length > 0){
        $('#deletePhoto').click(function(e) {
            e.preventDefault(); 
            // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            var title =  "Confirm Delete Photo?";
            var text = "If you click 'OK', you will delete the photo";
            var type = "warning";
            var showCancelButton = true;
            warnBeforeDelete(linkURL, title, text, type, showCancelButton);
        });
    }
});