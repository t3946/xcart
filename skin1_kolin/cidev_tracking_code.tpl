<script>
{literal}
    (function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"5024901"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
{/literal}
</script>
<noscript><img src="//bat.bing.com/action/0?ti=5024901&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>

<script type="text/javascript">
	var revenue = '{$order_subtotal}';
{literal}
	window.uetq = window.uetq || []; 
	if (revenue != '')
		window.uetq.push({'gv':revenue});
{/literal}
</script>



{* -------------------------------- *}

{if $config.Storefront_common_details.google_analitics_tracking_script ne "" && $config.Company.cidev_ga_code_number ne ""}

	{assign var=ga_ec_data value=""}

	{if $usertype eq "A" || $usertype eq "P"}
		{assign var=ga_send value=""}
	{else}
		{assign var=ga_send value="ga('send', 'pageview');"}
	{/if}

	{if $ga_page_name ne ""}
		{assign var=ga_ec_data value="ga('require', 'ec');"}
	{/if}

	{$config.Storefront_common_details.google_analitics_tracking_script|substitute:"ga_account_nr":$config.Company.cidev_ga_code_number|substitute:"ga_ec_data":$ga_ec_data|substitute:"ga_send":$ga_send}

<script>
//<![CDATA[
// Called when a link to a product is clicked.
{literal}

function onProductClick(pid, pname, pcategory, pbrand, pposition, plist, pprice) {

//alert(pname+' '+pposition);

  ga('ec:addProduct', {
    'id': "'"+pid+"'",
    'name': "'"+pname+"'",
    'category': "'"+pcategory+"'",
    'brand': "'"+pbrand+"'",
    'price': "'"+pprice+"'",
    'position': "'"+pposition+"'"
  });
  ga('ec:setAction', 'click', {list: "'"+plist+"'"});

  // Send click with an event, then send user to product page.
  ga('send', 'event', 'UX', 'click', 'Results', {
      hitCallback: function() {
        document.location = '/product.php?productid='+pid;
      }
  });
}

{/literal}
//]]>
</script>

	{if $ga_page_name ne "" && $products ne ""}

<script>
//<![CDATA[
		{foreach from=$products item=v key=k}


		{if $N_key eq ""}{assign var="N_key" value="0"}{/if}
		{math assign="N_key" equation="x+1" x=$N_key}


ga('ec:addImpression', {ldelim}
  'id': '{$v.productid}',                   // Product details are provided in an impressionFieldObject.
  'name': '{$v.product|escape}',
  'category': '{$v.category|escape}',
  'brand': '{$v.brand|escape}',
  'list': '{$ga_page_name}',
  'price': '{$v.price}',
  'position': {$N_key}                     // 'position' indicates the product position in the list.
{rdelim});
		{/foreach}
//]]>
</script>

	{/if}



{/if}

{*
{if $config.Company.cidev_ga_code_number ne ""}
<script type="text/javascript">
<!--
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$config.Company.cidev_ga_code_number}']);
  _gaq.push(['_setDomainName','none']);
  _gaq.push(['_trackPageview']);
{$cidev_tracking_code_add}
{literal}
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
//    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
{/literal}
-->
</script>
{/if}
*}
{* -------------------------------- *}



{if $config.Company.cidev_yandex_code_number ne ""}
{if $cidev_tracking_code_add2 ne ""}
<!-- Yandex.Metrika counter -->
{$cidev_tracking_code_add2}

<script type="text/javascript">
<!--
{literal}
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal} = new Ya.Metrika({id:{/literal}{$config.Company.cidev_yandex_code_number}{literal},
                    webvisor:{/literal}{if $HTTPS_used eq "Y" || $main eq "fast_lane_checkout"}{literal}false{/literal}{else}{literal}true{/literal}{/if}{literal},
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,params:window.yaParams||{ }});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
{/literal}
-->
</script>
<noscript><div><img src="//mc.yandex.ru/watch/{$config.Company.cidev_yandex_code_number}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
{else}
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
<!--
{literal}
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal} = new Ya.Metrika({id:{/literal}{$config.Company.cidev_yandex_code_number}{literal},
                    webvisor:{/literal}{if $HTTPS_used eq "Y" || $main eq "fast_lane_checkout"}{literal}false{/literal}{else}{literal}true{/literal}{/if}{literal},
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
{/literal}
-->
</script>
<noscript><div><img src="//mc.yandex.ru/watch/{$config.Company.cidev_yandex_code_number}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
{/if}
{/if}
