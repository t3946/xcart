/*@cc_on @*/

function initJSLayers(){
	isDOM=document.getElementById?true:false
	isOpera=isOpera5=window.opera && isDOM
	isOpera6=isOpera && window.print
	isOpera7=isOpera && document.readyState
	isMSIE=isIE=document.all && document.all.item && !isOpera
	isStrict=document.compatMode=='CSS1Compat'
	isNN=isNC=navigator.appName=="Netscape"
	isNN4=isNC4=isNN && !isDOM
	isMozilla=isNN6=isNN && isDOM
	isSafari=(navigator.userAgent.indexOf("Safari") >= 0)
	isIE55 = (isIE && (match = navigator.appVersion.match(/MSIE (\d+\.\d+)/)) && parseInt(match[1]) < 6);

	isMac = (navigator.appVersion.indexOf("Mac") != -1);
	isMacPPC = (isMac && (navigator.appVersion.indexOf("PPC") != -1 || navigator.appVersion.indexOf("PowerPC") != -1));

	if(!isDOM && !isNC && !isMSIE && !isOpera){
		JSLayers=false
		return false
	}

	pageLeft=0
	pageTop=0

	imgCount=0
	imgArray=new Array()

	imageRef="document.images[\""
	imagePostfix="\"]"
	styleSwitch=".style"
	layerPostfix="\"]"

	if(isNN4){
		layerRef="document.layers[\""
		styleSwitch=""
	}

	if(isMSIE){
		layerRef="document.all[\""
	}

	if(isDOM){
		layerRef="document.getElementById(\""
		layerPostfix="\")"
	}

	JSLayers=true
	return true
}

initJSLayers()

// document and window functions:

function getBody(w){
	if(!w) w=window
	if(isStrict){
		return w.document.documentElement
	}else{
		return w.document.body
	}
}

function getWindowLeft(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return w.screenLeft
	if(isNN || isOpera) return w.screenX
}

function getWindowTop(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return w.screenTop
	if(isNN || isOpera) return w.screenY
}

function getWindowWidth(w){
	if(!w) w=window
	if(isMSIE) return getBody(w).clientWidth
	if(isNN || isOpera) return w.innerWidth
}

function getWindowHeight(w){
	if(!w) w=window
	if(isMSIE) return getBody(w).clientHeight
	if(isNN || isOpera) return w.innerHeight
}

function getDocumentWidth(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return getBody(w).scrollWidth
	if(isNN) return w.document.width
	if(isOpera) return w.document.body.style.pixelWidth
}

function getDocumentHeight(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return getBody(w).scrollHeight
	if(isNN) return w.document.height
	if(isOpera) return w.document.body.style.pixelHeight
}

function getScrollX(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return getBody(w).scrollLeft
	if(isNN || isOpera) return w.pageXOffset
}

function getScrollY(w){
	if(!w) w=window
	if(isMSIE || isOpera7) return getBody(w).scrollTop
	if(isNN || isOpera) return w.pageYOffset
}

function getLeft(o) {
	if (isMSIE || isMozilla || isOpera)
		return getPageOffset(o)[0]-pageLeft
	if (isNN4)
		return o.pageX-pageLeft
}

function getWidth (o) {
	if (isMSIE || isMozilla || isOpera7)
		return o.offsetWidth
	if (isOpera && o.css)
		return o.css.pixelWidth
	if (isNN4)
		return o.document.width
}

function getTop(o) {
	if (isMSIE || isMozilla || isOpera)
		return getPageOffset(o)[1]-pageTop
	if (isNN4)
		return o.pageY-pageTop
}

function setBGImage(obj, url) {
	if (isMSIE || isMozilla || isOpera6) {
		obj.style.backgroundImage = "url("+url+")";
	} else if (isNN4) {
		obj.css.background.src = url;
	}
}

function preloadImage(imageFile){
	imgArray[imgCount]=new Image();
	imgArray[imgCount++].src=imageFile;
	return imgCount-1;
}

var LAYER=0
var IMAGE=1

function findObject(what,where,type){
	var i,j,l,s
	var len=eval(where+".length")
	for(j=0;j<len;j++){
		s=where+"["+j+"].document.layers"
		if(type==LAYER){
			l=s+"[\""+what+"\"]"
		}
		if(type==IMAGE){
			i=where+"["+j+"].document.images"
			l=i+"[\""+what+"\"]"
		}
		if(eval(l)) return l
		l=findObject(what,s,type)
		if(l!="null") return l
	}
	return "null"
}

function getObjectPath(name,parent,type){
	var l=((parent && isNN4)?(parent+"."):(""))+((type==LAYER)?layerRef:imageRef)+name+((type==LAYER)?layerPostfix:imagePostfix)
	if(eval(l))return l
	if(!isNN4){
		return l
	}else{
		return findObject(name,"document.layers",type)
	}
}

function layer(name){
	return new JSLayer(name,null)
}

function layerFrom(name,parent){
	if(parent.indexOf("document.")<0) parent=layer(parent).path
	return new JSLayer(name,parent)
}

function image(name){
	return new JSImage(name,null)
}

function imageFrom(name,parent){
	if(parent.indexOf("document.")<0) parent=layer(parent).path
	return new JSImage(name,parent)
}

// class "JSLayer":

function JSLayer(name,parent){
	this.path=getObjectPath(name,parent,LAYER)
	this.object=eval(this.path)
	if(!this.object)return
	this.style=this.css=eval(this.path+styleSwitch)
}

JSL=JSLayer.prototype

JSL.isExist=JSL.exists=function(){
	return (this.object)?true:false
}

function getPageOffset(o){ 
	var left=0
	var top=0
	do{
		left+=o.offsetLeft
		top+=o.offsetTop
	}while(o=o.offsetParent)
	return [left, top]
}

JSL.getLeft=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera) return o.offsetLeft-pageLeft
	if(isNN4) return o.x-pageLeft
}

JSL.getTop=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera) return o.offsetTop-pageTop
	if(isNN4) return o.y-pageTop
}

JSL.getAbsoluteLeft=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera) return getPageOffset(o)[0]-pageLeft
	if(isNN4) return o.pageX-pageLeft
}

JSL.getAbsoluteTop=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera) return getPageOffset(o)[1]-pageTop
	if(isNN4) return o.pageY-pageTop
}

JSL.getWidth=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera7) return o.offsetWidth
	if(isOpera) return this.css.pixelWidth
	if(isNN4) return o.document.width
}

JSL.getHeight=function(){
	var o=this.object
	if(isMSIE || isMozilla || isOpera7) return o.offsetHeight
	if(isOpera) return this.css.pixelHeight
	if(isNN4) return o.document.height
}

JSL.getZIndex=function(){ //deprecated
	return this.css.zIndex
}

JSL.setLeft=JSL.moveX=function(x){
	x+=pageLeft
	if(isOpera){
		this.css.pixelLeft=x
	}else if(isNN4){
		this.object.x=x
	}else{
		this.css.left=x+"px"
	}
}

JSL.setTop=JSL.moveY=function(y){
	y+=pageTop
	if(isOpera){
		this.css.pixelTop=y
	}else if(isNN4){
		this.object.y=y
	}else{
		this.css.top=y+"px"
	}
}

JSL.moveTo=JSL.move=function(x,y){
	this.setLeft(x)
	this.setTop(y)
}

JSL.moveBy=function(x,y){
	this.moveTo(this.getLeft()+x,this.getTop()+y)
}

JSL.setZIndex=JSL.moveZ=function(z){ //deprecated
	this.css.zIndex=z
}

JSL.setVisibility=function(v){
	this.css.visibility=(v)?(isNN4?"show":"visible"):(isNN4?"hide":"hidden")
	this.visible = v;
}

JSL.show=function(){
	this.setVisibility(true)
}

JSL.hide=function(){
	this.setVisibility(false)
}

JSL.isVisible=JSL.getVisibility=function(){
	return (this.css.visibility.toLowerCase().charAt(0)=='h')?false:true
}

JSL.setBgColor=function(c){
	if(isMSIE || isMozilla || isOpera7){
		this.css.backgroundColor=c
	}else if(isOpera){
		this.css.background=c
	}else if(isNN4){
		this.css.bgColor=c
	}
}

JSL.setBgImage=function(url){
	if(isMSIE || isMozilla || isOpera6){
		this.css.backgroundImage="url("+url+")"
	}else if(isNN4){
		this.css.background.src=url
	}
}

JSL.setClip=JSL.clip=function(top,right,bottom,left){
	if(isMSIE || isMozilla || isOpera7){
		this.css.clip="rect("+top+"px "+right+"px "+bottom+"px "+left+"px)"
	}else if(isNN4){
		var c=this.css.clip
		c.top=top
		c.right=right
		c.bottom=bottom
		c.left=left
	}
}

JSL.scrollTo=JSL.scroll=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY){
	if(scrollX>this.getWidth()-windowWidth) scrollX=this.getWidth()-windowWidth
	if(scrollY>this.getHeight()-windowHeight) scrollY=this.getHeight()-windowHeight
	if(scrollX<0)scrollX=0
	if(scrollY<0)scrollY=0
	var top=0
	var right=windowWidth
	var bottom=windowHeight
	var left=0
	left=left+scrollX
	right=right+scrollX
	top=top+scrollY
	bottom=bottom+scrollY
	this.moveTo(windowLeft-scrollX,windowTop-scrollY)
	this.setClip(top,right,bottom,left)
}

JSL.scrollBy=JSL.scrollByOffset=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY){
	var X=-parseInt(this.css.left)+windowLeft+scrollX
	var Y=-parseInt(this.css.top)+windowTop+scrollY
	this.scroll(windowLeft,windowTop,windowWidth,windowHeight,X,Y)
}

JSL.scrollByPercentage=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY){
	var X=(this.getWidth()-windowWidth)*scrollX/100
	var Y=(this.getHeight()-windowHeight)*scrollY/100
	this.scroll(windowLeft,windowTop,windowWidth,windowHeight,X,Y)
}

JSL.write=function(str){
	var o=this.object
	if(isNN4){
		var d=o.document
		d.open()
		d.write(str)
		d.close()
	}else{
		o.innerHTML=str
	}
}

JSL.add=function(str){
	var o=this.object
	if(isNN4){
		o.document.write(str)
	}else{
		o.innerHTML+=str
	}
}

// class "JSImage":

JSI = JSImage.prototype

function JSImage(name){
	this.path=getObjectPath(name,false,IMAGE)
	this.object=eval(this.path)
}

JSI.isExist=JSI.exists=function(){
	return (this.object)?true:false
}

JSI.getSrc=JSI.src=function(){
	return this.object.src
}

JSI.setSrc=JSI.load=function(url){
	this.object.src=url
}

var loaders = new Array();
// class "JSLoader":

JSL = JSLoader.prototype;

function JSLoader() {
	this.valid = !(isNN4);

	this.checkTimes = 500;
	this.forceScriptLoad = false;

	this.requester = null;
	this.loadFunc = null;
	this.checkLoadFunc = null;
	this.loadErrorFunc = null;

	loaders[loaders.length] = this;
	this.id = loaders.length-1;
}

JSL.load = function(urlJS, urlData) {
	if (!this.valid)
		return false;

	this.urlJS = urlJS;
	this.urlData = urlData;
	if (!this.forceScriptLoad && this.getHTTPRequester()) {
		return this.HTMLLoad();
	} else {
		return this.ScriptLoad();
	}
}

JSL.HTMLLoad = function() {
	this.requester.open('GET', this.urlData, false);
	this.requester.send(null);

	this.response = this.requester.responseText;
	this.loaded = true;

	if (this.loadFunc) {
		return this.loadFunc(this);
	} else {
		return this.response;
	}
}

JSL.getHTTPRequester = function() {

	try {
    	this.requester = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (err) {}

    if (!this.requester)
		try {
        	this.requester = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (err) {}

	try {
	    if (!this.requester && XMLHttpRequest)
	        this.requester = new XMLHttpRequest();
	} catch (err) {}

    return this.requester;
}

JSL.ScriptLoad = function() {

    // Generate container
    this.span = document.body.appendChild(document.createElement("SPAN"));
    this.span.style.display = 'none';
    this.span.id = 'jslSpan'+this.spanId;
	this.spanId++;
    this.span.innerHTML = '4IE<s'+'cript></'+'script>';

    // Start request with delayed onload event
	this.scriptTO = setTimeout('loaders['+this.id+'].ScriptLoadStart();', 100);
}

JSL.ScriptLoadStart = function() {
	this.span.language = 'JavaScript';
	if (!isOpera && !isIE && !isSafari)
		this.span.onload = this.ScriptLoaded;

	if (this.span.setAttribute) {
		this.span.setAttribute('src', this.urlJS);
	} else {
		this.span.src = this.urlJS;
	}

	if (isOpera || isIE || isSafari)
		this.ScriptLoadCheck();
}

JSL.ScriptLoadCheck = function() {
	if (this.checkLoadFunc(this)) {
		this.ScriptLoaded();
	} else if (this.checkTimes > 0) {
		this.tm = setTimeout('loaders['+this.id+'].ScriptLoadCheck();', 100);
		this.checkTimes = this.checkTimes-1;
	} else if (this.loadErrorFunc) {
		this.loadErrorFunc(this);
	}
}

JSL.ScriptLoaded = function() {
alert(0);
	this.loaded = true;
	this.loadFunc(this);
}

