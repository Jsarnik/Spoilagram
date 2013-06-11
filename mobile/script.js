window.onload = function () {
    setTimeout("if (!alreadyrunflag) initialize();", 0)
}
//
//
if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", function () { alreadyrunflag = 1; initialize(); }, false);
} else if (document.all && !window.opera) { //Crude test for IE
    //Define a "blank" external JavaScript tag
    document.write('<script type="text/javascript" id="contentloadtag" defer="defer" src="javascript:void(0)"><\/script>')
    var contentloadtag = document.getElementById("contentloadtag");
    contentloadtag.onreadystatechange = function () {
        if (this.readyState == "complete") { 
            alreadyrunflag = 1;
            initialize();
        }
            
    }
} 
//

var _startX = 0;            // mouse starting positions
var _startY = 0;
var _offsetX = 0;           // current element offset
var _offsetY = 0;
var _dragElement;           // needs to be passed from OnMouseDown to OnMouseMove
var _oldZIndex = 0;         // we temporarily increase the z-index during drag
var _debug = $('debug');    // makes life easier
var oldID = 'thumb0';
var isUploaded = 0;

function initialize(){

}

var inProgress = false;

function divSlide(id, eventID) {
var div = document.getElementById(id);	
var slideCount =  $('.Pages').length;
var divLeftVal = div.style.left;
var slidePos = 320;
var currentSlide = 0;
var valLeft = 0;
var nextSlide = 0;
var thisDiv = 1;
var importedImage = document.getElementById('uploadedImage');

	/* Check if the left style of the div is null if it is it is the first slide and give it a value of 0px */	
	if (divLeftVal == '' ) {
		divLeftVal = 0;
	}	
	divLeftVal = Math.abs(parseInt(divLeftVal));
	currentSlide = (Math.abs(divLeftVal)/slidePos) + 1;
	/* Check if the function is for moving left or right and if it is check to see if this is the last slide in 	that direction */
	
	switch(eventID) {
	case 1: // NEXT
		if (currentSlide >= slideCount) {
			return;
		}
		else {		
			valLeft = divLeftVal + slidePos;
			nextSlide = currentSlide + 1;				
			if (inProgress ==  false ){
				inProgress = true;		
				pageCounter(currentSlide,nextSlide);		
				//disableID('counter_'+currentSlide, 'counter_'+nextSlide);
				animatedSlide(div, valLeft); /* the minues sign is to move to the NEXT slide*/
			}			
		}//
	break;
	
	case 2: // PREV
		if (divLeftVal != 0) {
			valLeft = divLeftVal - slidePos;
			nextSlide = currentSlide - 1;						
			if (inProgress ==  false ){
				inProgress = true;
				pageCounter(currentSlide,nextSlide);
				//disableID('counter_'+currentSlide, 'counter_'+nextSlide);				
				animatedSlide(div, valLeft);		
			}				
		}
		else {
			return;
		}
	break;
	}
}

function pageCounter(prevSlide, nextSlide) { //IN THIS ONE I WILL CHANGE THE CSS PROPERTY OF THE *THUMBANIL* We are o
	var div = document.getElementById("Page_Num");
		div.innerHTML = 'Page ' + nextSlide + '/3';
	
	
}

function animatedSlide(id, px) {	
			$(id).animate({
			left: '-' + px	
			}, 800, function() {inProgress = false; })		
};

function thumbnails(isMouseover, id) {
	var thumb = document.getElementById(id);
	
	if (oldID == id){
		return;
		}
	
	if (isMouseover == true) {
		thumb.style.border = 'solid 1px';
		thumb.style.borderColor = '#cc3333';
		
	}
	else {
		thumb.style.border = '';
		thumb.style.borderColor = '';
	}
}
function Overlay(id, waterMark) {
	
	var div = document.getElementById('WaterMarks');
	var url = 'images/' + waterMark + '.png';
		
	div.style.backgroundImage = 'url(' + url + ')';
	document.getElementById('spoil').value = waterMark + '.png';
	selectedOverlay(id);
}

function selectedOverlay(newID){
	
	if(oldID == 'thumb0'){
		oldID = newID;
	}
	
	var oldDiv = document.getElementById(oldID);
	var newDiv = document.getElementById(newID);
	
	displayOverlay(true, oldDiv);
	
	oldDiv.style.border = '';
	oldDiv.style.borderColor = '';
	newDiv.style.border = 'solid 1px';
	newDiv.style.borderColor = '#cc3333';
	
	oldID = newID;	
}

function displayOverlay(isShow, oldDiv) {
	var oldDiv = document.getElementById(oldID);
if (isShow == true) {
	document.getElementById('WaterMarks').style.display = 'inline-block';
 }
 else {
	oldDiv.style.border = '';
	oldDiv.style.borderColor = '';
	document.getElementById('WaterMarks').style.display = 'none';
document.getElementById('spoil').value = '';
 }
 
}

function submit(){
	document.getElementById('upload').style.display="inline-block";
}

function createImageSave(){

	var image = document.getElementById('original').value;
	var overlay = 'images/' + document.getElementById('spoil').value;
	var div = document.getElementById('uploadedImage');
	var leftX = -1*(Math.abs(parseInt(div.style.left + 0)));
	var topY = -1*( Math.abs(parseInt(div.style.top + 0)));

if (image == '' || image == 'images/stock.png') {
	alert('Please upload an Image');
return;
}

if (overlay == 'images/') {
errorMessage('You have not spoiled your photo! Please select an overlay.');
return;
}
	$.ajax({
		type: 'POST',
		url: 'createImageSave.php',
		data: {
		X_pos: leftX,
		Y_pos: topY,
		Image: image,
		Overlay: overlay	
		},
		success: function(data) {
			//document.getElementById('original').value = "images/stock.png";
			//document.getElementById('uploadedImage').src = "images/stock.png";
			//document.getElementById('WaterMarks').style.backgroundImage = "url('images/01allwatermarks.png')";
			//selectedOverlay('thumb1');	
			
			if (data == 'error' && data.length != 27){ //this is the always static length of a successfully returned image path
				errorMessage('An unexpected error has occurred!');
				errorDeleteFile(image);
				return;
			}
			
			fancyBox(data);
			//Success Image has been created
			isUploaded = 1;
		},
		error: function() {
			alert('An error has occurred!');
		}
	});
}

function errorDeleteFile(file) {
	$.ajax({
	type: 'POST',
	url: 'deleteImage.php',
	data: {
	File: file
	},
	success: function() {
				alert('An error has occurred! Please try again.');
	},
	error: function() {
				alert('An error has occurred! Please try again.');
	}
});
}

function uploadImage() {
	var test = 'an/image/path';
	$.ajax({
		type: 'POST',
		url: 'uploadImage.php',
		data: {Upload: test},
		success: function(fuckit) {
		alert(fuckit);
		},
		error: function() {
			alert('An error has occurred!');
		}
	});
}

function uploadSave(isSave){	
	if(isSave == true){
		createImageSave();
		document.getElementById('Upload_btn').style.display = 'inline-block';
}
}

function errorMessage(message) {
		alert(message);
	}

function moveTool(isOver) {

if (isOver == true) {
document.getElementById('move').style.display = 'inline-block';
}
else {
document.getElementById('move').style.display = 'none';
}

}

function fancyBox(data) {

var link = 'fancybox.php';
var imagepath = data;

$.fancybox({
		href: link,
		type: 'ajax',
		width: 320,
		ajax: {
			type: 'POST',
			data: { 
				newImage: imagepath
				  },
			}
		});
}

function toggleBarVisibility() {
	var barFrame = document.getElementById('progressBar');
	var bar = document.getElementById('progress-Color');

	barFrame.style.display = "inline-block";
	bar.style.display = "inline-block";
}

function createRequestObject() {
	var http;
	if(navigator.appName == "Microsoft Internet Explorer") {
	http = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else {
	//http = XMLHttpRequest();
	}
	return http;
}

function sendRequest() {
	var http = createRequestObject();
	http.open("GET", "progress.php");
	http.onreadystatechange = function () {handleResponse(http); };
	http.send(null);
}

function handleResponse(http) {
    var response;
    if (http.readyState == 4) {
        response = http.responseText;
        //document.getElementById("progress-Color").style.width = response + "%";
        document.getElementById("status").innerHTML = 'Uploading... ';
document.body.style.cursor='wait'; 
        if (response < 100) {
            setTimeout("sendRequest()", 1000);
        }
        else {
            //toggleBarVisibility();
            //document.getElementById("status").innerHTML = "Done.";
        }
    }
}

function startUpload() {
	//toggleBarVisibility();
	sendRequest();

}

//************************ MOVING DIV ****************************//




InitDragDrop();

function InitDragDrop()
{
	document.onmousedown = OnMouseDown;
	document.onmouseup = OnMouseUp;
}

function OnMouseDown(e)
{
    // IE is retarded and doesn't pass the event object
    if (e == null) 
        e = window.event; 
    
    // IE uses srcElement, others use target
    var target = e.target != null ? e.target : e.srcElement;
    
    /*_debug.innerHTML = target.className == 'drag' 
        ? 'draggable element clicked' 
        : 'NON-draggable element clicked';*/

    // for IE, left click == 1
    // for Firefox, left click == 0
    if ((e.button == 1 && window.event != null || 
        e.button == 0) && 
        target.className == 'drag')
    {
        // grab the mouse position
        _startX = e.clientX;
        _startY = e.clientY;
        
        // grab the clicked element's position
        _offsetX = ExtractNumber(target.style.left);
        _offsetY = ExtractNumber(target.style.top);
        
        // bring the clicked element to the front while it is being dragged
        _oldZIndex = target.style.zIndex;
        target.style.zIndex = 10000;
        
        // we need to access the element in OnMouseMove
        _dragElement = target;
		
        // tell our code to start moving the element with the mouse
        document.onmousemove = OnMouseMove;
        
        // cancel out any text selections
        document.body.focus();

        // prevent text selection in IE
        document.onselectstart = function () { return false; };
        // prevent IE from trying to drag an image
        target.ondragstart = function() { return false; };
        
        // prevent text selection (except IE)
        return false;
    }
}

function OnMouseMove(e)
{
var importedImage = document.getElementById('uploadedImage');

importedImageWidth = importedImage.clientWidth;
importedImageHeight = importedImage.clientHeight;

	if (e == null)
		var e = windows.event;
		var minLeft = importedImageWidth - 320;
		var maxLeft = (minLeft * -1);
		var minTop = 0;		
		var maxTop = ((importedImageHeight - 320) * -1);				
		var left = (_offsetX + e.clientX - _startX);
		var top = (_offsetY + e.clientY - _startY);
		
		if (left > 0) {
			left = 0;
		}
		else if (left < maxLeft) {
			left = maxLeft;
		}
		
		if (top > 0) {
			top = 0;
		}
		else if (top < maxTop) {
			top = maxTop;
		}		
		
	//this is the actual drag code
	_dragElement.style.left = left + 'px';
	//alert(_offsetX + ' ' + e.clientX +' '+ _startX);
	_dragElement.style.top =  top + 'px';
	
    /*_debug.innerHTML = '(' + _dragElement.style.left + ', ' + 
        _dragElement.style.top + ')';   */
		
	//if position is 0 only able to move left -maxwidth px

		
}

function OnMouseUp(e)
{
    if (_dragElement != null)
    {
        _dragElement.style.zIndex = _oldZIndex;
		_dragElement.style.opacity = 1.0;

        // we're done with these events until the next OnMouseDown
        document.onmousemove = null;
        document.onselectstart = null;
        _dragElement.ondragstart = null;

        // this is how we know we're not dragging      
        _dragElement = null;
        
        //_debug.innerHTML = 'mouse up';
    }
}

function ExtractNumber(value)
{
    var n = parseInt(value);
	
    return n == null || isNaN(n) ? 0 : n;
}

// this is simply a shortcut for the eyes and fingers
function $(id)
{
    return document.getElementById(id);
}