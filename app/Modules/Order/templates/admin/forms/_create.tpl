{extends 'admin/create.tpl'}

{block 'before_form'}
    {$.call.Modules.Forms.Models.SnippetModel::renderSnippetsInfo()}
    <br/>
    <br/>
{/block}