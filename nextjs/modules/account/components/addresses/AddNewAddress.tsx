import React from "react";
import { useHistory } from "react-router-dom";
import PlusPanelButton from "@modules/account/components/common/PlusPanelButton";

export const AddNewAddress = ({ onClick }) => {
  return (
    <PlusPanelButton
      onClick={onClick}
      text={"Add new address"}
      classes={{
        container: "add-address address-container address",
        text: "add-address-label",
      }}
    />
  );
};
