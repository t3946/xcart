import React from "react";
import { Button, Collapse } from "@material-ui/core";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { UserPrivateVariantsEnum } from "@client/modules/account/ts/consts/user-private-variants.enum";
import classnames from "classnames";
import { SelectValue } from "@client/modules/account/ts/types/select-value.type";

interface ListProductItemBtnsProps {
  onMoveClick: (value: SelectValue<string, string>) => void;
  deleteItem: () => void;
  edit: boolean;
  btnLabel: string;
  mainBtnClasses?: string | string[];
  id: string;
  onMainBtnClick: () => void;
}

export const ListProductItemBtns: React.FC<ListProductItemBtnsProps> = ({
  onMoveClick,
  deleteItem,
  edit,
  btnLabel,
  mainBtnClasses,
  id,
  onMainBtnClick,
}) => {
  const lists = useSelector((e: AccountStore) => e.lists.lists);

  return (
    <div className={"product-list-item-btns-container"}>
      <div className="list-product-item-btns-text">Item added May 10, 2021</div>
      <Button
        className={classnames(
          "account-submit-btn  full-width-button",
          mainBtnClasses
        )}
        onClick={onMainBtnClick}
      >
        {btnLabel}
      </Button>
      {edit && (
        <div className="list-product-item-btns-container">
          <FormSelect
            items={lists
              .filter((e) => e.list_info.role !== UserPrivateVariantsEnum.VIEW)
              .map((e) => {
                return {
                  viewValue: e.name,
                  value: e.product_list_id,
                };
              })}
            name={""}
            label={""}
            onClick={(value) => onMoveClick(value)}
            value={{ viewValue: "Move", value: undefined }}
            id={`form-select-list-product-${id}`}
            classes={{
              group: "list-product-item-btns-move",
              selectHeader: "product-list-item-move-select",
            }}
          />
          <Button
            type={"submit"}
            onClick={deleteItem}
            className="account-submit-btn account-submit-btn-outline auto-width-button product-list-item-delete-button"
          >
            delete
          </Button>
        </div>
      )}
    </div>
  );
};
