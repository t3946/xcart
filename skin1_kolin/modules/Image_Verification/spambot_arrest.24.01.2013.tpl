{*$Id: spambot_arrest.tpl,v 1.1.2.1 2006/12/07 08:28:08 svowl Exp $*}

{if !$id}
{assign var="id" value="image"}
{/if}
{if $mode eq 'advanced'}
<tr>	
	<td colspan="3">

{if $reg_id ne ""} {* Message id:  537841186 *}
    <div id="{$reg_id}" {if $antibot_err eq "" and $show_code ne "Y"}style="display:none;"{/if}>
{/if}
<table width="100%">
<tr>
	<td colspan="2" class="RegSectionTitle">{$lng.lbl_word_verification}<hr size="1" noshade="noshade" /></td>
</tr>
<tr>
	<td colspan="2">{$lng.lbl_type_the_characters}</td>
</tr>
<tr>
	<td nowrap="nowrap" align="left" width="10%">
<img src="{$xcart_web_dir}/antibot_image.php?section={$id}" id="{$id}" alt="" /><br />
{if $js_enabled eq 'Y'}
<a class="VertMenuItems" href="javascript: change_antibot_image('{$id}');">{$lng.lbl_get_a_different_code}</a>&nbsp;&nbsp;&nbsp;
{/if}
	</td>
	<td align="left">
<input type="text" name="antibot_input_str" />
{if $antibot_err}
<font class="Star">&nbsp;&lt;&lt;</font>
{/if}
{if $is_flc}
<input type="hidden" name="login_antibot_on" value="1" />
{/if}
	</td>
</tr>
</table>
{if $reg_id ne ""}
    </div>
{/if}
	</td>
</tr>

{elseif $mode eq 'simple'}

<tr>
	<td colspan="3">

<br />

<table cellpadding="3" cellspacing="1">
<tr>
	<td colspan="2">{$lng.lbl_type_the_characters}:</td>
</tr>
<tr>
	<td  align="left" width="10%">
<img src="{$xcart_web_dir}/antibot_image.php?section={$id}" id="{$id}"alt="" /><br />
{if $js_enabled eq 'Y'}
<a href="javascript: change_antibot_image('{$id}');">{$lng.lbl_get_a_different_code}</a>
{/if}
	</td>
	<td align="left">
<input type="text" name="antibot_input_str" />
{if $antibot_err}
<font class="Star">&nbsp;&lt;&lt;</font>
{/if}
	</td>
</tr>
</table>

	</td>
</tr>
{elseif $mode eq 'simple_column'}
<tr>
	<td colspan="3">

	<br />

<table cellpadding="3" cellspacing="1">
<tr>
	<td colspan="2">{$lng.lbl_type_the_characters}:</td>
</tr>
<tr>
	<td  align="left" width="10%" colspan="2">
	<img src="{$xcart_web_dir}/antibot_image.php?section={$id}" id="{$id}"alt="" /><br />
	{if $js_enabled eq 'Y'}
	<a href="javascript: change_antibot_image('{$id}');">{$lng.lbl_get_a_different_code}</a>
	{/if}
	</td>
</tr>	
<tr>	
	<td align="left" colspan="2">
	<input type="text" name="antibot_input_str" />
	{if $antibot_err}
	<font class="Star">&nbsp;&lt;&lt;</font>
	{/if}
	</td>
</tr>
</table>

</td>
</tr>

{/if}
