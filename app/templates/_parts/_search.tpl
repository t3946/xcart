<div class="search-form-container">
    <form action="{$.app->router->url('catalog:search')}" method="get" itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">
        <input type="text"
               name='q'
               class="search"
               placeholder="Search art supply items, brands and categories"
               value="{$.app->request->get->get('q', '')}"
               itemprop="query-input"
               data-suggestion-url="{$.app->router->url('catalog:search:suggestion')}"
        />


        <meta itemprop="target" content="{$.app->router->url('catalog:search')}?q={ignore}{query}{/ignore}"/>

        <button class="button-search show-for-large"></button>
        <a class="button-clear {if $.app->request->get->get('q')}active{/if}"></a>

    </form>
</div>