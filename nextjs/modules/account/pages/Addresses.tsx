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
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { getTerritory } from "@redux/actions/account-actions/MainActions";

import Styles from "@modules/account/pages/Addresses.module.scss";

export const Addresses: React.FC = () => {
  const dispatch = useDispatch();
  const userId = useSelector((e: StoreInterface) => {
    return e.user?.user_id;
  });
  React.useEffect(() => {
    dispatch(getAddresses(userId));
    dispatch(getTerritory());
  }, []);

  const addresses = useSelector((e: StoreInterface) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    );
  });

  const addAddressDialog = useDialog();

  const history = useRouter();

  const breakpoint = useBreakpoint();

  return (
    <div>
      <div className="page-label">Addresses</div>
      <div className={Styles.list}>
        <AddNewAddress
          classes={{ container: Styles.addressBorder }}
          onClick={addAddressDialog.handleClickOpen}
        />
        {addresses && <AddressList addresses={addresses} />}
        <BootstrapDialogHOC
          show={addAddressDialog.open}
          title={"Add address"}
          onClose={addAddressDialog.handleClose}
          classes={{ modal: Styles.modalWidth, body: Styles.modalBody }}
        >
          <AddAddressForm onCancelClick={addAddressDialog.handleClose} />
        </BootstrapDialogHOC>
      </div>
    </div>
  );
};
