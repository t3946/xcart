import React from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";
import { useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { AddressTypeEnum } from "@client/modules/account/ts/consts/address-type.const";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "../hooks/useDialog";
import { AddAddressForm } from "@client/modules/account/components/addresses/AddAddressForm";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { useHistory } from "react-router-dom";

export const Addresses: React.FC = () => {
  const addresses = useSelector((e: StoreInterface) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    );
  });

  const addAddressDialog = useDialog();

  const history = useHistory();

  const breakpoint = useBreakpoint();

  return (
    <div>
      <div className="page-label">Addresses</div>
      <div className="addresses-list-container">
        <AddNewAddress
          onClick={() =>
            breakpoint({
              xs: () => history.push("/account/addresses"),
              md: addAddressDialog.handleClickOpen,
            })
          }
        />
        {addresses && <AddressList addresses={addresses} />}
        <BootstrapDialogHOC
          show={addAddressDialog.open}
          title={"Add address"}
          onClose={addAddressDialog.handleClose}
        >
          <AddAddressForm onCancelClick={addAddressDialog.handleClose} />
        </BootstrapDialogHOC>
      </div>
    </div>
  );
};
