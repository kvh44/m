
$(document).ready(function () {

    $('#photoListTable').DataTable({
        serverSide: true,
        processing: true,
        ordering: false,
        ajax: mphotoListAjaxUrl,
        drawCallback: function (settings) {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green hidden',
                radioClass: 'iradio_square-green hidden',
            });
            triggerCheckBox();
        },
        columns: [
            {data: function (row) {
                    return '<span id="' + row.internalId + '" class="photo_id">' + row.id + '</span><input type="checkbox" class="i-checks photo hidden" id="' + row.internalId + '" value="" />'
                }
            },
            {data: function (row) {
                    var url = iconDirectory + row.userId + '/' + row.photoIcon;
                    if (typeof row.photoType !== 'undefined') {
                        switch (row.photoType) {
                            case '1':
                                url = profilePhotoDirectory + url;
                                break;
                            case '2':
                                url = userPhotoDirectory + url;
                                break;
                            case '3':
                                url = postPhotoDirectory + url;
                                break;
                            default:
                                break;

                        }
                        url = '/' + uploadDirectory + url;
                    }

                    var showUrl = mphotoShowUrl;
                    showUrl = showUrl.replace('id', row.id);
                    return '<a href="' + showUrl + '"><img src="' + url + '" /></a>';
                }
            },
            {data: function ( data ) {
                    var showUrl = muserShowUrl;
                    showUrl = showUrl.replace('id', data.userId);
                    return '<a href="'+showUrl+'">'+data.username+'</a>';
                } 
            },
            {data: 'photoType'},
            {data: 'postId'},
            {data: 'photoOrigin'},
            {data: 'photoMedium'},
            {data: 'photoSmall'},
            {data: 'photoIcon'},
            {data: 'title'},
            {data: 'isDeleted'},
            {data: function (row) {
                    if (row.created) {
                        return row.created.date;
                    } else {
                        return '';
                    }
                }
            },
            {data: function (row) {
                    if (row.updated) {
                        return row.updated.date;
                    } else {
                        return '';
                    }
                }
            },
            {data: function (data, type, row) {
                    var showUrl = mphotoShowUrl;
                    showUrl = showUrl.replace('id', data.id);
                    return '<a href="' + showUrl + '">edit</a>';
                }
            }
        ],
        pageLength: 25,
        responsive: true
    });

    $('button#batch-edit-button').click(function () {
        if ($('.photo').parent().hasClass('hidden')) {
            $('.photo').parent().removeClass('hidden');
            $('.photo_id').addClass('hidden');
            var checked_photos = $("input:checked");

            if (checked_photos.length > 0) {
                $('button#delete-button').removeClass('hidden');
                $('button#delete-button .delete-button-total').removeClass('hidden').text('(' + checked_photos.length + ')');
            }

        } else {
            $('.photo').parent().addClass('hidden');
            $('.photo_id').removeClass('hidden');
            $('button#delete-button').addClass('hidden');
        }
    });

    $('button#delete-button').click(function () {
        var func = batchDeletePhoto;
        var title = "Confirm to delete the photos?";
        var text = $("input:checked").length + ' photos to delete';
        var type = "warning";
        var showCancelButton = true;
        warnBeforeJsCall(func, title, text, type, showCancelButton);
    });


    triggerCheckBox();

});

/**
 * triggerCheckBox works for the checkbox with ajax loading
 * @returns {undefined}
 */
triggerCheckBox = function () {
    $('.iCheck-helper').on('click', function () {

        var checked_photos = $("input:checked");

        if (checked_photos.length > 0) {
            $('button#delete-button').removeClass('hidden');
            $('button#delete-button .delete-button-total').removeClass('hidden').text('(' + checked_photos.length + ')');
        } else {
            $('button#delete-button').addClass('hidden');
            $('button#delete-button .delete-button-total').addClass('hidden').text('');
        }

    });
}

var batchDeletePhoto = function () {
    var checked_photos = $("input:checked");

    var photos_array = [];
    if (checked_photos.length > 0) {
        checked_photos.each(function (index, element) {
            photos_array.push($(element).attr('id'));
        });
        
        /*
        console.log(urlBatchDeletePhoto);
        console.log(photos_array);
        return;
        */
       
        $.ajax({
            url: urlBatchDeletePhoto,
            data: {photos: photos_array},
        }).done(function (data) {

            var linkURL = window.location.href;
            var title = "Delete photo successfully";
            var text = ('undefined' !== data.message) ? data.message : "Deleted Successfully";
            var type = "warning";
            var showCancelButton = false;
            warnBeforeRedirect(linkURL, title, text, type, showCancelButton);

        });

    }
}