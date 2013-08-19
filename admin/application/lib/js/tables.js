	function rgbstringToTriplet(rgbstring) {
		var commadelim = rgbstring.substring(4,rgbstring.length-1);
		var strings = commadelim.split(",");
		var numeric = [];
		for(var i=0; i<3; i++) { numeric[i] = parseInt(strings[i]); }
		return numeric;
	}
	
	function adjustColour(someelement) {
	   var rgbstring = someelement.style.backgroundColor;
	   var triplet = rgbstringToTriplet(rgbstring);
	   var newtriplet = [];
	   // black or white:
	   var total = 0; for (var i=0; i<triplet.length; i++) { total += triplet[i]; }
	   if(total > (3*256/2)) {
	   	 newtriplet = [0,0,0];
	   } else {
	   	 newtriplet = [255,255,255];
	   }
	   var newstring = "rgb("+newtriplet.join(",")+")";
	   someelement.style.color = newstring;
	   return true;
	}

