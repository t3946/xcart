import React from "react";
import { useHistory } from "react-router-dom";
import PlusPanelButton from "@client/jsx/modules/account/components/common/PlusPanelButton";

export const AddNewAddress = () => {
  const history = useHistory();

  return (
    <PlusPanelButton
      onClick={() => history.push("/account/addresses/add")}
      text={"Add new address"}
      classes={{
        container: "add-address address-container address",
        text: "add-address-label",
      }}
    />
  );
};
