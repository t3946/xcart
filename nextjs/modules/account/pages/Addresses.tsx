import React from "react";
import { AddNewAddress } from "../components/addresses/AddNewAddress";
import { AddressList } from "../components/addresses/AddressList";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { useDialog } from "../hooks/useDialog";
import { AddAddressForm } from "@modules/account/components/addresses/AddAddressForm";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
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
          <AddAddressForm
            onCancelClick={() =>
              breakpoint({
                xs: () => history.push("/account/addresses"),
                md: addAddressDialog.handleClose,
              })
            }
          />
        </BootstrapDialogHOC>
      </div>
    </div>
  );
};
