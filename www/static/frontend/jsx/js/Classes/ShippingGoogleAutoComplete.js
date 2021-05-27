import { GoogleAutoComplete } from "@/js/Classes/GoogleAutoComplete";

export class ShippingGoogleAutoComplete extends GoogleAutoComplete {
    constructor(elem, componentForm, fields) {
        super(elem, componentForm, fields);
        const self = this;

        this.autocomplete.addListener( "place_changed", function () {
            const streetNumber = self.autocomplete.getPlace().address_components[0].long_name;
            const streetName = self.autocomplete.getPlace().address_components[1].long_name;
            const shortAddress = `${streetNumber} ${streetName}`;

            self.addressField.value = self.autocomplete.getPlace().formatted_address;
            self.fillInAddress.call( self );

            /**
             * update  shipping  address components
             */
            if (self.addressField.id === 'CheckoutForm_s_full_address') {
                CheckoutForm_s_address.value = shortAddress;
            }

            /**
             * update  billing  address components
             */
            if (self.addressField.id === 'CheckoutForm_b_full_address') {
                CheckoutForm_b_address.value = shortAddress;
            }
        } );
    }

    afterFillField( addressType, addressComponent, field ) {
        switch ( addressType ) {
            case 'administrative_area_level_1':
            case 'country':
                field.setAttribute( 'data-code', addressComponent[ 'short_name' ] );
                break;
        }
    }
}
