{*
$Id: news.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.News_Management}
  {insert name="gate" func="news_exist" assign="is_news_exist" lngcode=$shop_language}
  {if $is_news_exist}
    {insert name="gate" func="news_subscription_allowed" assign="is_subscription_allowed" lngcode=$shop_language}
    <div data-role="collapsible" data-collapsed="true" data-collapsed-icon="info" data-expanded-icon="info" data-theme="a" data-content-theme="c" class="news">
      <h3>{$lng.lbl_news}</h3>
      <div>
        {if $news_message eq ""}
          {$lng.txt_no_news_available}
        {else}
          <b>{$news_message.date|date_format:$config.Appearance.date_format}</b>
          <h3>{$news_message.subject}</h3>
          {$news_message.body}
          <div class="clearing"></div>
          <div class="button-row">
            {include file="customer/buttons/button.tpl" href="news.php" button_title=$lng.lbl_previous_news style="link" data_mini="true"}
            {if $is_subscription_allowed}
              {include file="customer/buttons/button.tpl" href="news.php#subscribe" button_title=$lng.lbl_subscribe style="link" data_mini="true"}
            {/if}
          </div>
        {/if}
      </div>
    </div>
  {/if}
{/if}
