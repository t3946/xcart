( function () {
    function getLangCode() {
        const name = 'TranslatesFilterForm[name][]';
        const value = ( new RegExp( '[?&]' + encodeURIComponent( name ) + '=([^&]*)' ) ).exec( location.search );

        return value ? decodeURIComponent( value[ 1 ] ) : null;
    }

    $( document ).ready( function () {
        const lang_code = getLangCode();
        const $downloadTranslationsButton = $( '.download-translations-button' );

        $downloadTranslationsButton.click(function () {
            $.ajax( {
                url: `/admin/translates/upload-translates?lang_code=${ lang_code }`,
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                error() {
                    alert( 'Something went wrong' );
                }
            } );
        });

        const $uploadTranslatesForm = $( '.upload-translations-form' );

        if ( lang_code === null ) {
            alert( 'Filter translates by lang and try again' );
            return;
        }

        $uploadTranslatesForm.submit( function ( e ) {
            e.preventDefault();

            const data = new FormData();
            const $files = $uploadTranslatesForm.find( 'input[name="translates-list"]' )[ 0 ].files;

            $.each( $files, function ( i, file ) {
                data.append( 'file-' + i, file );
            } );

            $.ajax( {
                url: `/admin/translates/upload-translates?lang_code=${ lang_code }`,
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success() {
                    document.location.reload();
                },
                error() {
                    alert( 'Something went wrong' );
                }
            } );
        } );
    } );
} )();
