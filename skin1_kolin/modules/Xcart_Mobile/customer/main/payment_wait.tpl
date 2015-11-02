{*
$Id: payment_wait.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<?xml version="1.0" encoding="{$default_charset|default:"utf-8"}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{config_load file="$skin_config"}
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    {func_mobile_clear_modules}
    {include file="customer/service_head_mobile.tpl"}
  </head>
  <body>
    <div data-role="page" data-add-back-btn="false" data-dom-cache="false">
      <div data-role="header">
        <h1>{$lng.lbl_checkout}</h1>
      </div>
      <div data-role="content">
        <div class="payment-wait-title">
          <h1>{$lng.msg_order_is_being_placed}</h1>
