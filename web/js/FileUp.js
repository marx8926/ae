function FileUp(tabla){
	var	fileQueue = new Array(),
		filesups,
		cont = 0,
		tam = 0,
		form = $("#myForm");
	
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
			}
		}
	}

	var loadProgress = function (evt) {
        var file = evt.target.file;
		var div = document.createElement("div");
		if(file);
			var p = document.createElement("p");
            var pText = document.createTextNode(
                "File type: ("
                + file.type + ") - " +
                Math.round(file.size / 1024) + "KB");
            p.appendChild(pText);
            filesups[cont].parentNode.appendChild(div);
    		console.log(filesups[cont].parentNode);
            var divLoader = document.createElement("div");
            divLoader.className = "loadingIndicator";
            div.appendChild(divLoader);
            div.appendChild(p);
            fileQueue.push({
                file : file,
                div : div,
                name : filesups[cont].parentNod.id
            });
            cont++;
		}
	
	this.uploadQueue = function (ev) {
		consolelog("hola");
        ev.preventDefault();
        while (fileQueue.length > 0) {
            var item = fileQueue.pop();
            var p = document.createElement("p");
            p.className = "loader";
            var pText = document.createTextNode("Uploading...");
            p.appendChild(pText);
            item.li.appendChild(p);   
            if (item.file.size < 1048576) {
                uploadFile(item.file, item.li);
            } else {
                p.textContent = "File to large";
                p.style["color"] = "red";
            }
        }
    }
	
	var uploadFile = function (file, li) {
        if (li && file) {
            var xhr = new XMLHttpRequest(),
                upload = xhr.upload;
            upload.addEventListener("progress", function (ev) {
                if (ev.lengthComputable) {
                    var loader = li.getElementsByTagName("div")[0];
                    loader.style["width"] = (ev.loaded / ev.total) * 100 + "%";
                    if(ev.loaded == ev.total){
                        RegFile(file.name,"result"+li.id);
                   }
                }
            }, false);
            upload.addEventListener("load", function (ev) {
                var ps = li.getElementsByTagName("p");
                var div = li.getElementsByTagName("div")[0];
                div.style["width"] = "100%";
                div.style["backgroundColor"] = "#0f0";
                for (var i = 0; i < ps.length; i++) {
                    if (ps[i].className == "loader") {
                        ps[i].textContent = "Upload complete";
                        ps[i].style["color"] = "#3DD13F";
                        break;
                    }
                }
            }, false);
            upload.addEventListener("error", function (ev) {console.log(ev);}, false);
            xhr.open(
                "POST",
                "https://localhost/sym/web/upload.php"
            );
            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);
            xhr.send(file);
        }
    }
}