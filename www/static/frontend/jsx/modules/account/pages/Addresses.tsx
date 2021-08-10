import React from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";
import { useSelector } from "react-redux";
import { AddressTypeEnum } from "../ts/types/address-item.type";

export const Addresses = (): any => {
  const addresses = useSelector((e: any) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    );
  });

  return (
    <div>
      <div className="page-label">Addresses</div>
      <div className="addresses-list-container">
        <AddNewAddress />
        {addresses && <AddressList addresses={addresses} />}
      </div>
    </div>
  );
};
