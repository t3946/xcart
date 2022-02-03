import React from "react";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CardDialog } from "@modules/account/components/wallet/CardDialog";
import { RemoveCardDialog } from "@modules/account/components/wallet/RemoveCardDialog";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { CardHeader } from "@modules/account/components/wallet/CardHeader";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { Card as ICard } from "@stripe/stripe-js";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { deleteCard } from "@redux/actions/account-actions/PaymentsActions";
import { useDispatch } from "react-redux";

interface IProps {
  card: ICard;
  isDefault: boolean;
  changeDefaultCardId: (cardId: string) => void;
  openCardDialog: (card, dialog: any, path: string) => void;
}

const Card: React.FC<IProps> = (props) => {
  const dispatch = useDispatch();
  const { card, isDefault, changeDefaultCardId, openCardDialog } = props;
  const accordion = useAccordion();
  const removeDialog = useDialog();
  const editDialog = useDialog();
  const breakpoint = useBreakpoint();

  function expTemplate() {
    let month = card.exp_month.toString();

    if (card.exp_month < 10) {
      month = "0" + card.exp_month;
    }

    return `Exp: ${month}/${card.exp_year}`;
  }

  function changeDefaultCard(e: MouseEvent) {
    e.stopPropagation();
    changeDefaultCardId(card.id);
  }

  function removeCard() {
    dispatch(
      deleteCard({
        data: { cardId: card.id },
        success() {
          window.location.reload();
        },
      })
    );
  }

  return (
    <div className="wallet-card-container">
      <div onClick={accordion.onItemClick} className={`wallet-card-header `}>
        <CardHeader cardLast4={card.last4} cardType={card.brand} />
        <div className="wallet-card-billing wallet-card-billing-header">
          {expTemplate()}
        </div>
        <div className="wallet-header-arrow-block">
          <div
            className={`wallet-header-default-block ${
              isDefault && "wallet-header-default-block_is-default"
            }`}
            onClick={changeDefaultCard}
          >
            {isDefault ? "Default" : "Set default"}
          </div>

          <div
            className={`accordion-arrow black-arrow ${
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
            {/*<div className="wallet-card-content-label">Name on card </div>*/}
            <div>{card.name}</div>
          </div>
          <div className="wallet-card-billing">
            <div className="wallet-card-content-label">Billing address</div>
            <div>
              1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972 United States
            </div>
          </div>
          {/*{breakpoint({*/}
          {/*  xs: (*/}
          {/*    <AddEditBtnsBlock*/}
          {/*      handleRemove={() =>*/}
          {/*        openCardDialog(*/}
          {/*          card,*/}
          {/*          removeDialog,*/}
          {/*          "/account/payments/wallet/remove"*/}
          {/*        )*/}
          {/*      }*/}
          {/*      handleEdit={() =>*/}
          {/*        openCardDialog(*/}
          {/*          card,*/}
          {/*          editDialog,*/}
          {/*          "/account/payments/wallet/edit"*/}
          {/*        )*/}
          {/*      }*/}
          {/*      defaultItem={isDefault}*/}
          {/*      changeDefault={changeDefaultCardId}*/}
          {/*    >*/}
          {/*      <div className={"wallet-header-default-block_is-default"}>*/}
          {/*        Default*/}
          {/*      </div>*/}
          {/*    </AddEditBtnsBlock>*/}
          {/*  ),*/}
          {/*  md: (*/}

          {/*  ),*/}
          {/*})}*/}
          <div className="wallet-card-buttons">
            {/*<button*/}
            {/*  className="form-button account-submit-btn edit-card-btn"*/}
            {/*  onClick={() =>*/}
            {/*    openCardDialog(*/}
            {/*      card,*/}
            {/*      editDialog,*/}
            {/*      "/account/payments/wallet/edit"*/}
            {/*    )*/}
            {/*  }*/}
            {/*>*/}
            {/*  Edit*/}
            {/*</button>*/}
            {/*<button*/}
            {/*  onClick={() =>*/}
            {/*    openCardDialog(*/}
            {/*      card,*/}
            {/*      removeDialog,*/}
            {/*      "/account/payments/wallet/remove"*/}
            {/*    )*/}
            {/*  }*/}
            {/*  className="form-button account-submit-btn account-submit-btn-outline"*/}
            {/*>*/}
            {/*  Remove*/}
            {/*</button>*/}
            <Button theme={ETheme.outlined} onClick={removeCard}>
              remove
            </Button>
          </div>
        </div>
      </div>

      <CardDialog
        contentType={BillingAddressFormEnum.EDIT}
        actionType={BillingAddressFormEnum.EDIT}
        open={editDialog.open}
        card={card}
        handleClose={editDialog.handleClose}
      />

      <RemoveCardDialog
        open={removeDialog.open}
        handleClose={removeDialog.handleClose}
        card={card}
      />
    </div>
  );
};

export default Card;
