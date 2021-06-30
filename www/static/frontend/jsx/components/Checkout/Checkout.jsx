import { render, createRef } from "preact";
import PayByCardStripe from "@/components/Checkout/PayByCardStripe";
import CheckoutJs from "@/js/Components/checkout/Checkout";

export default class Checkout extends Component {
  constructor() {
    super();
    this.PayByCardStripe = createRef();
    this.checkoutSubmit.bind(this);
    this.state = {
      // need for update total in checkout only after last request
      checkoutUpdateQueries: 0,
    };

    $(document).on("updateRequestSend.checkout", () => {
      this.setState({
        checkoutUpdateQueries: this.state.checkoutUpdateQueries + 1,
      });

      this.toggleSkeletons(true);
    });

    $(document).on("updateRequestSuccess.checkout", (e, total) => {
      if (this.state.checkoutUpdateQueries === 1) {
        this.updateTotal(total);
      }
    });

    $(document).on("updateRequestComplete.checkout", () => {
      const checkoutUpdateQueries = this.state.checkoutUpdateQueries - 1;

      this.setState({ checkoutUpdateQueries });

      //hide preloader if this is the last query
      if (checkoutUpdateQueries === 0) {
        this.toggleSkeletons(false);
      }
    });

    $(document).on("update.total.checkout", () => {
      const $stripeTarget = $(".stripe-target");
      const options = {
        id: $stripeTarget.data("id"),
        pi: $stripeTarget.data("pi"),
        public_key: $stripeTarget.data("public_key"),
      };

      if ($stripeTarget.length) {
        render(
          <PayByCardStripe {...options} ref={this.PayByCardStripe} />,
          $stripeTarget[0]
        );
      }
    });

    $(document.forms.CheckoutForm9).on("beforeCheckoutSubmit", (e, data) => {
      this.checkoutSubmit(e, data);
    });
  }

  formatNumber(number) {
    return Intl.NumberFormat("en-US", { style: "currency", currency: "USD" })
      .format(number)
      .substr(1);
  }

  updateTotal(total) {
    $(".order-total .total .price").text(this.formatNumber(total["total"]));
    $(".shipping-total .price").text(
      this.formatNumber(total["total_shipping_cost"])
    );
    $(".total-sales-tax .price").text(
      this.formatNumber(total["total_sales_tax"])
    );
    $(".total-vat-tax .price").text(this.formatNumber(total["total_vat_tax"]));
    $(".grand-total .price").text(this.formatNumber(total["grand_total"]));
  }

  insertStripeField() {
    //insert stripe
    const $stripeTarget = $(".stripe-target");

    const $stripeField = $(".checkout-stripe");

    if ($stripeTarget.length) {
      $stripeTarget.append($stripeField.show());
    } else {
      $stripeField.hide();
    }
  }

  toggleSkeletons(isActive) {
    $(".grand-total-wrapper")
      .toggleClass("skeleton-box", isActive)
      .css("color", isActive ? "transparent" : "");

    $(".warehouse_products .cart-subtotal-wrapper")
      .toggleClass("skeleton-box", isActive)
      .css("color", isActive ? "transparent" : "");

    $(".shipping-method-item-wrapper").toggleClass("skeleton-box", isActive);
    $(".shipping-method-row").css({
      outline: isActive ? "none" : "",
      background: isActive ? "none" : "",
    });

    $(".to-see-shipping-options-wrapper")
      .toggleClass("skeleton-box", isActive)
      .css("color", isActive ? "transparent" : "");
  }

  componentDidMount() {
    //this solution have to smooth No-React to React transition
    //use html permutations and inserts for creating full-react component someday

    //no-react checkout
    const $checkoutElement = $(".cart_shipping-page.default-content-page");

    //react container
    const $root = $(".checkout");

    // insert no-react-code in react container
    $checkoutElement.appendTo($root);
    this.insertStripeField();

    this.toggleSkeletons(false);
  }

  componentDidUpdate(previousProps, previousState, previousContext) {
    this.insertStripeField();
  }

  shouldComponentUpdate(nextProps, nextState) {
    //dont update if only checkoutUpdateQueries changed
    if (this.state.checkoutUpdateQueries !== nextState.checkoutUpdateQueries) {
      return false;
    }
  }

  checkoutSubmit(e, data) {
    CheckoutJs.disableSubmitButton(true);

    /**
     * отправкой формы управляет валидатор, он решает позволять форме отправить данные или нет -- это не правильно,
     * надо забрать у него право это решать.
     */
    e = data.event;
    e.preventDefault();

    /**
     * валидатор проверил форму как смог, и если есть ошибки, не переходить к проверки поля карты
     */
    if (data.hasErrors === true) {
      return;
    }

    //если нужно проверять поле карты
    if (
      document.forms.CheckoutForm9["CheckoutForm[paymentid]"].value === "106"
    ) {
      /**
       * ещё до отправки формы у поля карты уже могут быть ошибки, и если они там есть -- не отправлять
       */
      const $stripeFieldError = $(
        "#CheckoutForm_pbc_card_holder_name_errors.common-field-error_visible"
      );
      if ($stripeFieldError.length) {
        //scroll to this errors
        window.scrollTo({
          top: $(".billing-form-fields").offset().top,
          behavior: "smooth",
        });

        CheckoutJs.disableSubmitButton(false);
        return;
      }

      /**
       * после отправки формы, поле карты делает запрос на свой сервер чтобы ещё раз капитально себя проверить.
       * Это последняя проверка, и если она пройдена -- можно отправлять заказ
       */
      this.PayByCardStripe.current.sendStripeRequest(
        () => {
          document.forms.CheckoutForm9.submit();
        },
        () => {
          CheckoutJs.disableSubmitButton(false);
        }
      );
    } else {
      document.forms.CheckoutForm9.submit();
    }
  }

  render() {
    const $stripeTarget = $(".stripe-target");

    return (
      <div className="checkout">
        {!!$stripeTarget.length && (
          <PayByCardStripe ref={this.PayByCardStripe} />
        )}
      </div>
    );
  }
}
