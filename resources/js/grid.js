/*
*  Javascript for saving the jg grid context
* 
*/

// cookies management	
function createCookie(value, days) {
	
	
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	//the document path  will be  used as cookie name 
	var path = window.location.pathname;
	document.cookie = path+"="+value+expires+"; path=/";
}
function readCookie() {
	var name = window.location.pathname;
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name,"",-1);
}

// grid context management
function getContext(){
	var context = readCookie();
	if (typeof(context) == 'string'){
		context = JSON.parse(context);
		//eraseCookie('grid_context');
	} else{
		context = new Object;
		context.page = 1;
		context.rowNum = 10;
	};
	return context;
}
function setContext(){
	var grid_context_str = JSON.stringify($("#item_list").jqGrid('getGridParam' , 'postData'), null, 0);
	createCookie(grid_context_str, 1);
}

//return element from array
function element(item, array, default_value){
	if (typeof(array[item]) != 'undefined'){
		return array[item];
	}else{	
		if (typeof(default_value) != 'undefined')
			return default_value;
		else 
			return '';
	}
}