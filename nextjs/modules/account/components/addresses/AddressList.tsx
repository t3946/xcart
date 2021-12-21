import React from "react";
import { AddressItem } from "./AddressItem";
import { useSelector } from "react-redux";
import { LoadingContainer } from "../shared/LoadingContainer";

export const AddressList = ({ addresses }: Record<any, any>): any => {
  const loading = useSelector((e: any) => e.addresses.loading);
  return (
    <React.Fragment>
      {addresses.map((e) => {
        return (
          <LoadingContainer key={e.address_id} classContainer={"address"} loading={loading}>
            <AddressItem addressInfo={e} defaultItem={e.is_default} />
          </LoadingContainer>
        );
      })}
    </React.Fragment>
  );
};
