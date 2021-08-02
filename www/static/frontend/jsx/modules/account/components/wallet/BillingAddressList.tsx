import React, { useState } from "react";
import { BillingAddressListItem } from "./BillingAddressListItem";

export const BillingAddressList = ({ addresses, value, setValue }) => {
  return (
    <div className="billing-address-list-container">
      {addresses.map((e) => {
        return (
          <BillingAddressListItem
            name="radio"
            id={e.addresses_id}
            viewValue={e.street}
            groupValue={value}
            radioValue={e.addresses_id}
            onChange={setValue}
          />
        );
      })}
    </div>
  );
};
