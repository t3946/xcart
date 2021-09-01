{extends $.request->getIsAjax() != false ? 'ajax.tpl' : 'base.tpl'}
{block "content-wrapper"}
    <div class="">
        {block "content"}{/block}
    </div>
{/block}