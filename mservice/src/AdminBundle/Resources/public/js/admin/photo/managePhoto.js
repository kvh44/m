

$(document).ready(function () {
    
    jQuery('#choose_profile_photo').show();

    var profileUploader = initiateUploader('choose_profile_photo', true, 1, '<div>profile</div>');
    var userUploader = initiateUploader('choose_user_photo', true, 2, '<div>user</div>');
    
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green hidden',
        radioClass: 'iradio_square-green',
    });
    
    $('button#batch-edit-button').click(function(){
        if($('.photo').parent().hasClass('hidden')){
            $('.photo').parent().removeClass('hidden');
            $('button#delete-button').removeClass('hidden');
        } else {
            $('.photo').parent().addClass('hidden');
            $('button#delete-button').addClass('hidden');
        }
    });
    
    
    
    $('button#delete-button').click(function(){
        var checked_photo = $("input:checked");
        console.log(checked_photo);
    });
    
});

var initiateUploader = function(buttonId, isLocal, photoType, uploadButtonHtml){
    return new qq.FineUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: document.getElementById(buttonId),
        request: {
            endpoint: urlUploadPhoto,
            params: {internal_token: internalToken, internal_id: internalId, type: photoType, is_local: isLocal}
        },
        validation: {
            allowedExtensions: ["jpeg", "jpg", "bmp", "gif", "png", "png8", "png24"],
            sizeLimit: 2000 * 1024
        },
        messages: {
            typeError: 'type error',
            sizeError: 'size error',
            emptyError: 'empty error',
            noFilesError: 'no file error',
            onLeave: 'leave error',
        },
        template: 'uploader-template',
        /*
		classes: {
		  buttonFocus: 'upload-button-focus',
		  buttonHover: 'upload-button-hover'
		},
        */
		/*
		thumbnails: {
		  placeholders: {
			notAvailablePath: "/bundles/front/fine-uploader/placeholders/not_available-generic.png",
			waitingPath: "/bundles/front/fine-uploader/placeholders/waiting-generic.png"
		  }
		},
		*/
        text: {
            fileInputTitle: uploadButtonHtml,
            formatProgress: '{percent}%'
        },
        multiple: false,
        disableCancelForFormUploads: true,
        maxConnections: 1,
        callbacks: {
            onComplete: function (id, fileName, responseJSON) {
                //console.log(responseJSON);
                $(location).attr('href', window.location.href);
            }
        }
    });
}