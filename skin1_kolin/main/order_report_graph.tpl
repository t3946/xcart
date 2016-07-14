<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
    <title>{$lng.txt_site_title}</title>
    { include file="meta.tpl" }
    <link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <!-- Additional files for the Highslide popup effect -->
    <script src="https://www.highcharts.com/samples/static/highslide-full.min.js"></script>
    <script src="https://www.highcharts.com/samples/static/highslide.config.js" charset="utf-8"></script>
    <script src="{$SkinDir}/js/order_reports.js" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="https://www.highcharts.com/samples/static/highslide.css" />
</head>
<body class="OrderReport">
    {foreach from=$report_data key=manufacturerid item=report}
        <div id="container_{$manufacturerid}" class="report_by_manufacturer" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        {literal}
        <script>
            showGraph("container_{/literal}{$manufacturerid}{literal}","{/literal}{$report.manufacturer}{literal}","{/literal}{$report.yAxis1}{literal}","Orders","{/literal}{$report.report_date_format}{literal}",{/literal}{$report.report_string_1}{literal},{/literal}{$report.report_string_2}{literal});
        </script>
        {/literal}
    {/foreach}
</body>
</html>