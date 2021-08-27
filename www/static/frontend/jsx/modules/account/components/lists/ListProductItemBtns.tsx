import React from "react";
import { Button } from "@material-ui/core";

export const ListProductItemBtns = () => {
  return (
    <div>
      <div className="list-product-item-btns-text">Item added May 10, 2021</div>
      <Button className="account-submit-btn  full-width-button">Cancel</Button>
      <div className="list-product-item-btns-container">
        <Button className="account-submit-btn account-submit-btn-outline auto-width-button list-product-item-btns-move">
          MOVE
        </Button>
        <Button
          type={"submit"}
          className="account-submit-btn account-submit-btn-outline auto-width-button "
        >
          delete
        </Button>
      </div>
    </div>
  );
};
