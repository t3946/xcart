// $Id: func.js,v 1.3 2005/09/15 07:17:13 max Exp $
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

isMac = (navigator.appVersion.indexOf("Mac") != -1);
isMacPPC = (isMac && (navigator.appVersion.indexOf("PPC") != -1 || navigator.appVersion.indexOf("PowerPC") != -1));

var imgPlus = new Image;
var imgMinus = new Image;
var imgLine = new Image;
var imgTail = new Image;
var imgExp = new Image;
var imgTailExp = new Image;

imgPlus.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_plus.gif";
imgMinus.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_minus.gif";
imgLine.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_line.gif"; 
imgTail.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_line_tail.gif"; 
imgExp.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_line_exp.gif"; 
imgTailExp.src = skinWebPath+"/"+fcConfig.img_skin+"/tree_subdir_line_tail_exp.gif";

function SwitchTreeItem(cat, is_last) {
	var obj = document.getElementById(catPrefix+cat);
    if (!obj)
		return false;

	var minus_flag = (obj.style.display == "none");
	obj.style.display = (minus_flag ? (isMSIE ? "block" : "") : "none");
        
	obj = document.getElementById(catPrefix+cat+'img');
	if (obj)
		obj.src = (minus_flag?imgMinus.src:imgPlus.src);

	obj = document.getElementById(catPrefix+cat+'imgbg');
	if (!obj)
		return false;

	if (is_last)
		var ImgSrc = (minus_flag) ? imgTailExp : imgTail;
	else
		var ImgSrc = (minus_flag) ? imgExp : imgLine;

	obj.style.background = "url("+ImgSrc.src+")";
}
