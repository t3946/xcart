import React, { useContext } from "react";
import { Button } from "@material-ui/core";
import { useHistory } from "react-router";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import Store from "@client/jsx/redux/stores/Store";
import { useDispatch, useSelector } from "react-redux";
import { removeCard } from "../../../../redux/actions/account-actions/PaymentsActions";
import { CardHeader } from "./CardHeader";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { CardItemDto } from "@client/modules/account/ts/types/wallet.type";

interface RemoveCardProps {
  cardInfo: CardItemDto;
}

export const RemoveCard: React.FC<RemoveCardProps> = ({ cardInfo }) => {
  const history = useHistory();

  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
  );

  const onRemoveEnd = () => {
    if (Store.getState().main.breakpoint.is768) {
      history.push("/account/payments/wallet");
      return;
    }
    context.handleClose();
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
        <Button
          onClick={onRemoveEnd}
          className="account-submit-btn account-submit-btn-outline auto-width-button cancel-edit-card-btn"
          disabled={submitCardFormLoading}
        >
          Cancel
        </Button>
        <Button
          disabled={submitCardFormLoading}
          onClick={handleSubmit}
          className="account-submit-btn auto-width-button"
        >
          Remove
        </Button>
      </div>
    </div>
  );
};
