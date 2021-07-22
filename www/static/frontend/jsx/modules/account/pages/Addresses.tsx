import React, { useEffect } from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";
import { useDispatch } from "react-redux";
import { getAddresses } from "../../../redux/actions/account-actions/AddressActions";

export const Addresses = () => {
  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(getAddresses());
  }, []);
  return (
    <div>
      <div className="page-label">Addresses</div>
      <div className="addresses-list-container">
        <AddNewAddress />
        <AddressList />
      </div>
    </div>
  );
};
