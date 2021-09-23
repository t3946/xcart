{*
2aca87f302048436ed08b4e6738089849840409f, v1 (xcart_4_5_3), 2012-08-07 09:50:06, new_arrivals_show_date.tpl, tito
vim: set ts=2 sw=2 sts=2 et:
*}
{if $new_arrivals_show_date eq 'Y'}
  <span class="new-arrivals-date">{$lng.lbl_added}:&nbsp;{$product.add_date|date_format:$config.Appearance.date_format}</span>
{/if}
