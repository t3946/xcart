{* $Id: browser_identificator_js.tpl,v 1.3.2.3 2004/11/22 09:16:26 max Exp $ *}
{literal}
function browser_identificator() {
var x, browser, version, screen_x, screen_y;
var plugins = '';
	for(x = 0; x < navigator.plugins.length; x++)
		plugins += (plugins == ''?"":"|")+urlEncode(navigator.plugins[x].name);
	screen_x = screen.width;
	screen_y = screen.height;
	return (localIsDOM?"Y":"N")+(localIsStrict?"Y":"N")+(localIsJava?"Y":"N")+"|"+localBrowser+"|"+localVersion+"|"+localPlatform+"|"+(localIsCookie?"Y":"N")+"|"+screen_x+"|"+screen_y+"|"+plugins;
}
{/literal}
