import React from "react";
import { AddAddressForm } from "../components/addresses/AddAddressForm";
import { useLocation } from "react-router-dom";
import { AddressItemDto } from "../ts/types/address-item.type";

interface LocationState {
  addressInfo: AddressItemDto;
}

export const AddAddressPage: React.FC = () => {
  const location = useLocation<LocationState>();

  const addressInfo = location?.state?.addressInfo;

  return (
    <div className="add-address-page">
      <div className="page-label">{addressInfo ? "Edit" : "Add"} Address</div>
      <div className="add-address-form-container">
        <AddAddressForm addressInfo={addressInfo} />
      </div>
    </div>
  );
};
