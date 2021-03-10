<div style="height:0px;overflow:hidden">
    <input type="{$type}" accept="{$field->getHtmlAccept()}" value="{$value}" id="{$id}" name="{$name}" {raw $html}>
</div>
<button type="button" onclick="$('#{$id}').attr('type', 'file').click();">Upload image file</button>
<button type="button" onclick="uploadUrl(this)">Upload from URL</button>
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
    function uploadUrl(o) {
        const url = prompt( 'Enter file url' );

        if ( url ) {
            const input = document.querySelector('#{$id}');
            input.setAttribute('value', url);
            input.type = 'hidden';

            const link = document.querySelector('.{$id}_current-image');
            link.innerHTML = url.split('/').pop();
            link.style.display = 'inline-block';
        }
    }

    document.querySelector( '#{$id}' ).addEventListener( 'change', function () {
        if ( this.files && this.files[ 0 ] ) {
            const $link = $( '.{$id}_current-image' );
            const $img = $link.find( 'img' );

            $link.css( 'display', 'inline-block' );
            $img.attr( 'src', URL.createObjectURL( this.files[ 0 ] ) );
        }
    } );
</script
