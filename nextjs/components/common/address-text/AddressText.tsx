import React from "react";

interface IProps {
  address: {
    street: string;
    detailed?: string;
    city: string;
    state?:
      | {
          label: string;
        }
      | false;
    zip: string;
    country?: {
      label: string;
    };
  };
}

const AddressText: React.FC<IProps> = ({ address }) => {
  console.log(address);
  return (
    <>
      {!!address.street && `${address.street},`} {address.detailed ?? ""}
      <br />
      {!!address.city && `${address.city},`}{" "}
      {!!address.state && address.state.label} {address.zip}
      <br />
      {!!address.country && address.country.label}
    </>
  );
};

export default AddressText;
