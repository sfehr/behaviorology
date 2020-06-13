/**
 * File bhv-ajax-loader.js
 *
 * Handles the search input
 * 
 */

( function( $ ) {
	
	// VARS
	var url = bhvAjax.ajaxurl; // passed over from wp_localize_script()
	var content_container = bhvAjax.contentContainer;
	var loader_action = bhvAjax.loader_action;
	
	// EVENT
	jQuery( document ).on( 'click', '', function() {
		
		event.preventDefault();
		
		// call ajax function

	});
	
	
	// AJAX CALLBACK
	var ajaxLoader function( event ) {	
		
		
		// serialize the form data
		var ajax_form_data = $( '#sf-advanced-search-form' ).serialize();
		
		//add our own ajax check as X-Requested-With is not always reliable
		ajax_form_data = ajax_form_data + '&ajaxrequest=true&submit=Submit+Form';		
		
		console.log( ajax_form_data );
		
		// AJAX call is made 
		var searchAjaxRequest = $.ajax({
			url: params.ajaxurl, // domain/wp-admin/admin-ajax.php
			type: 'POST',
			data: ajax_form_data
		})
			
			// on success
            .done( function( response ) { // response from the PHP action
                $( '#sf-form-response-container' ).html( '<h2>The request was successful </h2><br>' + response );
            })
            
            // something went wrong  
            .fail( function() {
                $( '#sf-form-response-container' ).html( '<h2>Something went wrong.</h2><br>' );
            })
        
            // after all this time?
            .always( function() {
//                event.target.reset();
            });	
		
	});	

});	
