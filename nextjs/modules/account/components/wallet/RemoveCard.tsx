import React, { useContext } from "react";
import { useRouter } from "next/router";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { useDispatch, useSelector } from "react-redux";
import { removeCard } from "../../../../redux/actions/account-actions/PaymentsActions";
import { CardHeader } from "./CardHeader";
import StoreInterface from "@modules/account/ts/types/store.type";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

interface RemoveCardProps {
  cardInfo: CardItemDto;
}

export const RemoveCard: React.FC<RemoveCardProps> = ({ cardInfo }) => {
  const router = useRouter();

  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
  );

  const breakpoint = useBreakpoint();

  const onRemoveEnd = () => {
    breakpoint({
      xs: () => router.push("/account/payments/wallet"),
      md: context.handleClose,
    });
  };

  const handleSubmit = () => {
    dispatch(removeCard(cardInfo.credit_card_id, onRemoveEnd));
  };
  return (
    <div className="billing-address-container">
      <CardHeader
        cardNumber={cardInfo.card_number}
        cardType={cardInfo.card_type}
        containerClass={["edit-card-top-part", "full-width"]}
      />
      <div>
        If you do not want this payment method to be displayed in your list of
        payment options, click "Remove". (Disabling this payment method will not
        cancel any of your open orders that use this method.)
      </div>
      <div className="edit-card-btns remove-card-btns">
        <button
          onClick={onRemoveEnd}
          className="form-button account-submit-btn account-submit-btn-outline auto-width-button cancel-edit-card-btn"
          disabled={submitCardFormLoading}
        >
          Cancel
        </button>
        <button
          disabled={submitCardFormLoading}
          onClick={handleSubmit}
          className="form-button account-submit-btn auto-width-button"
        >
          Remove
        </button>
      </div>
    </div>
  );
};
