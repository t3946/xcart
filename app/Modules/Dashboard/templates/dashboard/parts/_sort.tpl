<div class="raw" style="height: 30px; margin-top: 16px;">
    <div class="columns large-8">
        &nbsp;
    </div>
    <div class="columns large-4">
        <select name="search[sort]" onchange="window.location=this.value" class="big">
            {foreach $sorting_filter as $key => $value}
                <option value="{$view->urlPageSort($key)}"
                        {if $key == $view->getSort()}selected="selected"{/if}>{$value}</option>
            {/foreach}
        </select>
    </div>
</div>
