import React, { Dispatch } from "react";

interface BillingAddressListItemProps {
  id: number;
  groupValue: number;
  radioValue: number;
  viewValue: string;
  onChange: Dispatch<number>;
  name: string;
}

export const BillingAddressListItem: React.FC<BillingAddressListItemProps> = ({
  id,
  groupValue,
  radioValue,
  viewValue,
  onChange,
  name,
}) => {
  return (
    <div
      className={`d-flex alight-center billing-address-item-container form-radio ${
        groupValue === radioValue && "form-radio-checked"
      }`}
    >
      <input
        value={radioValue}
        onChange={() => onChange(radioValue)}
        name={name}
        id={String(id)}
        type="radio"
        checked={groupValue === radioValue}
      />
      <label htmlFor={String(id)}>{viewValue}</label>
    </div>
  );
};
