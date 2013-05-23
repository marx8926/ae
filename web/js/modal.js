// Function to show modal
$( '#registro' ).on( 'click', function( ev ) {
  $( '#modal' ).fadeIn();
  $( '#modal-background' ).fadeTo( 500, .5 );
  ev.preventDefault();
} );

// Function to hide modal
$( '#close-modal' ).on( 'click', function( ev ) {
  $( '#modal, #modal-background' ).fadeOut();
  ev.preventDefault();
} );

$("#finalizar").on('click', function(){
	$( '#modal, #modal-background' ).fadeOut();
	$("#RegPersonalForm").reset();
});