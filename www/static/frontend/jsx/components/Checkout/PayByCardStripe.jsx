import { Fragment, createRef } from "preact";
import { loadStripe } from "@stripe/stripe-js/pure";
import InputError from "@/components/Checkout/InputError";
import "regenerator-runtime/runtime";
import merge from "lodash/merge";
import Price from "@/components/product/card/components/Price";

export default class PayByCardStripe extends Component {
  constructor(props) {
    super(props);

    this.errorRef = createRef();

    this.state = merge(dataProvider.get("stripe"), {
      error: "",
      grand_total: app.options.order.total,
    });

    //update payment intent when checkout(and cart) changed
    $(document).on("updateRequestSuccess.checkout", (e, res) => {
      this.setState({ paymentIntent: res.payment_intent });
      this.setState({ grand_total: res.grand_total });
    });
  }

  async componentDidMount() {
    this.stripe = await loadStripe(this.state.publicKey);

    const stripe = this.stripe;
    // init stripe field
    const elements = stripe.elements({
      locale: "en",
    });
    const button = document.querySelector("button");
    const clientSecret = this.state.paymentIntent;
    const style = {
      base: {
        color: "#272727",
        fontSize: "20px",
        "::placeholder": {
          color: "#272727",
        },
      },
    };
    const { b_country, currency, total } = app.options.order;
    const paymentRequest = stripe.paymentRequest({
      country: "US",
      currency: currency.toLowerCase(),
      total: {
        label: "Total",
        amount: Math.floor(total * 100),
      },
      requestPayerName: true,
      requestPayerEmail: true,
    });

    paymentRequest.on("paymentmethod", (ev) => {
      stripe
        .confirmCardPayment(
          clientSecret,
          { payment_method: ev.paymentMethod.id },
          { handleActions: false }
        )
        .then((confirmResult) => {
          if (confirmResult.error) {
            ev.complete("fail");
          } else {
            ev.complete("success");
            stripe.confirmCardPayment(clientSecret).then((result) => {
              if (result.error) {
                const error = result.error ? result.error.message : "";
                this.setState({ error });
              } else {
                window.location = button.dataset.return;
              }
            });
          }
        });
    });

    const prButton = elements.create("paymentRequestButton", {
      paymentRequest: paymentRequest,
      classes: {
        base: "checkout_stripe-element-button",
      },
    });

    paymentRequest.canMakePayment().then(function (result) {
      //temporary disable apple pay button
      /*if (result) {
        prButton.mount("#payment-request-button");
      } else {
        document.getElementById("payment-request-button").style.display =
          "none";
      }*/
    });

    this.card = elements.create("card", {
      style: style,
      classes: {
        base: "stripe-element common-input",
        complete: "common-input__correct",
        invalid: "common-input__wrong",
      },
    });

    this.card.mount("#" + this.state.fieldId);

    this.card.on("change", (event) => {
      document.querySelector("button").disabled = event.empty || event.error;
      const error = event.error ? event.error.message : "";
      this.setState({ error });
    });
  }

  async sendStripeRequest(successCallback, errorCallback) {
    const stripe = this.stripe;
    const form = document.forms.CheckoutForm9;
    const clientSecret = this.state.paymentIntent;

    document.querySelector("button").disabled = true;

    function getValue(fieldName) {
      const field = form[`CheckoutForm[${fieldName}]`];

      if (fieldName === "s_country" || fieldName === "b_country") {
        return field.getAttribute("data-code");
      }

      return field.value;
    }

    await stripe
      .confirmCardPayment(clientSecret, {
        payment_method: {
          card: this.card,
          billing_details: {
            address: {
              city: getValue("b_city") || getValue("s_city"),
              country: getValue("b_country") || getValue("s_country"),
              line1: getValue("b_address") || getValue("s_address"),
              line2: getValue("b_address_2") || getValue("s_address_2"),
              postal_code: getValue("b_zipcode") || getValue("s_zipcode"),
              state: getValue("b_state") || getValue("s_state"),
            },
            name: getValue("b_firstname"),
            email: getValue("email"),
            phone: getValue("phone"),
          },
        },
        shipping: {
          address: {
            line1: getValue("s_address"),
            line2: getValue("s_address_2"),
            city: getValue("s_city"),
            country: getValue("s_country"),
            postal_code: getValue("s_zipcode"),
            state: getValue("s_state"),
          },
          name: getValue("s_firstname"),
        },
      })
      .then((result) => {
        if (result.error) {
          document.querySelector("button").disabled = false;
          const error = result.error ? result.error.message : "";
          this.setState({ error });

          window.scrollTo({
            top: $(".billing-form-fields").offset().top,
            behavior: "smooth",
          });

          if (typeof errorCallback === "function") {
            errorCallback();
          }
        } else if (typeof successCallback === "function") {
          successCallback();
        }
        this.toggleHeaderClasses();
      });
  }

  // решение проблемы с иконкой для поля, она не устанавливается
  // сама т.к. стандартная валидация не может обработать это поле
  toggleHeaderClasses() {
    const $stripeError = $(this.errorRef.current.base);
    const $fieldRow = $stripeError.parents(".checkout-field-row");
    const $fieldTitle = $fieldRow.find(".checkout-field-title");
    const $stripeField = $fieldRow.find("#CheckoutForm_pbc_card_details");

    if ($stripeField.hasClass("common-input__correct")) {
      $fieldTitle.addClass("field__correct");
    } else {
      $fieldTitle.removeClass("field__correct");
    }
  }

  // переместить сообщение об ошибке в поле карты, в нужное место формы
  moveErrorMessage() {
    const $stripeError = $(this.errorRef.current.base);
    const $fieldRow = $stripeError.parents(".checkout-field__row");
    $fieldRow.prepend($stripeError);
    this.toggleHeaderClasses();
  }

  shouldComponentUpdate(nextProps, nextState) {
    if (nextState.error !== this.state.error) {
      this.moveErrorMessage();
    }

    return true;
  }

  componentDidUpdate() {
    this.moveErrorMessage();
  }

  render(props, state) {
    return (
      <Fragment>
        <div className="checkout-stripe">
          <InputError message={this.state.error} ref={this.errorRef} />
          <div id="payment-request-button" />
          <div id="CheckoutForm_pbc_card_details" />
          <p className="checkout_stripe-description stripe-description">
            Your cart will be charged in the amount of USA of{" "}
            <span className="stripe-description-price">
              <Price
                currency={app.options.currency}
                price={this.state.grand_total}
              />
            </span>{" "}
            by S3 Stores, Inc.
          </p>
        </div>
      </Fragment>
    );
  }
}
