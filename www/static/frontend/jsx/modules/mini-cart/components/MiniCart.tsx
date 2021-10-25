import React from "react";
import { Provider, useSelector, useDispatch } from "react-redux";
import MiniCartItems from "@client/jsx/components/MiniCartItems";
import MiniCartInfo from "@client/modules/mini-cart/components/MiniCartInfo";
import storeCart from "@client/jsx/redux/stores/StoreCart";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import HoverIntent from "react-hoverintent";
import { setCartQuantityAction } from "@client/jsx/redux/actions/CartActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import TransitionFade from "@client/modules/account/components/shared/TransitionFade";
import hideAllMenu from "@client/jsx/modules/account/utils/hide-all-menu";

const MiniCart: React.FC = () => {
  const cart = useSelector((e: StoreInterface) => e.cart);
  const [isEnter, setIsEnter] = React.useState(false);
  const dispatch = useDispatch();

  function cartCountChanged(e) {
    dispatch(setCartQuantityAction(e.detail.quantity));
  }

  document.addEventListener("cartCountChanged", cartCountChanged);

  React.useEffect(function () {
    return () => {
      document.removeEventListener("cartCountChanged", cartCountChanged);
    };
  });

  function showMiniCart() {
    hideAllMenu(dispatch);
    setIsEnter(true);
    dispatch(setVisibleShadowPanelAction(true));
  }

  function hideMiniCart() {
    setIsEnter(false);
    dispatch(setVisibleShadowPanelAction(false));
  }

  return (
    <HoverIntent
      onMouseOver={showMiniCart}
      onMouseOut={hideMiniCart}
      sensitivity={10}
      interval={250}
      timeout={250}
    >
      <div className={"mini-cart-container"}>
        <Provider store={storeCart}>
          <TransitionFade show={isEnter && cart.quantity > 0}>
            <MiniCartItems store={storeCart} checkoutUrl={cart.checkoutUrl} />
          </TransitionFade>
        </Provider>

        <MiniCartInfo />
      </div>
    </HoverIntent>
  );
};

export default MiniCart;
