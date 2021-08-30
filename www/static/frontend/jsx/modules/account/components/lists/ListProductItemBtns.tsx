import React from "react";
import { Button } from "@material-ui/core";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

export const ListProductItemBtns = ({ onMoveClick }) => {
  const lists = useSelector((e: AccountStore) => e.lists.lists);

  return (
    <div>
      <div className="list-product-item-btns-text">Item added May 10, 2021</div>
      <Button className="account-submit-btn  full-width-button">Cancel</Button>
      <div className="list-product-item-btns-container">
        {/*<Button className="account-submit-btn account-submit-btn-outline auto-width-button list-product-item-btns-move">*/}
        {/*  MOVE*/}
        {/*</Button>*/}
        <FormSelect
          items={lists.map((e) => {
            return {
              viewValue: e.name,
              value: e.product_list_id,
            };
          })}
          name={""}
          label={""}
          onClick={(value) => onMoveClick(value)}
          value={{ viewValue: "Move", value: undefined }}
          id="form-select-list-product"
          classes={{ group: "list-product-item-btns-move" }}
        />
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
