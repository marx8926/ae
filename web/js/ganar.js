//lugares
function lug(dato)
{
             
    $('#lugar').empty();
    for(var i=0; i<dato.length; i++)
    {                     
        $('<option value='+dato[i].id+'>'+dato[i].nombre+'</option>').appendTo('#lugar');
    }           
}

function distrito(dato)
{                   
    $("#distrito_lista").empty();
    for(var i=0;i<dato.length;i++)
    {
                        $('<option value='+dato[i].id+'>'+dato[i].distrito+'</option>').appendTo('#distrito_lista');
    }
}


function redes(dato)
{        
    $('#red_lista').empty();
                     
    $("<option value='-1'>Sin red </option>").appendTo('#red_lista');
                
    for(var i=0;i<dato.length;i++)
    {
        $('<option value='+dato[i].id+'>'+dato[i].id+'-'+dato[i].nombre+' '+dato[i].apellidos+'</option>').appendTo('#red_lista');
    }    
          
                recargar_celula();
}
function celula(dato)
{
                $('#celula_lista').empty();
                    $("<option value='-1'>Sin  Celula </option>").appendTo('#celula_lista');

                for(var i=0;i<dato.length; i++)
                {
                    $('<option value='+dato[i].id+'>'+dato[i].id+'-'+dato[i].nombre+' '+dato[i].apellidos+'</option>').appendTo('#celula_lista');
                }
}

 
 
 function initDepartamentos(ruta)
 {
     $.ajax(
                        {url : ruta,
                         dataType:"json",
                        type: "POST",
                        async: false,
                        }
                        ).done(departamentos);
     //cambio de departamento
      $("#departamento_lista").change(recarga_provincias);
                  
      //cambio de provincia
      $("#provincia_lista").change(recarga_distritos);
      
 }
 function initLugar(url)
 {
     $.getJSON(url,lug);
 }
 
 function initRed(ruta)
 {
     //lista de redes
     $.ajax(
                        {url : ruta,
                         dataType:"json",
                        type: "POST",
                        async: false,
                        }
     ).done(redes); 
         
    $("#red_lista").change(recargar_celula);
 }
 
 function FechaActual(div){
			var f= new Date();
			var mes = "";
			var day = "";
			if(f.getMonth()<"10")
				mes = f.getMonth() + 1;
				mes = "0" + mes;
			if(f.getDate()<"10")
				day = "0" + f.getDate();
				
			$("#"+div).val(mes + "/" + day + "/" + f.getFullYear());
		}