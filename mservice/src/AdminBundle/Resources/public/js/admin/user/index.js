
$(document).ready(function(){
    $('#userListTable').DataTable({
        serverSide: true,
        processing: true,
        ordering: false,
        ajax: muserListAjaxUrl,
        columns: [
            { data: 'id' },
            { data: 'username' },
            { data: 'telephone' },
            { data: 'countryEn' },
            { data: 'cityEn' },
            { data: 'shopName' },
            { data: 'isActive' },
            { data: 'isDeleted' },
            { data: function ( row ) {
                       if(row.topTime){
                           return row.topTime.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( row ) {
                       if(row.created){
                           return row.created.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( row ) {
                       if(row.updated){
                           return row.updated.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( data, type, row ) {
                    var editUrl = muserEditUrl;
                    var showUrl = muserShowUrl;
                    editUrl = editUrl.replace('internalToken', data.internalToken);
                    showUrl = showUrl.replace('id', data.id);
                           return '<a href="'+editUrl+'">edit</a>'+'<br>'+'<a href="'+showUrl+'">show</a>';
                    }
            }
        ],
        pageLength: 25,
        responsive: true
    });

});