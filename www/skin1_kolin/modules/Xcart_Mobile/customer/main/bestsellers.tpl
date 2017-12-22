{*
$Id: bestsellers.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $bestsellers}
  {include file="customer/main/products.tpl" products=$bestsellers featured="Y" title=$lng.lbl_bestsellers}
{/if}