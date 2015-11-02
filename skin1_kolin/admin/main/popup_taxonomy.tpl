{ config_load file="$skin_config" }
<html>
<head>
<title>Taxonomy</title>

<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.admin.css" />
<script src="{$SkinDir}/jquery-1.4.3.min.js" type="text/javascript"></script>

</head>
<body>

<script type="text/javascript" language="JavaScript 1.2">
<!--

var id='{$id}';

{literal}

function func_send_google_product_taxonomy (g_id) {

	var str;
	var tmp_k;

	{/literal}
	{foreach from=$google_categories_full item=v key=k}
	{literal}
		tmp_k = {/literal}{$k}{literal};
		if (g_id == tmp_k){
			str = "{/literal}{$v}{literal}";
		}
	{/literal}
	{/foreach}
	{literal}

	if (window.opener){
		window.opener.document.getElementById(id).value = str;
		window.opener.document.getElementById('last_taxonomy').value = g_id;
		window.close();
	}
}

{/literal}
//]]>
</script>


{if $last_taxonomy ne ""}
Last opened: {$google_categories_full[$last_taxonomy]}<br /><br />
{/if}


{if $google_product_taxonomy_id ne ""}
<table>
{foreach from=$google_product_taxonomy_id item=v key=k}
 <tr>
 <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$k}');">{$google_categories_short[$k]}</a></td>
 <td valign="top">


  {if $v ne ""}
   {assign var="tmp" value="0"}
   {foreach from=$v item=vv key=kk}
    {if $tmp eq "0"}
   <a href="javascript: void(0);" onclick="javascript: $('#{$k}').toggle();">+</a>
    {assign var="tmp" value="1"}
    {/if}
   {/foreach}

   <table {if $open_cats[$k] ne "Y"}style="display: none;"{/if} id="{$k}">
    {foreach from=$v item=vv key=kk}
     <tr>
      <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kk}');">{$google_categories_short[$kk]}</a></td>
      <td valign="top">


       {if $vv ne ""}
        {assign var="tmp" value="0"}
        {foreach from=$vv item=vvv key=kkk}
         {if $tmp eq "0"}
        <a href="javascript: void(0);" onclick="javascript: $('#{$kk}').toggle();">+</a>
          {assign var="tmp" value="1"}
         {/if}
        {/foreach}

        <table {if $open_cats[$kk] ne "Y"}style="display: none;"{/if} id="{$kk}">
         {foreach from=$vv item=vvv key=kkk}
          <tr>
           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkk}');">{$google_categories_short[$kkk]}</a></td>
           <td valign="top">



           {if $vvv ne ""}
            {assign var="tmp" value="0"}
            {foreach from=$vvv item=vvvv key=kkkk}
             {if $tmp eq "0"}
            <a href="javascript: void(0);" onclick="javascript: $('#{$kkk}').toggle();">+</a>
              {assign var="tmp" value="1"}
             {/if}
            {/foreach}

            <table {if $open_cats[$kkk] ne "Y"}style="display: none;"{/if} id="{$kkk}">
             {foreach from=$vvv item=vvvv key=kkkk}
              <tr>
               <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkk}');">{$google_categories_short[$kkkk]}</a></td>
               <td valign="top">



               {if $vvvv ne ""}
                {assign var="tmp" value="0"}
                {foreach from=$vvvv item=vvvvv key=kkkkk}
                 {if $tmp eq "0"}
                <a href="javascript: void(0);" onclick="javascript: $('#{$kkkk}').toggle();">+</a>
                  {assign var="tmp" value="1"}
                 {/if}
                {/foreach}
       
                <table {if $open_cats[$kkkk] ne "Y"}style="display: none;"{/if} id="{$kkkk}">
                 {foreach from=$vvvv item=vvvvv key=kkkkk}
                  <tr>
                   <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkk}');">{$google_categories_short[$kkkkk]}</a></td>
                   <td valign="top">

  

                   {if $vvvvv ne ""}
                    {assign var="tmp" value="0"}
                    {foreach from=$vvvvv item=vvvvvv key=kkkkkk}
                     {if $tmp eq "0"}
                    <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkk}').toggle();">+</a>
                      {assign var="tmp" value="1"}
                     {/if}
                    {/foreach}

                    <table {if $open_cats[$kkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkk}">
                     {foreach from=$vvvvv item=vvvvvv key=kkkkkk}
                      <tr>
                       <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkk}');">{$google_categories_short[$kkkkkk]}</a></td>
                       <td valign="top">
  


                       {if $vvvvvv ne ""}
                        {assign var="tmp" value="0"}
                        {foreach from=$vvvvvv item=vvvvvvv key=kkkkkkk}
                         {if $tmp eq "0"}
                        <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkkk}').toggle();">+</a>
                          {assign var="tmp" value="1"}
                         {/if}
                        {/foreach}
                
                        <table {if $open_cats[$kkkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkkk}">
                         {foreach from=$vvvvvv item=vvvvvvv key=kkkkkkk}
                          <tr>
                           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkkk}');">{$google_categories_short[$kkkkkkk]}</a></td>
                           <td valign="top">
  


                           {if $vvvvvvv ne ""}
                            {assign var="tmp" value="0"}
                            {foreach from=$vvvvvvv item=vvvvvvvv key=kkkkkkkk}
                             {if $tmp eq "0"}
                            <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkkkk}').toggle();">+</a>
                              {assign var="tmp" value="1"}
                             {/if}
                            {/foreach}
                     
                            <table {if $open_cats[$kkkkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkkkk}">
                             {foreach from=$vvvvvvv item=vvvvvvvv key=kkkkkkkk}
                              <tr>
                               <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkkkk}');">{$google_categories_short[$kkkkkkkk]}</a></td>
                               <td valign="top">
                 
                

                               {if $vvvvvvvv ne ""}
                                {assign var="tmp" value="0"}
                                {foreach from=$vvvvvvvv item=vvvvvvvvv key=kkkkkkkkk}
                                 {if $tmp eq "0"}
                                <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkkkkk}').toggle();">+</a>
                                  {assign var="tmp" value="1"}
                                 {/if}
                                {/foreach}
                         
                                <table {if $open_cats[$kkkkkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkkkkk}">
                                 {foreach from=$vvvvvvvv item=vvvvvvvvv key=kkkkkkkkk}
                                  <tr>
                                   <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkkkkk}');">{$google_categories_short[$kkkkkkkkk]}</a></td>
                                   <td valign="top">
                     
                    
                   
                                   {if $vvvvvvvvv ne ""}
                                    {assign var="tmp" value="0"}
                                    {foreach from=$vvvvvvvvv item=vvvvvvvvvv key=kkkkkkkkkk}
                                     {if $tmp eq "0"}
                                    <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkkkkkk}').toggle();">+</a>
                                      {assign var="tmp" value="1"}
                                     {/if}
                                    {/foreach}
                              
                                    <table {if $open_cats[$kkkkkkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkkkkkk}">
                                     {foreach from=$vvvvvvvvv item=vvvvvvvvvv key=kkkkkkkkkk}
                                      <tr>
                                       <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkkkkkk}');">{$google_categories_short[$kkkkkkkkkk]}</a></td>
                                       <td valign="top">
                          
                         
                        
                                       {if $vvvvvvvvvv ne ""}
                                        {assign var="tmp" value="0"}
                                        {foreach from=$vvvvvvvvvv item=vvvvvvvvvvv key=kkkkkkkkkkk}
                                         {if $tmp eq "0"}
                                        <a href="javascript: void(0);" onclick="javascript: $('#{$kkkkkkkkkk}').toggle();">+</a>
                                          {assign var="tmp" value="1"}
                                         {/if}
                                        {/foreach}
                                  
                                        <table {if $open_cats[$kkkkkkkkkk] ne "Y"}style="display: none;"{/if} id="{$kkkkkkkkkk}">
                                         {foreach from=$vvvvvvvvvv item=vvvvvvvvvvv key=kkkkkkkkkkk}
                                          <tr>
                                           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$kkkkkkkkkkk}');">{$google_categories_short[$kkkkkkkkkkk]}</a></td>
                                           <td valign="top">
                              


                                           </td>
                                          </tr>
                                         {/foreach}
                                        </table>
                                       {/if}



                                       </td>
                                      </tr>
                                     {/foreach}
                                    </table>
                                   {/if}



                                   </td>
                                  </tr>
                                 {/foreach}
                                </table>
                               {/if}

 
 
                               </td>
                              </tr>
                             {/foreach}
                            </table>
                           {/if}

 
 
                           </td>
                          </tr>
                         {/foreach}
                        </table>
                       {/if}

 
 
                       </td>
                      </tr>
                     {/foreach}
                    </table>
                   {/if}


 
                   </td>
                  </tr>
                 {/foreach}
                </table>
               {/if}



               </td>
              </tr>
             {/foreach}
            </table>
           {/if}



           </td>
          </tr>
         {/foreach}
        </table>
       {/if}



      </td>
     </tr>
    {/foreach}
   </table>
  {/if}



 </td>
 </tr>
{/foreach}
</table>
{/if}

</body>
</html>

