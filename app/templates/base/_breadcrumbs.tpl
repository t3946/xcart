{if isset($breadcrumbs) && $breadcrumbs|instanceof:'Xcart\App\Components\Breadcrumbs' && $breadcrumbs->get()|count > 0}
    <nav class="breadcrumbs-container frame" data-breadcrumbs='{json_encode(array_values(array_merge([['name' => $.getSiteConfig->company_name->value, 'url' => $site->getAbsoluteUrl()]], $breadcrumbs->get())))}'></nav>
{/if}

