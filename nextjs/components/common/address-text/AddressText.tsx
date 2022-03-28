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
  const lines: any = [];
  let row: any = [];

  address.street && row.push(address.street);
  address.detailed && row.push(address.detailed);
  lines.push(row);

  row = [];
  address.city && row.push(address.city);
  address.state && row.push(address.state.state);
  row.push(address.zip);
  lines.push(row);

  row = [];
  address.country && row.push(address.country.label);
  lines.push(row);

  return (
    <>
      {lines[0].join(", ")}
      <br />
      {lines[1].join(", ")}
      <br />
      {lines[2].join(", ")}
    </>
  );
};

export default AddressText;
