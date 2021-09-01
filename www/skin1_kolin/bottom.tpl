{if $usertype eq "C"}

{if $main ne "fast_lane_checkout" && $pages_menu ne ""}
<div style="margin: 9px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;" border="0">
<tr>
<td align="left" style="vertical-align: top;">
<span class="ProductPrice">{$lng.lbl_help}</span>
</td>
</tr>

<tr>
<td>
        <table cellspacing="0" cellpadding="0" width="100%">
                <tr>

{assign var=cell_counter value=0}

{section name=pg loop=$pages_menu}

{if $cell_counter eq "0"}
                        <td width="25%" align="left" valign="top">
{/if}

{if $pages_menu[pg].new_link ne ""}
<a href="{$pages_menu[pg].new_link}" class="VertMenuItems">
    {$pages_menu[pg].title}
</a>
{else}
    {if $smarty.get.pageid ne $pages_menu[pg].pageid}
        <a href="/pages.php?pageid={$pages_menu[pg].pageid}" class="VertMenuItems">
    {else}
        <font class="VertMenuItems">
    {/if}
    {$pages_menu[pg].title}
    {if $smarty.get.pageid ne $pages_menu[pg].pageid}
        </a>
    {else}
        </font>
    {/if}
{/if}
<br />

{assign var=cell_counter value=$cell_counter+1}

{if $cell_counter eq $count_pages_menu_in_cell}
                        </td>
{assign var=cell_counter value=0}
{/if}

{/section}

{if $cell_counter gt 0}
                        </td>
{/if}

                </tr>
        </table>
</td>
</tr>
</table>

</div>
{/if}

{* --- *}
{if $config.Company.cidev_footer_code ne ""}
{$config.Company.cidev_footer_code}
{else}
{$config.Storefront_common_details.common_footer_code}
{/if}
{* --- *}

</td>

<tr>
<td class="Bottom" style="padding-left: 10px;text-align: center" {* height="30" *}>
{/if}
{include file="copyright.tpl"}{if $usertype eq "C"} <br/><br/>
</td>
</tr>
</table>
{/if}
