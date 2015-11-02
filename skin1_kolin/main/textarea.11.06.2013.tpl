{* $Id: textarea.tpl,v 1.5.2.3 2006/11/14 08:06:41 max Exp $ *}
{if $active_modules.HTML_Editor && !$disabled}
{include file="main/start_textarea.tpl" _include_once=1}
{assign var="id" value=$name|regex_replace:"/[^\w\d_]/":""}
<script type="text/javascript">
<!--
lbl_features = '{$lng.lbl_features}';
var abbrs = "{$abbreviations}".split(',');
{literal}
	for (i = 0; i < abbrs.length; i++) {
		abbrs[i] = jQuery.trim(abbrs[i]);
	}

	function implode( glue, pieces ) {
		return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
	}

	function ignore_abbreviations(a,b,c) {
		if (b.length > 0 && jQuery.inArray(b, abbrs) == -1) {
				return b + "<br />\r\n" + c;
		}
		return b + c;
	}

	function features_makeup(id) {
		var text = $('#' + id).val();
		var text_upper = text.toUpperCase();
		if (!text.match(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i)) {
			// step 0.5: delete space before [,!?.]
			var features = text.replace(/([a-zA-Z]*) +([\.\?!,])/g, '$1$2');
			// step 1: replace substrings like [word].
			features = features.replace(/([0-9a-zA-Z_"'`-]+[\.\?!])([^0-9])/g, ignore_abbreviations);
			features = features.replace(/([a-zA-Z_"'`-]+[\.\?!])([0-9])/g, ignore_abbreviations);
			// step 2: if there is no <br /> in the end of the string add it
			features = features.replace(/(.{1,6})\s*$/gm, function(a,b) { 
				if (b != '<br />') return b + "<br />"; else return a
			});
			// step 3: delete all spaces from the beggining of the strings
			features = features.replace(/^\s*(\S*)/gm, '$1');
			// step 4: add * in the beginning of every string
			features = features.replace(/^(.+)\s*$/gm, '* $1');
			// step 5: delete multiple spaces
			features = features.replace(/[ \t]+/gm, ' ');
			// step 6: add space after (, ; :)
			features = features.replace(/([,;:])(?!\s)/g, '$1 ');
			// step 7: apply product name replacement rules
			for (i = 0; i < reps.length; i++) {
				pattern = new RegExp(reps[i][0], 'g');
				features = features.replace(pattern, reps[i][1]);
			}
			// step 8: set uppercase for new line
			features = features.replace(/(\W.*?)([a-zA-Z])(.*?<br \/>)/g, function (a,b,c,d,e) {
				if ($.trim(b) == '*') {
					return b + c.toUpperCase() + d;
				}
				return b + c + d;
			});
			// step 9: remove <br /> from last line
//			features = features.replace(/(.*)<br \/>$/, function(a,b) { return b; });
			var newtext = "<b>" + lbl_features + ":</b><br />\r\n";
			newtext = newtext + features;
			$('#' + id).val(newtext);
		}
	}


	function remove_features(id) {
		var text = $('#' + id).val();
		if (text.match(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i)) {
			// step 1: delete "<b>Features:</b><br />..."
			var features = text.replace(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i, '');
			// step 2: delete "*" from the beginning of the string
			features = features.replace(/^\*\s*/gm,'');
			// step 3: delete <br /> from the end of the string
			features = features.replace(/<br\s*[\/]?>\s*$/gmi,'');
			$('#' + id).val(features);
		}
	}



        function features_makeup_new(id) {
                var text = $('#' + id).val();
                var text_upper = text.toUpperCase();
                if (!text.match(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i)) {

                        // step 0.5: delete space before [,!?.]
                        var features = text.replace(/([a-zA-Z]*) +([\.\?!,])/g, '$1$2');

                        // step 1: replace substrings like [word].
                        features = features.replace(/\b([0-9a-zA-Z_"'`-]+[\.\?!])([^0-9])/g, ignore_abbreviations);

                        //
                        features = features.replace(/[\r\n]/gm, ' ');
//                        features = features.replace(/[\.]+/gm, '.\r\n');

                        // step 2: if there is no <br /> in the end of the string add it
                        features = features.replace(/(.{1,6})\s*$/gm, function(a,b) {
                                if (b != '<br />') return b + "<br />"; else return a
                        });


			features = features.replace(/[\.<br\s/>\s]{8}/gm, '.<br />\r\n');	


                        // step 3: delete all spaces from the beggining of the strings
                        features = features.replace(/^\s*(\S*)/gm, '$1');

                        //
                        features = features.replace(/^[<br\s/>]{6}(\S*)/gm, '$1');

                        // step 4: add * in the beginning of every string
                        features = features.replace(/^(.+)\s*$/gm, '* $1');

                        // step 5: delete multiple spaces
                        features = features.replace(/[ \t]+/gm, ' ');

                        // step 6: add space after (, ; :)
                        features = features.replace(/([,;:])(?!\s)/, '$1 ');

                        // step 7: apply product name replacement rules
                        for (i = 0; i < reps.length; i++) {
                                pattern = new RegExp(reps[i][0], 'g');
                                features = features.replace(pattern, reps[i][1]);
                        }

                        // step 8: set uppercase for new line
                        features = features.replace(/(\W.*?)([a-zA-Z])(.*?<br \/>)/g, function (a,b,c,d,e) {
                                if ($.trim(b) == '*') {
                                        return b + c.toUpperCase() + d;
                                }
                                return b + c + d;
                        });

                        // step 9: remove <br /> from last line
//                        features = features.replace(/(.*)<br \/>$/, function(a,b) { return b; });

                        var newtext = "<b>" + lbl_features + ":</b><br />\r\n";
                        newtext = newtext + features;
                        $('#' + id).val(newtext);
                }
        }

        function remove_features_new(id) {
                var text = $('#' + id).val();
                if (text.match(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i)) {
                        // step 1: delete "<b>Features:</b><br />..."
                        var features = text.replace(/^\s*<b>\s*Features:\s*<\/b>\s*<br\s*[\/]?>\s*/i, '');
                        // step 2: delete "*" from the beginning of the string
                        features = features.replace(/^\*\s*/gm,'');
                        // step 3: delete <br /> from the end of the string
                        features = features.replace(/<br\s*[\/]?>\s*$/gmi,'');
                        features = features.replace(/[\r\n]/gm, ' ');
                        $('#' + id).val(features);
                }
        }


{/literal}
-->
</script>
{if $no_links ne "Y"}
<div class="AELinkBox" style="width: 576px;">
{if $name eq 'fulldescr'}
{*      <a href="javascript: void(0)" title="" onclick="javasctip: remove_features_new('{$id}');">Remove features (Make string)</a>&nbsp;&nbsp;&nbsp; *}
        <a href="javascript: void(0)" class="features" title="" onclick="javasctip: features_makeup('{$id}');">{$lng.lbl_apply_features}</a>&nbsp;&nbsp;
        <a href="javascript: void(0)" class="cidev_features_new" title="" onclick="javasctip: features_makeup_new('{$id}');">Apply features (D)</a>&nbsp;&nbsp;
{*      <a href="javascript: void(0)" title="" onclick="javasctip: remove_features('{$id}');">{$lng.lbl_remove_features}(LB+D)</a>&nbsp;&nbsp; *}
        <a href="javascript: void(0)" title="" onclick="javasctip: remove_features('{$id}');">{$lng.lbl_remove_features}</a>&nbsp;&nbsp;

{*
	<a href="javascript: void(0)" class="features" title="" onclick="javasctip: features_makeup('{$id}');">{$lng.lbl_apply_features}</a>&nbsp;&nbsp;&nbsp;
	<a href="javascript: void(0)" title="" onclick="javasctip: remove_features('{$id}');">{$lng.lbl_remove_features}</a>&nbsp;&nbsp;&nbsp;
*}
{/if}
<a href="javascript:void(0);" style="display: none;" id="{$id}Dis" onclick="javascript: disableEditor('{$id}','{$name}');">{$lng.lbl_default_editor}</a>
<b id="{$id}DisB">{$lng.lbl_default_editor}</b>
&nbsp;&nbsp;
<a href="javascript:void(0);" id="{$id}Enb" onclick="javascript: enableEditor('{$id}','{$name}');">{$lng.lbl_advanced_editor}</a>
<b id="{$id}EnbB" style="display: none;">{$lng.lbl_advanced_editor}</b>
</div>
{/if}
<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 576px;">{$data|escape:"html"}</textarea>
<div id="{$id}Box" style="width: 576px; display: none;">
<textarea id="{$id}Adv"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 576px;{if $no_links eq 'Y'}display:none;{/if}">{$data|escape:"html"}</textarea>

{include file="modules/HTML_Editor/editors/tinymce/textarea.tpl" id=$id name=$name data=$data}

</div>

<script type="text/javascript">
//<![CDATA[
var isOpen = getCookie('{$id}EditorEnabled');
if (isOpen && isOpen == 'Y')
  $('#{$id}Enb').click();
//]]>
</script>

{else}
<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if}{if $style} style="{$style}"{/if}{if $disabled} disabled="disabled"{/if}>{$data|escape:"html"}</textarea>
{/if}
