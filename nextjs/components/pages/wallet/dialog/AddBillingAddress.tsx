import React from "react";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import AddBillingAddressForm from "@modules/account/components/wallet/AddBillingAddressForm";

import StylesAddresses from "@modules/account/pages/Addresses.module.scss";

interface IProps {
  handleClose: () => void;
  open: boolean;
  contentType: BillingAddressFormEnum;
  actionType: BillingAddressFormEnum;
  cardInfo?: CardItemDto;
  address: any;
}

const AddBillingAddress: React.FC<IProps> = (props) => {
  const { handleClose, open } = props;

  return (
    <BootstrapDialogHOC
      onClose={handleClose}
      show={open}
      title={`Add billing address`}
      classes={{
        modal: StylesAddresses.modalWidth,
        body: StylesAddresses.modalBody,
      }}
    >
      <AddBillingAddressForm />
    </BootstrapDialogHOC>
  );
};

export default AddBillingAddress;
