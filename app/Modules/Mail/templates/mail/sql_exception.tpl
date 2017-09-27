{extends 'mail/base.tpl'}

{block 'content'}
    {raw $msg|nl2br}
{/block}