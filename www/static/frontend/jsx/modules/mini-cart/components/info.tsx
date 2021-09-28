import React, { useEffect } from "react";
import t from "../../../i18n";
import MiniCartInfoDto from "@modules/mini-cart/ts/types/MiniCartInfoDto";
import { convertCartNumber } from "../../../utils/convertCartNumber";

export const Info: React.FC<MiniCartInfoDto> = (props: MiniCartInfoDto) => {
  return (
    <a className="cart_info" href={props.url}>
      <div className="header-info-cart">
        <span className="logo-image">
          <span className="left-logo-cart" />
          <span className="center-logo-cart">
            <span className="count-text-cart" id="desktop-cart-quantity">
              {convertCartNumber(Number(props.quantity))}
            </span>
          </span>
          <span className="right-logo-cart" />
        </span>
        <span className="title-cart-header">
          <span className="text">{t("Cart")}</span>
        </span>
      </div>
    </a>
  );
};
