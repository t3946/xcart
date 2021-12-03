import React from "react";
import t from "@utils/i18n";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import classnames from "classnames";
import { route } from "@utils/AppData";
import Styles from "@modules/mini-cart/components/MiniCartInfo.module.scss";

const MiniCartInfo: React.FC = () => {
  const cart = useSelector((e: StoreInterface) => e.cart);

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
    <div className={Styles.miniCart} ref={buttonRef}>
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
