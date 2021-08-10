import React, { useContext } from "react";
import { Button } from "@material-ui/core";
import { useHistory } from "react-router";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { accountStore } from "../../../../redux/stores/StoreAccount";
import { useDispatch, useSelector } from "react-redux";
import { removeCard } from "../../../../redux/actions/account-actions/WalletActions";
import { CardHeader } from "./CardHeader";

export const RemoveCard = ({ cardInfo }) => {
  const history = useHistory();

  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const submitCardFormLoading = useSelector(
    (e: any) => e.wallet.submitCardFormLoading
  );

  const onRemoveEnd = () => {
    if (accountStore.getState().main.breakpoint.is768) {
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
