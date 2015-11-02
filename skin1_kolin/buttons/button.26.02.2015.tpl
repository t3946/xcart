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


{if $button_type eq "submit_order"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_submit_order"></span>
{elseif $button_type eq "continue"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_continue"></span>
{elseif $button_type eq "submit"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_submit"></span>
{elseif $button_type eq "checkout"} 
<span onclick="{$js_link}" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_checkout"></span>
{elseif $add_to_cart_btn eq "big"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_big"></span>
{elseif $add_to_cart_btn eq "small"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_small"></span>
{elseif $new_add_to_cart_btn eq "Y"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_b">
<span class="t">{$button_title}</span>
</span>

{elseif $btn_to_checkout eq "Y" || ($smarty.get.mode eq "checkout" && ($button_title eq "Submit" || $button_title eq "Continue" || $button_title eq "Submit order")) || ($smarty.get.mode eq "update" && $smarty.get.action eq "cart" && $button_title eq "Submit")}


<span onclick="{$js_link}" style="cursor: pointer;" id="btn_to_checkout" class="btn_to_checkout">
<span class="t">{$button_title}</span>
</span>


{elseif $btn_other eq "Y"}
<span onclick="{$js_link}" style="cursor: pointer;" id="btn_other" class="btn_other">
<span class="t">{$button_title}</span>
</span>
{else}
{if $class eq "new_button_green" || $class eq "new_button_blue"}

	{if $class eq "new_button_blue"}

{*		<input type="button" value="{$button_title}" class="cidev_new_button cidev_new_white" onclick="{$js_link}"> *}
		<span class="cidev_new_button cidev_new_white" onclick="{$js_link}">{$button_title}</span>

{*
	{if $class eq "new_button_blue" && $button_title ne "More info"}
		<span onclick="{$js_link}" style="cursor: pointer;" id="btn_other" class="btn_other">
		<span class="t">{$button_title}</span>
		</span>
*}
	{else}
		<span onclick="{$js_link}" class="{$class}" style="cursor: pointer;">
		{$button_title}
		</span>
	{/if}
{else}
<table border="0" cellspacing="0" cellpadding="0" onclick="{$js_link}" style="cursor: pointer;" valign="middle"{if $title ne ''} title="{$title}"{/if}{if $class} class="{$class}"{/if}>
<tr>
<td class="Button2Off" valign="middle" onMouseOver="this.className='Button2On'" onMouseOut="this.className='Button2Off'"><font class="Button2" {if $b_size ne ""}style="font-size: {$b_size}px;"{/if}>{if $b eq "1"}<b>{/if}{if $blue_link eq "Y"}<span style="color: blue;">{/if}{$button_title}{if $blue_link eq "Y"}</span>{/if}{if $b eq 1}</b>{/if}</font></td>
</tr>
</table>
{/if}

{/if}
