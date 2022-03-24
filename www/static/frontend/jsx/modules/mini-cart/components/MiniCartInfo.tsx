import React from "react";
import t from "@client/jsx/i18n";
import { useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import classnames from "classnames";

const MiniCartInfo: React.FC = () => {
  const cart = useSelector((e: StoreInterface) => e.cart);

  const buttonRef = React.useRef();

  const classes = {
    button: [
      "cart_info cart-info-button",
      { "cart_info cart-info-button__not-empty": cart.quantity },
    ],
    text: [
      "mini-cart-button-text",
    ],
  };

  return (
    <div className="minicart" ref={buttonRef}>
      <a className={classnames(classes.button)} href={"/cart/"}>
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
