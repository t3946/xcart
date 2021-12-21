import React, { useState } from "react";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { CardAction } from "./CardAction";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";

interface CardDialogProps {
  handleClose: () => void;
  open: boolean;
  contentType: BillingAddressFormEnum;
  actionType: BillingAddressFormEnum;
  cardInfo?: CardItemDto;
}

export const CardDialog: React.FC<CardDialogProps> = ({
  handleClose,
  open,
  contentType,
  actionType,
  cardInfo,
}) => {
  return (
    <BootstrapDialogHOC
      onClose={handleClose}
      show={open}
      title={`${
        actionType === BillingAddressFormEnum.ADD_ADDRESS
          ? "Add Card"
          : "Edit Card"
      }`}
      classes={{ modal: "payment-method__modal" }}
    >
      <CardAction
        contentType={contentType}
        actionType={actionType}
        cardInfo={cardInfo}
        onDialogClose={handleClose}
      />
    </BootstrapDialogHOC>
  );
};
