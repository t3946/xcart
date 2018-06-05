{if isset($breadcrumbs) && $breadcrumbs|instanceof:'Xcart\App\Components\Breadcrumbs' && $breadcrumbs->get()|count > 0}

    <nav class="breadcrumbs-container frame">
        {*<section class="back show-for-small">*}
        {*<a href="#" onclick="history.back()">*}
        {*<i></i>*}
        {*</a>*}
        {*</section>*}

        <ol class="breadcrumb-list no-bullet slidee" itemscope itemtype="http://schema.org/BreadcrumbList" itemprop="breadcrumb">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="/">
                <span itemprop="name">
                    {$.getSiteConfig->company_name->value}
                </span>
                </a>
                <meta itemprop="position" content="0" />
            </li>

            {foreach $breadcrumbs->get() as $item index=$index last=$last}
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    {if !$last && $item.url}
                        <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{$item.url}">
                        <span itemprop="name">
                            {$item.name}
                        </span>
                        </a>
                    {else}
                        <span itemscope itemtype="http://schema.org/Thing" itemprop="item">
                        <span itemprop="name">
                            {$item.name}
                        </span>
                    </span>
                    {/if}

                    <meta itemprop="position" content="{$index +1}" />
                </li>
            {/foreach}
        </ol>
    </nav>
    <div class="scrollbar">
        <div class="handle"></div>
    </div>
{/if}

