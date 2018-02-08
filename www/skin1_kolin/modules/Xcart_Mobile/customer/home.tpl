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
    <script type="text/javascript" src="{$SkinDir}/js/jquery.visible.min.js"></script>
    <script src="{$SkinDir}/js/google_analytics_impressions.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$SkinDir}/js/spinner.js"></script>
    <script type="text/javascript" src="{$SkinDir}/js/group.js"></script>
    <link rel="stylesheet" href="/static/backend/fonts/icons/css/style.css">

  </head>
  <body>
{else}
  <div data-role="header">
      {include file="meta_titles.tpl" }
  </div>
{/if}

    {include file="customer/page.tpl"}

{if !$is_ajax_request}
    {load_defer_code type="js"}
    {load_defer_code type="css"}

{$xcartApp->template->render('inSmarty/raw_static_notifications.tpl')}
  </body>
</html>
{/if}
