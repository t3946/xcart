import React, { useContext, useState } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { Button, Grid } from "@material-ui/core";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useSelector } from "react-redux";
import { AddressTypeEnum } from "../../ts/types/address-item.type";

export const BillingAddress = () => {
  const context = useContext(WalletCardsDialogContext);

  const billingAddresses = useSelector((e: any) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    );
  });

  const [value, setValue] = useState(billingAddresses[0].addresses_id);

  return (
    <div className="billing-address-container">
      <div className="dialog-title">Select a billing address</div>
      {billingAddresses && (
        <BillingAddressList
          value={value}
          setValue={setValue}
          addresses={billingAddresses}
        />
      )}
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
