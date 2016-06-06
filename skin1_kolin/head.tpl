{* $Id: head.tpl,v 1.40.2.3 2004/11/19 06:40:15 max Exp $ *}

{if $main eq "fast_lane_checkout" && $smarty.get.mode eq ""}
<script type="text/javascript">
//<![CDATA[
{literal}
$(function(){
 document.onkeydown = function(e) {
        if (e.keyCode == "81"){
                if (document.getElementById('s3_logo')){
			$('#s3_logo').attr('href', "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no'); void(0);");
                }
        }
 }

 document.onkeyup = function(e) {
	if (document.getElementById('s3_logo')){
		$('#s3_logo').attr('href', 'javascript: void(0);');
	}
 }

});
{/literal}
//]]>
</script>
{/if}


<table border="0" cellpadding="0" cellspacing="0" align="center" {if $main ne "fast_lane_checkout" && $main ne "order_message"}class="header_line2_table"{/if}>
<tr>
<td>



<table border="0" cellpadding="0" cellspacing="0" width="984" align="center" class="header_line1">
 <tr>
  <td width="58%" style="padding-left: 20px;">
{if $top_pages_menu ne "" && !(($smarty.get.mode eq "checkout") || ($smarty.get.mode eq "update" && $smarty.get.action eq "cart")) }
  {section name=top_page loop=$top_pages_menu}
    {if $top_pages_menu[top_page].image.filename ne ""}
      {if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}
	<a href="/pages.php?pageid={$top_pages_menu[top_page].pageid}">{/if}<img src="{if $HTTPS_url eq "N" && $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}/image.php?id={$top_pages_menu[top_page].image.id}&amp;type=A" alt="" {if $top_pages_menu[top_page].image.image_x gt "16"}width="16"{/if} />{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}</a>
      {/if}
    {/if}

    {if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}<a class="top_links" href="/pages.php?pageid={$top_pages_menu[top_page].pageid}">{else}<font style="color: #cccccc;">{/if}{$top_pages_menu[top_page].title}{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}</a>{else}</font>{/if}

    {* if !%top_page.last% *}
      <font class="top_links">&nbsp;|&nbsp;</font>
    {* /if *}

  {/section}

  {if $main ne "help" && $smarty.get.section ne "contactus"}<a class="top_links" href="/help.php?section=contactus&mode=update">{else}<font style="color: #cccccc;">{/if}Contact Us{if $main ne "help" && $smarty.get.section ne "contactus"}</a>{else}</font>{/if}

{/if}
  </td>
  <td width="*" align="right" style="padding-right: 20px;">
<font class="top_text_1">
 Place order online or call
</font>
<font class="top_text_2">
{if $geo_litecity_location.phone ne ""}
&nbsp; {$geo_litecity_location.phone}
{else}
&nbsp; {$config.Company.cidev_top_header_code}
{/if}
</font>
  </td>
 </tr>
</table>



<TABLE border="0" cellpadding="0" cellspacing="0" {if $main eq "fast_lane_checkout"}class="header_line_flc2"{else}class="header_line2"{/if} align="center">
<TR>
<TD {* height="130" *} valign="bottom">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>

        <td width="250" valign="middle">
        {if !($main eq "catalog" && $current_category.category eq "") || $smarty.get.page ne "" || $search_keyword eq true}<a href="/">{/if}<img src="{if $HTTPS_url eq "N" && $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S" {if $current_storefront_info.image.image_x gt "250"} width="250" {/if}  alt="Home page" >{if !($main eq "catalog" && $current_category.category eq "") || $smarty.get.page ne "" || $search_keyword eq true}</a>{/if}
        </td>

{if $main eq "fast_lane_checkout" || $main eq "order_message"}
        <td width="*" valign="middle" align="center">
	        <a href="javascript: void(0);" style="cursor: default;" id="s3_logo"><img src="{$ImagesDir}/S3-Stores-Logo-S2.png" alt="" /></a>
        </td>
        <td width="150" valign="middle" align="right">
{if $config.Security.ssl_seal ne ""}
{$config.Security.ssl_seal}
{/if}
        </td>
{else}
        <td width="*" valign="middle">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
			<td width="10">&nbsp;</td>
                        <td valign="middle" width="*" align="center">
                        </td>
                        <td width="10">&nbsp;</td>
                        <td width="204" valign="middle" align="right">
                        {include file="customer/menu_cart.tpl"}

{if $variant_id_for_point5 ne "" && $variant_id_for_point5 eq "0" && $main ne "product" && !($main eq "catalog" && $current_category.category eq "")}
{assign var="social_buttons_data_services" value=$config.Appearance.social_buttons_data_services}
{$config.Appearance.social_buttons_script_code|replace:"[data-services]":"$social_buttons_data_services"|replace:"[size]":"medium"}
{/if}

                        </td>
                        </tr>
                </table>
        </td>
{/if}
        </tr>
        </table>
</TD>
</TR>
</TABLE>

</td>
</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" align="center" class="header_line3">
 <tr>
  <td align="center">
{if $main ne "fast_lane_checkout"}
<table border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>
   <table border="0" cellpadding="0" cellspacing="0" align="center" class="header_line31">
    <tr>
	<td><span class="product_search">Product search</span></td>
	<td><img src="{$ImagesDir}/new/home/arrow.png" alt="" /></td>
	<td width="620">{include file="customer/search.tpl"}</td>
    </tr>
   </table>
</td></tr></table>
{/if}
  </td>
 </tr>
</table>

{if $main eq "order_message"}
<br />
<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td class="cidev_checkout_bar6"></td></tr>
</table>
{/if}


{include file="customer/top_menu.tpl"}

{if $variant_id_for_point3 eq "1" && ($main ne "product" && $main ne "fast_lane_checkout")}
{$config.Storefront_common_details.common_header_code}
{/if}

<a id="scrollTop" class="button_new grey" href="#" title="Up">Up</a>

<script type="text/javascript">
//<![CDATA[
{literal}

function scrollTop(){
    if(jQuery(window).scrollTop() > jQuery('#header').height()){
        jQuery('#scrollTop').fadeIn('slow');
    } else {
        jQuery('#scrollTop').fadeOut('fast');
    }
}
jQuery('#scrollTop').live('click', function(){
    jQuery('body,html').animate({
        scrollTop: 0
    }, 300);
  return false;
});
jQuery(window).scroll(function(){
    scrollTop();
});

{/literal}
//]]>
</script>
