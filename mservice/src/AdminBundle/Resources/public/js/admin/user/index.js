
try{

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
            { data: function(data) {
                    if(data.cityEn != null || data.postNumber != null){
                        return data.cityEn + data.postNumber;
                    }
                    else {
                        return '';
                    } 
                }
            },
            { data: 'shopName' },
            { data: 'isActive' },
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

}
catch(e)
{
	
}