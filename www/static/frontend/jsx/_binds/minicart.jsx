import { Fragment, render } from "preact";
import { Provider } from "react-redux";
import MiniCartItems from "../components/MiniCart";
import storeCart from "../stores/StoreCart";
import { Info } from "../modules/mini-cart/components/info";

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

      <Info
        quantity={miniCart.dataset.quantity}
        url={miniCart.dataset.cartUrl}
      />
    </Fragment>,
    miniCart
  );
}
