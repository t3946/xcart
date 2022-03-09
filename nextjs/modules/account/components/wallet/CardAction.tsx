import React, { useState } from "react";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { AddBillingAddressForm } from "./AddBillingAddressForm";
import { BillingAddress } from "./BillingAddress";
import { AddCardForm } from "./AddCardForm";
import { EditCard } from "./EditCard";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";

interface CardActionProps {
  contentType: BillingAddressFormEnum;
  actionType: BillingAddressFormEnum;
  cardInfo?: CardItemDto;
  onDialogClose?: () => void;
}

export const CardAction: React.FC<CardActionProps> = ({
  contentType,
  actionType,
  cardInfo = undefined,
  onDialogClose = undefined,
}) => {
  const [content, setContent] = useState(contentType);

  const dialogClose = () => {
    onDialogClose();
    setTimeout(() => {
      setContent(contentType);
    }, 200);
  };

  const showContent = (type) => {
    switch (type) {
      case BillingAddressFormEnum.ADD_ADDRESS: {
        return <AddBillingAddressForm edit={Boolean(cardInfo)} />;
      }
      case BillingAddressFormEnum.ADD_CARD: {
        return <AddCardForm />;
      }
      case BillingAddressFormEnum.LIST_ADDRESS: {
        return <BillingAddress cardInfo={cardInfo} />;
      }
      case BillingAddressFormEnum.EDIT: {
        return <EditCard cardInfo={cardInfo} />;
      }
    }
  };
  return (
    <WalletCardsDialogContext.Provider
      value={{
        setContent,
        actionType,
        handleClose: dialogClose,
      }}
    >
      {showContent(content)}
    </WalletCardsDialogContext.Provider>
  );
};
