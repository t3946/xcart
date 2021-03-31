import { Switcher } from "@/js/Classes/Switcher";

export class SwitcherSlider extends Switcher {
    /**
     * @param elem - string or jquery object
     * @param onAction
     * @param offAction
     * @param callback
     */
    constructor( elem, onAction, offAction, callback ) {
        super( elem, onAction, offAction, callback );
        this.$ball = this.$button.find( '.switcher-slider-ball' );
        this.$captionEnabled = $( '.switcher-slider-caption_enabled' );
        this.$captionDisabled = $( '.switcher-slider-caption_disabled' );
        this.animationSpeedMS = 150;
        this.updateStyles();
    }

    set isOn( value ) {
        if (this.$input) {
            this.$input.prop( 'checked', value );
        }

        this._isOn = value;

        this.updateStyles();
    }
    get isOn() {
        return this._isOn;
    }

    updateStyles() {
        if ( this._isOn ) {
            this.$ball.css( { left: 54 } );
            this.$captionDisabled.css( { left: 13, opacity: 1 } );
            this.$captionEnabled.css( { right: -87, opacity: 0 } );
        } else {
            this.$ball.css( { left: 4 } );
            this.$captionDisabled.css( { left: -87, opacity: 0 } );
            this.$captionEnabled.css( { right: 13, opacity: 1 } );
        }
    }

    animatedUpdateStyles() {
        if ( this._isOn ) {
            this.$ball.stop( true, false ).animate( { left: 54 }, this.animationSpeedMS, 'linear' );
            this.$captionDisabled.stop( true, false ).animate( { left: 13, opacity: 1 }, this.animationSpeedMS, 'linear' );
            this.$captionEnabled.stop( true, false ).animate( { right: -37, opacity: 0 }, this.animationSpeedMS, 'linear' );
        } else {
            this.$ball.stop( true, false ).animate( { left: 4 }, this.animationSpeedMS, 'linear' );
            this.$captionDisabled.stop( true, false ).animate( { left: -37, opacity: 0 }, this.animationSpeedMS, 'linear' );
            this.$captionEnabled.stop( true, false ).animate( { right: 13, opacity: 1 }, this.animationSpeedMS, 'linear' );
        }
    }

    toggle( event ) {
        super.toggle( event );

        this.animatedUpdateStyles();
    }
}
