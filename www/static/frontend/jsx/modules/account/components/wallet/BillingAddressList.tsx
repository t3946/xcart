import React, { useState } from "react";
import { BillingAddressListItem } from "./BillingAddressListItem";

export const BillingAddressList = () => {
  const mass = [1, 2, 3, 4, 5, 6];

  const value = useState(1);
  return (
    <div>
      {mass.map((e) => {
        return (
          <BillingAddressListItem
            name="radio"
            id={e}
            viewValue={e}
            value={value}
          />
        );
      })}
    </div>
  );
};
