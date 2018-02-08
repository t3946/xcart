{* $Id: textarea.tpl,v 1.5.2.3 2006/11/14 08:06:41 max Exp $ *}
{if $active_modules.HTML_Editor && !$disabled}
{assign var="id" value=$name|regex_replace:"/[^\w\d_]/":""}
<div class="AELinkBox" style="width: 576px;">
<a href="javascript: void(0);" style="display: none;" id="{$id}Dis" onclick="javascript: disableEditor('{$id}','{$name}', {$id}Editor);">{$lng.lbl_default_editor}</a>
<b id="{$id}DisB">{$lng.lbl_default_editor}</b>
&nbsp;&nbsp;
<a href="javascript: void(0);" id="{$id}Enb" onclick="javascript: enableEditor('{$id}','{$name}', {$id}Editor);">{$lng.lbl_advanced_editor}</a>
<b id="{$id}EnbB" style="display: none;">{$lng.lbl_advanced_editor}</b>
</div>
<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 576px;">{$data|escape:"html"}</textarea>
<div id="{$id}Box" style="width: 576px;">
<textarea id="{$id}Adv"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 576px;">{$data|escape:"html"}</textarea>
<script type="text/javascript">
<!--

	if (isHTML_Editor) {ldelim}
		if (!isHTML_EditorFF)
			document.getElementById('{$id}Box').style.display = 'none';

		var {$id}Editor = new InnovaEditor('{$id}Editor');
		{$id}Editor.width = 576;
		if (navigator.appName.indexOf('Microsoft')!=-1)
			{$id}Editor.height = {$rows|default:30}*13;
		else if (navigator.appName.indexOf('Netscape')!=-1)
			{$id}Editor.height = {$rows|default:30}*14;
		else
			{$id}Editor.height = {$rows|default:30}*12;

		{$id}Editor.mode = '{$html_editor_mode|default:"XHTMLBody"}';
		{$id}Editor.REPLACE("{$id}Adv");
		if (isHTML_EditorFF)
			document.getElementById('{$id}Box').style.display = 'none';

		var reg = new RegExp("(;|^){$id}EditorEnabled=Y","");
		if (document.cookie.search(reg) != -1)
			document.getElementById('{$id}Enb').onclick;

	{rdelim} else
		document.getElementById('{$id}Box').style.display = 'none';
-->
</script>
</div>
{else}
<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if}{if $style} style="{$style}"{/if}{if $disabled} disabled="disabled"{/if}>{$data|escape:"html"}</textarea>
{/if}
