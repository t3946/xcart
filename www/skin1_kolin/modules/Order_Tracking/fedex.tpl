{* $Id: fedex.tpl,v 1.7.2.3 2006/09/04 08:26:07 max Exp $ *}
<form name="tracking" action="http://www.fedex.com/Tracking">
<input type="hidden" name="ascend_header" value="1">
<input type="hidden" name="clienttype" value="dotcom">
<input type="hidden" name="cntry_code" value="us">
<input type="hidden" name="language" value="english">
<input type="hidden" name="tracknumbers" value="{$order.tracking}" />
<input type="submit" value="{$lng.lbl_track_it|strip_tags:false|escape}" />
<br />
{$lng.txt_fedex_redirection}
</form>
