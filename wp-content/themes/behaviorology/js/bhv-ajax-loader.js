/**
 * File bhv-ajax-loader.js
 *
 * Handles WP over Ajax
 * 
 */

jQuery( document ).ready( function( $ ) {
	
	// VARS
	var url = sf_ajax_loader_params.ajaxurl; // passed over from wp_localize_script()
	var content_container = sf_ajax_loader_params.contentContainer; // passed over from wp_localize_script()
	var loader_action = sf_ajax_loader_params.loader_action; // passed over from wp_localize_script()
	var link_obj = '';
	var target = {};

	
	// EVENT
	$( document ).on( 'click', 'a[data-target]', function( event ) { 
		
		// Exclude specific links from processing
		if( $( this ).parent().hasClass( 'lang-item' ) ){
			return;
		}
		
		event.preventDefault();
		
		link_obj = $( this ).data( 'target' ); // term_id, name, post_id
		target.term_id = link_obj.term_id;
		target.post_id = link_obj.post_id;
		target.type = link_obj.type;
		
		ajaxLoader( target );

	});
	
	
	// AJAX CALLBACK
	function ajaxLoader() {	
		
		var data = {
			action : 'sf_ajax_loader',
			target  : target,			
			ajaxRequest: 'yes',
			nonce  : sf_ajax_loader_params.nonce
		};		
		
		// AJAX call is made 
		var ajaxLoaderRequest = $.ajax({			
			
			url        : url, // domain/wp-admin/admin-ajax.php
			type       : 'POST',
			data       : data,
//			beforeSend : function ( xhr ) {
//				button.text('Loading...'); // change the button text, you can also add a preloader image
//			},			
		})
			
			// on success
            .done( function( response ) { // response from the PHP action
                $( content_container ).html( response[ 'data' ] );
				$( '#page-title' ).text( link_obj.name );
				$( 'body' ).removeClass( 'home initial' );
				
				// SINGLE
				if( '' == link_obj.term_id && '' !== link_obj.post_id && undefined == link_obj.type ){
					$( 'body' ).addClass( 'single' );
					$( 'body' ).removeClass( 'list-view' );
					$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
				}
				
				// PAGE
				if( link_obj.type ){
					$( 'body' ).addClass( 'page' );
				}				
				
				// LIST VIEW
				if( ( '' !== link_obj.term_id && '' == link_obj.post_id ) || ( 0 == link_obj.term_id && '' == link_obj.post_id ) ){
					$( 'body' ).addClass( 'list-view' );
				}				
            })
            
            // something went wrong  
            .fail( function() {
                $( content_container ).html( '<h2>Something went wrong.</h2><br>' );
            })
        
            // after all this time?
            .always( function() {
//                event.target.reset();
            });	
	}	

});	
