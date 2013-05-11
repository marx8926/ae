var markerPosiciones = [];
var markerDirecciones = [];
var idle = false;
var map = null;

function start(url1){
    initMapa(url1);
    
    
}

function initMapa(url) {
	var latlng = new google.maps.LatLng(-8.111682,-79.028628);
	var myOptions = {
		zoom: 14,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    map = new google.maps.Map(document.getElementById("mapa"), myOptions);
    google.maps.event.addListener(map, 'idle', function(){
    	if(idle==true)
        	cargarPA(url1);
    });         
}

function cargarPA(url1){
    
    idle=true;
    
    var arrayPA=new Array();
    
    var boundsMap=map.getBounds(),      
    ne = boundsMap.getNorthEast(),
    sw = boundsMap.getSouthWest();

    if(map.getZoom()>13){
    
        $.ajax({
            url: url1,
            type: 'GET',
            data: {
                seleccionados: arrayPA,
                ne_lat:  ne.lat(),
                ne_lng: ne.lng(),
                sw_lat:  sw.lat(),
                sw_lng: sw.lng()
            },
            dataType: 'json',
            success: function(data){
                
                while(vMarkerBCPPA[0]){
                    vMarkerBCPPA.pop().setMap(null);
                }
                
                while(infoArrWindowTemp[0]){
                    infoArrWindowTemp.pop();
                }
                           
                if(data.length>0){
                   
                    var html='';
                    var iconIdle;
                    var iconIW;
                    var imgLst;
                    var cls;
                    var titulo;
                       
                    for(var i=0;i<data.length;i++){
                        
                        var params = i + ',' +data[i].lat +','+ data[i].lng;
                        
                        switch (data[i].tipo) {
                            case "Agencia":
                                iconIdle=new google.maps.MarkerImage("img/icons/agenciaIdle.png", new google.maps.Size(35,56),null,null);
                                iconIW="img/icons/agencia_iw.png";
                                imgLst="agencia.png";
                                cls="tit_agencia";
                                titulo=data[i].tipo +" "+ data[i].nombre;
                                break;
                            case "Agente":
                                iconIdle=new google.maps.MarkerImage("img/icons/agenteIdle.png", new google.maps.Size(35,56),null,null);
                                iconIW="img/icons/agente_iw.png";
                                imgLst="agente.png";
                                cls="tit_agente";
                                titulo=data[i].tipo +' BCP "'+ data[i].nombre+'"';
                                break;
                            case "Cajero":
                                iconIdle=new google.maps.MarkerImage("img/icons/cajeroIdle.png", new google.maps.Size(35,56),null,null);
                                iconIW="img/icons/cajero_iw.png";
                                imgLst="cajero.png";
                                cls="tit_cajero";
                                titulo=data[i].tipo +" "+ data[i].nombre;
                                break;
                        }
                    
                        html += "<li style='list-style: none; cursor: pointer; cursor: hand''>";
                        html += "<div onclick='goToMarker("+ params +")'>";
                        html += "<table>";
                        html += "<tr><td valign='top' class='tdimg'><img src='img/icons/"+ imgLst +"'/></td><td valign='top'><p class='"+ cls +"'>"+ titulo +"</p><div class='separator2'></div><p>"+ data[i].direccion +"</p><div class='separator'></div><p>Horario de atenciÃ³n:</p><p>"+ data[i].horario +"</p></td></tr>";
                        html += "</table>";
                        html += "</div>";
                        html += "</li>";
                                        
                        ptosMarker(data[i],iconIdle,iconIW);

                    }
                
                    $('#lateral>ul').empty();
                    $('#lateral>ul').append(html);
                        
                }
                                             
            },
            error: function(data){}
        });
    
    }else{
        
        while(vMarkerBCPPA[0]){
            vMarkerBCPPA.pop().setMap(null);
        }
        
        while(infoArrWindowTemp[0]){
            infoArrWindowTemp.pop();
        }
        
    }
            
}
