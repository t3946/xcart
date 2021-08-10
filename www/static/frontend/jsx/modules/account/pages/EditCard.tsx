import React from "react";
import { CardAction } from "../../account/components/wallet/CardAction";
import { useLocation } from "react-router-dom";
import { BillingAddressFormEnum } from "../ts/consts/billing-address-form-types";

export const EditCard = () => {
  const location = useLocation<any>();
  return (
    <CardAction
      contentType={BillingAddressFormEnum.EDIT}
      actionType={BillingAddressFormEnum.EDIT}
      cardInfo={location.state.cardInfo}
    />
  );
};
