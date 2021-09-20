import React from "react";
import t from "../../../i18n";
import MiniCartInfoDto from "@modules/mini-cart/ts/types/MiniCartInfoDto";

const Info: React.FC<MiniCartInfoDto> = (props: MiniCartInfoDto) => {
  return (
    <a className="cart_info cart-info-button" href={props.url}>
      <span className="count">
        <span id="desktop-cart-quantity" className="mc_count">
          {props.quantity * 1}
        </span>
      </span>
      <span className="text">{t("Cart")}</span>
    </a>
  );
};

export default Info;
