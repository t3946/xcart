import React from "react";

interface AddressItemPropsDto {
  defaultItem?: boolean;
  addressInfo?: any;
}

export const AddressItem: React.FC<AddressItemPropsDto> = ({
  defaultItem = false,
  addressInfo,
}) => {
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
          Sergei Vorozhtsov
        </div>
        <div className="address-text address-text-address">
          1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972-9725
        </div>
        <div className="address-text">United States</div>
        <div className="address-phone-wrapper">
          <div className="address-text">Phone number:</div>
          <div className="address-text">(763) 635-4364</div>
        </div>
        <div className="address-footer">
          <div className="address-footer-left-part">
            <div className="address-footer-btn">Edit</div>
            <div className="address-footer-barrier" />
            <div className="address-footer-btn">Remove</div>
          </div>
          {!defaultItem && (
            <div className="address-footer-btn"> Set as Default</div>
          )}
        </div>
      </div>
    </div>
  );
};
