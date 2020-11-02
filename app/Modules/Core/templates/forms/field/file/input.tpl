<div style="height:0px;overflow:hidden">
    <input type="{$type}" accept="{$field->getHtmlAccept()}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
</div>
<span class="file_name_label">{if $value}{$field->getCurrentFileName()}{/if}</span> &nbsp;
<button class="button yellow-white waves waves-orange waves-effect" type="button" onclick="$('#{$id}').attr('type', 'file').click();">Upload</button>

<script>
    document.querySelector('#{$id}').addEventListener('change', function() {
        let fileName = $(this).val().split('/').pop().split('\\').pop();
        let lbl = document.querySelector('.file_name_label');
        lbl.textContent = fileName;
    })
</script>


{*
{if $field->canClear()}
    <br/><br/>
    <input value="{$field->getClearValue()}" id="{$id}_clear" type="checkbox" name="{$name}">
    <label for="{$id}_clear">{t 'Clean'}</label>
{/if}*}
