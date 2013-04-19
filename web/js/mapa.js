function mapeo_position(lati, lngi, mapa, dib)
{
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            $('#'+lati).val(position.coords.latitude);
            $('#'+lngi).val(position.coords.longitude);
            console.log(position.coords.latitude);
            console.log(position.coords.longitude);
            
            $(function() {
		var addresspickerMap = $( '#'+mapa ).addresspicker({
		  elements: {
		    map:      '#'+dib,
		    lat:      "#"+lati,
		    lng:      "#"+lngi
		  }
		});
		var gmarker = addresspickerMap.addresspicker( "marker");
		gmarker.setVisible(true);
		addresspickerMap.addresspicker( "updatePosition");
		
                });
        });
    } else {
        error('Geo Location is not supported');
    }
}