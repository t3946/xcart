import React, { useState } from "react";
import { Dialog } from "@material-ui/core";
import { DialogHeader } from "../dialog/DialogHeader";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { CardAction } from "./CardAction";
import { CardItemDto } from "@client/modules/account/ts/types/wallet.type";

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
    <Dialog
      className="email-send-dialog"
      fullWidth={true}
      onClose={handleClose}
      maxWidth="md"
      aria-labelledby="simple-dialog-title"
      open={open}
      PaperProps={{
        style: {
          borderRadius: 0,
          overflowY: "initial",
        },
      }}
    >
      <DialogHeader
        label={`${
          actionType === BillingAddressFormEnum.ADD_ADDRESS
            ? "Add Card"
            : "Edit Card"
        }`}
        onClose={handleClose}
      />
      <CardAction
        contentType={contentType}
        actionType={actionType}
        cardInfo={cardInfo}
        onDialogClose={handleClose}
      />
    </Dialog>
  );
};
