{extends 'mail/raw_template.tpl'}

{block 'content'}
    {raw $msg|nl2br}
{/block}