import React from "react";
import { DeleteAddress } from "@modules/account/components/addresses/DeleteAddress";
import { useHistory, useLocation } from "react-router-dom";

export const DeleteAddressPage: React.FC = () => {
  const history = useHistory();

  const location = useLocation<LocationState>();

  return (
    <div>
      <DeleteAddress
        onCancelClick={() => history.push("account/addresses")}
        onDeleteClick={}
      />
    </div>
  );
};
