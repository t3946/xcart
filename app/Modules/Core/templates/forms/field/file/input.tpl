<div style="height:0px;overflow:hidden">
    <input type="{$type}" accept="{$field->getHtmlAccept()}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
</div>
<span class="file_name_label">{if $value}{$field->getCurrentFileName()}{/if}</span>
<button class="waves waves-orange waves-effect common-button-upload" type="button" onclick="$('#{$id}').attr('type', 'file').click();">Upload</button>

<script>
    document.querySelector('#{$id}').addEventListener('change', function() {
        let fileName = $(this).val().split('/').pop().split('\\').pop();
        let lbl = document.querySelector('.file_name_label');
        lbl.textContent = fileName;
    })
</script>
