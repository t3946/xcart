import React from "react";
import { AddressItem } from "./AddressItem";
import { useSelector } from "react-redux";
import { LoadingContainer } from "../shared/LoadingContainer";

export const AddressList = ({ addresses }: Record<any, any>): any => {
  const loading = useSelector((e: any) => e.addresses.loading);
  return (
    <React.Fragment>
      {addresses.map((address) => {
        return (
          <LoadingContainer key={address.address_id} loading={loading}>
            <AddressItem
              addressInfo={address}
              defaultItem={address.is_default}
            />
          </LoadingContainer>
        );
      })}
    </React.Fragment>
  );
};
