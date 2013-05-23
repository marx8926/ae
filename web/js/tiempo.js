 function FechaActualT(div){
			var f= new Date();

                        var mes = "";
			var day = "";
			if(f.getMonth()<"10")
			{mes = f.getMonth() + 1;
				mes = "0" + mes.toString();
                        }
                        else mes = (f.getMonth()+1).toString();
                        

			if(f.getDate()<"10")
				day = "0" + f.getDate().toString();
                        else
                               day = f.getDate().toString();
				
			$("#"+div).val(day + "/" + mes+'/'+  f.getFullYear().toString());
        		$("#"+div).text(day + "/" + mes+'/'+ f.getFullYear().toString());

		}