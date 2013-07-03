
function mapeo_position(lati, lngi, mapa, dib)
{      

    
    if (navigator.geolocation) {
            console.log('mapeo_position');
            

        navigator.geolocation.getCurrentPosition(function(position){
          

            $('#'+lati).val(position.coords.latitude);
            $('#'+lngi).val(position.coords.longitude);
      
            dibujar_mapa(dib,lati, lngi,mapa);
        },function(error){
            if(error.code===2)
            {
                       $('#'+lati).val('-8.097944');
                       $('#'+lngi).val('-79.03704479999999');
                       dibujar_mapa(dib,lati, lngi,mapa); 
            }
        });
        
       
    } else {
        console.log('Geo Location is not supported');
        $('#'+lati).val('-8.097944');
        $('#'+lngi).val('-79.03704479999999');
        dibujar_mapa(dib,lati, lngi,mapa);
               
    }

    

}
function dibujar_mapa(dib, lati,lngi,mapa) {
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
		
}