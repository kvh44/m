
try{

$(document).ready(function(){
    $('#postListTable').DataTable({
        serverSide: true,
        processing: true,
        ordering: false,
        ajax: mpostListAjaxUrl,
        columns: [
            { data: 'id' },
            { data: 'username' },
            { data: 'categoryEn' },
            { data: 'draftId' },
            { data: 'title' },
            { data: 'isZh' },
            { data: 'isFr' },
            { data: 'isEn' },
            { data: 'isDeleted' },
            { data: function ( data ) {
                       if(data.topTime){
                           return data.topTime.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( data ) {
                       if(data.created){
                           return data.created.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( data ) {
                       if(data.updated){
                           return data.updated.date;
                       } else {
                           return '';
                       }
                    }
            },
            { data: function ( data, type, row ) {
                    var editUrl = mpostEditUrl;
                    var showUrl = mpostShowUrl;
                    editUrl = editUrl.replace('internalId', data.internalId);
                    showUrl = showUrl.replace('id', data.id);
                           return '<a href="'+editUrl+'">edit</a>'+'<br>'+'<a href="'+showUrl+'">show</a>';
                    }
            }
        ],
        pageLength: 25,
        responsive: true
    });

});

}
catch(e)
{
	
}