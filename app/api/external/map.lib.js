function loadURL(url){
    var xmlhttp;
    if(window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if(xmlhttp.readyState==4 && xmlhttp.status==200){
                document.body.innerHTML=xmlhttp.responseText;
                //document.getElementById(id).innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open('GET',url,true);
        xmlhttp.send();
        return false;
    }
}

function loadMap(xm,ym){
    return loadURL('?xm='+xm+'&ym='+ym);
}

function loadSelectedMap(selected_x,selected_y){
    return loadURL('?selected_x='+selected_x+'&selected_y='+selected_y);
}