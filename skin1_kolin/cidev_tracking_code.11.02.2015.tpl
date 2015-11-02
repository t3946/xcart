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
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,params:window.yaParams||{ }});
    	    w.yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.clickmap({ignoreTags: ['script']});
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
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
    	    w.yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.clickmap({ignoreTags: ['script']});
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
