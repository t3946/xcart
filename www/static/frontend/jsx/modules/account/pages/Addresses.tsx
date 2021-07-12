import React from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";

export const Addresses = () => {
  return (
    <div className="addresses-list-container">
      <AddNewAddress />
      <AddressList />
    </div>
  );
};
