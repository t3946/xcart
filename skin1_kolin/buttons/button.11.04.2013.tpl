{* $Id: button.tpl,v 1.16.2.1 2004/11/01 07:33:15 max Exp $ *}
{if $config.Adaptives.platform eq 'MacPPC' && $config.Adaptives.browser eq 'NN'}{assign var="js_to_href" value="Y"}{/if}
{if $type eq 'input'}{assign var="img_type" value='INPUT type="image"'}{else}{assign var="img_type" value='IMG'}{/if}
{assign var="js_link" value=$href|regex_replace:"/^\s*javascript\s*:/Si":""}
{if $js_link eq $href}{assign var="js_link" value="javascript: self.location='`$href`'"}
{else}{assign var="js_link" value=$href}{if $js_to_href ne 'Y'}{assign var="onclick" value=$href}{assign var="href" value="javascript: void(0);"}{/if}{/if}

{if $usertype eq "C"}
{if $class eq "ajax_button"}
{assign var="class" value="new_button_green"}
{else}
{assign var="class" value="new_button_blue"}
{/if}
{/if}

{if $new_add_to_cart_btn eq "Y"}

<span onclick="{$js_link}" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_b">
<span class="t">{$button_title}</span>
</span>

{else}

{if $class eq "new_button_green" || $class eq "new_button_blue"}
<span onclick="{$js_link}" class="{$class}" style="cursor: pointer;">
{$button_title}
</span>
{else}
<table border="0" cellspacing="0" cellpadding="0" onclick="{$js_link}" style="cursor: pointer;" valign="middle"{if $title ne ''} title="{$title}"{/if}{if $class} class="{$class}"{/if}>
<tr>
<td class="Button2Off" valign="middle" onMouseOver="this.className='Button2On'" onMouseOut="this.className='Button2Off'"><font class="Button2" {if $b_size ne ""}style="font-size: {$b_size}px;"{/if}>&nbsp;{if $b eq "1"}<b>{/if}{$button_title}&nbsp;{if $b eq 1}</b>{/if}</font></td>
</tr>
</table>
{/if}

{/if}
