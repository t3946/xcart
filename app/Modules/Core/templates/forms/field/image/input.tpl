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
        const url = prompt( 'Enter file url' );

        if ( url ) {
            const $input = $( '#{$id}' );

            $input.val( url );
            $input[ 0 ].type = 'hidden';

            const $link = $( '.{$id}_current-image' );
            const $img = $link.find( 'img' );
            const blob = await fetch( 'https://cors-anywhere.herokuapp.com/' + url ).then( r => r.blob() );

            $link.css( 'display', 'inline-block' );
            $img.attr( 'src', URL.createObjectURL( blob ) );
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
