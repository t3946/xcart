import React from "react";
import { AddressItem } from "./AddressItem";
import { useSelector } from "react-redux";

export const AddressList = () => {
  const addresses = useSelector((e: any) => e.addresses);

  const loading = useSelector((e: any) => e.loading);
  return (
    <React.Fragment>
      {addresses.map((e) => {
        return (
          <AddressItem
            loading={loading}
            addressInfo={e}
            defaultItem={e.is_default}
          />
        );
      })}
    </React.Fragment>
  );
};
