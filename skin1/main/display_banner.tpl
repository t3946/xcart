{* $Id: display_banner.tpl,v 1.8.2.3 2005/05/12 05:35:28 max Exp $ *}
{if $type eq 'iframe'}
<IFRAME marginwidth="0" marginheight="0" frameborder="0" scrolling="no" style="border-width: 0px; border-style: none;" width="{$banner.banner_x}px" height="{$banner.banner_y}px" src="{$http_location}/banner.php?bid={$banner.bannerid}&partner={$partner}&type=iframe{if $productid > 0}&productid={$productid}{/if}"></IFRAME>
{else}
{if $banner.banner_type eq 'G'}
{if $banner.legend ne ''}
<TABLE border="0">
<TR>
{if $banner.direction eq 'U'}<TD align="center">{$banner.legend|escape}</TD></TR><TR>{/if}
{if $banner.direction eq 'L'}<TD valign="middle">{$banner.legend|escape}</TD>{/if}
<TD>
{/if}
<A href="{$catalogs.customer}/home.php{if $partner ne ''}?bid={$banner.bannerid}&partner={$partner}{/if}"{if $banner.open_balnk eq 'Y'} target="_blank"{/if}><IMG src="{$http_location}/banner.php?bid={$banner.bannerid}{if $partner ne ''}&partner={$partner}{/if}" border="0"{if $banner.alt ne ''} alt="{$banner.alt|escape}"{/if}></A>
{if $banner.legend ne ''}
</TD>
{if $banner.direction eq 'D'}</TR><TR><TD align="center">{$banner.legend|escape}</TD>{/if}
{if $banner.direction eq 'R'}<TD valign="middle">{$banner.legend|escape}</TD>{/if}
</TR>
</TABLE>
{/if}
{elseif ($banner.banner_type eq 'M' || $banner.banner_type eq 'T' || $banner.banner_type eq 'P')}
{if $type ne 'ssi'}
<SCRIPT type="text/javascript" language="JavaScript 1.2" src="{$http_location}/banner.php?bid={$banner.bannerid}{if $partner ne ''}&partner={$partner}{/if}{if $productid > 0}&productid={$productid}{/if}"></SCRIPT>
{else}
&lt;!--#include virtual="{$http_location}/banner.php?bid={$banner.bannerid}{if $partner ne ''}&partner={$partner}{/if}&type=ssi{if $productid > 0}&productid={$productid}{/if}"--&gt;
{/if}
{/if}
{/if}
