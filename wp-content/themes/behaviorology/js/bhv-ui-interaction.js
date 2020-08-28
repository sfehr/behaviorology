/**
 * File bhv-ui-interaction.js
 *
 * Handles UI interactions.
 * 
 * ON READY 
 * ON AJAX SUCCESS
 * RESIZE EVENTS
 * sf_start_page_image()			| Handles the interaction with the image on the start page
 * sf_initialize_list()				| Adds interaction to the list
 * sf_search_focus_handler()		| Handles search UI interaction 
 * sf_add_marquee()					| Finds overflown elements among selected elements and adds marquee class
 * sf_list_up()						| Adds custom CSS property '--animation-order' to list-row elements. This creates a smooth list-up animation.
 * sf_close_button()				| Handles click event for the close button in header
 * sf_scroll_anchor()				| Smoothly scrolls to the anchor
 * sf_sort_list()					| Sorts the table alphabetically
 * sf_touch_device()  				| Handles the touch device actions.
 * 
 *
 * 
 *  
 */
/*


/* ON READY
 *
 * Fires on initial page load
 *
 */ 
jQuery( document ).ready( 
	sf_start_page_image(),
	sf_initialize_list(),
	sf_search_focus_handler(),
	sf_close_button(),
	sf_page_title_update(),
	sf_expand_media(),
	sf_scroll_anchor(),
	sf_touch_device()
);



/* ON AJAX SUCCESS
 *
 * Fires after ajax success
 *
 */
jQuery( document ).ajaxSuccess( sf_initialize_list );



/* RESIZE EVENTS
 *
 * Fires after window resize with a delay of 100mx
 *
 */
// resize timeout
var resizing;
window.onresize = function() {
	
	clearTimeout( resizing );
		
	resizing = setTimeout( function() {
		sf_add_marquee();
	}, 100);
};



/* LIST
 *
 * Handles the interaction with the image on the start page
 *
 */ 
function sf_start_page_image(){
	
	// check if its start page
	if( jQuery( 'body' ).hasClass( 'home' ) ){
		
		// add class initial to body
		jQuery( 'body' ).addClass( 'initial' )
	
		// get the image and add it to the container
		var images = jQuery( 'body' ).find( '.post-thumbnail' );
		jQuery( '#content-container' ).html( images );

		// remove initial class again on click event
		jQuery( images ).on( 'click', this, function( e ){
			e.preventDefault();
			jQuery( 'body' ).removeClass( 'initial' );
			clearInterval( diashow );
			sf_initialize_list();
		});
		
		// Image Slider
		jQuery( '#content-container > .post-thumbnail:gt(0)' ).hide();

		var diashow = setInterval( function() { 
		  jQuery( '#content-container > .post-thumbnail:first' )
			.fadeOut( 2000 )
			.next()
			.fadeIn( 2000 )
			.end()
			.appendTo( '#content-container' );
		},  5000);
	}
}
	

/* LIST
 *
 * Adds interaction to the list
 *
 */ 
function sf_initialize_list(){
	
	// expands the list item on click to preview content
	jQuery( '.list-entry div' ).not( '.list-image, .list-content' ).on( 'click', function(){
		jQuery( this ).parent().toggleClass( 'expanded' );
	});
	
	// CALLBACKS
	sf_add_marquee();
	sf_list_up();
	sf_scroll_anchor();
	
	// CONDITIONAL CALLBACKS
	if( jQuery( 'body' ).hasClass( 'list-view' ) ){
		sf_sort_list(); // ad tiny sort when list is displayed
	}
	if( jQuery( 'body' ).hasClass( 'search-active' ) ){
		jQuery( 'body' ).addClass( 'result-view' ); // add result-view class when results are output
		jQuery( 'body' ).removeClass( 'initial' ); // remove initial class in case it is still applied
	}
	
		
}



/* SEARCH FOCUS
 *
 * Handles search UI interaction 
 *
 */
function sf_search_focus_handler(){
	
	// ON FOCUS
	jQuery( 'body' ).on( 'click focus', '.main-navigation .menu-item-type-custom:not(.lang-item)', function( event ){ // adds search-active class to body 
		if( event == 'click' ){
			event.preventDefault();
		}
		jQuery( 'body' ).addClass( 'search-active' );
		jQuery( '#sf-search-box' ).focus();
		
	});
	// ON FOCUSOUT
	jQuery( 'body' ).on( 'focusout', '#sf-search-box', function(){ // remove search-active class from body 
		jQuery( 'body' ).removeClass( 'search-active' ); 
	});	
}



/* MARQUEE
 *
 * Finds overflown elements among selected elements and adds marquee class
 *
 */
function sf_add_marquee(){
	
	jQuery( 'body' ).find( '.page-title-container, .studio-container .menu-item' ).each( function(){

		if( jQuery( this )[0].scrollWidth > jQuery( this ).innerWidth() ){
			// Text has over-flown
			jQuery( this ).addClass( 'marquee' );
		}
		else{
			// Text fits in container
			jQuery( this ).removeClass( 'marquee' );
		}

	});
}



/* LIST UP
 *
 * Adds custom CSS property '--animation-order' to list-row elements. This creates a smooth list-up animation.
 *
 */
function sf_list_up(){
	
	jQuery( 'body' ).find( '.studio-container .menu-item' ).each( function( ind ){
		jQuery( this ).get( 0 ).style.setProperty( '--animation-order', ind.toString() ); // studio container (big list)
	});
	
	jQuery( 'body' ).find( '.list-entry' ).each( function( ind ){
		jQuery( this ).get( 0 ).style.setProperty( '--animation-order', ind.toString() ); // list entries (normal list)
	});	
	
}



/* CLOSE BUTTON
 *
 * Handles click event for the close button in header
 *
 */
function sf_close_button(){
	
	jQuery( 'body' ).on( 'click', '.ui-btn-close', function(){
		jQuery( '#content-container' ).empty(); // remove content from container
		jQuery( 'body' ).addClass( 'home' ); // add home class to body
		jQuery( 'body' ).removeClass( 'single' ); // remove single class from body
		jQuery( 'body' ).removeClass( 'page' ); // remove single class from body
		jQuery( 'body' ).removeClass( 'list-view' ); // remove list-view class from body
		jQuery( 'body' ).removeClass( 'result-view' ); // Search: remove list-view class from body
		jQuery( '#sf-search-box' ).val( '' ) // Search: reset the input field
		sf_initialize_list(); // initialize list for restoring event listeners etc.
	});
	
}



/* PAGE TITLE
 *
 * Updates the page title when a term query is active or a project is selected.
 * This function is only called when accessing a project (single state) directly.
 *
 */
function sf_page_title_update(){
	
	if( jQuery( 'body' ).hasClass( 'single' ) || jQuery( 'body' ).hasClass( 'page' ) ){
		var title = jQuery( '.entry-title' ).first().text();
		jQuery( '#page-title' ).text( title ); // replace page title with entry-title of a single post  
	}
	
}



/* EXPAND IMAGES
 *
 * Expands the project media (images, movies) when clicked.
 *
 */
function sf_expand_media(){
	
	jQuery( 'body' ).on( 'click', '.section-media .itm', function(){
		jQuery( this ).toggleClass( 'expanded' );	
	});
	
}



/* SCROLL ANCHOR
 *
 * Smoothly scrolls to the anchor
 *
 */
function sf_scroll_anchor(){
	
	if( jQuery( 'a[href^=\\#]' ) && jQuery( 'body' ).hasClass( 'single' ) ){
		
		jQuery( 'a[href^=\\#]' ).click( function( e ) { 
			e.preventDefault(); 
			var dest = jQuery( this ).attr( 'href' ); 
			jQuery( 'html, body' ).animate({ 
				scrollTop: jQuery( dest ).offset().top + 2 }, 'smooth' ); 
		});		
		
	}
}



/* LIST SORTING
 *
 * Sorts the table alphabetically
 *
 */
function sf_sort_list(){
	
	var table = document.getElementById( 'content-container' )
		,tableHead = table.querySelector( '.list-header' )
		,tableHeaders = tableHead.querySelectorAll( 'div' )
	;
	var sortIcon = document.createElement( 'SPAN' );
	sortIcon.className = 'ui-sort-icon';
	
	tableHead.addEventListener( 'click', function( e ){
		var tableHeader = e.target
			,tableHeaderIndex, isAscending, order
		;
		
		while ( tableHeader.nodeName !== 'DIV' ) {
			tableHeader = tableHeader.parentNode;
		}
		
		tableHeaderIndex = Array.prototype.indexOf.call( tableHeaders, tableHeader );
		isAscending = tableHeader.getAttribute( 'data-order' ) === 'asc';
		order = isAscending ? 'desc' : 'asc';
		tableHeaders.forEach( element => element.removeAttribute( 'data-order' ) ); // remove previous order attribute
		tableHeader.setAttribute( 'data-order', order ); // set order attribute
		
		// Sorting
		tinysort(
			table.querySelectorAll( '.list-entry:not(.type-class)' )
			,{
				order: order,
				natural: true,
			}
		);
		
		tableHeader.appendChild( sortIcon ); // add sort icon
	});	
}



/* TOUCH DEVICE
 *
 * Handles the touch device actions.
 *
 */
function sf_touch_device(){
	
	var is_coarse = matchMedia( '(pointer:coarse)' ).matches;
	
	if( is_coarse ){
		jQuery( 'body' ).on( 'click', '.main-navigation', function(){
			jQuery( 'body' ).removeClass( 'initial' );
			sf_initialize_list();
		});
	}	
}	
