import { GoogleAutoComplete } from "@/js/Classes/GoogleAutoComplete";
import Checkout from "@/js/Components/checkout/Checkout";

export class ShippingGoogleAutoComplete extends GoogleAutoComplete {
  constructor(elem, componentForm, fields) {
    super(elem, componentForm, fields);

    const self = this;

    this.autocomplete.addListener("place_changed", function () {
      const streetNumber = self.autocomplete.getPlace().address_components[0]
        .long_name;
      const streetName = self.autocomplete.getPlace().address_components[1]
        .long_name;

      self.addressField.value = `${streetNumber} ${streetName}`;
      self.fillInAddress.call(self);

      /**
       * update  shipping  address components
       */
      if (self.addressField.id === "CheckoutForm_s_address") {
        const data = {};

        data[CheckoutForm_s_address.name] = CheckoutForm_s_address.value;
        data[CheckoutForm_s_country.name] = CheckoutForm_s_country.value;
        data[CheckoutForm_s_zipcode.name] = CheckoutForm_s_zipcode.value;
        data[CheckoutForm_s_state.name] = CheckoutForm_s_state.value;
        data[CheckoutForm_s_city.name] = CheckoutForm_s_city.value;

        Checkout.fieldUpdate(data);
      }

      /**
       * update  billing  address components
       */
      if (self.addressField.id === "CheckoutForm_b_address") {
        const data = {};

        data[CheckoutForm_b_address.name] = CheckoutForm_b_address.value;
        data[CheckoutForm_b_country.name] = CheckoutForm_b_country.value;
        data[CheckoutForm_b_zipcode.name] = CheckoutForm_b_zipcode.value;
        data[CheckoutForm_b_state.name] = CheckoutForm_b_state.value;
        data[CheckoutForm_b_city.name] = CheckoutForm_b_city.value;

        Checkout.fieldUpdate(data);
      }
    });
  }

  afterFillField(addressType, addressComponent, field) {
    switch (addressType) {
      case "administrative_area_level_1":
      case "country":
        field.setAttribute("data-code", addressComponent["short_name"]);
        break;
    }
  }
}
