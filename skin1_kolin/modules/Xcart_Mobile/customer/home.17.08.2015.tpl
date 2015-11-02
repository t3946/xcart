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
  </head>
  <body>
{/if}
    {include file="customer/page.tpl"}
{if !$is_ajax_request}
    {load_defer_code type="js"}
    {load_defer_code type="css"}

<script type="text/javascript">
//<![CDATA[
{literal}

    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 's3stores';
    
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
{/literal}
//]]>
</script>

  </body>
</html>
{/if}
