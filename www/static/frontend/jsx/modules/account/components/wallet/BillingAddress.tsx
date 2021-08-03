import React, { useContext, useState } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { Button, Grid } from "@material-ui/core";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useDispatch, useSelector } from "react-redux";
import { AddressTypeEnum } from "../../ts/types/address-item.type";
import { addCard } from "../../../../redux/actions/account-actions/WalletActions";
export const BillingAddress = () => {
  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const billingAddresses = useSelector((e: any) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    );
  });

  const [value, setValue] = useState(billingAddresses[0]?.addresses_id);

  const cardSubmitData = useSelector((e: any) => e.wallet.submitFormData);

  const onSubmit = () => {
    dispatch(
      addCard(
        {
          ...cardSubmitData,
          address: {
            address_id: value,
          },
        },
        context.handleClose
      )
    );
  };

  return (
    <div className="billing-address-container">
      <div className="dialog-title">Select a billing address</div>

      <BillingAddressList
        value={value}
        setValue={setValue}
        addresses={billingAddresses}
      />

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
          disabled={!value}
          onClick={onSubmit}
        >
          USE THIS ADDRESS
        </Button>
      </Grid>
    </div>
  );
};
