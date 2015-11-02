{*
$Id: config_recommends.tpl,v 1.2 2010/07/22 07:17:13 aim Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
<table cellpadding="10" cellspacing="0" class="general-settings">
<tr>
  <td>

{if $system_requirements_errors}

  {include file="main/subheader.tpl" title=$lng.txt_xpc_requirements_failed}

  <ul>
    {foreach from=$system_requirements_errors item=e}
      <li>{$e}</li>
    {/foreach}
  </ul>
  <br />
{/if}

{if $check_sys_errs}

  {include file="main/subheader.tpl" title=$lng.txt_xpc_sys_check_failed}

  <ul>
    {foreach from=$check_sys_errs item=e}
      <li>{$e}</li>
    {/foreach}
  </ul>
  <br />
{/if}

{if $xpc_recommends}

  {include file="main/subheader.tpl" title=$lng.lbl_xpc_recommendations}

  <table cellpadding="7" cellspacing="1">

    {foreach from=$xpc_recommends key=type item=recommends}

      {foreach from=$recommends key=key item=recommendation}

        <tr{cycle values=', class="TableSubHead"'}>
          <td>
            <img src="{$ImagesDir}/{if $type eq 'E'}icon_error_small.gif{else}icon_warning_small.gif{/if}" alt="" />
          </td>
          <td>
            {if $key eq "payment_methods"}

              {$lng.lbl_xpc_recommend_payment_methods}<br />
              <ul>
                {foreach from=$recommendation item=payment_module}
                  <li>{$payment_module}</li>
                {/foreach}
              </ul>

            {else}

              {$recommendation}

            {/if}
          </td>
        </tr>

      {/foreach}

    {/foreach}

  </table>

{/if}
  </td>
</tr>
</table>
