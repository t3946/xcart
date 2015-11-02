<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from cidev_tracking_code.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'cidev_tracking_code.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "cidev_tracking_code.tpl"), $this); endif; ?><script>
<?php echo '
    (function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"5024901"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
'; ?>

</script>
<noscript><img src="//bat.bing.com/action/0?ti=5024901&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>

<script type="text/javascript">
	var revenue = '<?php echo $this->_tpl_vars['order_subtotal']; ?>
';
<?php echo '
	window.uetq = window.uetq || []; 
	if (revenue != \'\')
		window.uetq.push({\'gv\':revenue});
'; ?>

</script>

<?php if ($this->_tpl_vars['config']['Company']['cidev_ga_code_number'] != ""): ?>
<script type="text/javascript">
<!--
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $this->_tpl_vars['config']['Company']['cidev_ga_code_number']; ?>
']);
  _gaq.push(['_setDomainName','none']);
  _gaq.push(['_trackPageview']);
<?php echo $this->_tpl_vars['cidev_tracking_code_add']; ?>

<?php echo '
  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
//    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    ga.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'stats.g.doubleclick.net/dc.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();
'; ?>

-->
</script>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Company']['cidev_yandex_code_number'] != ""):  if ($this->_tpl_vars['cidev_tracking_code_add2'] != ""): ?>
<!-- Yandex.Metrika counter -->
<?php echo $this->_tpl_vars['cidev_tracking_code_add2']; ?>


<script type="text/javascript">
<!--
<?php echo '
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter';  echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number'];  echo ' = new Ya.Metrika({id:';  echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number'];  echo ',
                    webvisor:';  if ($this->_tpl_vars['HTTPS_used'] == 'Y' || $this->_tpl_vars['main'] == 'fast_lane_checkout'):  echo 'false';  else:  echo 'true';  endif;  echo ',
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
'; ?>

-->
</script>
<noscript><div><img src="//mc.yandex.ru/watch/<?php echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number']; ?>
" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php else: ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
<!--
<?php echo '
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter';  echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number'];  echo ' = new Ya.Metrika({id:';  echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number'];  echo ',
                    webvisor:';  if ($this->_tpl_vars['HTTPS_used'] == 'Y' || $this->_tpl_vars['main'] == 'fast_lane_checkout'):  echo 'false';  else:  echo 'true';  endif;  echo ',
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
'; ?>

-->
</script>
<noscript><div><img src="//mc.yandex.ru/watch/<?php echo $this->_tpl_vars['config']['Company']['cidev_yandex_code_number']; ?>
" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php endif;  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "cidev_tracking_code.tpl"), $this); endif; ?>