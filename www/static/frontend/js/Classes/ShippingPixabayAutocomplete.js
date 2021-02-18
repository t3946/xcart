import AutoComplete from 'bower_components/javascript-auto-complete/auto-complete';

export class ShippingPixabayAutocomplete {
    constructor( elem, autocompleteOptions ) {
        this.variants = null;
        this.forceCompleted = false;
        this.input = null;
        this.input = typeof elem === 'string' ? document.querySelector( elem ) : elem;
        const self = this;

        new AutoComplete( {
            selector: self.input,
            cache: false,
            offsetTop: 0,
            minChars: 1,
            renderItem: autocompleteOptions.renderItem,
            source: function ( term, suggest ) {
                if ( self.forceCompleted === true ) {
                    return;
                }

                autocompleteOptions.source( term, function ( data ) {
                    self.variants = data;
                    suggest( data );
                } );
            },
            onSelect: function ( e, term, item ) {
                e.preventDefault();

                self.variants = null;
                if ( autocompleteOptions.onSelect ) {
                    autocompleteOptions.onSelect( e, term, item, self );
                } else if ( item.dataset.code !== undefined ) {
                    self.input.dataset.code = item.dataset.code;
                    self.input.value = item.dataset.val;
                } else {
                    self.input.value = term;
                }

                self.throwJsChangeEvent( self.input );
            }
        } );

        const $input = $( this.input );

        // force select when nothing selected
        function forceUpdate() {
            console.log('force');
            self.forceCompleted = true;

            // use first autocomplete variant as selected
            let variant = self.variants[ 0 ];

            if ( autocompleteOptions.onSelect ) {
                autocompleteOptions.onSelect( variant, null, null, self );
                return;
            }

            let i = 0;

            if ( typeof variant === 'string' ) {
                //search compatible variant if it exists
                while ( i < self.variants.length ) {
                    if (self.input.value === self.variants[i]) {
                        variant = self.variants[i];
                    }

                    i++;
                }

                self.input.value = variant;
            } else {
                //search compatible variant if it exists
                while ( i < self.variants.length ) {
                    if (self.input.value === self.variants[i]['name']) {
                        variant = self.variants[i];
                    }

                    i++;
                }

                self.input.value = variant.name;
                self.input.dataset.code = variant.code;
            }

            self.throwJsChangeEvent( self.input );
            self.variants = null;
        }

        /**
         * press tab when without selected item in autocomplete list
         */
        $input.keydown( function ( e ) {
            e.stopPropagation();
            e.keyCode === 9 && self.variants && self.variants[ 0 ]
                ? forceUpdate()
                : self.forceCompleted = false;
        } );

        /**
         * focus blur when without selected item in autocomplete list
         */
        $input.blur( function () {
            self.variants && self.variants[ 0 ]
                ? forceUpdate()
                : self.forceCompleted = false;
        } );
    }

    throwJsChangeEvent( element ) {
        let detail = { element };
        let event = new CustomEvent( 'change-event', { detail } );
        element.dispatchEvent( event );
    }
}