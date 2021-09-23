{*
$Id: language_selector.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $all_languages|@count gt 1}
  {capture name="lang_selector"}
    <ul data-role="listview" data-theme="c">
      <li data-role="list-divider" data-theme="b">
        {$lng.lbl_select_language}
      </li>
      {foreach from=$all_languages item=l name=languages}
        <li>
          {if $store_language eq $l.code}
            {assign var="current_lang" value=$l}
            <img class="ui-li-icon" src="{if not $l.is_url}{$current_location}{/if}{$l.tmbn_url|amp}" alt="{$l.language|escape}" title="{$l.language|escape}" width="{$l.image_x}" height="{$l.image_y}" />{$l.language}
          {else}
            <a href="{$smarty.server.PHP_SELF}?{if $smarty.server.QUERY_STRING}{$smarty.server.QUERY_STRING}&{/if}sl={$l.code}"><img class="ui-li-icon" src="{if not $l.is_url}{$current_location}{/if}{$l.tmbn_url|amp}" alt="{$l.language|escape}" title="{$l.language|escape}" width="{$l.image_x}" height="{$l.image_y}" />{$l.language}</a>
          {/if}
        </li>
      {/foreach}
    </ul>
  {/capture}
  
  <a href="#popup-lang" data-role="button" data-inline="false" data-theme="a" data-rel="popup"><img src="{if not $current_lang.is_url}{$current_location}{/if}{$current_lang.tmbn_url|amp}" alt="{$current_lang.language|escape}" width="{$current_lang.image_x}" height="{$current_lang.image_y}" title="{$current_lang.language|escape}" />&nbsp;{$current_lang.language|escape}</a>
  
{*
  <ul data-role="listview" data-theme="a">
    <li data-icon="false">
      <a href="#popup-lang" data-inline="false" data-theme="c" data-rel="popup"><img src="{if not $current_lang.is_url}{$current_location}{/if}{$current_lang.tmbn_url|amp}" alt="{$current_lang.language|escape}" width="{$current_lang.image_x}" height="{$current_lang.image_y}" title="{$current_lang.language|escape}" class="ui-li-icon" />{$current_lang.language|escape}</a>
    </li>
  </ul>
*}
  <div id="popup-lang" data-role="popup">
    <a href="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">{$lng.lbl_close}</a>
    {$smarty.capture.lang_selector}
  </div>
{/if}