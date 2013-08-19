/*
** ecrypt library
** decrypts emails crypted using the principles in: http://www.alistapart.com/articles/gracefulemailobfuscation/
** by David Grevink
**
** usage:
** 		$('a').edecrypt();
**
*/

jQuery.fn.edecrypt = function() {
	return this.each(function(){
		var rot13 = true;
		if (rot13) // Initiate ROT13 only if needed
			var map = rot13init(); 
		var href = this.href;
		var innertext = $(this).html();
		var address = href.replace(/.*contact\/([a-z0-9._%-]+)\+([a-z0-9._%-]+)\+([a-z.]+)/i, '$1' + '@' + '$2' + '.' + '$3');
		if (href != address) {
			this.href = 'mailto:' + (rot13 ? str_rot13(address,map) : address); // Add mailto link	
			if (href.indexOf(innertext) != -1) {
				$(this).html((rot13 ? str_rot13(address,map) : address));
			}
		}
		
	});
}

function rot13init() {
	var map = new Array();
	var s = "abcdefghijklmnopqrstuvwxyz";
	for (var i = 0 ; i < s.length ; i++)
		map[s.charAt(i)] = s.charAt((i+13)%26);
	for (var i = 0 ; i < s.length ; i++)
		map[s.charAt(i).toUpperCase()] = s.charAt((i+13)%26).toUpperCase();
	return map;
}

function str_rot13(a,map) {
	var s = "";
	for (var i = 0 ; i < a.length ; i++) {
		var b = a.charAt(i);
		s += (b>='A' && b<='Z' || b>='a' && b<='z' ? map[b] : b);
	}
	return s;
}



