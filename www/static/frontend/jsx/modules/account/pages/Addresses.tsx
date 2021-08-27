import React from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";
import { useSelector } from "react-redux";
import { AccountStore } from "../ts/types/account-store.type";
import { AddressTypeEnum } from "@client/modules/account/ts/consts/address-type.const";

export const Addresses: React.FC = () => {
  const addresses = useSelector((e: AccountStore) => {
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
