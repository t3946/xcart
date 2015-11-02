{if $usertype eq "C"}

{if $main ne "fast_lane_checkout" }
<div style="margin: 9px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;" border="0">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
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
<a href="{$pages_menu[pg].new_link}" class="VertMenuItems">{$pages_menu[pg].title}</a>
{else}
{if $smarty.get.pageid ne $pages_menu[pg].pageid}<a href="pages.php?pageid={$pages_menu[pg].pageid}" class="VertMenuItems">{else}<font class="VertMenuItems">{/if}{$pages_menu[pg].title}{if $smarty.get.pageid ne $pages_menu[pg].pageid}</a>{else}</font>{/if}
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
{* --- *}

<TABLE border="0" width="100%" cellpadding="0" cellspacing="10" align="center">
{*<tr>{*<td colspan="4" height=20">&nbsp;</td></tr>
*}
<tr>

<td align="left" valign="top" width="27%" style="padding-left: 20px; padding-right: 20px; font-size: 15px;" bgcolor="#F0F1F3"><br>
<b>Telephone Customer Service</b><br>
Everyday: 9 a.m. to 8 p.m. EST<br>
<span style="color: #CC3333;">Toll Free: 1-800-929-2431</span><br>
<span style="color: #000000;">Tel: (201) 299-7928</span><br>
<span style="color: #006600;">Fax: (813) 944-4516</span><br>
<span style="color: #000000;">Email: <a href="http://www.artistsupplysource.com/help.php?section=contactus&mode=update" class="SPItems2">Contact us</a></span><br>
<br>
<b>Live Chat Customer Service</b><br>
<center><!-- BEGIN Comm100 Live Chat Button Code --><div><div id="comm100_LiveChatDiv"></div><a href="http://www.comm100.com/livechat/" onclick="comm100_Chat();return false;" target="_blank" title = "Live Chat Live Help"><img id="comm100_ButtonImage" src="http://chatserver.comm100.com/BBS.aspx?siteId=20980&planId=559" border="0px" alt="Live Chat Help" /></a><script src="http://chatserver.comm100.com/js/LiveChat.js?siteId=20980&planId=559" type="text/javascript"></script></div><!-- End Comm100 Live Chat Button Code --></center>
<br />
</td>

<td align="left" valign="top" width="26%" style="padding-left: 20px; font-size: 15px;" bgcolor="#F0F1F3"><br>
<b>USA Address</b><br>
S3 Stores, Inc.<br>
2885 Sanford Ave SW #12717<br>
Grandville, MI  49418<br>
USA<br>
<br>
<b>Canadian Address</b><br>
S3 Stores, Inc.<br>
27 Joseph St.<br>
Chatham, Ontario  N7L 3G4<br>
Canada<br>
</td>

<td align="left" valign="top" width="23%" style="padding-left: 20px; padding-right: 20px; font-size: 15px;" bgcolor="#F0F1F3"><br>
<b>Payment Methods</b><br>
We accept all major credit cards, PayPal, checks and money orders.<br>
<center><img src="{$ImagesDir}/payments.gif" alt="We accept all major credit cards"></center>
<br>
<center><a href="http://www.artistsupplysource.com/pages.php?pageid=28" class="SPItems">Purchase orders</a></center>
</td>

<td align="left" valign="top" width="24%" style="padding-left: 20px; padding-right: 20px; font-size: 15px;" bgcolor="#F0F1F3"><br>
{if $active_modules.Mailchimp_Subscription}
<form action="mailchimp_news.php" name="subscribeform1" method="post">
{else}
<form action="news.php" name="subscribeform1" method="post">
{/if}
<input type="hidden" name="subscribe_lng" value="US" />
<b>Newsletter</b><br>
Receive special offers<br>
on the finest art supplies:<br>
Subscribe to our newsletter!<br>
<font style="color: #008000; font-size: 15px;"><b>Email:</b></font>
<input type="text" name="newsemail" size="16" />
<br />
<TABLE border="0" cellspacing="0" cellpadding="0" onclick="javascript: document.subscribeform1.submit();" style="cursor: pointer;" valign="middle">
<TR>
<TD class="Button2Off" valign="middle" onMouseOver="this.className='Button2On'" onMouseOut="this.className='Button2Off'">Subscribe</TD>
</TR>
</TABLE>
</form>
<br>
<center>
Follow us on
<a href="http://www.facebook.com/s3stores" target="_blank"><img src="http://www.artistsupplysource.com/skin1_kolin/images/social/facebook.gif"></a>
<a href="https://twitter.com/#!/s3stores" target="_blank"><img src="http://www.artistsupplysource.com/skin1_kolin/images/social/twitter.gif"></a>
<a href="http://www.youtube.com/user/artistsupplysource" target="_blank"><img src="http://www.artistsupplysource.com/skin1_kolin/images/social/youtube.gif"></a>
</center>
</td>

</TR>
</FORM>

</TABLE>

{* --- *}
{/if}
{* --- *}

</td></td>

<TR>
<TD class="Bottom" align="left" colspan="4" style="padding-left: 10px;" {* height="30" *}>
{/if}
{include file="copyright.tpl"}{if $usertype eq "C"}</TD>
</TR>
</TABLE>
{/if}
