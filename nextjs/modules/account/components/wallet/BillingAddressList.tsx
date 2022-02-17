import React, { Dispatch } from "react";
import cn from "classnames";
import { BillingAddressListItem } from "./BillingAddressListItem";
import { AddressItemDto } from "../../ts/types/address-item.type";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import RadioButton from "@modules/ui/RadioButton";

interface BillingAddressListProps {
  addresses: AddressItemDto[];
  value: number | null;
  onChange: Dispatch<number>;
  disabled: boolean;
  classes?: {
    container?: any;
  };
}

export const BillingAddressList: React.FC<BillingAddressListProps> = ({
  addresses,
  value,
  onChange,
  disabled,
  classes,
}) => {
  return (
    <div className={cn(classes?.container, "billing-address-list-container")}>
      {addresses.length ? (
        addresses.map((e: AddressItemDto) => {
          return (
            <label
              key={e.address_id}
              className={cn("d-flex", "align-items-center", "mb-10")}
            >
              <RadioButton
                classes={"me-10"}
                name="address"
                onChange={onChange}
                value={e.address_id?.toString()}
                checkedValue={value}
                disabled={disabled}
              />
              <span>{e.street}</span>
            </label>
          );
        })
      ) : (
        <div>You have not added any addresses yet</div>
      )}
    </div>
  );
};
