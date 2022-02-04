import React from "react";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import AddBillingAddressForm from "@modules/account/components/wallet/AddBillingAddressForm";

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
      title={`Edit payment method`}
      classes={{ modal: "payment-method__modal" }}
    >
      <AddBillingAddressForm />
    </BootstrapDialogHOC>
  );
};

export default AddBillingAddress;
