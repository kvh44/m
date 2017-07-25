
$(document).ready(function(){
    $('#photoListTable').DataTable({
        serverSide: true,
        processing: true,
        ordering: false,
        ajax: mphotoListAjaxUrl,
        columns: [
            { data: 'id' },
			{ data: function ( row ) {
				    var url = iconDirectory+row.userId+'/'+row.photoIcon;
				    if(typeof row.photoType !== 'undefined'){
					    switch(row.photoType){
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
						url = '/'+uploadDirectory+url;
				    }
					return '<img src="'+url+'" />';
				}
			},
            { data: 'userId' }, 
            { data: 'photoType' }, 
            { data: 'postId' }, 
            { data: 'photoOrigin' },
            { data: 'photoMedium' },
            { data: 'photoSmall' },
            { data: 'photoIcon' },   
            { data: 'title' },  
 			{ data: 'isDeleted' }, 
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
                           return '<a href="">edit</a>';
                    }
            }
        ],
        pageLength: 25,
        responsive: true
    });

});