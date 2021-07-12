import React from "react";
import { AddressItem } from "./AddressItem";

export const AddressList = () => {
  const items = [true, false, false];
  return (
    <React.Fragment>
      {items.map((e) => {
        return <AddressItem defaultItem={e} />;
      })}
    </React.Fragment>
  );
};
