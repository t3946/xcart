<title>{$title}</title>
{if $keywords}<meta name="keywords" content="{$keywords}" />{/if}
{if $description}<meta name="description" content="{$description|escape}" />{/if}
{if $canonical}<link rel="canonical" href="{$canonical}" />{/if}
{if $noIndex}<meta name="robots" content="noindex">{/if}

{if $advanced}
    {foreach $advanced as $type => $vals}
        {switch $type}
        {case 'link'}
            <link rel="{$vals.name}" href="{$vals.content}">
        {case 'meta'}
            <meta name="{$vals.name}" content="{$vals.content|escape}">
        {/switch}
    {/foreach}
{/if}