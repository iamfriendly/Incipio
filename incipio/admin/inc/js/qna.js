jQuery( '.section-qna .option' ).each( function(){ jQuery(this ).slideUp(); } );

jQuery( '.section-qna .heading' ).on( 'click', function(){

	var next_answer = jQuery( this ).next( '.option' );

	if( next_answer.hasClass( 'shown_answer' ) ){

		jQuery( this ).next( '.option' ).slideUp().removeClass('shown_answer');

	}else{

		jQuery( this ).next( '.option' ).slideDown().addClass('shown_answer');

	}

} );