import React from "react";
import { BillingAddressList } from "./BillingAddressList";

export const BillingAddress = () => {
  return (
    <div>
      <div className="dialog-title">Select a billing address</div>
      <BillingAddressList />
    </div>
  );
};
