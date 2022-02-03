import React from "react";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import Button, { ETheme } from "@modules/ui/forms/Button";

interface IProps {
  handleClose: () => void;
  open: boolean;
  contentType: BillingAddressFormEnum;
  actionType: BillingAddressFormEnum;
  cardInfo?: CardItemDto;
  address: any;
}

const CardDialog: React.FC<IProps> = (props) => {
  const { handleClose, open, address } = props;

  return (
    <BootstrapDialogHOC
      onClose={handleClose}
      show={open}
      title={`Edit payment method`}
      classes={{ modal: "payment-method__modal" }}
    >
      <div>Billing address:</div>
      <p>foo</p>
      <Button className={"w-auto"}>change</Button>
    </BootstrapDialogHOC>
  );
};

export default CardDialog;
