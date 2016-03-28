{*
$Id: home.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if !$is_ajax_request}
<!DOCTYPE html>
  {config_load file="$skin_config"}
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    {func_mobile_clear_modules}
    {include file="customer/service_head_mobile.tpl"}

    <script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

  </head>
  <body>
{/if}

    {include file="customer/page.tpl"}

{if !$is_ajax_request}
    {load_defer_code type="js"}
    {load_defer_code type="css"}

  </body>
</html>
{/if}
