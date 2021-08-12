import React from "react";
import { CardAction } from "../components/wallet/CardAction";
import { BillingAddressFormEnum } from "../ts/consts/billing-address-form-types";

export const AddCard: React.FC = () => {
  return (
    <CardAction
      contentType={BillingAddressFormEnum.ADD_CARD}
      actionType={BillingAddressFormEnum.ADD_CARD}
    />
  );
};
