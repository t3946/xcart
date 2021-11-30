import React, { Dispatch } from "react";
import { BillingAddressListItem } from "./BillingAddressListItem";
import { AddressItemDto } from "../../ts/types/address-item.type";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";

interface BillingAddressListProps {
  addresses: AddressItemDto[];
  value: number;
  setValue: Dispatch<number>;
}

export const BillingAddressList: React.FC<BillingAddressListProps> = ({
  addresses,
  value,
  setValue,
}) => {
  return (
    <div className="billing-address-list-container">
      {addresses.length ? (
        addresses.map((e: AddressItemDto) => {
          return (
            <RadioBtn
              name="radio"
              id={e.address_id}
              viewValue={e.street}
              groupValue={value}
              radioValue={e.address_id}
              onChange={setValue}
              groupClasses={{
                group: "billing-address-item-container",
                checked: "form-radio-checked",
              }}
            />
          );
        })
      ) : (
        <div>You have not added any addresses yet</div>
      )}
    </div>
  );
};
