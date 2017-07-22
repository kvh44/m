$(document).ready(function () {
    if($('#deleteUser').length > 0){
        $('#deleteUser').click(function(e) {
            e.preventDefault(); 
            // Prevent the href from redirecting directly
            var linkURL = $(this).attr("href");
            warnBeforeDelete(linkURL);
        });
    }
});