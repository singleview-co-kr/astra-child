jQuery( document ).ready( function( $ ) {
	// close global search popup layer
	$( document ).on( 'click', '.close-popup', function( event ) {
		elementorProFrontend.modules.popup.closePopup( {}, event );
	} );
} );