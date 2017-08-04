

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
			var checked_photos = $("input:checked");

			if(checked_photos.length > 0){
				$('button#delete-button').removeClass('hidden');
				$('button#delete-button .delete-button-total').removeClass('hidden').text('('+checked_photos.length+')');
			}
            
        } else {
            $('.photo').parent().addClass('hidden');
            $('button#delete-button').addClass('hidden');
        }
    });
	
	$('.iCheck-helper').click(function(){
		console.log('g');
		var checked_photos = $("input:checked");
		
		if(checked_photos.length > 0){
			$('button#delete-button').removeClass('hidden');
			$('button#delete-button .delete-button-total').removeClass('hidden').text('('+checked_photos.length+')');
		} else {
			$('button#delete-button').addClass('hidden');
			$('button#delete-button .delete-button-total').addClass('hidden').text('');
		}
		
	});
    
    
    
    $('button#delete-button').click(function(){
        var checked_photos = $("input:checked");

		var photos_array = [];
		if(checked_photos.length > 0){
			checked_photos.each(function(index, element){
				console.log($(element).attr('id'));
				photos_array.push($(element).attr('id'));
			});
			
			$.ajax({
			  url: urlBatchDeletePhoto,
              data: {photos: photos_array},
			}).done(function(data) {

			  console.log(data);
			  console.log(window.location.href);
				var linkURL = window.location.href;
				var title =  "Delete photo successfully";
				var text = ('undefined' !== data.message)? data.message : "Deleted Successfully";
				var type = "warning";
				var showCancelButton = false;
				warnBeforeRedirect(linkURL, title, text, type, showCancelButton);
			  
			});
			
		}
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