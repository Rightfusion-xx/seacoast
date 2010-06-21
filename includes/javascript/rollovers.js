// The following functions were pulled from lib.js 
// by Caio Chassot (http://v2studio.com/k/code/)
Array.prototype.find = function(value, start) {
	start = start || 0;
	for (var i=start; i<this.length; i++) 
		if (this[i]==value)
			return i;
	return false; 
}

Array.prototype.count = function(value) {
	var pos, start = 0, count = 0;
	while ((pos = this.find(value, start))!==false) {
		start = pos + 1;
		count++;
	}
	return count;
}

function isUndefined(v) { 
	var undef;
	return v===undef;
}

function undef(v) {  return  isUndefined(v) }
function isdef(v) {  return !isUndefined(v) }

function map(list, fn) {
	if (typeof(fn)=='string') return map(list, __strfn('item,idx,list', fn));

	var result = [];
	fn = fn || function(v) {return v};
	for (var i=0; i < list.length; i++) result.push(fn(list[i], i, list)); 
	return result;
}

function filter(list, fn) { 
	if (typeof(fn)=='string') return filter(list, __strfn('item,idx,list', fn));

	var result = [];
	fn = fn || function(v) {return v};
	map(list, function(item,idx,list) { if (fn(item,idx,list)) result.push(item) } );
	return result;
}

String.prototype.insert = function(idx,value) { 
	return this.slice(0,idx) + value + this.slice(idx);
}

function getElem(elem) { 
	if (document.getElementById) {
		if (typeof elem == "string") {
			elem = document.getElementById(elem);
			if (elem===null) throw 'cannot get element: element does not exist';
		} else if (typeof elem != "object") {
			throw 'cannot get element: invalid datatype';
		}
	} else throw 'cannot get element: unsupported DOM';
	return elem;
}

function getElementsByClass(className, tagName, parentNode) { 
	parentNode = isdef(parentNode)? getElem(parentNode) : document;
	if (undef(tagName)) tagName = '*';
	return filter(parentNode.getElementsByTagName(tagName), 
		function(elem) { return hasClass(elem, className) });
}

function hasClass(elem, className) { 
	return getElem(elem).className.split(' ').count(className);
}

// ATTACH ROLLOVERS
// This function loops through every image and input tag
// looking for the class "rollover", if found it then preloads
// the rollover image and attaches mouse events for the rollover
// by Tim Walling

// User Defined Variables
var erClassName = "rollover";
var erImageName = "_";

function newImageName(oldName) {
	var newName = oldName.insert (oldName.length-4,erImageName);
	return newName;
}

function oldImageName(curName) {
	var re = new RegExp (erImageName, 'gi') ;
	var oldName = curName.replace(re, '') ;
	return oldName;
}

function attachRollovers(){
	var btnsTemp = new Array();
	var origSrc = new Array();
	var newSrc = new Array();
	var btnsImage = getElementsByClass(erClassName,'img'); // for images
	var btnsInput = getElementsByClass(erClassName,'input'); // for inputs
	var btns = btnsImage.concat(btnsInput); // combine into one array
	
	for (var i=0; i<btns.length; i++){
		origSrc[i] = btns[i].src;
		newSrc[i] = newImageName(origSrc[i]);
		btnsTemp[i] = new Image();
        btnsTemp[i].src = newSrc[i];
        btns[i].onmouseover = function() {
			var newSrcString = newImageName(this.src);
            this.setAttribute('src',newSrcString)
        }
        btns[i].onmouseout = function() {
			var oldSrcString = oldImageName(this.src);
            this.setAttribute('src',oldSrcString)
        }
	}
}
window.onload = attachRollovers;

