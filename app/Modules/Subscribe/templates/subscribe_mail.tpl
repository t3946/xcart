{extends "mail/base.tpl"}

{block "content"}
    {$message}
    <form class="subscribe" method="post" action="{url subscribe:set}">
        <input type="hidden" name="email" value="{$email}">
        <input type="hidden" name="domain" value="{$domain}">
        <input type="submit" value="subscribe">
    </form>
{/block}