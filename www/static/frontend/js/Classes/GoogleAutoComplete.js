export class GoogleAutoComplete {
    /**
     * @param elem selector or address input element
     * @param componentForm object
     * @param fields object
     * @url https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform?hl=ru#all
     */
    constructor( elem, componentForm, fields ) {
        const options = { types: [ "geocode" ] };

        this.addressField = typeof elem === 'string' ? document.querySelector( elem ) : elem;
        this.componentForm = componentForm;
        this.fields = {};

        for ( const key in fields ) {
            if ( fields.hasOwnProperty( key ) ) {
                this.fields[ key ] = document.querySelector( fields[ key ] );
            }
        }

        const self = this;

        this.autocomplete = new google.maps.places.Autocomplete( this.addressField, options );
        this.autocomplete.addListener( "place_changed", function () {
            const streetNumber = self.autocomplete.getPlace().address_components[0].long_name;
            const streetName = self.autocomplete.getPlace().address_components[1].long_name;

            self.addressField.value = `${streetNumber} ${streetName}`;
            self.fillInAddress.call( self );
        } );
        this.addressField.onfocus = () => this.geoLocate();
        this.addressField.onkeydown = function ( e ) {
            if ( e.keyCode === 13 ) {
                e.stopPropagation();
            }
        }
    }

    afterFillField( addressType, addressComponent, field ) {
    }

    throwAddressChangeEvent() {
        const event = new CustomEvent( 'autocomplete.change' );
        this.addressField.dispatchEvent( event );
    }

    /**
     * Bias the autocomplete object to the user's geographical location,
     * as supplied by the browser's 'navigator.geolocation' object.
     */
    geoLocate() {
        if ( navigator.geolocation ) {
            navigator.geolocation.getCurrentPosition( ( position ) => {
                const geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                const circle = new google.maps.Circle( {
                    center: geolocation,
                    radius: position.coords.accuracy,
                } );
                this.autocomplete.setBounds( circle.getBounds() );
            } );
        }
    }

    fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = this.autocomplete.getPlace();

        if ( !place.address_components ) {
            return;
        }

        for ( const addressComponent of place.address_components ) {
            const addressType = addressComponent.types[ 0 ];

            // if unused field
            if ( !this.componentForm[ addressType ] ) {
                continue;
            }

            const valueType = this.componentForm[ addressType ];
            const value = addressComponent[ valueType ];
            const field = this.fields[ addressType ];

            this.afterFillField( addressType, addressComponent, field );

            if ( field ) {
                field.value = value;
            }
        }

        this.throwAddressChangeEvent();
    }
}
