{* $Id: common_js.tpl,v 1.2.2.4 2005/01/06 13:56:24 max Exp $ *}
{literal}
<SCRIPT type="text/javascript" language="JavaScript 1.2">
<!--

//
// Enviroment identificator
//
var localIsDOM = document.getElementById?true:false;
var localIsJava = navigator.javaEnabled();
var localIsStrict = document.compatMode=='CSS1Compat';
var localPlatform = navigator.platform;
var localVersion = "0";
var localBrowser = "";
if(window.opera && localIsDOM) {
	localBrowser = "Opera";
	if(navigator.userAgent.search(/^.*Opera.([\d.]+).*$/) != -1)
		localVersion = navigator.userAgent.replace(/^.*Opera.([\d.]+).*$/, "$1");
	else if(window.print)
		localVersion = "6";
	else
		localVersion = "5";
} else if(document.all && document.all.item)
	localBrowser = 'MSIE';
if(navigator.appName=="Netscape") {
	if(!localIsDOM) {
		localBrowser = 'Netscape';
		localVersion = navigator.userAgent.replace(/^.*Mozilla.([\d.]+).*$/, "$1");
		if(localVersion != '')
			localVersion = "4";
	} else if(navigator.userAgent.indexOf("Safari") >= 0)
		localBrowser = 'Safari';
	else if(navigator.userAgent.indexOf("Netscape") >= 0)
		localBrowser = 'Netscape';
	else if(navigator.userAgent.indexOf("Firefox") >= 0)
		localBrowser = 'Firefox';
	else 
		localBrowser = 'Mozilla';
	
}
if(navigator.userAgent.indexOf("MSMSGS") >= 0)
	localBrowser = "WMessenger";
else if(navigator.userAgent.indexOf("e2dk") >= 0)
	localBrowser = "Edonkey";
else if(navigator.userAgent.indexOf("Gnutella") + navigator.userAgent.indexOf("Gnucleus") >= 0)
	localBrowser = "Gnutella";
else if(navigator.userAgent.indexOf("KazaaClient") >= 0)
	localBrowser = "Kazaa";

if(localVersion == '0' && localBrowser != '') {
	var rg = new RegExp("^.*"+localBrowser+".([\\d.]+).*$");
	localVersion = navigator.userAgent.replace(rg, "$1");
}
var localIsCookie = ((localBrowser == 'Netscape' && localVersion == '4')?(document.cookie != ''):navigator.cookieEnabled);

//
// Opener/Closer HTML block
//
function visibleBox(id,skipOpenClose) {
	elm1 = document.getElementById("open"+id);
	elm2 = document.getElementById("close"+id);
	elm3 = document.getElementById("box"+id);

	if(!elm3)
		return false;

	if (skipOpenClose) {
		elm3.style.display = (elm3.style.display == "")?"none":"";
	} else if(elm1) {
		if (elm1.style.display == "") {
			elm1.style.display = "none";
			if(elm2)
				elm2.style.display = "";
			elm3.style.display = "none";
		} else {
			elm1.style.display = "";
			if(elm2)
				elm2.style.display = "none";
			elm3.style.display = "";
		}
	}
}

//
// URL encode
//
function urlEncode(url) {
	return url.replace(/\s/g, "+").replace(/&/, "&amp;").replace(/"/, "&quot;")
}

//
// Substitute
//
function substitute(lbl) {
var x, rg;
	for(x = 1; x < arguments.length; x+=2) {
		if(arguments[x] && arguments[x+1]) {
			rg = new RegExp("\\{\\{"+arguments[x]+"\\}\\}", "gi");
			lbl = lbl.replace(rg,  arguments[x+1]);
			rg = new RegExp('~~'+arguments[x]+'~~', "gi");
			lbl = lbl.replace(rg,  arguments[x+1]);
		}
	}
	return lbl;
}

-->
</SCRIPT>
{/literal}
