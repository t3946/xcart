{*
$Id: spambot_arrest.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{* Honeypot emulation *}
{assign var="jqm_current_url" value=$php_url.url}
{if $php_url.query_string ne ''}
  {assign var="jqm_current_url" value=$jqm_current_url|cat:"?`$php_url.query_string`"}
{/if}
{capture name="iv_code"}
  <input type="text" name="antibot_input_str" value="" />
  <input type="hidden" name="jqm_current_url" value="{$jqm_current_url}" />
{/capture}
{if $mode eq 'advanced' or $mode eq 'simple' or $mode eq 'simple_column'}
  <div class="iv-box">
    {$smarty.capture.iv_code}
  </div>
  {if $button_code}
    <div>
      {$button_code}
    </div>
  {/if}
{elseif $mode eq 'data-table'}
  <tr class="iv-box">
    <td colspan="3">
      {$smarty.capture.iv_code}
    </td>
  </tr>
  {if $button_code}
    <tr>
      <td colspan="3">
        {$button_code}
      </td>
    </div>
  {/if}
{/if}
