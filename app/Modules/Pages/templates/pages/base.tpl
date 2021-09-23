{extends $.request->getIsAjax() != false ? 'ajax.tpl' : 'base.tpl'}
{block "content-wrapper"}
    <div class="pages page default-content-page">
        {block "content"}{/block}
    </div>
{/block}