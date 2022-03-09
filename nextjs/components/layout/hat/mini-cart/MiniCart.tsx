import React from "react";
import { useSelector, useDispatch } from "react-redux";
import MiniCartItems from "@components/layout/hat/mini-cart/MiniCartItems";
import MiniCartInfo from "@components/layout/hat/mini-cart/MiniCartInfo";
import StoreInterface from "@modules/account/ts/types/store.type";
import HoverIntent from "react-hoverintent"; // из-за этого модуля пришлось установить ещё один -- babel-runtime
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import hideAllMenu from "@modules/account/utils/hide-all-menu";
import cn from "classnames";
import Styles from "@components/layout/hat/mini-cart/MiniCart.module.scss";

const MiniCart: React.FC = () => {
  const cart = useSelector((e: StoreInterface) => e.cart);
  const user = useSelector((e: StoreInterface) => e.user);
  const [isEnter, setIsEnter] = React.useState(false);
  const dispatch = useDispatch();

  function showMiniCart() {
    if (cart.quantity === 0) {
      return;
    }

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
        <TransitionFade show={isEnter && cart.quantity > 0}>
          <MiniCartItems checkoutUrl={cart.checkoutUrl} />
        </TransitionFade>

        <MiniCartInfo />
      </div>
    </HoverIntent>
  );
};

export default MiniCart;
