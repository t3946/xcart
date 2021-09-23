import React from "react";
import t from "@client/jsx/i18n";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import classnames from "classnames";
import { route } from "@client/jsx/utils/AppData";

const MiniCartInfo: React.FC = () => {
  const cart = useSelector((e: AccountStore) => e.cart);

  const buttonRef = React.useRef();

  const classes = {
    button: [
      "cart_info cart-info-button",
      { "cart_info cart-info-button__not-empty": cart.quantity > 0 },
    ],
    text: [
      "mini-cart-button-text",
      {
        "mini-cart-button-text__not-empty": cart.quantity,
      },
    ],
  };

  return (
    <div className="minicart" ref={buttonRef}>
      <a className={classnames(classes.button)} href={route("cart:list")}>
        <span className="count">
          <span id="desktop-cart-quantity" className="mc_count">
            {cart.quantity}
          </span>
        </span>

        <span className={classnames(classes.text)}>{t("Cart")}</span>
      </a>
    </div>
  );
};

export default MiniCartInfo;
