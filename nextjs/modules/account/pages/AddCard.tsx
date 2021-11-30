import React from "react";
import { CardAction } from "../components/wallet/CardAction";
import { BillingAddressFormEnum } from "../ts/consts/billing-address-form-types";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

export const AddCard: React.FC = () => {
  return (
    <React.Fragment>
      <MobileMenuBackBtn
        redirectUrl={`/account/payments/wallet/`}
        label={"back"}
      />
      <CardAction
        contentType={BillingAddressFormEnum.ADD_CARD}
        actionType={BillingAddressFormEnum.ADD_CARD}
      />
    </React.Fragment>
  );
};
