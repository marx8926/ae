function FileAPI (t, d, f) {

    var fileList = t,
        fileField = f,
        dropZone = d,
        fileQueue = new Array()
        preview = null;
        cont = 0;


    this.init = function () {
        fileField.onchange = this.addFiles;
        dropZone.addEventListener("dragenter",  this.stopProp, false);
        dropZone.addEventListener("dragleave",  this.dragExit, false);
        dropZone.addEventListener("dragover",  this.dragOver, false);
        dropZone.addEventListener("drop",  this.showDroppedFiles, false);
    }

    this.addFiles = function () {
        addFileListItems(this.files);
    }

    this.showDroppedFiles = function (ev) {
        ev.stopPropagation();
        ev.preventDefault();
        var files = ev.dataTransfer.files;
        addFileListItems(files);
    }

    this.clearList = function (ev) {
        ev.preventDefault();
        while (fileList.childNodes.length > 0) {
            fileList.removeChild(
                fileList.childNodes[fileList.childNodes.length - 1]
            );
        }
        document.getElementById("uploadfile").reset();
        document.getElementById("datos");
        cont  = 0;
    }

    this.dragOver = function (ev) {
        ev.stopPropagation();
        ev.preventDefault();
        this.style["backgroundColor"] = "#F0FCF0";
        this.style["borderColor"] = "#3DD13F";
        this.style["color"] = "#3DD13F"
    }

    this.dragExit = function (ev) {
        ev.stopPropagation();
        ev.preventDefault();
        dropZone.style["backgroundColor"] = "#FEFEFE";
        dropZone.style["borderColor"] = "#CCC";
        dropZone.style["color"] = "#CCC"
    }

    this.stopProp = function (ev) {
        ev.stopPropagation();
        ev.preventDefault();
    }

    this.uploadQueue = function (ev) {
        ev.preventDefault();
        while (fileQueue.length > 0) {
            var item = fileQueue.pop();
            var p = document.createElement("p");
            p.className = "loader";
            var pText = document.createTextNode("Subiendo...");
            p.appendChild(pText);
            item.li.appendChild(p);   
            if (item.file.size < 1048576) {
                uploadFile(item.file, item.li);
            } else {
                p.textContent = "Archivo muy grande";
                p.style["color"] = "red";
            }
        }
    }

    var addFileListItems = function (files) {
        for (var i = 0; i < files.length; i++) {
            var fr = new FileReader();
            fr.file = files[i];
            fr.onloadend = showFileInList;
            fr.readAsDataURL(files[i]);
        }
    }



    var showFileInList = function (ev) {
        var file = ev.target.file;

        var resultado = document.createElement("p");
        resultado.id = "file"+cont;

        url =ExtArchivo(file);
        if (file) {
            var li = document.createElement("li");
            li.id=cont;
            cont++;
                var thumb = new Image();
                thumb.src = url;
                thumb.addEventListener("mouseover", showImagePreview, false);
                thumb.addEventListener("mouseout", removePreview, false);
                li.appendChild(thumb);
            var h3 = document.createElement("h4");
            var h3Text = document.createTextNode(file.name);
            h3.appendChild(h3Text);
            li.appendChild(h3);
            var p = document.createElement("p");
            var pText = document.createTextNode(
                "File type: ("
                + file.type + ") - " +
                Math.round(file.size / 1024) + "KB"
            );
            p.appendChild(pText);
            li.appendChild(p);
            li.appendChild(resultado);
            var divLoader = document.createElement("div");
            divLoader.className = "loadingIndicator";
            li.appendChild(divLoader);
            fileList.appendChild(li);
            fileQueue.push({
                file : file,
                li : li
            });
        }
    }

    var showImagePreview = function (ev) {
        var div = document.createElement("div");
        div.style["top"] = (ev.pageY + 10) + "px";
        div.style["left"] = (ev.pageX + 10) + "px";
        div.style["opacity"] = 0;
        div.className = "imagePreview";
        var img = new Image();
        img.src = ev.target.src;
        div.appendChild(img);
        document.body.appendChild(div);
        document.body.addEventListener("mousemove", movePreview, false);
        preview = div;
        fadePreviewIn();
    }

    var movePreview = function (ev) {
        if (preview) {
            preview.style["top"] = (ev.pageY + 10) + "px";
            preview.style["left"] = (ev.pageX + 10) + "px";
        }
    }

    var removePreview = function (ev) {
        document.body.removeEventListener("mousemove", movePreview, false);
        document.body.removeChild(preview);
    }

    var fadePreviewIn = function () {
        if (preview) {
            var opacity = preview.style["opacity"];
            for (var i = 10; i < 250; i = i+10) {
                (function () {
                    var level = i;
                    setTimeout(function () {
                        preview.style["opacity"] = opacity + level / 250;
                    }, level);
                })();
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
                "https://localhost/ae/web/upload.php"
            );
            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);
            xhr.send(file);
        }
    }
    
}

window.onload = function () {
    if (typeof FileReader == "undefined") alert ("Sorry your browser does not support the File API and this demo will not work for you");
    FileAPI = new FileAPI(
        document.getElementById("fileList"),
        document.getElementById("fileDrop"),
        document.getElementById("fileField")
    );
    FileAPI.init();
    var reset = document.getElementById("reset");
    reset.onclick = FileAPI.clearList;
    var upload = document.getElementById("upload");
    upload.onclick = FileAPI.uploadQueue;
}
function ExtArchivo(file) {
    fic=file.name;
    fic = fic.split('\\');
    nom = fic[fic.length-1];
    ext = nom.substr(nom.indexOf('.'),nom.length).toLowerCase();

    if (file.type.search(/video\/.*/) != -1){
                    url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/video.png";
    } else{
        url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/default.png";
    }
    if (file.type.search(/audio\/.*/) != -1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/audio.png";
    }
    if ("image/jpeg".indexOf(file.type) > -1) {
            url="https://dl.dropboxusercontent.com/u/67744385/img-ae/image-jpeg.png";
    }
    if ("image/png".indexOf(file.type)>-1) {
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/image-png.png";
    }
    if ("image/gif".indexOf(file.type)>-1) {
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/image-gif.png";
    }
    if ("application/pdf".indexOf(file.type)>-1) {
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-pdf.png";
    }
    if(ext.indexOf(".rar")>-1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-rar.png";
    }
    if(ext.indexOf(".zip")>-1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-zip.png";
    }
    if(ext.indexOf(".doc")>-1||ext.indexOf(".docx")>-1||ext.indexOf(".docm")>-1||ext.indexOf(".dotx")>-1||ext.indexOf(".dotm")>-1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-word.png";
    }
    if(ext.indexOf(".xlsx")>-1||ext.indexOf(".xlsm")>-1||ext.indexOf(".xltx")>-1||ext.indexOf(".xltm")>-1||ext.indexOf(".xlsb")>-1||ext.indexOf(".xlam")>-1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-excel.png";
    }
    if(ext.indexOf(".ppt")>-1||ext.indexOf(".pptx")>-1||ext.indexOf(".pptm")>-1||ext.indexOf(".potx")>-1||ext.indexOf(".potm")>-1||ext.indexOf(".ppam")>-1||ext.indexOf(".ppsx")>-1||ext.indexOf(".ppsm")>-1||ext.indexOf(".sldx")>-1){
            url= "https://dl.dropboxusercontent.com/u/67744385/img-ae/application-ppt.png";
    }
    return url;
}

function RegFile(file,resultado){
    var conexion;
    if(file.length == 0){
        document.getElementById(resultado).innerHTML='';
        return;
    }
    if (window.XMLHttpRequest){
        conexion=new XMLHttpRequest();
    }
    else{
        conexion=new ActiveXObject("Microsoft.XMLHTTP");
    }
    conexion.onreadystatechange=function()
    {
        if (conexion.readyState==4 && conexion.status==200) {
        document.getElementById(resultado).innerHTML=conexion.responseText;
        }
    }
    
    var es = document.getElementById("lechesp");
    console.log(es.value);
    
    conexion.open("GET","https://localhost/ae/web/app_dev.php/file_leche?name="+file+"&id="+es.value,true);
    conexion.send();
}