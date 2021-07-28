import React, { useState } from "react";
import { BillingAddressListItem } from "./BillingAddressListItem";

export const BillingAddressList = () => {
  const mass = Array(15)
    .fill(1)
    .map((e, index) => {
      return {
        id: index,
        value: "Azina 44, Kirov, Kirov region, 610027, Russian Federation",
      };
    });

  const [value, setValue] = useState(mass[0]);
  return (
    <div className="billing-address-list-container">
      {mass.map((e) => {
        return (
          <BillingAddressListItem
            name="radio"
            id={e.id}
            viewValue={e.value}
            groupValue={value}
            radioValue={e.id}
            onChange={setValue}
          />
        );
      })}
    </div>
  );
};
