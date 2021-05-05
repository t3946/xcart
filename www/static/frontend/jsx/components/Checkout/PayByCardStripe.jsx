import { Fragment } from "preact";
import { loadStripe } from "@stripe/stripe-js";
import InputError from "@/components/Checkout/InputError";
import "regenerator-runtime/runtime";

export default class PayByCardStripe extends Component {
  constructor(props) {
    super(props);
    this.state = {
      error: "",
    };
  }

  async componentDidMount() {
    this.stripe = await loadStripe(this.props.public_key);

    const stripe = this.stripe;
    // init stripe field
    const elements = stripe.elements({
      locale: "en",
    });
    const button = document.querySelector("button");
    const clientSecret = this.props.pi;
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
      country: b_country || "US",
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
      if (result) {
        prButton.mount("#payment-request-button");
      } else {
        document.getElementById("payment-request-button").style.display =
          "none";
      }
    });

    this.card = elements.create("card", {
      style: style,
      classes: {
        base: "stripe-element common-input",
        complete: "common-input__correct",
        invalid: "common-input__wrong",
      },
    });

    this.card.mount("#" + this.props.id);

    this.card.on("change", (event) => {
      document.querySelector("button").disabled = event.empty || event.error;
      const error = event.error ? event.error.message : "";
      this.setState({ error });
    });
  }

  sendStripeRequest() {
    const stripe = this.stripe;
    const form = document.forms.CheckoutForm9;
    const clientSecret = this.props.pi;

    document.querySelector("button").disabled = true;

    function getValue(fieldName) {
      const field = form[`CheckoutForm[${fieldName}]`];

      if (fieldName === "s_country" || fieldName === "b_country") {
        return field.getAttribute("data-code");
      }

      return field.value;
    }

    stripe
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
        }
      });
  }

  render() {
    return (
      <Fragment>
        <div className="checkout-stripe">
          <InputError message={this.state.error} />
          <div id="payment-request-button"></div>
          <div id="CheckoutForm_pbc_card_details"></div>
        </div>
      </Fragment>
    );
  }
}
