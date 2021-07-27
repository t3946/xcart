import React from "react";
import { useDispatch } from "react-redux";
import {
  changeDefaultAddress,
  removeAddress,
} from "../../../../redux/actions/account-actions/AddressActions";
import { useHistory } from "react-router-dom";

interface AddressItemPropsDto {
  defaultItem?: boolean;
  addressInfo?: any;
  loading: boolean;
}

export const AddressItem: React.FC<AddressItemPropsDto> = ({
  defaultItem = false,
  addressInfo,
  loading,
}) => {
  const dispatch = useDispatch();
  const history = useHistory();

  const changeDefault = () => {
    dispatch(changeDefaultAddress(addressInfo.addresses_id));
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
    <div
      className={`address-loading-container ${
        loading && "address-loading-block-is-loading"
      }`}
    >
      <div className="address-container address-item">
        <div
          className={`address-header ${
            defaultItem && "address-header-default"
          } `}
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
          <div className="address-footer">
            <div className="address-footer-left-part">
              <div onClick={editAddress} className="address-footer-btn">
                Edit
              </div>
              <div className="address-footer-barrier" />
              <div onClick={handleRemoveAddress} className="address-footer-btn">
                Remove
              </div>
            </div>
            {!defaultItem && (
              <div onClick={changeDefault} className="address-footer-btn">
                Set as Default
              </div>
            )}
          </div>
        </div>
      </div>
      <div
        className={`address-loading-block ${
          loading && "address-loading-block-is-loading"
        }`}
      />
    </div>
  );
};
