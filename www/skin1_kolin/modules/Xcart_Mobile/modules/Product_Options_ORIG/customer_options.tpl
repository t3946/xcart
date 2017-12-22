{*
$Id: customer_options.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $product_options ne '' or $product_wholesale ne ''}
  {if $nojs ne 'Y'}
    <div class="ui-select" style="display: none;">
      <script type="text/javascript">
        //<![CDATA[
        var alert_msg = '{$alert_msg|escape:javascript}';
        //]]>
      </script>
      {include file="modules/Product_Options/check_options.tpl"}
    </div>
  {/if}
  {foreach from=$product_options item=v}
    {if $v.options ne '' or $v.is_modifier eq 'T' or $v.is_modifier eq 'A'}
      {capture name="prop_name"}
        {if $usertype eq "A"}
          {$v.class}
        {else}
          {$v.classtext|escape|default:$v.class}
        {/if}
      {/capture}
      {if $cname ne ""}
        {assign var="poname" value="$cname[`$v.classid`]"}
      {else}
        {assign var="poname" value="product_options[`$v.classid`]"}
      {/if}
      {if $v.is_modifier eq 'T'}
        <label form="po{$v.classid}">{$smarty.capture.prop_name}</label>
        <input id="po{$v.classid}" type="text" name="{$poname}" value="{$v.default|escape}" />
      {elseif $v.is_modifier eq 'A'}
        <label form="po{$v.classid}">{$smarty.capture.prop_name}</label>
        <textarea id="po{$v.classid}" name="{$poname}">{$v.default|escape}</textarea>
      {else}
        <select id="po{$v.classid}" name="{$poname}"{if $disable} disabled="disabled"{/if}{if $nojs ne 'Y'} onchange="javascript: check_options();"{/if}>
          {foreach from=$v.options item=o}
            <option value="{$o.optionid}"{if $o.selected eq 'Y'} selected="selected"{/if}>
              {strip}
                {$smarty.capture.prop_name}:&nbsp;
                {$o.option_name|escape}
                {if $v.is_modifier eq 'Y' and $o.price_modifier ne 0}
                  &nbsp;(
                  {if $o.modifier_type ne '%'}
                    {currency value=$o.price_modifier display_sign=1 plain_text_message=1}
                  {else}
                    {$o.price_modifier}%
                  {/if}
                  )
                {/if}
              {/strip}
            </option>
          {/foreach}
        </select>
      {/if}
    {/if}
  {/foreach}
{/if}
{if $product_options_ex ne ""}

  <div class="warning-message ui-popup ui-overlay-shadow ui-corner-all ui-body-e" colspan="3" id="exception_msg" {*data-role="popup"*} style="display: none;"></div>

  {if $err ne ''}
    <div class="customer-message ui-popup ui-overlay-shadow ui-corner-all ui-body-e">{$lng.txt_product_options_combinations_warn}:</div>
    {foreach from=$product_options_ex item=v}
      <div class="poptions-exceptions-list">
        {foreach from=$v item=o}
          {strip}
            {if $usertype eq "A"}
              {$o.class}
            {else}
              {$o.classtext|escape}
            {/if}
            : {$o.option_name|escape}
          {/strip}
        {/foreach}
      </div>
    {/foreach}
  {/if}
{/if}
