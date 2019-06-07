<div class="pager-container">
<select name="search[sort]" onchange="window.location=this.value" class="big">
    {foreach $sorting_filter as $key => $value}
        <option value="{$view->urlPageSort($key)}" {if $key == $view->getSort()}selected="selected"{/if}>{$value}</option>
    {/foreach}
</select>
</div>
