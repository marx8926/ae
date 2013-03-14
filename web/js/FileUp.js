function FileUp(tabla){
	var	fileQueue = new Array(),
		filesups,
		cont = 0,
		tam = 0;

	this.init = function(){
		filesups = document.getElementsByClassName("fileup");
		tam = filesups.length;
		for(i = 0; i<tam; i++){
			filesups[i].onchange = this.addFile;
		}
	}

	this.addFile = function(){
		cont = 0;
		for (i = 0 ; i < tam;i++){
			if(filesups[i].files[0]){
				var file = new FileReader();
				file.file = filesups[i].files[0];
				file.onloadend = loadProgress;
				file.readAsDataURL(filesups[i].files[0]);
				cont++;
			}
		}
	}
	
	var showFileInList = function (ev) {
        var file = ev.target.file;
        console.log("holalalala");

    }

	var loadProgress = function (evt) {
        var file = evt.target.file;
		console.log("holalalala");
		var div = document.createElement("div");
		if(file);
			var p = document.createElement("p");
            var pText = document.createTextNode(
                "File type: ("
                + file.type + ") - " +
                Math.round(file.size / 1024) + "KB");
            p.appendChild(pText);
            filesups[cont].parentNode.appendChild(div);
            var divLoader = document.createElement("div");
            divLoader.className = "loadingIndicator";
            div.appendChild(divLoader);
            div.appendChild(p);
            fileQueue.push({
                file : file,
                div : div
            });
		}
}