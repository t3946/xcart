import { Switcher }  from "./Switcher";

export class SwitcherButton extends Switcher {
    /**
     * @param elem - string or jquery object
     * @param onAction
     * @param offAction
     * @param callback
     */
    constructor( elem, onAction, offAction, callback ) {
        super( elem, onAction, offAction, callback );
        this.$minusIcon = this.$button.find( '.switcher-button-icon-minus' );
        this.$plusIcon = this.$button.find( '.switcher-button-icon-plus' );
        this.toggleCaption();
    }

    set isOn( value ) {
        this._isOn = value;
        this.toggleCaption();
    }

    get isOn() {
        return this._isOn;
    }

    toggleCaption() {
        if ( this._isOn ) {
            this.$minusIcon.show();
            this.$plusIcon.hide();
        } else {
            this.$minusIcon.hide();
            this.$plusIcon.show();
        }
    }

    toggle( event ) {
        super.toggle( event );
        this.toggleCaption();
    }
}
