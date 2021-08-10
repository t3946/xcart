import React from "react";
import { CardAction } from "../../account/components/wallet/CardAction";
import { BillingAddressFormEnum } from "../ts/consts/billing-address-form-types";

export const AddCard = () => {
  return (
    <CardAction
      contentType={BillingAddressFormEnum.ADD_CARD}
      actionType={BillingAddressFormEnum.ADD_CARD}
    />
  );
};
