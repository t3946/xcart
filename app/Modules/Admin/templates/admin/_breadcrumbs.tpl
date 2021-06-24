<div class="breadcrumbs-block">
    <ul class="breadcrumbs-list mb-2.5 mt-2.5">
        <li><a href="{url route="admin:index"}" class="">Home</a></li>

        {foreach $breadcrumbs as $item}
            <li class="delimiter">»</li>

            <li><a href="{$item['url']}" class="">{$item['name']}</a></li>
        {/foreach}
    </ul>
</div>
