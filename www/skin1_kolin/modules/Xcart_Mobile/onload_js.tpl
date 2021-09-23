{*
$Id: onload_js.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name=onload_js}
  {if $config.SEO.clean_urls_enabled eq "Y"}
    {literal}
      //  Fix a.href if base url is defined for page
      function anchor_fix() {
      var links = document.getElementsByTagName('A');
      var m;
      var _rg = new RegExp("(^|" + self.location.host + xcart_web_dir + "/)#([\\w\\d_]+)$")
      for (var i = 0; i < links.length; i++) {
      if (links[i].href && (m = links[i].href.match(_rg))) {
      links[i].href = 'javascript:void(self.location.hash = "' + m[2] + '");';
      }
      }
      }
      if (window.addEventListener)
      window.addEventListener("load", anchor_fix, false);
      else if (window.attachEvent)
      window.attachEvent("onload", anchor_fix);

      $.ajaxSetup({
        cache: false,
        beforeSend: function(){
          this.cache = false;
        }
      });
    {/literal}
  {/if}
  {if $products ne "" or $free_products ne "" or $product ne ""}
    {literal}
      if (products_data == undefined) {
      var products_data = [];
      }
    {/literal}
  {/if}
  var txt_are_you_sure = '{$lng.txt_are_you_sure|wm_remove|escape:"javascript"}';
{/capture}
{load_defer file="onload_js" direct_info=$smarty.capture.onload_js type="js" queue="1"}
{if $active_modules.EU_Cookie_Law ne ""}
  {include file="modules/EU_Cookie_Law/init.tpl"}
{/if}
{if $active_modules.Product_Options ne ""}
  {load_defer file="modules/Product_Options/func.js" type="js"}
{/if}
{if $products or $free_products}
  {load_defer file="js/check_quantity.js" type="js"}
  {if $active_modules.Feature_Comparison and not $printable and $products_has_fclasses}
    {load_defer file="modules/Feature_Comparison/products_check.js" type="js"}
  {/if}
{/if}
