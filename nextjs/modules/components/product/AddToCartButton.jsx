import classnames from "classnames";
import CatalogContext from "@/components/catalog/CatalogContext";
import { cartAdd } from "../../redux/reduсers/appCartReducer";
import * as preact from "preact";
import CreateWaitButton from "@/components/AnimateWaitButton";
import t from "@/i18n";

export default class AddToCartButton extends Component {
  constructor(props) {
    super(props);

    this.SIMPLE_MODE = "simple";
    this.COMPLEX_MODE = "complex";
    this.state = {
      classes: {},
      mode: this.SIMPLE_MODE,
    };

    this.onAddToCart = this.onAddToCart.bind(this);

    this.checkoutLink = preact.createRef();
    this.button = preact.createRef();
    this.mainWrapper = preact.createRef();
    this.checkoutWrapper = preact.createRef();
  }

  /**
   * computed html classes for redraw component
   */
  computeClasses() {
    const mainWrapper = ["add-to-cart-button"];
    const button = [
      "add",
      "button",
      "yellow",
      "wait-button",
      "add-to-cart-button-add",
    ];
    const checkoutLinkWrapper = ["add-to-cart-button-wrapper"];
    const checkoutLink = [
      "button",
      "yellow-white",
      "waves waves-orange",
      "waves-effect",
      "add-to-cart-button-checkout",
    ];
    const addToCartLongText = [
      "button-text",
      {
        complex: this.state.mode === this.COMPLEX_MODE,
      },
    ];

    const classes = {
      mainWrapper,
      button,
      checkoutLinkWrapper,
      checkoutLink,
      addToCartLongText,
      waitText: ["wait-text", "button-wait-text"],
    };

    const propsClasses = this.props.classes;

    // extend computed classes by props classes
    if (propsClasses) {
      button.push(`add-to-cart-button-add__${this.state.mode}`);
      checkoutLink.push(`add-to-cart-button-checkout__${this.state.mode}`);

      if (this.state.mode === this.COMPLEX_MODE) {
        button.push(propsClasses.buttonComplex);
        checkoutLink.push(propsClasses.checkoutLinkComplex);
      }

      for (let key in classes) {
        classes[key].push(propsClasses[key]);
      }
    }

    // join classes
    for (let key in classes) {
      classes[key] = classnames(classes[key]);
    }

    this.classes = classes;
  }

  /**
   * print no need account template part if need
   */
  noAccount() {
    const noAccount = false;

    if (noAccount) {
      return (
        <div className="no-account">
          {t("No account needed! \n Checkout only takes 3 minutes.")}
        </div>
      );
    }
  }

  productItemResetState(product) {
    const input = product.querySelector(".quantity-group input");
    const val = input.min;

    input.value = val;
    product.dataset.quantity = val;

    $(document).trigger("component.quantity.change", {
      target: product,
      val: val,
      product: product,
    });
  }

  onAddToCart() {
    const button = this.button.current;

    if (this.state.mode === this.SIMPLE_MODE) {
      setTimeout(() => {
        this.setState({ mode: this.COMPLEX_MODE });
        this.computeClasses();

        if (typeof this.props.onChangeMode === "function") {
          this.props.onChangeMode();
        }
      }, 1000);
    }

    const buttonAnimation = CreateWaitButton(button);
    const product = button.closest("[data-product]");

    if (product) {
      let form = null;

      // product options form (need to determine exactly sort of product)
      const infoFormId = button
        .closest(".cart-add")
        .getAttribute("data-form-id");

      if (infoFormId) {
        form = document.getElementById(infoFormId);

        if (
          typeof document.formValidators !== "undefined" &&
          document.formValidators[infoFormId] !== "undefined"
        ) {
          let formValidate = document.formValidators[infoFormId];
          formValidate.checkAllForm();

          if (formValidate.hasErrors) {
            return false;
          }
        }
      }

      let opt = [];
      let values = $(form).serializeArray();

      console.log(values);

      for (let oneValue of values) {
        let valueParts = oneValue.value.split("_");
        let identifiersParts = valueParts[0].split("-");
        opt.push({
          optionId: identifiersParts[0],
          variantId: identifiersParts[1],
        });
      }

      console.log(this.props);
      let data = [
        {
          id: product.dataset.product,
          quantity: this.props.quantity,
          options: opt,
        },
      ];

      buttonAnimation.start();

      cartAdd(data, () => {
        this.productItemResetState(product);

        // show caption on product page
        $(".jackpot").show();
      });

      window.sendAnalytics.addToCart(product);
    }
  }

  render() {
    this.computeClasses();

    return (
      <div className={this.classes.mainWrapper} ref={this.mainWrapper}>
        <a
          className={this.classes.button}
          onClick={this.onAddToCart}
          ref={this.button}
        >
          <span className={this.classes.addToCartLongText}>
            {t("Add to cart")}
          </span>
          <span className={this.classes.waitText}>{t("Added")}</span>
        </a>

        {this.state.mode === this.COMPLEX_MODE && (
          <div
            className={this.classes.checkoutLinkWrapper}
            ref={this.checkoutWrapper}
          >
            <a
              href={this.context.checkoutUrl}
              className={this.classes.checkoutLink}
              ref={this.checkoutLink}
            >
              {t("Checkout")}
            </a>
            {this.noAccount()}
          </div>
        )}
      </div>
    );
  }
}

AddToCartButton.contextType = CatalogContext;
