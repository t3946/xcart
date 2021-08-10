import React from "react";
import { useAccordion } from "../../hooks/useAccordion";
import { Button } from "@material-ui/core";
import { useDialog } from "../../hooks/useDialog";
import { CardDialog } from "./CardDialog";
import { RemoveCardDialog } from "./RemoveCardDialog";
import { useDispatch } from "react-redux";
import { changeDefaultCard } from "../../../../redux/actions/account-actions/WalletActions";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import { AddEditBtnsBlock } from "../shared/AddEditBtnsBlock";
import { useHistory } from "react-router";
import { CardHeader } from "./CardHeader";

export const CardItem = ({ cardInfo, firstChild, breakPoint }) => {
  const accordion = useAccordion();

  const dispatch = useDispatch();

  const removeDialog = useDialog();

  const editDialog = useDialog();

  const history = useHistory();

  const expires = new Date(Number(cardInfo.expires));

  const changeDefault = (e) => {
    e.stopPropagation();
    if (!cardInfo.is_default) {
      dispatch(changeDefaultCard(cardInfo.credit_card_id));
    }
  };

  const openCardDialog = (dialog, path) => {
    if (breakPoint.is768) {
      history.push({
        pathname: path,
        state: { cardInfo: cardInfo },
      });
      return;
    }
    dialog.handleClickOpen();
  };

  return (
    <div className="wallet-card-container">
      <div
        onClick={accordion.onItemClick}
        className={`wallet-card-header ${
          firstChild && "wallet-card-header-first"
        }`}
      >
        <CardHeader
          cardNumber={cardInfo.card_number}
          cardType={cardInfo.card_type}
        />
        <div className="wallet-card-billing wallet-card-billing-header">
          Exp: {expires.getMonth() + "/" + expires.getFullYear()}
        </div>
        <div className="wallet-header-arrow-block">
          <div
            className={`wallet-header-default-block ${
              cardInfo.is_default && "wallet-header-default-block_is-default"
            }`}
            onClick={changeDefault}
          >
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
          {breakPoint.is768 ? (
            <AddEditBtnsBlock
              handleRemove={() =>
                openCardDialog(removeDialog, "/account/payments/wallet/remove")
              }
              handleEdit={() =>
                openCardDialog(editDialog, "/account/payments/wallet/edit")
              }
              defaultItem={cardInfo.is_default}
              changeDefault={changeDefault}
            >
              <div className={"wallet-header-default-block_is-default"}>
                Default
              </div>
            </AddEditBtnsBlock>
          ) : (
            <div className="wallet-card-buttons">
              <Button
                className="account-submit-btn edit-card-btn"
                onClick={() =>
                  openCardDialog(editDialog, "/account/payments/wallet/edit")
                }
              >
                Edit
              </Button>
              <Button
                onClick={() =>
                  openCardDialog(
                    removeDialog,
                    "/account/payments/wallet/remove"
                  )
                }
                className="account-submit-btn account-submit-btn-outline"
              >
                Remove
              </Button>
            </div>
          )}
        </div>
      </div>
      <CardDialog
        contentType={BillingAddressFormEnum.EDIT}
        actionType={BillingAddressFormEnum.EDIT}
        open={editDialog.open}
        cardInfo={cardInfo}
        handleClose={editDialog.handleClose}
      />
      <RemoveCardDialog
        open={removeDialog.open}
        handleClose={removeDialog.handleClose}
        cardInfo={cardInfo}
      />
    </div>
  );
};
