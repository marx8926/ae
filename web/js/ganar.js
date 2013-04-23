//lugares
function lug(dato)
{
             
    $('#lugar').empty();
    for(var i=0; i<dato.length; i++)
    {                     
        $('<option value='+dato[i].id+'>'+dato[i].nombre+'</option>').appendTo('#lugar');
    }
                
}