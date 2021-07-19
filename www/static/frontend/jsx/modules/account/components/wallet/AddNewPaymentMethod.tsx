import React from "react";
import { Button } from "@material-ui/core";

export const AddNewPaymentMethod = () => {
  return (
    <div className="add-new-payment-method-container">
      <Button className="account-submit-btn edit-card-btn">
        Add a credit or debit card
      </Button>
      <div>S3 Stores Inc accepts major credit and debit cards</div>
    </div>
  );
};
