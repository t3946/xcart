import React from "react";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { Card as ICard } from "@stripe/stripe-js";
import { EditCard } from "@modules/account/components/wallet/EditCard";
import { BillingAddress } from "@modules/account/components/wallet/BillingAddress";
import { AddBillingAddressForm } from "@modules/account/components/wallet/AddBillingAddressForm";

interface IProps {
  handleClose: () => void;
  open: boolean;
  contentType: BillingAddressFormEnum;
  actionType: BillingAddressFormEnum;
  card: ICard | null;
}

export enum formType {
  main = "main",
  billing = "billing",
  newBilling = "newBilling",
}

const EditPaymentMethod: React.FC<IProps> = (props) => {
  const { handleClose, open, card } = props;
  const [typeChanging, setTypeChanging] = React.useState<formType>(
    formType.main
  );

  function modalContentTemplate() {
    switch (typeChanging) {
      case formType.main:
        return (
          card && (
            <EditCard
              cardInfo={card}
              changeAddress={() => setTypeChanging(formType.billing)}
              onCancel={onCloseModal}
            />
          )
        );

      case formType.billing:
        return (
          card && (
            <BillingAddress
              cardInfo={card}
              onSuccess={() => window.location.reload()}
              addAddress={() => setTypeChanging(formType.newBilling)}
            />
          )
        );

      case formType.newBilling:
        return (
          <AddBillingAddressForm
            edit={Boolean(card)}
            onCancel={() => setTypeChanging(formType.billing)}
          />
        );
    }
  }

  function onCloseModal() {
    setTypeChanging(formType.main);
    handleClose();
  }

  return (
    <BootstrapDialogHOC
      onClose={onCloseModal}
      show={open}
      title={`Edit payment method`}
      classes={{ modal: "payment-method__modal" }}
    >
      {modalContentTemplate()}
    </BootstrapDialogHOC>
  );
};

export default EditPaymentMethod;
