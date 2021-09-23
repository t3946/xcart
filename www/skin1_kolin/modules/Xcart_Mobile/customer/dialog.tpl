{*
$Id: dialog.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="dialog{if $additional_class} {$additional_class}{/if}">
  {if not $noborder}
    <div class="title">
      <h2>{$title}</h2>
    </div>
  {/if}
  <div class="content">{$content}</div>
</div>
