{* $Id: category_selector.tpl,v 1.3.2.3 2006/11/30 07:56:14 max Exp $ *}
{if $display_field eq ''}{assign var="display_field" value="category_path"}{/if}
{literal}
<script type="text/javascript" language="javascript">
var isNN = document.layers ? true : false;
var isIE = document.all ? true : false;
var mouseX;
var mouseY;

init();

function init() {
	if ( isNN )
		document.captureEvents(Event.MOUSEMOVE)
    document.onmousemove = handleMouseMove;
}

function handleMouseMove(evt) {
	mouseX = !isIE ? evt.pageX : window.event.clientX;
	mouseY = !isIE ? evt.pageY : window.event.clientY;

	return true;
}

function hideTitle(id) {
	var layer = document.getElementById(id);
	layer.style.display = "none"; 
}

function showTitle(value, position) {
	if (value.length < 40) {
		return;
	}
	if (!isIE) {
		var layer = document.getElementById('layer');
		setTimeout("hideTitle('layer');", 3000);
		layer.innerHTML = value;
	} else {
		var layer = document.getElementById('iframe');
		setTimeout("hideTitle('iframe');", 3000);
		layer.style.width = value.length * 6;
		layer.contentWindow.document.body.innerHTML = value;
		layer.contentWindow.document.body.style.fontSize = "12px";
		layer.contentWindow.document.body.style.marginLeft = "0px";
		layer.contentWindow.document.body.style.marginTop = "0px";
		layer.contentWindow.document.body.style.background = "#FFFBD3";
	}
		layer.style.display = "";
		if (position == 'left') {
			var length = layer.style.width.substr(0, layer.style.width.length - 2);
			layer.style.left = (mouseX - length) + "px";
		} else if (position == 'right') {
			layer.style.left = mouseX+"px";
		}
		layer.style.top = mouseY+"px";
}
</script>
{/literal}
<div id='layer' style="display:none; position:absolute; background-color:#FFFBD3; border:1px solid #000000;left:0px;top:0px;z-index: 10;"></div>
<iframe  scrolling="no" frameborder="0" style="position:absolute;top:0px;left:0px;display:none;width:100px;height:14px;" id="iframe" src="{$ImagesDir}/spacer.gif"></iframe>
<select name="{$field|default:"categoryid"}"{$extra} onChange="{if $override_onchange eq ''}javascript: showTitle(this.options[this.selectedIndex].text, 'right');{else}{$override_onchange}{/if}">
{if $display_empty eq 'P'}
<option value="">{$lng.lbl_please_select_category}</option>
{elseif $display_empty eq 'E'}
<option value=""></option>
{/if}
{foreach from=$allcategories item=c key=catid}
<option value="{$catid}"{if $categoryid eq $catid} selected="selected"{/if}>{$c.$display_field}</option>
{/foreach}
</select>
