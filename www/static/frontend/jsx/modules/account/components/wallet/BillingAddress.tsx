import React, { useContext } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { Button, Grid } from "@material-ui/core";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";

export const BillingAddress = () => {
  const context = useContext(WalletCardsDialogContext);
  return (
    <div className="billing-address-container">
      <div className="dialog-title">Select a billing address</div>
      <BillingAddressList />
      <Grid container>
        <Button
          type={"submit"}
          onClick={() => context.setContent(BillingAddressFormEnum.ADD_ADDRESS)}
          className="account-submit-btn account-submit-btn-outline auto-width-button add-billing-address-btn"
        >
          ADD new ADDRESS
        </Button>
        <Button
          type={"submit"}
          className="account-submit-btn auto-width-button"
        >
          USE THIS ADDRESS
        </Button>
      </Grid>
    </div>
  );
};
