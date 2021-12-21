import React, { useContext, useState } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { useDispatch, useSelector } from "react-redux";
import {
  addCard,
  addDataFromSubmitCardForm,
} from "@redux/actions/account-actions/PaymentsActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { CardItemDto } from "../../ts/types/wallet.type";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";

interface BillingAddressProps {
  cardInfo: CardItemDto;
}

export const BillingAddress: React.FC<BillingAddressProps> = ({ cardInfo }) => {
  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const billingAddresses = useSelector((e: StoreInterface) => {
    return e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    );
  });
  const cardSubmitData = useSelector(
    (e: StoreInterface) => e.payments.submitFormData
  );

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
  );

  const [value, setValue] = useState(
    cardSubmitData?.address?.address_id ||
      cardInfo?.address_id ||
      billingAddresses[0]?.address_id
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
        <button
          type={"submit"}
          onClick={() => context.setContent(BillingAddressFormEnum.ADD_ADDRESS)}
          className="form-button account-submit-btn account-submit-btn-outline auto-width-button add-billing-address-btn"
          disabled={submitCardFormLoading}
        >
          ADD new ADDRESS
        </button>
        <button
          type={"submit"}
          className="form-button account-submit-btn auto-width-button"
          disabled={!value || submitCardFormLoading}
          onClick={onSubmit}
        >
          USE THIS ADDRESS
        </button>
      </div>
    </div>
  );
};
