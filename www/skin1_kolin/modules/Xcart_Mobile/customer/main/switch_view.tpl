{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<a class="switch-mobile link-switch" data-theme="b" data-icon="switch" data-role="button" href="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}#switch-view" data-rel="popup"><h3>{$lng.lbl_switch_to_desktop}</h3></a>
<div data-role="popup" id="switch-view" data-overlay-theme="e" data-theme="e" style="max-width:400px;" class="ui-corner-all">
  <a href="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">{$lng.lbl_close}</a>
  <h1>{$lng.txt_mobile_switch_view_dialog_header}</h1>
  {$lng.txt_mobile_switch_view_dialog_content_mobile}
  <div class="ui-grid-a">
    <div class="ui-block-a">
      <a href="{$php_url.url}{if $php_url.query_string}?{$php_url.query_string}{/if}#" data-role="button" data-inline="false" onclick="javascript: $('#switch-view').popup('close');" data-theme="c">{$lng.lbl_cancel}</a>
    </div>
    <div class="ui-block-b">
      <a href="{$php_url.url}?{if $php_url.query_string}{$php_url.query_string}&{/if}switch_view=common" rel="external" data-role="button" data-inline="false" data-theme="b">{$lng.lbl_switch}</a>
    </div>
  </div>
</div>