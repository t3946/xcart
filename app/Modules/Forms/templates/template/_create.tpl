{extends 'admin/create.tpl'}

{block 'before_form'}
    {Modules\Forms\Models\SnippetModel::renderSnippetsInfo()}
    <br/>
    <br/>
{/block}