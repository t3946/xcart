import React from "react";
import PlusPanelButton from "@modules/account/components/common/PlusPanelButton";

import AddressItemStyles from "@modules/account/components/addresses/AddressItem.module.scss";

interface IProps {
  onClick: any;
  classes?: {
    container?: any;
    text?: any;
  };
}

export const AddNewAddress: React.FC<IProps> = ({ onClick, classes }) => {
  return (
    <PlusPanelButton
      onClick={onClick}
      text={"Add new address"}
      classes={{
        container: [
          "add-address address-container",
          AddressItemStyles.address,
          classes?.container,
        ],
        text: ["add-address-label", classes?.text],
      }}
    />
  );
};
