{* $Id: fancy_categories.tpl,v 1.2 2005/11/17 06:55:45 max Exp $ *}
{include file="main/include_js.tpl" src="`$fc_skin_path`/func.js"}
<script type="text/javascript">
<!--
var bLeftOn = new Image();
var bLeftOff = new Image();
var bCenterOn = new Image();
var bCenterOff = new Image();
var bRightOn = new Image();
var bRightOff = new Image();

bLeftOn.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_l_on.gif";
bLeftOff.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_l_off.gif";
bCenterOn.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_m_on.gif";
bCenterOff.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_m_off.gif";
bRightOn.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_r_on.gif";
bRightOff.src = "{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_r_off.gif";
-->
</script>
<div id="{$fancy_cat_prefix}rootmenu">
{include file="`$fc_skin_path`/fancy_subcategories.tpl" level=0}
</div>
