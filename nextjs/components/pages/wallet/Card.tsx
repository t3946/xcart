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
import AddCard from "@components/pages/wallet/dialog/AddCard";

export function addressToString(address) {
  const parts = [];
  const fields = ["street", "city", "state", "zip", "country"];

  for (const field of fields) {
    parts.push(address[field]);
  }

  return parts.join(", ");
}

interface IProps {
  card: ICard;
  isDefault: boolean;
  changeDefaultCardId: (cardId: string) => void;
  editCard: (card: ICard) => void;
}

const Card: React.FC<IProps> = (props) => {
  const [showAddModal, setShowAddModal] = React.useState(false);
  const dispatch = useDispatch();
  const { card, isDefault, changeDefaultCardId, editCard } = props;
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

  function billingAddressTemplate() {
    if (!card.metadata.address) {
      return;
    }

    return (
      <div className="wallet-card-billing">
        <div className="wallet-card-content-label">Billing address</div>
        <div>{addressToString(card.metadata.address)}</div>
      </div>
    );
  }

  return (
    <div className="wallet-card-container">
      <div
        onClick={accordion.onItemClick}
        className={`wallet-card-header row m-0`}
      >
        <CardHeader cardLast4={card.last4} cardType={card.brand} />
        <div className="col-4">{expTemplate()}</div>

        <div className="col-4 d-flex align-items-center justify-content-end justify-content-md-between pe-0">
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
        <div className={`wallet-card-content row m-0`}>
          <div className="col-4 ps-0">
            {card.metadata?.cardHolderName && (
              <div className="wallet-card-content-label">Name on card </div>
            )}
            <div>{card.metadata.cardHolderName}</div>
          </div>

          <div className="col-4">{billingAddressTemplate()}</div>

          <div className="col-4 row">
            <div className="col-6">
              <button
                className="form-button account-submit-btn edit-card-btn"
                onClick={() => editCard(card)}
              >
                Edit
              </button>
            </div>

            <div className="col-6">
              <Button
                className={"col-6"}
                theme={ETheme.outlined}
                onClick={removeCard}
              >
                remove
              </Button>
            </div>
          </div>
        </div>
      </div>

      <AddCard
        open={showAddModal}
        handleClose={() => {
          setShowAddModal(false);
        }}
      />

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
