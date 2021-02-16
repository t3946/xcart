export class Switcher {
    constructor( elem, onAction, offAction, callback ) {
        this._isOn = false;
        this.$button = typeof elem === 'string' ? $( elem ) : elem;
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
            this.$button.addClass( 'tumbler-button__on' );
            this.$button.removeClass( 'tumbler-button__off' );
            this.onAction();
        } else {
            this.$button.addClass( 'tumbler-button__off' );
            this.$button.removeClass( 'tumbler-button__on' );
            this.offAction();
        }

        if ( this.callback ) {
            this.callback( event );
        }
    }
}
