<input type="{$type}" accept="{$field->getHtmlAccept()}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>

{if $value}
    <br>
    <a target="_blank" class="current-image" style="margin: 10px;
            vertical-align: middle;
            display: inline-block;
            width: 200px;
            min-height: 100px;
            background:  no-repeat center center;
            background-size: contain; background-image: url('{$field->getSizeImage()}')" href="{$field->getCurrentFileUrl()}"></a>
{/if}

{if $field->canClear()}
    <input style="width: 1rem;" value="{$field->getClearValue()}" id="{$id}_clear" type="checkbox" name="{$name}">
    <label for="{$id}_clear">{t 'Delete image'}</label>
{/if}
