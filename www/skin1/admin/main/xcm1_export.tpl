{if $m1_module}

{*Feed page*}
<script type="text/javascript" src="{$xcart_web_dir}/m1/libs/jquery/jquery.js"></script>
<script type="text/javascript" src="{$xcart_web_dir}/m1/export_all/common/func.js"></script>
<script language="javascript">
<!--
{literal}
function SelectAll(id)
{
    var el;
    el = document.getElementById(id);
    for(i=0; i < el.length; i++)
    {
        el.options[i].selected = true;
    }

}

function UnSelectAll(id)
{
    var el;
    el = document.getElementById(id);
    for(i=0; i < el.length; i++)
    {
        el.options[i].selected = false;
    }

}

function up(act) {
    if (check_fields(act) && UcConfirmed(act))
    {
          document.m1_export_form.action = 'm1_{/literal}{$m1_module->ProductCode}{literal}.php?action='+act;
          document.m1_export_form.submit();
    }
    return false;
}

function trim(tmp)
{
 blanks={' ':true,"\n":true,"\r":true,"\t":true};
 while (blanks[tmp.charAt(0)])//ltrim
  tmp=tmp.substring(1,tmp.length);
 while (blanks[tmp.charAt(last=tmp.length-1)])
  tmp=tmp.substring(0,last); //rtrim
 return tmp;
}
        
function check_fields(act){
	var message = '';
    {/literal}
    {foreach from=$cfgArr key=key item=value}
   	{if $value.Required or $value.AllowedChars}
   	{if $value.Required}
   	var required = true;
   	{else}
   	var required = false;
   	{/if}
   	{if $value.Section eq 8}
   	if (document.getElementById('ga_options_row_1').style.display != 'none')
   	{/if}
   	message = CheckField(document.m1_export_form.{$value.Key}, '{$value.Title}', message, '{$value.AllowedChars}', required);
   	{/if}
   	{/foreach}
   	{literal}
   	
   	if (message) {
   		message = AddMessage('Feed with empty required fields will be rejected during submission', message);
   	}
   	
   	switch (act) {
   		case 'save_config':
   			document.m1_export_form.config_name.value = trim(document.m1_export_form.config_name.value);
   			message = CheckField(document.m1_export_form.config_name, 'Save Configuration As', message, '', true);
   			break;
   		case 'load_config':
   		case 'replace_config':
   		case 'delete_config':
   			message = CheckField(document.m1_export_form.m1_config_list, 'Saved configurations', message, '', true);
   			break;
   	}
   	if (message){
   		alert(message);
   		return false;
   	} else { 
   		return true;
   	}
}

function CheckField(field, title, message, allowed_chars, required) {
	
	if ((!field.value || (field.value == 0)) && (required)) {
		
		//Required field is empty
   		message = AddMessage(title + ' is required', message);
	
	} else {
		
		//Validate value
		if ((allowed_chars) && (field.value)) {
			var re = new RegExp('[^'+allowed_chars+']');
   		
			if (field.value.search(re) != -1) {
				
				//Value contains invalid characters
   				message = AddMessage(title + ' field contains invalid characters!', message);
   			}
		}
	}
	return message;
}

function AddMessage(add_line, message) {
	message = message + add_line + "\n";
	return message;
}

function UcConfirmed(act) {
	switch (act) {
		case 'delete_config':
			return confirm('Are you sure to delete config '+document.m1_export_form.m1_config_list.value+'?');
			break;
		case 'replace_config':
			return confirm('Are you sure to replace config '+document.m1_export_form.m1_config_list.value+'?');
			break;
		default:
			return true;
			break;
	}
}

function check_select_option(select, last_selected) {
	if (!select.value) {
		select.value=last_selected;
	}
	return select.value;
}

function getSelected(opt) {
	var selected = new Array();
	var index = 0;
	for (var intLoop=0; intLoop < opt.length; intLoop++) {
		if (opt[intLoop].selected) {
			index = selected.length;
			selected[index] = opt[intLoop].value;
		}
	}
	var output = selected.join(",");
	return output;
}

var isIE = (document.all) ? 1 : 0;

function keyFilter(e, strPattern)
{
   var chr = (isIE) ? e.keyCode : e.which;
   var ch = String.fromCharCode(chr);

   if (chr != 13 && chr != 8 && chr != 0)
   {
      var re = new RegExp(strPattern);

      if (ch.search(re) == -1)
      {
         if(isIE)
          e.returnValue = false;
         else
          e.preventDefault();
      }
   }
}

function ShowCategoryMatching(url) {
	$.post(
		url, 
		{ categories: getSelected(document.getElementById('export_categories')) },
		function () {
			window.open(url, 'm1_category_matching');
		}
	);
}

function setGoogleAnalyticsOptions() {
	var r;
	var k = 5 + 1;	//Count of Google Analytics options + separator

	for (i = 1; i <= k; i++) {
		r=document.getElementById('ga_options_row_' + i);
		
		if (r.style.display == 'none') {
			r.style.display = '';

		} else {
			r.style.display = 'none';
		}
	}
}

function fillList(listId, values, defValue) {
	var objSel = document.getElementById(listId);
	
	if (objSel != 'undefined') {

		//clear list
		objSel.options.length = 0;

		//fill list
		for (var i = 0; i < values.length; i++) {
			objSel.options[i] = new Option(values[i][1], values[i][0]);

			if (values[i][1] == defValue) {
				objSel.options[i].selected = true;
			}
		}
	}
}

{/literal}
{$feed_javascript}
-->
</script>

{literal}
<style type="text/css">

#export_list_container {
width: 400px;
overflow: auto;
}

</style>
{/literal}

{strip}

<H1 align=center>
{if $m1_module->ProductCode eq 'amazon'}
{$m1_export_company_name} {$m1_module->Caption} Export Settings
{else}
{$m1_export_company_name} {$m1_module->Caption} Export Configuration{if $m1_module->Beta} (Beta){/if}
{/if}
</H1>
{/strip}
<TABLE border="0" cellpadding="0" cellspacing="0" {$extra} width="100%" align="center">
<TR> 
{strip}
<TD height="15" class="DialogTitle" background="{$ImagesDir}/dialog_bg_n.gif" valign="bottom">&nbsp;&nbsp;
{if $m1_module->ProductCode eq 'amazon'}
{$m1_export_company_name} {$m1_module->Caption} Export Settings
{else}
{$m1_module->Caption} Export Configuration{if $m1_module->Beta} (Beta){/if}
{/if}
</TD>
{/strip}
</TR>
<TR><TD class="DialogBorder"><TABLE border="0" cellpadding="10" cellspacing="1" width="100%">
<TR><TD class="DialogBox">
<table width=100% border="0">
<tr>
<td align="left"><form method="post" action="m1_{$m1_module->ProductCode}.php?action=update" id="m1_export_form" name="m1_export_form">
<table width="80%" align="center">
<tr>
<td>
{if $output_sales_channel_fieldset}
<!-- Sales Channel Analytics section -->
<fieldset><legend>Sales Channel Analysis</legend>
<table border="0" cellpadding="10" cellspacing="1" width="100%">
<tr><td class="DialogBox"><a href="m1_export_stats.php?interval=day&start={$curdate_label}&source={$m1_module->ProductCode}">Click here to see statistics on this Sales Channel</a></td></tr>
</table>    
</fieldset><br>
<!-- End of Sales Channel Analytics section -->
{/if}

{if $license_section}
<!-- License section -->
<fieldset><legend>{$license_section_title}</legend>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
{$license_section}
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2" align="center">{$buttons}</td>
</tr>
</table>    
</fieldset><br>
<!-- End of license section -->
{/if}

{$control_sections}

{if $output_ftp_fieldset}

<!-- Export File / FTP / Email section -->

<fieldset><legend>Export File / FTP / Email</legend>
{assign var = "filename" value = `$m1_module->ProductName`FILENAME}
{assign var = "save_directory" value = `$m1_module->ProductName`SAVE_DIRECTORY}
{assign var = "gzip" value = `$m1_module->ProductName`GZIP}
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td width="35%" align="right" valign="top">Export File Name:</td>
  <td><input type="text" style="width: 200px;" name="{$m1_module->ProductName}FILENAME" value="{$cfgData[$filename]}" size="30">&nbsp;<font color="Red">*&nbsp;Required</font><br>
  Use file name to separate from files for different services.<br>Recommended to use name: {$m1_module->feedDefaultFilename}</td>
</tr>
<tr>
  <td width="35%" align="right" valign="top">Server Encoding:</td>
  <td>{$server_encoding_field}</td>
</tr>
<tr>
  <td width="35%" align="right" valign="top"></td>
  <td><input type="checkbox" name="{$m1_module->ProductName}GZIP"{if $cfgData[$gzip]} checked{/if}><b>&nbsp;Check to Compress Output (GZIP)</b><br>
  Check to enable compression of feed contents</td>
</tr>
<tr>
  <td width="35%" align="right" valign="top">Save to Directory on Server:</td>
  <td><input type="text" style="width: 200px;" name="{$m1_module->ProductName}SAVE_DIRECTORY" value="{$cfgData[$save_directory]}" size="30"><br>
  Ignored if empty. Path can be absolute or relative, starting with <b>{$xcart_dir}</b><br>Example 1: <b>upload/</b><br>Example 2: <b>../somedir/subdir</b></td>
</tr>
{$ftp_section}
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2" align="center">{$buttons}</td>
</tr>
</table>    
</fieldset><br>

<!-- End of Export File / FTP / Email section -->

{/if}

{if $grouped_configuration[9]}

<!-- Restrictions section -->
<fieldset><legend>Restrictions</legend>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
{foreach from=$grouped_configuration[9] item=option key=key}
<tr>
  <td width="35%" align="right" valign="top">{$option.Title}:</td>
  <td>{$restriction_controls[$key]}{$option.Description}</td>
</tr>
{/foreach}
</table>
</fieldset><br>
<!-- End of Restrictions section -->

{/if}

{if $output_configuration_fieldset}

<!-- Configuration section -->

<fieldset><legend>Configuration</legend>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
{if $m1_save_config_res eq 1}
<tr>
  <td colspan="2" style="color:red" align="center"><b>{$m1_export_text_config_name_exists}</b></td>
</tr>
{/if}
<tr>
  <td width="35%" align="right" valign="top">Save Configuration As:</td>
  <td><input style="width:200px;" type="text" name="config_name" value="" size=24>
  <input type="button" onclick='up("save_config");' value="SAVE"><br>
Configuration is set of parameters that you can call at once.<br>
It is good to use it when you need different configurations for different target systems.<br>
See example how to call specific configuration below
  </td>
</tr>
<tr>
   <td colspan="2"></td>
</tr>
<tr>
  <td align="right" valign="top">Saved Configurations:</td>
  <td><select name="m1_config_list">
      {foreach from=$saved_config_list item=item key=key}
      <option value="{$item.id}">{$item.text}</option>
      {/foreach}
      </select>
      <br><br>
      <input type="button" onclick='up("load_config");' value="LOAD">
      <input type="button" onclick='up("replace_config");' value="REPLACE">
      <input type="button" onclick='up("delete_config");' value="DELETE">
</td>
</tr>
</table>
</fieldset><br>
<!-- End of configuration section -->
{/if}

{if $live_links}

<!-- Information for large stores section -->
<fieldset>
<legend>For Large Stores</legend>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>
  <a href="{$http_location}/m1_export.php?export={$m1_module->ProductCode}&chunked=1" style="font-weight: bold;">Click here to export large number of products</a>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr>
   <td>
This tool should be used to export large number of products (5000+) OR when you have any problems exporting using primary program controls.
Please create temporary directory - "tmp" in store web root directory and make it writable.<br><br>
If you face any issues during generation of the feed, we recommend to select smaller number of categories or to generate a separate feed for each category.
   </td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
</table>
</fieldset><br>
<!-- End of information for large stores section -->

{/if}

<!-- Information section -->
<fieldset>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
{if $live_links}
<tr>
  <td>
  <h3 class="pageheading">Your Store Live Product Feed</h3>
  {$live_links}
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
{/if}
{$sections}
<tr>
   <td align=center>{$m1_export_footer}</td>
</tr>
</table>
</fieldset>
<!-- End of information section -->
</form>
</td>
</tr>
</table>
</td></tr>
</table></td></tr>
</table></td></tr>
</table>
{$m1_export_init_code}

{else}

{*Main export page*}
<table width=100%>
<tr>
  <td>
  <table>
  <tr height="2"><td ></td></tr>
  <tr>
    <td class="smallText" valign="top"><H1>{$m1_export_company_name} Export Tools:</H1></td>
  </tr>
  <tr height="2"><td ></td></tr>
  <tr height="2"><td >Set of tools{if $m1_export_company_name} developed by {if $m1_export_company_url}<A href="{$m1_export_company_url}" target=_blank>{/if}{$m1_export_company_name}{if $m1_export_company_url}</A>{/if}{/if}, which would help maximize outcome from your on-line store</td></tr>
  <tr height="2"><td ></td></tr>
  </table>
  </td>
</tr>
</table>

{/if}