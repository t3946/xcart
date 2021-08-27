import React from "react";
import { CardAction } from "../../account/components/wallet/CardAction";
import { useLocation } from "react-router-dom";
import { BillingAddressFormEnum } from "../ts/consts/billing-address-form-types";
import { CardItemDto } from "../ts/types/wallet.type";

interface LocationCardState {
  cardInfo: CardItemDto;
}

export const EditCard: React.FC = () => {
  const location = useLocation<LocationCardState>();
  return (
    <CardAction
      contentType={BillingAddressFormEnum.EDIT}
      actionType={BillingAddressFormEnum.EDIT}
      cardInfo={location.state.cardInfo}
    />
  );
};
