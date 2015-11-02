{*
$Id: button.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{assign var="js_link" value=$href|regex_replace:"/^\s*javascript\s*:/Si":""}
{if $js_link eq $href}
  {if $href}
    {assign var="is_link" value=true}
  {/if}
  {assign var="js_link" value=false}
  {assign var="href" value=$href|amp}
{else}
  {assign var="js_link" value=$href}
  {if $js_to_href ne 'Y'}
    {assign var="onclick" value=$href}
    {if $link_href}
      {assign var="href" value=$link_href}
    {else}
      {assign var="href" value="javascript:void(0);"}
    {/if}
  {/if}
{/if}
{if !$data_theme && $additional_button_class|@has_string:"main-button"}
  {assign var="data_theme" value="b"}
{/if}
{if $style eq 'link'}
  {if $type eq 'input'}
    {strip}
      <button type="submit" class="button{if $additional_button_class} {$additional_button_class}{/if}" title="{$title|default:$button_title|strip_tags|escape}"{if $js_link} onclick="{$js_link}"{/if} data-theme="{$data_theme|default:'b'}" data-inline="{$data_inline|default:'true'}"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if}{if $button_id} id="{$button_id}"{/if} data-mini="{$data_mini|default:'false'}">
        {$button_title|amp}
      </button>
    {/strip}
  {else}
    {strip}
      <a class="button{if $additional_button_class} {$additional_button_class}{/if}" href="{$href|amp}" {if $onclick ne ''} onclick="{$onclick}; return false;"{/if} title="{$title|default:$button_title|strip_tags|escape}" {if $target ne ''} target="{$target}"{/if} data-role="button" data-theme="{$data_theme|default:'c'}" data-inline="{$data_inline|default:'true'}"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if}{if $button_id} id="{$button_id}"{/if} data-mini="{$data_mini|default:'false'}"{if $rel_external} rel="external"{/if}>
        {$button_title|amp}
      </a>
    {/strip}
  {/if}
{elseif $style eq 'image'}
  {* TODO: explore if we have "image"-button in jQMobile *}
  {if $type eq 'input'}
    <a href="#"{if $js_link} onclick="{$js_link}"{/if}{if $button_id} id="{$button_id|escape}"{/if}{if $rel_external} rel="external"{/if}><img class="button{if $additional_button_class} {$additional_button_class}{/if}" src="{$ImagesDir}/spacer.gif" alt="{$title|default:$button_title|strip_tags|escape}" /></a>
  {else}
    {strip}
      <a class="button{if $additional_button_class} {$additional_button_class}{/if}" href="{$href|amp}"{if $onclick ne ''} onclick="{$onclick}"{/if} title="{$title|default:$button_title|strip_tags|escape}"{if $target ne ''} target="{$target}"{/if} data-role="button" data-icon="{$data_icon|default:'arrow-r'}" data-iconpos="notext" data-theme="{$data_theme|default:'c'}" data-inline="{$data_inline|default:'true'}" data-mini="{$data_mini|default:'false'}"{if $rel_external} rel="external"{/if}>
        {$title|default:$button_title|strip_tags|escape}
      </a>
    {/strip}
  {/if}
{elseif $is_link}

  <a class="button{if $additional_button_class} {$additional_button_class}{/if}" href="{$href}" onclick="{if $js_link}{$js_link};{else}javascript:{/if} if (event) event.cancelBubble = true;" title="{$title|default:$button_title|strip_tags|escape}"{if $button_id} id="{$button_id|escape}"{/if} data-role="button" data-theme="{$data_theme|default:'c'}" data-inline="{$data_inline|default:'true'}"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if} data-mini="{$data_mini|default:'false'}"{if $rel_external} rel="external"{/if}>{$button_title|amp}</a>

{elseif $style eq 'dropout'}
  {* TODO: explore dropout button *}
  <a href="#dropout_btn_{$prefix|default:'dropout'}_{$dropout_id}" data-rel="popup" class="button{if $additional_button_class} {$additional_button_class}{/if}" title="{$title|default:$button_title|strip_tags|escape}" data-role="button" data-theme="{$data_theme|default:'c'}" data-inline="{$data_inline|default:'true'}"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if} data-mini="{$data_mini|default:'false'}"{if $rel_external} rel="external"{/if}>{$button_title|amp}</a>
  <div id="dropout_btn_{$prefix|default:'dropout'}_{$dropout_id}" data-role="popup" data-theme="{$data_popup_theme|default:$data_theme|default:'c'}">
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-left">{$lng.lbl_close}</a>
    {include file=$dropout_tpl}
  </div>

{elseif $style eq 'div_button'}
  <div class="button{if $additional_button_class} {$additional_button_class}{/if}" title="{$title|default:$button_title|strip_tags|escape}"{if $js_link} onclick="{$js_link}"{/if}{if $button_id} id="{$button_id|escape}"{/if}  data-role="button"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if} data-theme="{$data_theme|default:'c'}" data-inline="{$data_inline|default:'true'}" data-mini="{$data_mini|default:'false'}">
    {$button_title|amp}
  </div>
{else}
  {strip}
    <button class="button{if $additional_button_class} {$additional_button_class}{/if}" type="{if $type eq 'input'}submit{else}button{/if}" title="{$title|default:$button_title|strip_tags|escape}"{if $js_link} onclick="{$js_link}"{/if}{if $button_id} id="{$button_id|escape}"{/if} data-theme="{if $type eq 'input'}{$data_theme|default:'b'}{else}{$data_theme|default:'c'}{/if}" data-inline="{$data_inline|default:'true'}"{if $data_icon} data-icon="{$data_icon}" data-iconpos="{$data_iconpos|default:'right'}"{/if} data-mini="{$data_mini|default:'false'}">
      {$button_title|amp}
    </button>
  {/strip}
{/if}
