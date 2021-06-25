<ul class="list-unstyled m-0">
    <li class="breadcrumbs-item">
        {if count($breadcrumbs)}
            <a href="{url route="admin:index"}" class="breadcrumbs-link">Home</a>
        {else}
            <span class="breadcrumbs-link text-decoration-none">Home</span>
        {/if}
    </li>

    {foreach $breadcrumbs as $item last=$last}
        <li class="breadcrumbs-item">
            {if !$last}
                <a href="{$item['url']}" class="breadcrumbs-link">{$item['name']}</a>
            {else}
                <span class="breadcrumbs-link text-decoration-none">{$item['name']}</span>
            {/if}
        </li>
    {/foreach}
</ul>
