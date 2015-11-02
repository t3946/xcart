<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
<br />
{capture name=dialog}

<form name="ab_form" method="post" action="ab_testing.php">

<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="delete_variant_id_id" value="" id="delete_variant_id_id" />
<input type="hidden" name="add_variant_id_point_id" value="" id="add_variant_id_point_id" />

{include file="admin/main/ab_testing_foreach.tpl" title="Running tests" show_enabled="Y"}
<br />
{include file="admin/main/ab_testing_foreach.tpl" title="Dormant tests" show_enabled="N"}
<br />

<INPUT type="button" value="Update" onclick="document.ab_form.mode.value='update'; document.ab_form.submit();">
</form>

{/capture}
{include file="dialog.tpl" title="A/B testing" content=$smarty.capture.dialog extra="width=100%"}
