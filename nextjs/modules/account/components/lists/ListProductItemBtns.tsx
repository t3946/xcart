import React from "react";
import FormSelect from "@modules/ui/forms/Select";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import classnames from "classnames";
import { SelectValue } from "@modules/account/ts/types/select-value.type";

interface ListProductItemBtnsProps {
  onMoveClick: (value: SelectValue<string, string>) => void;
  deleteItem: () => void;
  edit: boolean;
  btnLabel: string;
  mainBtnClasses?: string | string[];
  id: string;
  onMainBtnClick: () => void;
  time: string;
  listId: string;
  outOfStock?: boolean;
}

export const ListProductItemBtns: React.FC<ListProductItemBtnsProps> = ({
  onMoveClick,
  deleteItem,
  edit,
  btnLabel,
  mainBtnClasses,
  id,
  onMainBtnClick,
  time,
  listId,
  outOfStock,
}) => {
  const lists = useSelector((e: StoreInterface) => e.lists.lists);

  return (
    <div className={"product-list-item-btns-container"}>
      <button
        disabled={outOfStock}
        className={classnames(
          "form-button",
          "account-submit-btn  full-width-button",
          mainBtnClasses
        )}
        onClick={onMainBtnClick}
      >
        {btnLabel}
      </button>
      {edit && (
        <div className="list-product-item-btns-container">
          <FormSelect
            items={lists
              .filter((e) => e.list_info.role !== UserPrivateVariantsEnum.VIEW)
              .filter((e) => e.product_list_id !== listId)
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
          <button
            type={"submit"}
            onClick={deleteItem}
            className="form-button account-submit-btn account-submit-btn-outline auto-width-button product-list-item-delete-button"
          >
            delete
          </button>
        </div>
      )}
    </div>
  );
};
