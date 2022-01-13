import React from "react";
import { Provider, useSelector, useDispatch } from "react-redux";
import MiniCartItems from "@modules/old-components/MiniCartItems";
import MiniCartInfo from "@modules/mini-cart/components/MiniCartInfo";
import storeCart from "@redux/stores/StoreCart";
import StoreInterface from "@modules/account/ts/types/store.type";
import HoverIntent from "react-hoverintent"; // из-за этого модуля пришлось установить ещё один -- babel-runtime
import { setCartQuantityAction } from "@redux/actions/CartActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import hideAllMenu from "@modules/account/utils/hide-all-menu";
import cn from "classnames";
import Styles from "@modules/mini-cart/components/MiniCart.module.scss";

const MiniCart: React.FC = () => {
  const cart = useSelector((e: StoreInterface) => e.cart);
  const user = useSelector((e: StoreInterface) => e.user);
  const [isEnter, setIsEnter] = React.useState(false);
  const dispatch = useDispatch();

  function cartCountChanged(e) {
    dispatch(setCartQuantityAction(e.detail.quantity));
  }

  React.useEffect(function () {
    document.addEventListener("cartCountChanged", cartCountChanged);

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
      <div
        className={cn(Styles.container, {
          [Styles.container_user_logined]: user,
        })}
      >
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
