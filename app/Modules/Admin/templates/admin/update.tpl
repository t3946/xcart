{extends "admin/create.tpl"}

{block 'heading'}
    {if !$form->getSections()}
    <h1>{if $form->getName()}{$form->getName()}: {/if}{$model}</h1>
    {/if}
{/block}

{block 'page_class'}update{/block}