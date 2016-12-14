function toggle_visibility(id) {
    var thelist = document.getElementsByClassName("item-content");
    var thelink = document.getElementsByClassName("image");
    var thetitle = document.getElementsByClassName("subcat-container");
    	for (var i = 0; i < thelist.length; i++) {
            if (thelist[i] != document.getElementById(id))
            {
                thelist[i].style.display = 'none';
                thelink[i].style.opacity = '0.1';
                for (var j = 0; j < thetitle.length; j++){
                	thetitle[j].style.opacity = '0.1';
                }
            }
            else if (thelist[i] == document.getElementById(id) && thelist[i].style.display == 'block')
            {
                for (var i = 0; i < thelist.length; i++) {
                    thelink[i].style.opacity = '1.0';}
                for (var j = 0; j < thetitle.length; j++){
                	thetitle[j].style.opacity = '1.0';
                }
            }
            else
            {
                thelink[i].style.opacity = '1.0';
                for (var j = 0; j < thetitle.length; j++){
                	thetitle[j].style.opacity = '1.0';
                }
            }
    	}
    
    var e = document.getElementById(id);
    	if(e.style.display == 'block') {
    		e.style.display = 'none';

    	} else {
    		e.style.display = 'block';
    	}
}
