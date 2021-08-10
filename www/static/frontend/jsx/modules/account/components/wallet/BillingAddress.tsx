import React, { useContext, useState } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { Button, Grid } from "@material-ui/core";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useDispatch, useSelector } from "react-redux";
import { AddressTypeEnum } from "../../ts/types/address-item.type";
import {
  addCard,
  addDataFromSubmitCardForm,
} from "../../../../redux/actions/account-actions/WalletActions";

export const BillingAddress = ({ cardInfo }) => {
  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const billingAddresses = useSelector((e: any) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    );
  });
  const cardSubmitData = useSelector((e: any) => e.wallet.submitFormData);

  const submitCardFormLoading = useSelector(
    (e: any) => e.wallet.submitCardFormLoading
  );

  const [value, setValue] = useState(
    cardSubmitData?.address?.address_id ||
      cardInfo?.address_id ||
      billingAddresses[0]?.addresses_id
  );

  const onSubmit = () => {
    if (cardInfo) {
      dispatch(
        addDataFromSubmitCardForm({
          address: {
            address_id: value,
          },
        })
      );
      context.setContent(BillingAddressFormEnum.EDIT);
      return;
    }
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
      <div className="billing-address-butns">
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
          disabled={!value || submitCardFormLoading}
          onClick={onSubmit}
        >
          USE THIS ADDRESS
        </Button>
      </div>
    </div>
  );
};
