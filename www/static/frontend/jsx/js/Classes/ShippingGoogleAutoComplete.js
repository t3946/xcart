import { GoogleAutoComplete } from "@/js/Classes/GoogleAutoComplete";

export class ShippingGoogleAutoComplete extends GoogleAutoComplete {
    constructor(elem, componentForm, fields) {
        super(elem, componentForm, fields);
        const self = this;

        this.autocomplete.addListener( "place_changed", function () {
            const streetNumber = self.autocomplete.getPlace().address_components[0].long_name;
            const streetName = self.autocomplete.getPlace().address_components[1].long_name;

            self.addressField.value = `${streetNumber} ${streetName}`;
            self.fillInAddress.call( self );
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
