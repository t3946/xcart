{extends 'admin/create.tpl'}

{block 'before_form'}
    {Modules\Core\Models\LanguageModel::translate('lbl_templates_order_related_messages_top')}
{/block}