<br />
<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">

{capture name=dialog}
    {if !$ready_to_classify}
        <div style="text-align:right;width:100%; background: #FFFFFF; margin-bottom: 10px;">
                <a href="?ready_to_classify=Y">Ready to classification</a>
        </div>
    {/if}
<form name="pc_form2" method="POST" autocomplete="off">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="last_taxonomy" value="{$last_taxonomy}" id="last_taxonomy">
<table border="0" width="100%" cellpadding="3" cellspacing="1" style="background: #EEEEEE;">

<tr class='TableSubHead' style="background: #CCCCCC;">
<td width="5"><b>R</b></td>
<td width="500"><b>Category path</b></td>
<td><B>CategoryID</B></td>

<td width="250"><b>Inherited taxonomy</b></td>
<td nowrap="nowrap"><b>Google Product Category</b></td>
<td><b>Product Management</b></td>
<td nowrap="nowrap"><b>Back-end link</b></td>
</tr>

{foreach from=$all_categories item=v key=k}
<tr {* {cycle values=", class='TableSubHead'" name="cycle_totals"} *}
{if $v.prev_google_product_category ne "" && $v.google_product_category eq ""}
style="background: #FFF2CC;"
{elseif $v.prev_google_product_category eq "" && $v.google_product_category eq ""}
style="background: #F4CCCC;"
{else}
style="background: #FFFFFF;"
{/if}
>

<td>{if $v.pc_ready_to_classify eq "Y"}*{/if}</td>

<td>
{if $v.categoryid_path_arr ne ""}
{foreach from=$v.categoryid_path_arr item=vv key=kk}
{if $kk eq ($v.categoryid_path_arr_count - 1)}<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.categoryid}" target="_blank" style="color: blue;">{if $v.product_count gt 0}<B>{/if}{/if}{$vv}{if $kk eq ($v.categoryid_path_arr_count - 1)}{if $v.product_count gt 0}</B>{/if}</a>{/if}{if $kk < ($v.categoryid_path_arr_count - 1)} <B>></B> {/if} {if $v.product_count gt 0 && $kk eq ($v.categoryid_path_arr_count - 1)}({$v.count_pc_products}){/if}
{/foreach}
{/if}
</td>

<td align="center">{$v.categoryid}</td>

<td>
{if $v.prev_google_product_category ne ""}
{$v.prev_google_product_category}
{/if}
</td>

<td nowrap="nowrap">
<input id="google_product_category_{$v.categoryid}" type="text" name="google_product_category_arr[{$v.categoryid}]" value="{$v.google_product_category}" />

<input type="button" name="b_t" value="+" onclick="javascript: window.open('popup_taxonomy.php?id=google_product_category_{$v.categoryid}&last_taxonomy='+encodeURIComponent($('#last_taxonomy').val()),'popup_taxonomy','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" />
</td>
<td>
    <div id="relist_reclass_buttons" class="ui buttons" data-category="{$v.categoryid}">
        <div data-action="Relist" class="ui button item" style="border: 1px solid #808080;">Relist</div>
    <div style="border-color: #808080; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0;"
         class="ui combo top right dropdown icon button">
        <i class="dropdown icon"></i>
        <div class="menu">
            <div data-action="Reclassify" class="item">Reclassify</div>
        </div>
    </div>
    </div>
</td>
<td>
<a href="category_modify.php?cat={$v.categoryid}" target="_blank" style="color: blue;">Back-end link</a>
</td>

</tr>
{/foreach}

</table>

<input type="submit" name="update" value="update" />
</form>

{/capture}
{include file="dialog.tpl" title="Category structure" content=$smarty.capture.dialog extra='width="100%"'}

<script src="{$SkinDir}/js/semantic/components/dropdown.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.dropdown').dropdown();
    {literal}
        $( document ).ready(function() {
            $('#relist_reclass_buttons .item').click(function(){
                var action_value = $(this).data('action');
                if (confirm("Are you sure " + action_value + " products of this (and below this) category ?\n(you should process them at classification workplace then)")) {

                    $.post('ajax_admin.php', {
                                action: action_value,
                                category: $(this).closest('#relist_reclass_buttons').data('category'),
                                ajax_action: 'category_structure_change'
                            },
                            function (data) {
                                if (data.result)
                                    alert(action_value + " has been done!");
                            }, 'json');
                }
            })
        });
    {/literal}
</script>