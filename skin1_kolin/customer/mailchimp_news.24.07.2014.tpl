{*
$Id: mailchimp_news.tpl 6 2010-11-03 19:56:51Z max $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.Mailchimp_Subscription}
  {insert name="gate" func="news_exist" assign="is_news_exist" lngcode=$shop_language}

  {if $is_news_exist}

    {insert name="gate" func="news_subscription_allowed" assign="is_subscription_allowed" lngcode=$shop_language}

    {capture name=menu}

      <div class="news">

        {if $news_message eq ""}

          {$lng.txt_no_news_available}

        {else}
{if $active_modules.News_Management}
          <strong>{$news_message.date|date_format:$config.Appearance.date_format}</strong><br />
          {$news_message.body}<br /><br />
          <a href="news.php" class="prev-news">{$lng.lbl_previous_news}</a>
      {/if}  
          
           {if $active_modules.Mailchimp_Subscription}
             &nbsp;&nbsp;
            <a href="mailchimp_news.php#subscribe" class="subscribe">{$lng.lbl_subscribe}</a>
          
          {elseif $is_subscription_allowed}
            &nbsp;&nbsp;
            <a href="news.php#subscribe" class="subscribe">{$lng.lbl_subscribe}</a>
          {/if}
                 
          
        {/if}

      </div>

    {/capture}
    {include file="customer/menu_dialog.tpl" title=$lng.lbl_news content=$smarty.capture.menu additional_class="menu-news"}

  {/if}
{/if}
