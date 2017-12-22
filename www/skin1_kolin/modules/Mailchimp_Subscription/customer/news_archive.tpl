{*
$Id: news_archive.tpl,v 1.3 2010/07/22 08:33:38 joy Exp $
vim: set ts=2 sw=2 sts=2 et:
*}

<h1>{$lng.lbl_news}</h1>

{if $news_messages eq ""}

  {$lng.txt_no_news_available}

{else}

  {include file="customer/main/navigation.tpl"}

  <div class="news-list">

    {foreach from=$news_messages item=m name=news}

      <div>
        <div class="news-date">{$m.date|date_format:$config.Appearance.date_format}</div>

        <div class="news-title">{$m.subject|escape}</div>

        <div class="news-body">
          {if $m.allow_html eq "N"}
            {$m.body|escape|nl2br}
          {else}
            {$m.body|escape}
          {/if}
        </div>

      </div>

    {/foreach}

  </div>

  {include file="customer/main/navigation.tpl"}

<br /><br />

{/if}

{insert name="gate" func="news_subscription_allowed" assign="is_subscription_allowed" lngcode=$shop_language}

{if $active_modules.Mailchimp_Subscription}

  <a name="subscribe"></a>
  {capture name=dialog}

    <form action="mailchimp_news.php" name="subscribeform" method="post">
      <input type="hidden" name="subscribe_lng" value="{$store_language|escape}" />

      <table cellspacing="1" class="data-table">

        <tr>
          <td class="data-name"><label for="semail">{$lng.lbl_your_email}</label></td>
          <td class="data-required">*</td>
          <td><input type="text" class="input-email" id="semail" name="newsemail" size="30" value="{$newsemail|default:""|escape}" /></td>
        </tr>

        {if $active_modules.Image_Verification and $show_antibot.on_news_panel eq 'Y'}
          {include file="modules/Image_Verification/spambot_arrest.tpl" mode="data-table" id=$antibot_sections.on_news_panel}
        {/if}
  
        <tr>
          <td colspan="3" class="button-row">
            {include file="buttons/subscribe_menu.tpl" type="input"}
          </td>
        </tr>

      </table>

    </form>

  {/capture}
  {include file="dialog.tpl" title=$lng.lbl_subscribe content=$smarty.capture.dialog}

{/if}
