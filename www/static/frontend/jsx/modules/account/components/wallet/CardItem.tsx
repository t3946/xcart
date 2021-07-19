import React from "react";
import { useAccordion } from "../../hooks/useAccordion";
import { Button } from "@material-ui/core";

export const CardItem = () => {
  const accordion = useAccordion();

  return (
    <div className="wallet-card-container">
      <div onClick={accordion.onItemClick} className="wallet-card-header">
        <div className="wallet-card-name wallet-card-name-header">
          <img
            className="card-img"
            src="/static/frontend/dist/images/icons/account/master-card.svg"
          />
          <div>Mastercard ending in 5996</div>
        </div>

        <div className="wallet-card-billing">Exp: 10/2021</div>
        <div className="wallet-header-arrow-block">
          <div>Default</div>
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
        <div className="wallet-card-content">
          <div className="wallet-card-name">
            <div className="wallet-card-content-label">Name on card </div>
            <div>Sergey Vorozhtsov</div>
          </div>
          <div className="wallet-card-billing">
            <div className="wallet-card-content-label">Billing address</div>
            <div>
              1370 BRIDGETON HILL RD UPPER BLACK EDDY, PA 18972 United States
              (763) 635-4364
            </div>
          </div>
          <div className="wallet-card-buttons">
            <Button className="account-submit-btn edit-card-btn">Edit</Button>
            <Button className="account-submit-btn account-submit-btn-outline">
              Remove
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
};
