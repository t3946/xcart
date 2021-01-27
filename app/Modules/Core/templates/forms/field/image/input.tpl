<div style="height:0px;overflow:hidden">
    <input type="{$type}" accept="{$field->getHtmlAccept()}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
</div>
    <button type="button" onclick="$('#{$id}').attr('type', 'file').click();">Upload image</button>
    <button type="button" onclick="uploadUrl()">Upload from url</button>
    <br>
    <a target="_blank" class="{$id}_current-image" style="
            margin: 10px;
            vertical-align: middle;
            {if $value}display: inline-block; {else} display: none; {/if}
            min-height: 100px;
            background: #E8E8E8 no-repeat center center;
            border: 1px solid #ff8600;
            " href="{$field->getCurrentFileUrl()}
    ">
        <img style="display: block" src="{$field->getSizeImage()}" width="200" alt="">
    </a>


{if $field->canClear()}
    <input style="width: 1rem;" value="{$field->getClearValue()}" id="{$id}_clear" type="checkbox" name="{$name}">
    <label for="{$id}_clear">{t 'Delete image'}</label>
{/if}
<script>
    async function uploadUrl() {
        let url = prompt('Enter file url');
        if (url) {
            let input = document.querySelector('#{$id}');
            input.setAttribute('value', url);
            input.type='hidden';
            let img = document.querySelector('.{$id}_current-image');
            let blob = await fetch('https://cors-anywhere.herokuapp.com/' + url).then(r => r.blob());
            img.style.backgroundImage = 'url(' + URL.createObjectURL(blob) + ')';
            img.style.display = 'inline-block';
        }
    }
    document.querySelector('#{$id}').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var img = document.querySelector('.{$id}_current-image');
            img.style.backgroundImage = 'url('+ URL.createObjectURL(this.files[0]) + ')';
            img.style.display = 'inline-block';
        }
    });
</script
