{*
$Id: dialog_message.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $top_message.content ne "" or $alt_content ne ""}
  <div id="dialog-message" class="ui-body ui-body-e">
    <div class="box message-{$top_message.type|lower|default:"i"}"{if $top_message.title} title="{$top_message.title|escape}"{/if}>

      {if $top_message.type eq 'E'}
        {assign var="dialog_icon" value="alert"}
      {elseif $top_message.type eq 'W'}
        {assign var="dialog_icon" value="gear"}
      {else}
        {assign var="dialog_icon" value="info"}
      {/if}
      <h3 class="ui-title"><span class="ui-icon ui-icon-{$dialog_icon} ui-icon-shadow">&nbsp;</span>{if $top_message.title}{$top_message.title|escape}{/if}</h3>
      <div class="ui-content-e">
        {$top_message.content|default:$alt_content}
      </div>
      {if $top_message.no_close eq ""}
        <div class="ui-grid-b">
          <div class="ui-block-a">&nbsp;</div>
          <div class="ui-block-b">&nbsp;</div>
          <div class="ui-block-c">
            <a data-role="button" data-theme="e" data-icon="delete" data-iconpos="right" href="#" onclick="javascript: $('#dialog-message').remove();">{$lng.lbl_close}</a>
          </div>
        </div>
      {/if}
      {*if $top_message.anchor ne ""}
      Has no sence in the mobile version
      <li>
      <a href="#{$top_message.anchor}" data-role="button" data-icon="arrow-r" data-iconpos="right">{$lng.lbl_go_to_last_edit_section}</a>
      </li>
      {/if*}
    </div>
  </div>
{/if}
