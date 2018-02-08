{* $Id: australia_post.tpl,v 1.1.2.1 2006/10/02 14:07:32 twice Exp $ *}
<form action="http://www.eparcel.com.au/EPTrack_Internet_Send_Message.process?nav_main=eparcel_track&nav_sub=Tracking" method="post">
<input type="hidden" name="IWPEProcessFlow.submitted.sequenceID" value="EPTrack_Internet_Main|WriteDisplay" />
<input type="hidden" name="EParcel_Number"  value="{$order.tracking}" />
<input type="submit" value="{$lng.lbl_track_it|strip_tags:false|escape}" />
<br />
{$lng.txt_apost_redirection}
</form>
