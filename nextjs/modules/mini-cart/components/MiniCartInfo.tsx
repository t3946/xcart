import React from "react";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import cn from "classnames";
import Styles from "@modules/mini-cart/components/MiniCartInfo.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import IconCart from "@modules/icon/components/common/cart/Cart";

const MiniCartInfo: React.FC = () => {
  const routes = useSelectorAccount((e) => e.routes);
  const cart = useSelector((e: StoreInterface) => e.cart);

  const buttonRef = React.useRef<any>();

  const classes = {
    button: [
      "d-flex",
      "align-items-center",
      "justify-content-center",
      "text-decoration-none",
      Styles.button,
    ],
    text: [
      "mini-cart-button-text",
      {
        "mini-cart-button-text__not-empty": cart.quantity,
      },
    ],
    counter: ["position-relative", Styles.counter],
    quantity: ["position-absolute", Styles.counter__quantity],
  };

  return (
    <div className={Styles.miniCart} ref={buttonRef}>
      <a className={cn(classes.button)} href={routes["cart:list"]}>
        <div className={cn(classes.counter)}>
          <span className={cn(classes.quantity)}>{cart.quantity}</span>
          <IconCart className={[Styles.icon, "d-block"]} />
        </div>

        <span className={cn(classes.text)}>Cart</span>
      </a>
    </div>
  );
};

export default MiniCartInfo;
