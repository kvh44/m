

$(document).ready(function () {

    jQuery('#choose_profile_photo').show();

    var galleryUploader = new qq.FineUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: document.getElementById('choose_profile_photo'),
        request: {
            endpoint: urlUploadPhoto,
            params: {internal_token: internalToken, internal_id: internalId, type: 1, is_local: true}
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
        text: {
            uploadButton: '<i class="icon-cloud icon-white"></i> uploader',
            formatProgress: '{percent}%'
        },
        multiple: false,
        disableCancelForFormUploads: true,
        maxConnections: 1,
        callbacks: {
            onComplete: function (id, fileName, responseJSON) {
                console.log(responseJSON);
            }
        }
    });

});