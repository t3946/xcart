import React from "react";
import { RemoveCard } from "./RemoveCard";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";

interface RemoveCardDialogProps {
  handleClose: () => void;
  open: boolean;
  cardInfo: CardItemDto;
}

export const RemoveCardDialog: React.FC<RemoveCardDialogProps> = ({
  handleClose,
  open,
  cardInfo,
}) => {
  return (
    <BootstrapDialogHOC onClose={handleClose} show={open} title={"Remove card"}>
      <WalletCardsDialogContext.Provider
        value={{
          handleClose,
        }}
      >
        <RemoveCard cardInfo={cardInfo} />
      </WalletCardsDialogContext.Provider>
    </BootstrapDialogHOC>
  );
};
