import CreateWaitButton from "@/components/AnimateWaitButton";
import { cartAdd } from "../../redux/reducers/appCartReducer";
import Storage from "@/utils/localStorage/storage";
import Store from "../../redux/stores/Store";

const SIMPLE_MODE = 1;
const COMPLEX_MODE = 2;

export class AddToCartButton {
  constructor(elem) {
    this.$root = $(elem);
    this.$button = this.$root.find(".button");
    this.$addButton = this.$root.find(".add-to-cart-button-add");
    this.$checkoutButton = this.$root.find(".add-to-cart-button-checkout");
    this.$noAccountMessage = this.$root.find(".no-account");
    this.$buttonWrapper = this.$root.find(".add-to-cart-button-wrapper");

    this.mode = SIMPLE_MODE;

    const self = this;

    let productItemResetState = (product) => {
      let input = product.querySelector(".quantity-group input");
      let val = input.min;

      input.value = val;
      product.dataset.quantity = val;

      $(document).trigger("component.quantity.change", {
        target: product,
        val: val,
        product: product,
      });
    };

    this.$addButton.click(function (e) {
      if (self.mode === SIMPLE_MODE) {
        setTimeout(function () {
          self.toggleMode.call(self);
          self.$buttonWrapper.removeClass("hide");
        }, 1000);
      }

      let buttonAnimation = CreateWaitButton(e.target.closest(".wait-button"));
      let product = e.target.closest("[data-product]");

      if (product) {
        let form = null;
        let infoFormId = e.target
          .closest(".cart_add")
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

        for (let oneValue of values) {
          let valueParts = oneValue.value.split("_");
          let identifiersParts = valueParts[0].split("-");
          opt.push({
            optionId: identifiersParts[0],
            variantId: identifiersParts[1],
          });
        }

        let data = [
          {
            id: product.dataset.product,
            quantity: product.dataset.quantity || 1,
            options: opt,
          },
        ];

        buttonAnimation.start();

        cartAdd(data, () => {
          productItemResetState(product);
          $(".jackpot").show();
        });

        window.sendAnalytics.addToCart(product);
      }
    });

    $(document).on("change-category-view", function () {
      self.update.call(self);
    });
  }

  update() {
    if (this.mode === COMPLEX_MODE) {
      const mainButtonComplexClass = this.$root.data("addComplexClass");
      const checkoutButtonComplexClass = this.$root.data(
        "checkoutComplexClass"
      );

      this.$addButton
        .addClass(mainButtonComplexClass)
        .find(".text, .wait-text")
        .remove();
      this.$checkoutButton.addClass(checkoutButtonComplexClass);


      if (!Store.getState().user) {
        this.$noAccountMessage.show();
      }

      this.$buttonWrapper.show();

      if (AddToCartButton.isCategoryPage()) {
        const categoryViewType = Storage.get("cviewt");

        if (categoryViewType === "list-view") {
          this.$buttonWrapper.show();
        } else if (categoryViewType === "tile-view") {
          this.$buttonWrapper.hide();
        }
      }
    }
  }

  static isCategoryPage() {
    return document.location.href.indexOf("/category/") > -1;
  }

  toggleMode() {
    this.mode = COMPLEX_MODE;
    this.update();
  }
}
