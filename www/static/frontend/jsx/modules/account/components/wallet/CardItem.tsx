import React from "react";
import { useAccordion } from "../../hooks/useAccordion";
import { Button } from "@material-ui/core";
import { useDialog } from "../../hooks/useDialog";
import { EditCardDialog } from "./EditCardDialog";
import { RemoveCardDialog } from "./RemoveCardDialog";
import { useDispatch } from "react-redux";
import { changeDefaultCard } from "../../../../redux/actions/account-actions/WalletActions";

export const CardItem = ({ cardInfo, firstChild }) => {
  const accordion = useAccordion();

  const dispatch = useDispatch();

  const removeDialog = useDialog();

  const editDialog = useDialog();

  const changeDefault = (e) => {
    e.stopPropagation();
    if (!cardInfo.is_default) {
      dispatch(changeDefaultCard(cardInfo.credit_card_id));
    }
  };

  return (
    <div className="wallet-card-container">
      <div
        onClick={accordion.onItemClick}
        className={`wallet-card-header ${
          firstChild && "wallet-card-header-first"
        }`}
      >
        <div className="wallet-card-name wallet-card-name-header">
          <img
            className="wallet-card-img"
            src={`/static/frontend/dist/images/icons/account/cards/${cardInfo.card_type}.svg`}
          />
          <div>
            Mastercard ending in{" "}
            {cardInfo.card_number.substr(cardInfo.card_number.length - 4)}
          </div>
        </div>

        <div className="wallet-card-billing">Exp: 10/2021</div>
        <div className="wallet-header-arrow-block">
          <div onClick={changeDefault}>
            {cardInfo.is_default ? "Default" : "Set default"}
          </div>
          <div
            className={`accordion-arrow ${
              accordion.open && "accordion-arrow-open"
            }`}
          />
        </div>
      </div>
      <div
        style={{
          height: accordion.height,
        }}
        ref={accordion.ref}
        className="wallet-card-content-container"
      >
        <div className={`wallet-card-content `}>
          <div className="wallet-card-name">
            <div className="wallet-card-content-label">Name on card </div>
            <div>{cardInfo.name}</div>
          </div>
          <div className="wallet-card-billing">
            <div className="wallet-card-content-label">Billing address</div>
            <div>
              1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972 United States
              {cardInfo.address.phone_number}
            </div>
          </div>
          <div className="wallet-card-buttons">
            <Button
              className="account-submit-btn edit-card-btn"
              onClick={editDialog.handleClickOpen}
            >
              Edit
            </Button>
            <Button
              onClick={removeDialog.handleClickOpen}
              className="account-submit-btn account-submit-btn-outline"
            >
              Remove
            </Button>
          </div>
        </div>
      </div>
      <EditCardDialog
        open={editDialog.open}
        handleClose={editDialog.handleClose}
      />
      <RemoveCardDialog
        open={removeDialog.open}
        handleClose={removeDialog.handleClose}
      />
    </div>
  );
};
