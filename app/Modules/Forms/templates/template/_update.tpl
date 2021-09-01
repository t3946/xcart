{extends "template/_create.tpl"}

{block 'heading'}
    <h1>{if $form->getName()}{$form->getName()}: {/if}{$model}</h1>
{/block}

{block 'page_class'}update{/block}