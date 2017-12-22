{*
$Id: invoice.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}
{foreach from=$orders item=order}
  {assign var="oOrder" value=$order.oOrder}
  {include file="mail/html/order_invoice.tpl" is_nomail='Y' products=$order.products giftcerts=$order.giftcerts userinfo=$order.userinfo order=$order.order}
  <br />
  <br />
  <br />
  <br />

  {if $active_modules.Interneka}
    {include file="modules/Interneka/interneka_tags.tpl"}
  {/if}

{/foreach}

{if $active_modules.Google_Analytics and $config.Google_Analytics.ganalytics_e_commerce_analysis eq "Y" and $ga_track_commerce eq "Y"}
  {include file="modules/Google_Analytics/ga_commerce_form.tpl"}
{/if}
