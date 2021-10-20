{if isset($breadcrumbs) && $breadcrumbs|instanceof:'Xcart\App\Components\Breadcrumbs' && $breadcrumbs->get()|count > 0}
    {set $breadcrumbsJson = json_encode(array_values(array_merge([['name' => $site->company_name, 'url' => $site->getAbsoluteUrl()]], $breadcrumbs->get())))}
    <nav class="breadcrumbs-container frame" data-breadcrumbs='{str_replace("'", '&#39;', $breadcrumbsJson)}'></nav>
{/if}