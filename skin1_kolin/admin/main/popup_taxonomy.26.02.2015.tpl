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

function func_send_google_product_taxonomy (str) {

	str = str.replace(/[\_]+/gm, ' ');

	if (window.opener){
		window.opener.document.getElementById(id).value = str;
		window.close();
	}
}

{/literal}
//]]>
</script>

{if $google_product_taxonomy ne ""}
<table>
{assign var="tmp_counter" value="0"}
{foreach from=$google_product_taxonomy item=v key=k}
 {math assign="tmp_counter" equation="x+1" x=$tmp_counter}
 {assign var="prod_taxonomy" value=$k}
 <tr>
 <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$k|replace:"_":" "}</a></td>
 <td valign="top">



  {if $v ne ""}
   {assign var="tmp" value="0"}
   {foreach from=$v item=vv key=kk}
    {if $tmp eq "0"}
    <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
    {assign var="tmp" value="1"}
    {/if}
   {/foreach}

   <table style="display: none;" id="{$tmp_counter}">
    {assign var="tmp" value="0"}
    {foreach from=$v item=vv key=kk}
     {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
     {assign var="prod_taxonomy" value="`$k` > `$kk`"}
     <tr>
      <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kk|replace:"_":" "}</a></td>
      <td valign="top">



       {if $vv ne ""}
        {assign var="tmp" value="0"}
        {foreach from=$vv item=vvv key=kkk}
         {if $tmp eq "0"}
          <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
          {assign var="tmp" value="1"}
         {/if}
        {/foreach}

        <table style="display: none;" id="{$tmp_counter}">
         {assign var="tmp" value="0"}
         {foreach from=$vv item=vvv key=kkk}
          {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
          {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk`"}
          <tr>
           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkk|replace:"_":" "}</a></td>
           <td valign="top">



           {if $vvv ne ""}
            {assign var="tmp" value="0"}
            {foreach from=$vvv item=vvvv key=kkkk}
             {if $tmp eq "0"}
              <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
              {assign var="tmp" value="1"}
             {/if}
            {/foreach}

            <table style="display: none;" id="{$tmp_counter}">
             {assign var="tmp" value="0"}
             {foreach from=$vvv item=vvvv key=kkkk}
              {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
              {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk`"}
              <tr>
               <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkk|replace:"_":" "}</a></td>
               <td valign="top">



               {if $vvvv ne ""}
                {assign var="tmp" value="0"}
                {foreach from=$vvvv item=vvvvv key=kkkkk}
                 {if $tmp eq "0"}
                  <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                  {assign var="tmp" value="1"}
                 {/if}
                {/foreach}
       
                <table style="display: none;" id="{$tmp_counter}">
                 {assign var="tmp" value="0"}
                 {foreach from=$vvvv item=vvvvv key=kkkkk}
                  {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                  {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk`"}
                  <tr>
                   <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkk|replace:"_":" "}</a></td>
                   <td valign="top">

  

                   {if $vvvvv ne ""}
                    {assign var="tmp" value="0"}
                    {foreach from=$vvvvv item=vvvvvv key=kkkkkk}
                     {if $tmp eq "0"}
                      <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                      {assign var="tmp" value="1"}
                     {/if}
                    {/foreach}

                    <table style="display: none;" id="{$tmp_counter}">
                     {assign var="tmp" value="0"}
                     {foreach from=$vvvvv item=vvvvvv key=kkkkkk}
                      {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                      {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk`"}
                      <tr>
                       <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkk|replace:"_":" "}</a></td>
                       <td valign="top">
  


                       {if $vvvvvv ne ""}
                        {assign var="tmp" value="0"}
                        {foreach from=$vvvvvv item=vvvvvvv key=kkkkkkk}
                         {if $tmp eq "0"}
                          <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                          {assign var="tmp" value="1"}
                         {/if}
                        {/foreach}
                 
                        <table style="display: none;" id="{$tmp_counter}">
                         {assign var="tmp" value="0"}
                         {foreach from=$vvvvvv item=vvvvvvv key=kkkkkkk}
                          {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                          {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk` > `$kkkkkkk`"}
                          <tr>
                           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkkk|replace:"_":" "}</a></td>
                           <td valign="top">
  


                           {if $vvvvvvv ne ""}
                            {assign var="tmp" value="0"}
                            {foreach from=$vvvvvvv item=vvvvvvvv key=kkkkkkkk}
                             {if $tmp eq "0"}
                              <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                              {assign var="tmp" value="1"}
                             {/if}
                            {/foreach}
                     
                            <table style="display: none;" id="{$tmp_counter}">
                             {assign var="tmp" value="0"}
                             {foreach from=$vvvvvvv item=vvvvvvvv key=kkkkkkkk}
                              {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                              {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk` > `$kkkkkkk` > `$kkkkkkkk`"}
                              <tr>
                               <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkkkk|replace:"_":" "}</a></td>
                               <td valign="top">
                 
                

                               {if $vvvvvvvv ne ""}
                                {assign var="tmp" value="0"}
                                {foreach from=$vvvvvvvv item=vvvvvvvvv key=kkkkkkkkk}
                                 {if $tmp eq "0"}
                                  <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                                  {assign var="tmp" value="1"}
                                 {/if}
                                {/foreach}
                         
                                <table style="display: none;" id="{$tmp_counter}">
                                 {assign var="tmp" value="0"}
                                 {foreach from=$vvvvvvvv item=vvvvvvvvv key=kkkkkkkkk}
                                  {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                                  {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk` > `$kkkkkkk` > `$kkkkkkkk` > `$kkkkkkkkk`"}
                                  <tr>
                                   <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkkkkk|replace:"_":" "}</a></td>
                                   <td valign="top">
                     
                    
                   
                                   {if $vvvvvvvvv ne ""}
                                    {assign var="tmp" value="0"}
                                    {foreach from=$vvvvvvvvv item=vvvvvvvvvv key=kkkkkkkkkk}
                                     {if $tmp eq "0"}
                                      <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                                      {assign var="tmp" value="1"}
                                     {/if}
                                    {/foreach}
                              
                                    <table style="display: none;" id="{$tmp_counter}">
                                     {assign var="tmp" value="0"}
                                     {foreach from=$vvvvvvvvv item=vvvvvvvvvv key=kkkkkkkkkk}
                                      {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                                      {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk` > `$kkkkkkk` > `$kkkkkkkk` > `$kkkkkkkkk` > `$kkkkkkkkkk`"}
                                      <tr>
                                       <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkkkkkk|replace:"_":" "}</a></td>
                                       <td valign="top">
                          
                         
                        
                                       {if $vvvvvvvvvv ne ""}
                                        {assign var="tmp" value="0"}
                                        {foreach from=$vvvvvvvvvv item=vvvvvvvvvvv key=kkkkkkkkkkk}
                                         {if $tmp eq "0"}
                                          <a href="javascript: void(0);" onclick="javascript: $('#{$tmp_counter}').toggle();">+</a>
                                          {assign var="tmp" value="1"}
                                         {/if}
                                        {/foreach}
                                  
                                        <table style="display: none;" id="{$tmp_counter}">
                                         {assign var="tmp" value="0"}
                                         {foreach from=$vvvvvvvvvv item=vvvvvvvvvvv key=kkkkkkkkkkk}
                                          {if $tmp eq "0"}{math assign="tmp_counter" equation="x+1" x=$tmp_counter}{assign var="tmp" value="1"}{/if}
                                          {assign var="prod_taxonomy" value="`$k` > `$kk` > `$kkk` > `$kkkk` > `$kkkkk` > `$kkkkkk` > `$kkkkkkk` > `$kkkkkkkk` > `$kkkkkkkkk` > `$kkkkkkkkkk` > `$kkkkkkkkkkk`"}
                                          <tr>
                                           <td valign="top" nowrap="nowrap"><a href="javascript: void(0);" onclick="func_send_google_product_taxonomy('{$prod_taxonomy}');">{$kkkkkkkkkkk|replace:"_":" "}</a></td>
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

