import React from "react";
import {AddNewAddress} from "../components/addresses/AddNewAddress";
import {AddressList} from "../components/addresses/AddressList";
import {useDispatch, useSelector} from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import {AddressTypeEnum} from "@modules/account/ts/consts/address-type.const";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import {useDialog} from "@modules/account/hooks/useDialog";
import {AddAddressForm} from "@modules/account/components/addresses/AddAddressForm";
import {getAddresses} from "@redux/actions/account-actions/AddressActions";
import {getTerritory} from "@redux/actions/account-actions/MainActions";
import Styles from "@modules/account/pages/Addresses.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Link from "next/link";

export const Addresses: React.FC = () => {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
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

  function addNewAddress() {
    if (user) {
      return (
        <AddNewAddress
          classes={{ container: Styles.addressBorder }}
          onClick={addAddressDialog.handleClickOpen}
        />
      );
    }

    return (
      <Link href={"/login"}>
        <a className={"text-decoration-none"}>
          <AddNewAddress classes={{ container: Styles.addressBorder }} />
        </a>
      </Link>
    );
  }

  return (
    <div>
      <div className="page-label">Addresses</div>
      <div className={Styles.list}>
        {addNewAddress()}

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
