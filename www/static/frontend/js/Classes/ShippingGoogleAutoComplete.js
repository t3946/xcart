import { GoogleAutoComplete } from "./GoogleAutoComplete";

export class ShippingGoogleAutoComplete extends GoogleAutoComplete {
    afterFillField( addressType, addressComponent, field ) {
        switch ( addressType ) {
            case 'administrative_area_level_1':
            case 'country':
                field.setAttribute( 'data-code', addressComponent[ 'short_name' ] );
                break;
        }
    }
}
