import React from "react";

interface IProps {
  address: any;
}

const AddressText: React.FC<IProps> = ({ address }) => {
  const lines: any = [];
  let line: any = [];

  address.street && line.push(address.street);
  address.detailed && line.push(address.detailed);
  line.length > 0 && lines.push(line.join(", ").toUpperCase());

  line = [];
  address.city && line.push(address.city);
  address.state && line.push(address.state.state);
  address.zip && line.push(address.zip);
  line.length > 0 && lines.push(line.join(", ").toUpperCase());

  line = [];
  address.country && line.push(address.country.name);
  line.length > 0 && lines.push(line.join(", "));

  const items = [];

  for (let i = 0; i < lines.length; i++) {
    if (i > 0) {
      items.push(<br key={`text-br-${i}`} />);
    }

    items.push(<span key={`text-${i}`}>{lines[i]}</span>);
  }

  return items;
};

export default AddressText;
