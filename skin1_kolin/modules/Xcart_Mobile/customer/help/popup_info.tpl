{*
$Id: popup_info.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div data-role="dialog" id="popup-dialog" data-url="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}">
  <div data-role="header" data-inline="true">
    <h1>{$popup_title|default:"&nbsp;"}</h1>
  </div>
  <div data-role="content" class="popup-dialog-content">
    {include file="customer/dialog_message.tpl"}
    {if $template_name ne ""}
      {include file=$template_name assign="popup_template"}
      {$popup_template|replace:'<form':'<form data-ajax="false"'}
    {elseif $pre ne ""}
      {$pre|replace:'<form':'<form data-ajax="false"'}
    {else}
      {include file="main/error_page_not_found.tpl"}
    {/if}
    
  </div>
  
</div>
