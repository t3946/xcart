import React from "react";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CardDialog } from "@modules/account/components/wallet/CardDialog";
import { RemoveCardDialog } from "@modules/account/components/wallet/RemoveCardDialog";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import { AddEditBtnsBlock } from "@modules/account/components/shared/AddEditBtnsBlock";
import { CardHeader } from "@modules/account/components/wallet/CardHeader";
import { CardItemDto } from "@modules/account/ts/types/wallet.type";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { formatPhone } from "@utils/phoneNumber";

interface IProps {
  cardInfo: CardItemDto;
  changeDefault: (
    cardInfo: CardItemDto,
    e: React.MouseEvent<HTMLDivElement>
  ) => void;
  openCardDialog: (cardInfo: CardItemDto, dialog: any, path: string) => void;
}

export const CardItem: React.FC<IProps> = ({
  cardInfo,
  changeDefault,
  openCardDialog,
}) => {
  const accordion = useAccordion();
  const removeDialog = useDialog();
  const editDialog = useDialog();
  const expires = new Date(Number(cardInfo.expires));
  const breakpoint = useBreakpoint();

  return (
    <div className="wallet-card-container">
      <div onClick={accordion.onItemClick} className={`wallet-card-header `}>
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
            onClick={(e) => changeDefault(cardInfo, e)}
          >
            {cardInfo.is_default ? "Default" : "Set default"}
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
            <div className="wallet-card-content-label">Name on card </div>
            <div>{cardInfo.name}</div>
          </div>
          <div className="wallet-card-billing">
            <div className="wallet-card-content-label">Billing address</div>
            <div>
              1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972 United States
              {formatPhone(cardInfo.address.phone_number)}
            </div>
          </div>
          {breakpoint({
            xs: (
              <AddEditBtnsBlock
                handleRemove={() =>
                  openCardDialog(
                    cardInfo,
                    removeDialog,
                    "/account/payments/wallet/remove"
                  )
                }
                handleEdit={() =>
                  openCardDialog(
                    cardInfo,
                    editDialog,
                    "/account/payments/wallet/edit"
                  )
                }
                defaultItem={cardInfo.is_default}
                changeDefault={(e) => changeDefault(cardInfo, e)}
              >
                <div className={"wallet-header-default-block_is-default"}>
                  Default
                </div>
              </AddEditBtnsBlock>
            ),
            md: (
              <div className="wallet-card-buttons">
                <button
                  className="form-button account-submit-btn edit-card-btn"
                  onClick={() =>
                    openCardDialog(
                      cardInfo,
                      editDialog,
                      "/account/payments/wallet/edit"
                    )
                  }
                >
                  Edit
                </button>
                <button
                  onClick={() =>
                    openCardDialog(
                      cardInfo,
                      removeDialog,
                      "/account/payments/wallet/remove"
                    )
                  }
                  className="form-button account-submit-btn account-submit-btn-outline"
                >
                  Remove
                </button>
              </div>
            ),
          })}
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
