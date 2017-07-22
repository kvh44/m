
$(document).ready(function(){
    $('#userListTable').DataTable({
        serverSide: true,
        processing: true,
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
        ],
        pageLength: 25,
        responsive: true
    });

});