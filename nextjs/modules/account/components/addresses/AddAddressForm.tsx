import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/AddAddressForm.module.scss";
import InputGroup from "./InputGroup";
import {useSnackbar} from "@modules/account/hooks/useSnackbar";
import Address from "@components/common/forms/Address";

interface IProps {
  canBeDefault?: boolean;
  addressInfo?: any;
  onCancelClick?: any;
}

export const AddAddressForm: React.FC<IProps> = (props) => {
  const { canBeDefault = true, addressInfo, onCancelClick } = props;
  const snackbar = useSnackbar();

  function onSubmitted() {
    onCancelClick();
    snackbar.show(
      `${
        !addressInfo
          ? "New address successfully added"
          : "Address successfully updated"
      }`
    );
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
      canBeDefault={canBeDefault}
      editMode={!!addressInfo}
      address={addressInfo}
    />
  );
};
