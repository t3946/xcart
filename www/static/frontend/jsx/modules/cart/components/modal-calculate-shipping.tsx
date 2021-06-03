import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import Modal from "@material-ui/core/Modal";
import Backdrop from "@material-ui/core/Backdrop";
import Fade from "@material-ui/core/Fade";
import $ from "jquery";
import { ShippingPixabayAutocomplete } from "@/js/Classes/ShippingPixabayAutocomplete";
import classnames from "classnames";

/**
 * Модуль авто-заполнения не может работать со state из React, по этому ему нужен адаптер в виде внешнего хранилища
 */
const autocompleteData = {
  countryCode: "US",
  stateCode: "",
  country: "United States",
  city: "",
  zipcode: "",
  state: "",
};

function ModalCalculateShipping() {
  const useStyles = makeStyles((theme) => ({
    modal: {
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
    },
    paper: {
      backgroundColor: theme.palette.background.paper,
      border: "2px solid #000",
      boxShadow: theme.shadows[5],
      padding: theme.spacing(2, 4, 3),
      position: "relative",
    },
  }));

  const classes = useStyles();
  const method = "POST";
  const actionUrl = "/cart/calculate_shipping";
  const [open, setOpen] = React.useState(false);
  const [qPressed, setQPressed] = React.useState(false);
  const [shippingPrice, setShippingPrice] = React.useState({});

  const [countryCode, setCountryCode] = React.useState("US");
  const [stateCode, setStateCode] = React.useState("");
  const [country, setCountry] = React.useState("United States");
  const [city, setCity] = React.useState("");
  const [zipcode, setZipcode] = React.useState("");
  const [state, setState] = React.useState("");

  const [errors, setErrors] = React.useState({
    country: [],
    zipcode: [],
    state: [],
    city: [],
  });
  //для предотвращения инициализации авто-заполнителя несколько раз
  const [initialized, setInitialized] = React.useState(false);

  function getCountry() {
    return country;
  }

  function initAddressAutocompleteFields(
    countryInput,
    stateInput,
    cityInput,
    zipcodeInput
  ) {
    setInitialized(true);
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

      onSelect: function (e, term, item) {
        const code = item.getAttribute("data-code");
        setCountryCode(code);
        setCountry(term);
        autocompleteData.country = term;
        autocompleteData.countryCode = code;
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
            country: autocompleteData.countryCode,
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

        const code = item.getAttribute("data-code");
        setStateCode(code);
        setState(term);
        autocompleteData.stateCode = code;
        autocompleteData.state = term;
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
            country: autocompleteData.countryCode,
            state: autocompleteData.stateCode,
          },
          function (data) {
            suggest(data);
          }
        );
      },

      onSelect: function (e, term, item) {
        setCity(term);
        autocompleteData.city = term;
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
          country: autocompleteData.countryCode,
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

        setStateCode(stateCode);
        setCity(city);
        setState(stateName);
        setZipcode(val);

        autocompleteData.stateCode = stateCode;
        autocompleteData.city = city;
        autocompleteData.state = stateName;
        autocompleteData.zipcode = val;

        // save other update fields
        const data = {};

        data[cityInput.name] = cityInput.value;
        data[stateInput.name] = stateInput.value;
      },
    });
  }

  React.useEffect(() => {
    const country = document.getElementById("CountShippingForm_country");
    const state = document.getElementById("CountShippingForm_state");
    const city = document.getElementById("CountShippingForm_city");
    const zipcode = document.getElementById("CountShippingForm_zipcode");

    if (country && state && city && zipcode && !initialized) {
      initAddressAutocompleteFields(country, state, city, zipcode);
    }
  });

  const handleOpen = () => {
    if (qPressed) {
      setOpen(true);
    }
  };

  const handleClose = () => {
    setOpen(false);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const data = objectifyForm();

    $.ajax({
      url: actionUrl,
      method,
      data,
      dataType: "json",
      success(res) {
        setShippingPrice(res.result);
      },
      error(err) {
        // form has errors
        if (err.responseJSON.errors) {
          setErrors(err.responseJSON.errors);
        }
      },
    });
  };

  const printShippingPrice = () => {
    const result = [];

    for (const key in shippingPrice) {
      result.push(`${key}: ${shippingPrice[key]}`);
    }

    if (result.length === 0) {
      return;
    }

    return <div className="column margin-top-1">{result}</div>;
  };

  const printErrors = (errorsList = []) => {
    const errorElements = [];

    for (const error of errorsList) {
      errorElements.push(<div style={{ color: "red" }}>{error}</div>);
    }

    return errorElements;
  };

  /**
   * watch press Q key
   */
  (function () {
    const getChar = (event) => {
      if (event.which && (event.charCode || event.keyCode)) {
        if (event.which < 32) {
          return null;
        }
        return String.fromCharCode(event.which);
      }
      // спец. символ
      return null;
    };

    document.addEventListener("keydown", (event) => {
      const symbol = getChar(event);
      if (symbol == "Q") {
        setQPressed(true);
      }
    });

    document.addEventListener("keyup", (event) => {
      const symbol = getChar(event);
      if (symbol == "Q") {
        setQPressed(false);
      }
    });
  })();

  // serialize form to object data
  function objectifyForm() {
    const formArray = $(
      document.forms["calculate-shipping-form"]
    ).formToArray();
    const returnObject = {};

    for (let i = 0; i < formArray.length; i++) {
      returnObject[formArray[i]["name"]] = formArray[i]["value"];
    }
    return returnObject;
  }

  return (
    <div>
      <img
        src={"/static/frontend/dist/images/logos/s3stores.svg"}
        alt="s3stores"
        className="show-for-large s3-logo-big"
        onClick={handleOpen}
      />
      <Modal
        aria-labelledby="transition-modal-title"
        aria-describedby="transition-modal-description"
        className={classes.modal}
        open={open}
        onClose={handleClose}
        closeAfterTransition
        BackdropComponent={Backdrop}
        BackdropProps={{
          timeout: 500,
        }}
      >
        <Fade in={open}>
          <div className={classnames(classes.paper, "modal-paper")}>
            <i onClick={handleClose} className="modal-close modal_close" />
            <form
              action={actionUrl}
              method={method}
              onSubmit={handleSubmit}
              name="calculate-shipping-form"
            >
              <div className="row form-row">
                <div className="column">
                  <label htmlFor="CountShippingForm_country">Country</label>
                  <input
                    type="text"
                    value={country}
                    id="CountShippingForm_country"
                    name="CountShippingForm[country]"
                    placeholder="United States"
                    className="auto-complete country required common-input"
                    autoComplete="new-password"
                    onChange={(e) => {
                      setCountry(e.target.value);
                    }}
                    required
                  />
                  {printErrors(errors.country)}
                </div>
              </div>

              <div className="row form-row">
                <div className="column">
                  <label htmlFor="CountShippingForm_country">
                    Zip/Postal Code
                  </label>
                  <input
                    type="text"
                    value={zipcode}
                    id="CountShippingForm_zipcode"
                    name="CountShippingForm[zipcode]"
                    placeholder="08540"
                    className="auto-complete zip required common-input"
                    autoComplete="new-password"
                    inputMode="numeric"
                    onChange={(e) => {
                      setZipcode(e.target.value);
                    }}
                    required
                  />
                  {printErrors(errors.zipcode)}
                </div>
              </div>

              <div className="row form-row">
                <div className="column">
                  <label htmlFor="CountShippingForm_country">
                    State/Province
                  </label>
                  <input
                    type="text"
                    value={state}
                    id="CountShippingForm_state"
                    name="CountShippingForm[state]"
                    placeholder="New Jersey"
                    className="auto-complete state required common-input"
                    autoComplete="new-password"
                    onChange={(e) => {
                      setState(e.target.value);
                    }}
                    required
                  />
                  {printErrors(errors.state)}
                </div>
              </div>

              <div className="row form-row">
                <div className="column">
                  <label htmlFor="CountShippingForm_country">City</label>
                  <input
                    type="text"
                    value={city}
                    id="CountShippingForm_city"
                    name="CountShippingForm[city]"
                    placeholder="Princeton"
                    className="auto-complete city required common-input"
                    autoComplete="new-password"
                    onChange={(e) => {
                      setCity(e.target.value);
                    }}
                    required
                  />
                  {printErrors(errors.city)}
                </div>
              </div>

              <div className="row align-center">
                <div className="column small-12">
                  <div className="buttons text-center">
                    <button
                      type="submit"
                      className="button submit yellow waves waves-orange waves-effect button__big margin-top-2"
                    >
                      Submit
                    </button>
                  </div>
                </div>
              </div>

              {printShippingPrice()}
            </form>
          </div>
        </Fade>
      </Modal>
    </div>
  );
}

export default ModalCalculateShipping;
