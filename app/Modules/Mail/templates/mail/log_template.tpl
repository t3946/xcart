{extends 'mail/raw_template.tpl'}

{block 'content'}
    {raw $message|nl2br}
{/block}