$(document).ready(function() {

    var isWider = $( '.wider' );
    isWider.next( '.container' ).addClass( 'push-down' );

    if(isWider.length) {
        $( window ).scroll(function() {

            var tp = $( 'body' ).scrollTop();

            if(tp > 50) {

                $( '.navbar' ).removeClass( 'wider') ;
            }
            else if(tp < 50) {
        
                $( '.navbar' ).addClass( 'wider') ;
            }
        }); 
    }

	// Hide all abstracts after pafe load    
    $( '.journal-article-list-abstract' ).hide();
    $( ".trigger-abstract" ).click(function() {
   		
   		var id = $(this).attr('id').replace('display_', 'abstract_')
    	$( '#' + id ).slideDown('slow');
    });

    $( '#togglePast' ).change(function() {

    	if($(this).is(":checked")) {

			$( '#type' ).val('.*');
    	}
    	else {

			$( '#type' ).val('^$|^honorary$');
    	}
    });

    // CMS login AJAX

    $( '#cmsLogin' ).submit(function(e){

        var formData = $( '#cmsLogin' ).serialize();

        $.ajax({

            url : base_url + "data/verifyAndUpdate",
            type : "POST",
            data : formData,
            cache : false,
            processData : false,
            success : function (data){
                
                if ($.trim(data) === 'False') {
                    
                    $('.spinner').fadeOut().remove();
                    $('button[type="submit"]').before('<p class="text-primary error-note">Invalid email/password or email not authorized for this journal</p>');
                }
                else{

                    window.location.href = base_url + 'data/updateCompleted/journal'
                }
            },
            beforeSend : function(){

                $( '.error-note' ).remove();
                $('button[type="submit"]').before('<p class="spinner"><i class="fa fa-spinner fa-spin"></i></p>');
            },
            error : function(){

                $('button[type="submit"]').before('<p class="text-primary">Error! Please try again</p>');
            }
        });

        e.preventDefault();
    });
});
