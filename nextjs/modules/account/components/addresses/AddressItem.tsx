import React from "react";
import {useSnackbar} from "@modules/account/hooks/useSnackbar";
import {useDispatch} from "react-redux";
import {changeDefaultAddress, removeAddress,} from "@redux/actions/account-actions/AddressActions";
import {AddEditBtnsBlock} from "../shared/AddEditBtnsBlock";
import Store from "@redux/stores/Store";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import {AddAddressForm} from "@modules/account/components/addresses/AddAddressForm";
import {useDialog} from "@modules/account/hooks/useDialog";
import {DeleteAddress} from "@modules/account/components/addresses/DeleteAddress";
import cn from "classnames";
import {formatPhone} from "@utils/phoneNumber";
import AddressText from "@components/common/address-text/AddressText";

import StylesAddresses from "@modules/account/pages/Addresses.module.scss";
import Styles from "@modules/account/components/addresses/AddressItem.module.scss";

interface AddressItemPropsDto {
  defaultItem?: boolean;
  addressInfo?: any;
}

export const AddressItem: React.FC<AddressItemPropsDto> = ({
  defaultItem = false,
  addressInfo,
}) => {
  const dispatch = useDispatch();
  const snackbar = useSnackbar();
  const editAddressDialog = useDialog();
  const deleteAddressDialog = useDialog();
  const onPended = (message: string) => {
    snackbar.show(message);
  };

  const changeDefault = () => {
    dispatch(
      changeDefaultAddress(
        addressInfo.address_id,
        Store.getState().user.id,
        () => {
          onPended("The default address has been successfully changed");
        }
      )
    );
  };

  const handleRemoveAddress = () => {
    dispatch(
      removeAddress(addressInfo.address_id, () => {
        editAddressDialog.handleClose();
        onPended("Address successfully deleted");
      })
    );
  };

  const editAddress = () => {
    editAddressDialog.handleClickOpen();
  };

  return (
    <div
      className={cn(
        Styles.address,
        "d-flex",
        "flex-dir-column",
        "address-container address-border address-item"
      )}
    >
      <div
        className={`address-header ${
          defaultItem ? "address-header-default" : "d-none"
        } `}
      >
        {defaultItem && "Default"}
      </div>

      <div
        className={cn(
          Styles.addressContent,
          "d-flex",
          "flex-dir-column",
          "address-content",
          { [Styles.addressContent_default]: defaultItem }
        )}
      >
        <div
          className={`address-name ${defaultItem && "address-name-default"}`}
        >
          {addressInfo.full_name}
        </div>
        <div className="address-text">
          <AddressText address={addressInfo} />
        </div>
        <div>
          <span className="address-text">Phone number:</span>
          <span className="address-text">
            {" "}
            {formatPhone(addressInfo.phone_number)}
          </span>
        </div>
        <AddEditBtnsBlock
          handleEdit={editAddress}
          defaultItem={defaultItem}
          changeDefault={changeDefault}
          handleRemove={deleteAddressDialog.handleClickOpen}
        />
      </div>
      <BootstrapDialogHOC
        classes={{
          modal: StylesAddresses.modalWidth,
          body: StylesAddresses.modalBody,
        }}
        show={editAddressDialog.open}
        title={"Edit address"}
        onClose={editAddressDialog.handleClose}
      >
        <AddAddressForm
          addressInfo={addressInfo}
          onCancelClick={editAddressDialog.handleClose}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteAddressDialog.open}
        title={"Delete address"}
        onClose={deleteAddressDialog.handleClose}
      >
        <DeleteAddress
          onDeleteClick={handleRemoveAddress}
          onCancelClick={deleteAddressDialog.handleClose}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
