import React from "react";
import { useHistory } from "react-router-dom";

export const AddNewAddress = () => {
  const history = useHistory();

  return (
    <div
      onClick={() => history.push("/account/addresses/add")}
      className="add-address address-container"
    >
      <div>
        <img src="/static/frontend/images/icons/account/plus.svg" />
      </div>
      <div className="add-address-label">Add new address</div>
    </div>
  );
};
