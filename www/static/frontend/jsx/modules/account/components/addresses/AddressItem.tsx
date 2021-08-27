import React from "react";
import { useDispatch } from "react-redux";
import {
  changeDefaultAddress,
  removeAddress,
} from "../../../../redux/actions/account-actions/AddressActions";
import { useHistory } from "react-router-dom";
import { AddEditBtnsBlock } from "../shared/AddEditBtnsBlock";
import { accountStore } from "../../../../redux/stores/StoreAccount";

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

  const changeDefault = () => {
    dispatch(
      changeDefaultAddress(
        addressInfo.addresses_id,
        accountStore.getState().user.id
      )
    );
  };

  const handleRemoveAddress = () => {
    dispatch(removeAddress(addressInfo.addresses_id));
  };

  const editAddress = () => {
    history.push({
      pathname: "/account/addresses/add",
      state: { addressInfo: addressInfo },
    });
  };

  return (
    <div className="address-container address-item">
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
          handleRemove={handleRemoveAddress}
        />
      </div>
    </div>
  );
};
