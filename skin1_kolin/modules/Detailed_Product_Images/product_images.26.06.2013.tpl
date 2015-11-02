{* $Id: product_images.tpl,v 1.16.2.1 2006/05/18 08:02:40 max Exp $ *}
{if $images ne ""}
{capture name=dialog}
<center>
{section name=image loop=$images}
{if $images[image].avail eq "Y"}
{if $images[image].tmbn_url}
<img src="{$images[image].tmbn_url}" alt="{$images[image].alt|escape}" style="padding-bottom: 10px;" />
{else}
<img src="{$xcart_web_dir}/image.php?id={$images[image].imageid}&amp;type=D" alt="{$images[image].alt|escape}" style="padding-bottom: 10px;" />
{/if}
<br />
{/if}
{/section}
</center>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_detailed_images content=$smarty.capture.dialog extra='width="100%"' do_not_use_h1="Y"}
{/if}
{if $product_files ne ''}
	{capture name=dialog}
		<ul class="no_marker">
			{section name=pfile loop=$product_files}
				<li><a href="get_product_file.php?file={$product_files[pfile].fileid}&amp;productid={$product_files[pfile].productid}" class="VertMenuItems"><font size=2>{$product_files[pfile].filename}</font></a>&nbsp;::&nbsp;{$product_files[pfile].description}</li>
			{/section}
		</ul>
	{/capture}
	{include file="dialog.tpl" title=$lng.lbl_product_files content=$smarty.capture.dialog extra='width="100%" class="recommends no_padding_bottom"'}
{/if}
