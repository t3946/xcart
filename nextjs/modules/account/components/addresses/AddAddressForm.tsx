import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/AddAddressForm.module.scss";
import InputGroup from "./InputGroup";
import {useSnackbar} from "@modules/account/hooks/useSnackbar";
import Address from "@components/common/forms/Address";

export const AddAddressForm: React.FC<any> = ({
  addressInfo = undefined,
  onCancelClick = undefined,
}) => {
  const snackbar = useSnackbar();

  function onSubmitted() {
    onCancelClick();
    snackbar.show(`${!addressInfo ? "Address added!" : "Address edit!"}`);
  }

  function footerTemplate(isSubmitting: boolean) {
    return (
      <InputGroup
        classNames={{ container: "m-0" }}
        component={
          <button
            disabled={isSubmitting}
            type={"submit"}
            className={cn(Styles.button, "form-button", "w-md-auto")}
          >
            {addressInfo ? "Save changes" : "Add Address"}
          </button>
        }
      />
    );
  }

  return (
    <Address
      footerTemplate={footerTemplate}
      onSubmitted={onSubmitted}
      addressType={"shipping"}
      canBeDefault={true}
      editMode={!!addressInfo}
      address={addressInfo}
    />
  );
};
