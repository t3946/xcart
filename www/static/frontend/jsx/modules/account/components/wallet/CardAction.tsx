import React, { useState } from "react";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { AddBillingAddressForm } from "./AddBillingAddressForm";
import { BillingAddress } from "./BillingAddress";
import { AddCardForm } from "./AddCardForm";
import { EditCard } from "./EditCard";
import { useSelector } from "react-redux";

export const CardAction = ({
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

  const breakPoint = useSelector((e: any) => e.main.breakPoint);

  const showContent = (type) => {
    switch (type) {
      case BillingAddressFormEnum.ADD_ADDRESS: {
        return <AddBillingAddressForm edit={cardInfo && cardInfo} />;
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
        breakPoint,
      }}
    >
      {showContent(content)}
    </WalletCardsDialogContext.Provider>
  );
};
