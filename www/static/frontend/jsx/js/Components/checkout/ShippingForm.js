import { ShippingPixabayAutocomplete } from "@/js/Classes/ShippingPixabayAutocomplete";
import "node_modules/imask";
import { ShippingGoogleAutoComplete } from "@/js/Classes/ShippingGoogleAutoComplete";
import Checkout from "@/js/Components/checkout/Checkout";

export const ShippingForm = (function () {
  // no checkout page
  if (document.querySelector(".checkout-page") === null) {
    return;
  }

  // autocomplete for main address field
  const componentForm = {
    street_number: "short_name",
    route: "long_name",
    locality: "short_name",
    administrative_area_level_1: "long_name",
    country: "long_name",
    postal_code: "short_name",
  };

  const shipping_fields = {
    locality: "#CheckoutForm_s_city",
    administrative_area_level_1: "#CheckoutForm_s_state",
    country: "#CheckoutForm_s_country",
    postal_code: "#CheckoutForm_s_zipcode",
  };

  new ShippingGoogleAutoComplete(
    "#CheckoutForm_s_address",
    componentForm,
    shipping_fields
  );

  const constructor = function () {
    this.$otherFields = $(".checkout-shipping-other-fields");
    this.addressField = document.getElementById("CheckoutForm_s_address");

    /* phone mask */
    const CheckoutForm_ci_phone = document.getElementById("CheckoutForm_phone");
    CheckoutForm_ci_phone &&
      IMask(CheckoutForm_ci_phone, { mask: "(000) 000-0000" });

    const CheckoutForm_ci_phone_ext = document.getElementById(
      "CheckoutForm_phone_ext"
    );
    CheckoutForm_ci_phone_ext &&
      IMask(CheckoutForm_ci_phone_ext, { mask: "00000" });
  };

  /**
   * установить автозаполнение для группы полей: country, state, city, zipcode
   */
  function initAddressAutocompleteFields(
    countryInput,
    stateInput,
    cityInput,
    zipcodeInput
  ) {
    new ShippingPixabayAutocomplete(countryInput, {
      renderItem: function (item) {
        return (
          '<div class="autocomplete-suggestion" data-val="' +
          item.name +
          '" data-code="' +
          item.code +
          '">' +
          item.name +
          "</div>"
        );
      },

      source: function (term, suggest) {
        $.getJSON(
          "/checkout/auto_complete_country/",
          { search: term },
          function (data) {
            suggest(data);
          }
        );
      },
    });

    new ShippingPixabayAutocomplete(stateInput, {
      renderItem: function (item) {
        return (
          '<div class="autocomplete-suggestion" data-val="' +
          item.state +
          '" data-code="' +
          item.code +
          '">' +
          item.state +
          "</div>"
        );
      },

      source: function (term, suggest) {
        $.getJSON(
          "/checkout/auto_complete_state/",
          {
            search: term,
            country: countryInput.dataset.code,
          },
          function (data) {
            suggest(data);
          }
        );
      },

      onSelect: function (e, term, item) {
        let state, codeState;

        if (e.constructor === Object) {
          state = e.state;
          codeState = e.code;
        } else {
          state = item.dataset.val;
          codeState = item.dataset.code;
        }

        stateInput.value = state;
        stateInput.dataset.code = codeState;
      },
    });

    new ShippingPixabayAutocomplete(cityInput, {
      renderItem: function (item) {
        const $suggestion = $("<div>", {
          class: "autocomplete-suggestion",
          attr: {
            "data-val": item,
          },
          text: item,
        });

        return $suggestion[0].outerHTML;
      },

      source: function (term, suggest) {
        $.getJSON(
          "/checkout/auto_complete_city/",
          {
            search: term,
            country: countryInput ? countryInput.getAttribute("data-code") : "",
            state: CheckoutForm_s_state
              ? CheckoutForm_s_state.getAttribute("data-code")
              : "",
          },
          function (data) {
            suggest(data);
          }
        );
      },
    });

    new ShippingPixabayAutocomplete(zipcodeInput, {
      renderItem: function (item) {
        const $zip = $("<span>", {
          class: "zip",
          text: item.zip,
        });

        const $city = $("<span>", {
          class: "city",
          text: `${item.primary_city}, ${item.state}`,
        });

        const $suggestion = $("<div>", {
          class: "autocomplete-suggestion",
          attr: {
            "data-state-code": item.state,
            "data-city": item.primary_city,
            "data-val": item.zip,
            "data-state-name": item.state_name,
          },
        });

        $suggestion.append($zip).append($city);

        return $suggestion[0].outerHTML;
      },

      source: function (term, suggest) {
        const path = "/checkout/auto_complete_zip_code/";
        const options = {
          search: term,
          country: countryInput ? countryInput.getAttribute("data-code") : "",
        };
        const callback = (data) => {
          suggest(data);
        };

        $.getJSON(path, options, callback);
      },

      onSelect: function (e, term, item) {
        e.preventDefault();

        // zip code передаёт сразу 3 типа данных - город,
        // штат и почтовый код -- здесь они записываются в поля формы
        const { city, stateName, stateCode, val } = item.dataset;

        zipcodeInput.value = val;
        cityInput.value = city;
        stateInput.value = stateName;
        stateInput.dataset.code = stateCode;

        // save other update fields
        const data = {};

        data[cityInput.name] = cityInput.value;
        data[stateInput.name] = stateInput.value;

        Checkout.fieldUpdate(data);
      },
    });
  }

  /**
   * автозаполнение адреса shipping формы
   */
  initAddressAutocompleteFields(
    document.getElementById('CheckoutForm_s_country'),
    document.getElementById('CheckoutForm_s_state'),
    document.getElementById('CheckoutForm_s_city'),
    document.getElementById('CheckoutForm_s_zipcode')
  );

  /**
   * автозаполнение адреса billing формы
   */
  if (
    typeof CheckoutForm_b_country !== "undefined" &&
    typeof CheckoutForm_b_state !== "undefined" &&
    typeof CheckoutForm_b_city !== "undefined" &&
    typeof CheckoutForm_b_zipcode !== "undefined"
  ) {
    initAddressAutocompleteFields(
      document.getElementById('CheckoutForm_b_country'),
      document.getElementById('CheckoutForm_b_state'),
      document.getElementById('CheckoutForm_b_city'),
      document.getElementById('CheckoutForm_b_zipcode')
    );
  }

  return new constructor();
})();
