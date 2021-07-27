import React from "react";
import { AddAddressForm } from "../components/addresses/AddAddressForm";
import { useLocation } from "react-router-dom";

export const AddAddressPage = () => {
  const location = useLocation<any>();
  const addressInfo = location.state?.addressInfo;

  return (
    <div className="add-address-page">
      <div className="page-label">{addressInfo ? "Edit" : "Add"} Address</div>
      <AddAddressForm addressInfo={addressInfo} />
    </div>
  );
};
