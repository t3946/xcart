import React, { useState } from "react";
import { BillingAddressList } from "./BillingAddressList";
import { useDispatch, useSelector } from "react-redux";
import {
  addCard,
  changeAddressCard,
} from "@redux/actions/account-actions/PaymentsActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { Card as ICard } from "@stripe/stripe-js";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface BillingAddressProps {
  cardInfo: ICard;
  onSuccess?: () => void;
}

export const BillingAddress: React.FC<BillingAddressProps> = ({
  cardInfo,
  onSuccess,
  addAddress,
}) => {
  const dispatch = useDispatch();
  const userId = useSelectorAccount((e) => {
    return e.user?.user_id;
  });

  React.useEffect(() => {
    dispatch(getAddresses(userId));
  }, []);

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
      cardInfo?.metadata.address?.address_id ||
      billingAddresses?.[0]?.address_id
  );

  const onSubmit = () => {
    if (cardInfo) {
      dispatch(
        changeAddressCard({
          addressId: parseInt(value),
          cardId: cardInfo.id,
          success: onSuccess ? onSuccess : () => {},
        })
      );
      return;
    } else {
      dispatch(
        addCard(
          {
            ...cardSubmitData,
            address: {
              address_id: parseInt(value),
            },
          },
          onSuccess
        )
      );
    }
  };

  return (
    <div className="billing-address-container">
      <div className="dialog-title">Select a billing address</div>
      {billingAddresses && (
        <BillingAddressList
          value={value}
          onChange={(e) => setValue(e.target.value)}
          addresses={billingAddresses}
        />
      )}
      <div className="billing-address-butns">
        <button
          type={"submit"}
          onClick={addAddress}
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
