{* $Id: menu_export.tpl 454 2008-06-25 14:36:03Z tsergiy $ *}
{capture name=menu}
<a href="{$catalogs.admin}/m1_export_stats.php?selected_box=m1_export" class="VertMenuItems">Sales Channel Analysis</a><br>
<a href="{$catalogs.admin}/m1_become.php" class="VertMenuItems">Become.com Export</a><br>
<a href="{$catalogs.admin}/m1_brokerbin.php" class="VertMenuItems">BrokerBin.com Export</a><br>
<a href="{$catalogs.admin}/m1_buyersedge.php" class="VertMenuItems">BuyersEdge Export</a><br>
<a href="{$catalogs.admin}/m1_cnet.php" class="VertMenuItems">CNET.com Export</a><br>
<a href="{$catalogs.admin}/m1_cwebusa.php" class="VertMenuItems">CWebUSA Export</a><br>
<a href="{$catalogs.admin}/m1_edirectory.php" class="VertMenuItems">eDirectory Export</a><br>
<a href="{$catalogs.admin}/m1_elmar.php" class="VertMenuItems">Elm@r Export</a><br>
<a href="{$catalogs.admin}/m1_epier.php" class="VertMenuItems">ePier Export</a><br>
<a href="{$catalogs.admin}/m1_everyprice.php" class="VertMenuItems">EveryPrice.com Export</a><br>
<a href="{$catalogs.admin}/m1_froogle.php" class="VertMenuItems">Froogle Export</a><br>
<a href="{$catalogs.admin}/m1_getprice.php" class="VertMenuItems">Getprice Export</a><br>
<a href="{$catalogs.admin}/m1_googlebase.php" class="VertMenuItems">Google Base Export</a><br>
<a href="{$catalogs.admin}/m1_jellyfish.php" class="VertMenuItems">Jellyfish.com Export</a><br>
<a href="{$catalogs.admin}/m1_kelkoo.php" class="VertMenuItems">Kelkoo Export</a><br>
<a href="{$catalogs.admin}/m1_windowslive.php" class="VertMenuItems">MSN Shopping Export</a><br>
<a href="{$catalogs.admin}/m1_myshopping.php" class="VertMenuItems">MyShopping Export</a><br>
<a href="{$catalogs.admin}/m1_mysimon.php" class="VertMenuItems">mySimon Export</a><br>
<a href="{$catalogs.admin}/m1_nextag.php" class="VertMenuItems">NexTag Export</a><br>
<a href="{$catalogs.admin}/m1_powersource.php" class="VertMenuItems">PowerSource Export</a><br>
<a href="{$catalogs.admin}/m1_pricegrabber.php" class="VertMenuItems">PriceGrabber Export</a><br>
<a href="{$catalogs.admin}/m1_pricerunner.php" class="VertMenuItems">PriceRunner Export</a><br>
<a href="{$catalogs.admin}/m1_ibuyer.php" class="VertMenuItems">PriceSaving Export</a><br>
<a href="{$catalogs.admin}/m1_pricescan.php" class="VertMenuItems">PriceSCAN.com Export</a><br>
<a href="{$catalogs.admin}/m1_pronto.php" class="VertMenuItems">Pronto.com Export</a><br>
<a href="{$catalogs.admin}/m1_rss.php" class="VertMenuItems">RSS Export</a><br>
<a href="{$catalogs.admin}/m1_shop.php" class="VertMenuItems">SHOP.COM Export</a><br>
<a href="{$catalogs.admin}/m1_shopferret.php" class="VertMenuItems">ShopFerret Export</a><br>
<a href="{$catalogs.admin}/m1_shopify.php" class="VertMenuItems">Shopify Export</a><br>
<a href="{$catalogs.admin}/m1_shopmania.php" class="VertMenuItems">ShopMania Export</a><br>
<a href="{$catalogs.admin}/m1_shoppingcom.php" class="VertMenuItems">Shopping.com Export</a><br>
<a href="{$catalogs.admin}/m1_shopzilla.php" class="VertMenuItems">Shopzilla.com Export</a><br>
<a href="{$catalogs.admin}/m1_smarter.php" class="VertMenuItems">Smarter.com Export</a><br>
<a href="{$catalogs.admin}/m1_sortprice.php" class="VertMenuItems">Sortprice.com Export</a><br>
<a href="{$catalogs.admin}/m1_streetprice.php" class="VertMenuItems">StreetPrices.com Export</a><br>
<a href="{$catalogs.admin}/m1_thefind.php" class="VertMenuItems">TheFind.com Export</a><br>
<a href="{$catalogs.admin}/m1_vast.php" class="VertMenuItems">Vast Export</a><br>
<a href="{$catalogs.admin}/m1_yahoo.php" class="VertMenuItems">Yahoo Store Export</a><br>
<a href="{$catalogs.admin}/m1_custom.php" class="VertMenuItems">Custom Data Feed Export</a><br>
<a href="{$catalogs.admin}/m1_xml.php" class="VertMenuItems">Advanced XML Export</a><br>
{/capture}
{include file="menu.tpl" dingbats="dingbats_categorie.gif" menu_title='Export' menu_content=$smarty.capture.menu}

