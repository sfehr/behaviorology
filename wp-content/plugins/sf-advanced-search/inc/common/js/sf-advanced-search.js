( function( $ ) {
	"use strict";

	// HashMap for searched keys and their post titles.
	var searchCache = {};

	// track the currect AJAX request.
	var sameAjaxRequest;

	// get cached post titles from the params object of wp_localize_script.
//	var cachedPostTitles = ( false !== params.cached_post_titles && params.cached_post_titles.length ) ? params.cached_post_titles : false;
	
	// get cached posts data from the params object of wp_localize_script.
	var cachedPostsData = ( false !== params.cached_posts_data && params.cached_posts_data.length ) ? params.cached_posts_data : false;
	
	if( cachedPostsData ) {

		// this will be visible when you have run the search at least once.		
		// extract the data
		cachedPostsData = sf_extract_data( cachedPostsData );		
		
	}	
	
	// the css class '.sf-search-result-container' can optionally be used as a container (stated in the plugin amdin page). If not used '#sf-form-response-container' will be used instead
	var resultsContainer = ( undefined !== $( '.sf-search-result-container' ) ) ? $( '.sf-search-result-container' ) : $( '#sf-form-response-container' );
	
	// AUTO-SUGGEST
	
	$( "#sf-advanced-search-form #sf-search-box" ).autocomplete({
		delay: 300,
//		disabled: true,
		appendTo: '.sf-input-container label',
//		position: { 
//			within : '#sf-search-box', 
//		},
		source: function( request, response ) {

			// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp.
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );

			// function to search for suggestions
			var searchTitlesForSuggestions = function( searchData ) {

				// function used to search the key in the post titles.
				var suggestions = $.grep( searchData, function( item ) {
									return matcher.test( item );
								});

				// cache the search term with its data in a hash map.
				cachedPostsData = searchData;
				searchCache[ request.term ] = suggestions;
				
				// limit the results to one entry
				suggestions = suggestions.slice( 0, 1 );
				
				return suggestions;
			};
			

			// check if the search key was already cached in the HashMap.
			if ( request.term in searchCache ) {

				// return suggestions using the cache object. 
				response( searchCache[ request.term ] );
				
				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;

			} // else check if cached post tiles exists.
			else if ( cachedPostsData ) {				
				
				// cachedPostTitles array may have been set in previous AJAX call or inititally by wp_localize_script.
				var searchSuggestions = searchTitlesForSuggestions( cachedPostsData );
				
				// return the suggestions for the search term.
				response( searchSuggestions );

				// exit and avoid an ajax call as we can use data that was cached in earlier ajax calls.
				return;
			}
			
			
			// Else Make an AJAX Request.

			// AJAX call is made if wp_localize_script sent an empty array for post titles.
			sameAjaxRequest = $.ajax ({

				url: params.ajaxurl, // domain/wp-admin/admin-ajax.php
				type: "POST",
				dataType: "json",
				data: {
					action: "sf_advanced_search_autosuggest",
					ajaxRequest: "yes",
					term: request.term
				}
			})

				// on success.
				.done( function( data, textStatus, jqXHR ) {

					if ( jqXHR === sameAjaxRequest && null !== data && "undefined" !== typeof( data ) ) {

						// data contains the post titles sent by the AJAX handler.
						// extract the data
						data = sf_extract_data( data );
						var searchSuggestions = searchTitlesForSuggestions( postData );
						// return the suggestions for the search term.
						response( searchSuggestions );

					}
				})

				// on failure.
				.fail( function( xhr, status, errorThrown ) {

					$( "#sf-search-box" ).val( "An error occurred ..." );
				})

				// after all this time?
				.always( function( xhr, status ) {
				
				});


		},
		minLength: 3,
	});
	
	
	// FORM INPUT 
	
	$( '#sf-search-box' ).on( 'keyup', function( event ) {	
		
		// check that at least 3 letters have been input
		if( $( this ).val().length > 2 ){
			
			$( '#sf-advanced-search-form' ).submit();
			
		}
		
	});
	
	
	// FORM SUBMISSION
	
	$( '#sf-advanced-search-form' ).submit( function( event ) {	
		
		event.preventDefault();
		
		// serialize the form data
		var ajax_form_data = $( '#sf-advanced-search-form' ).serialize();
		
		//add our own ajax check as X-Requested-With is not always reliable
		ajax_form_data = ajax_form_data + '&ajaxrequest=true&submit=Submit+Form';		
		
		// AJAX call is made 
		var searchAjaxRequest = $.ajax({
			url: params.ajaxurl, // domain/wp-admin/admin-ajax.php
			type: 'POST',
			data: ajax_form_data
		})
			
			// on success
            .done( function( response ) { // response from the PHP action
                resultsContainer.html( response );
            })
            
            // something went wrong  
            .fail( function() {
                resultsContainer.html( '<h2>Something went wrong.</h2>' );
            })
        
            // after all this time?
            .always( function() {
//                event.target.reset();
            });	
		
	});
	
	
	// DATA EXTRACTION 
	function sf_extract_data( data ) {
		
		// POST TITLES
		var postTitles = data.map( function( post ) {
			return post.title; // 1D Array
		});

		// POST TERMS
		var postTerms_arr = data.map( function( post ) {
			return post.terms; // 2D Array
		});

		// convert 2D array to 1D and avoids empty (false) terms
		var postTerms = [];
		for( var i = 0; i < postTerms_arr.length; i++ ){
			if( postTerms_arr[ i ] ){ 
				postTerms = postTerms.concat( postTerms_arr[ i ] );
			}
		}

		// merge titles and terms to one array
		var postData = $.merge( $.merge( [], postTitles ), postTerms );
		// remove duplicates in array
		postData = uniq( postData );
		
		return postData;
	}
	
	
	// REMOVE DUPLICATES IN ARRAY
	function uniq( a ) {
		return Array.from( new Set( a ) );
	}
	

})( jQuery );
