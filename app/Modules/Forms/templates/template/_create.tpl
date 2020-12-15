{extends 'admin/create.tpl'}

{block 'before_form'}
    <div style="font-weight: 500;">
        Templates OrderRelatedMessages options
        <hr/>
    </div>
    {Modules\Core\Models\LanguageModel::translate('lbl_templates_order_related_messages_top')}
{/block}