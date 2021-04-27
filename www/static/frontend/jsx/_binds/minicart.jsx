import { Fragment, render } from "preact";
import { Provider } from "preact-redux";
import MiniCartItems from "../components/MiniCart";
import MiniCartInfo from "@/modules/mini-cart/components/info";
import storeCart from "../stores/StoreCart";

let miniCart = document.querySelector(".mini-cart-container");

if (miniCart) {
  render(
    <Fragment>
      <Provider store={storeCart}>
        <MiniCartItems
          store={storeCart}
          labels={miniCart.dataset}
          checkoutUrl={miniCart.dataset.checkoutUrl}
        />
      </Provider>

      <MiniCartInfo
        quantity={miniCart.dataset.quantity}
        url={miniCart.dataset.cartUrl}
      />
    </Fragment>,
    miniCart
  );
}
