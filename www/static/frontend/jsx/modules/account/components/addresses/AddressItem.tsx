import React, { useContext } from "react";
import { useDispatch } from "react-redux";
import {
  changeDefaultAddress,
  removeAddress,
} from "../../../../redux/actions/account-actions/AddressActions";
import { useHistory } from "react-router-dom";
import { AddEditBtnsBlock } from "../shared/AddEditBtnsBlock";
import Store from "@client/jsx/redux/stores/Store";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { AddAddressForm } from "@client/modules/account/components/addresses/AddAddressForm";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { DeleteAddress } from "@client/modules/account/components/addresses/DeleteAddress";

interface AddressItemPropsDto {
  defaultItem?: boolean;
  addressInfo?: any;
}

export const AddressItem: React.FC<AddressItemPropsDto> = ({
  defaultItem = false,
  addressInfo,
}) => {
  const dispatch = useDispatch();
  const history = useHistory();

  const breakpoint = useBreakpoint();

  const { showSnackbar } = useContext(SnackbarContext);

  const editAddressDialog = useDialog();

  const deleteAddressDialog = useDialog();

  const onPended = (message: string) => {
    showSnackbar({
      header: "Success",
      message: message,
      theme: "success",
    });
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
        onPended("The address has been successfully delete");
      })
    );
  };

  const editAddress = () => {
    breakpoint({
      xs: () =>
        history.push({
          pathname: "/account/addresses/edit",
          state: { addressInfo: addressInfo },
        }),
      md: editAddressDialog.handleClickOpen,
    });
  };

  return (
    <div className="address-container address-border address-item">
      <div
        className={`address-header ${defaultItem && "address-header-default"} `}
      >
        {defaultItem && "Default:"}
      </div>

      <div className="address-content">
        <div
          className={`address-name ${defaultItem && "address-name-default"}`}
        >
          {addressInfo.full_name}
        </div>
        <div className="address-text address-text-address">
          {addressInfo.street}, {addressInfo.detailed}
        </div>
        <div className="address-text">{addressInfo.country.viewValue}</div>
        <div className="address-phone-wrapper">
          <div className="address-text">Phone number:</div>
          <div className="address-text">{addressInfo.phone_number}</div>
        </div>
        <AddEditBtnsBlock
          handleEdit={editAddress}
          defaultItem={defaultItem}
          changeDefault={changeDefault}
          handleRemove={deleteAddressDialog.handleClickOpen}
        />
      </div>
      <BootstrapDialogHOC
        show={editAddressDialog.open}
        title={"Edit address"}
        onClose={editAddressDialog.handleClose}
      >
        <AddAddressForm
          addressInfo={addressInfo}
          onCancelClick={() =>
            breakpoint({
              xs: () => history.push("/account/addresses"),
              md: editAddressDialog.handleClose,
            })
          }
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
