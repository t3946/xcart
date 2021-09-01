{* $id: reviews.tpl,v 1.9 2005/05/16 12:06:36 svowl Exp $ *}
<tr style="display:none">
<td>
<script type="text/javascript">
<!--
var lbl_err_filling_form = "{$lng.err_filling_form|strip_tags|escape:javascript}";
var required_review_Fields = [
                		["review_author", "{$lng.lbl_your_name|escape:javascript}"],
		                ["review_message", "{$lng.lbl_your_message|escape:javascript}"],
		            ];
{literal}
function check_reviews_form() {
	var result;

	result = checkRequired(required_review_Fields);
	if (result) {
		document.getElementById('reviewform').submit();		
	}
}
{/literal}
-->
</script>
{include file="main/include_js.tpl" src="check_required_fields_js.js"}
</td>
</tr>
<tr>
	<td colspan="2">{include file="main/subheader.tpl" title=$lng.lbl_customer_reviews}</td>
</tr>
{if $reviews ne ""}
{foreach from=$reviews item=r}
<tr>
	<td colspan="2"><br /><b>{$lng.lbl_author}: {$r.email|default:$lng.lbl_unknown}</b><br />{$r.message|replace:"\n":"<br />"}<br /><br /></td>
</tr>
{/foreach}
{else}
<tr>
	<td colspan="2" align="center"><br/>{$lng.txt_no_customer_reviews}</td>
</tr>
{/if}
{if ($config.Customer_Reviews.writing_reviews eq "A") or ($login ne "" and $config.Customer_Reviews.writing_reviews eq "R")}
<tr>
	<td colspan="2"><br /><b><font class="ProductDetailsTitle">{$lng.lbl_add_your_review}</font></b></td>
</tr>
<tr>
	<td colspan="2">

<form method="post" action="product.php?mode=review&amp;productid={$product.productid}" id="reviewform">
<input type="hidden" name="productid" value='{$product.productid}' />
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
	<td colspan="3"><img src="{$ImagesDir}/spacer.gif" width="1" height="3" alt="" /></td>
</tr>
<tr>
	<td width="20%" class="FormButton">{$lng.lbl_your_name}:</td>
	<td width="10" align="center"><font class="Star">*</font></td>
	<td width="80%"><input type="text" size="24" name="review_author" id="review_author"{if $login ne ""} value="{$customer_info.firstname|escape} {$customer_info.lastname|escape} ({$customer_info.email|escape})"{else}value="{$review.author|escape}"{/if} />{if $review.author eq  "" and $review.error ne ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>
<tr>
	<td class="FormButton">{$lng.lbl_your_message}:</td>
	<td align="center"><font class="Star">*</font></td>
	<td>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<textarea cols="40" rows="4" name="review_message" id="review_message">{$review.message|escape}</textarea>
		</td>
		{if $review.message eq  "" and $review.error ne ""}
		<td align="left"><font class="Star">&lt;&lt;</font></td>
		{/if}
	</tr>
	</table>

	</td>
</tr>
{assign var="antibot_err" value=$review.antibot_err}
{if $active_modules.Image_Verification and $show_antibot.on_reviews eq 'Y'}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="simple" id=$antibot_sections.on_reviews}
{/if}
<tr>
	<td colspan="3">{include file="buttons/button.tpl" button_title=$lng.lbl_add_review style="button" href="javascript: check_reviews_form();" type="input"}</td>
	
</tr>
</table>
</form>

	</td>
</tr>
{/if}

