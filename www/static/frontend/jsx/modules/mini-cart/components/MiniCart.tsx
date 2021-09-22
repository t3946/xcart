import React from "react";
import { Provider, useSelector, useDispatch } from "react-redux";
import MiniCartItems from "@client/jsx/components/MiniCartItems";
import MiniCartInfo from "@client/modules/mini-cart/components/MiniCartInfo";
import storeCart from "@client/jsx/redux/stores/StoreCart";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import HoverIntent from "react-hoverintent";
import { setCartQuantityAction } from "@client/jsx/redux/actions/CartActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";

const MiniCart: React.FC = () => {
  const cart = useSelector((e: AccountStore) => e.cart);
  const [isEnter, setIsEnter] = React.useState(false);
  const dispatch = useDispatch();
  const classes = {
    miniCartItems: {
      items: { "d-none": isEnter === false || cart.quantity === 0 },
    },
  };

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
          <MiniCartItems
            store={storeCart}
            checkoutUrl={cart.checkoutUrl}
            classes={classes.miniCartItems}
          />
        </Provider>

        <MiniCartInfo />
      </div>
    </HoverIntent>
  );
};

export default MiniCart;
