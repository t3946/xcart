export class Switcher {
    constructor( elem, onAction, offAction, callback ) {
        this._isOn = false;
        this.$button = typeof elem === 'string' ? $( elem ) : elem;
        this.$input = this.$button.find( 'input[type="checkbox"]' ).eq( 0 );
        this.onAction = onAction;
        this.offAction = offAction;
        this.callback = callback;

        const self = this;

        this.$button.click( function ( event ) {
            self.toggle( event );
        } );
    }

    set isOn( value ) {
        if ( typeof value !== 'boolean' ) {
            throw new Error( 'isOn expected type boolean, passed ' + typeof value );
        }

        this._isOn = value;
    }

    get isOn() {
        return this._isOn;
    }

    toggle( event ) {
        this._isOn = !this._isOn;

        if ( this._isOn === true ) {
            if ( this.$input ) {
                this.$input.prop( 'checked', true );
            }

            this.onAction();
        } else {
            if ( this.$input ) {
                this.$input.prop( 'checked', false );
            }

            this.offAction();
        }

        if ( this.callback ) {
            this.callback( event );
        }
    }
}
