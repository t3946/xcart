import React, { useContext } from "react";
import Select, { Item } from "@modules/ui/forms/Select";
import { useDispatch } from "react-redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import classnames from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";

interface ListProductItemBtnsProps {
  handleDelete: () => void;
  edit: boolean;
  btnLabel: string;
  mainBtnClasses?: string | string[];
  productId: number;
  onMainBtnClick: () => void;
  time: string;
  listId: string;
  outOfStock?: boolean;
}

export const ListProductItemBtns: React.FC<ListProductItemBtnsProps> = ({
  handleDelete,
  edit,
  btnLabel,
  mainBtnClasses,
  productId,
  onMainBtnClick,
  time,
  listId,
  outOfStock,
}) => {
  const dispatch = useDispatch();
  const { showSnackbar } = useContext(SnackbarContext);

  const { lists, listView }: AccountListsStore = useSelectorAccount(
    (state) => state.lists
  );
  const handleMove = (item: Item) => {
    const toListId = item.value;
    const toList = lists.find((list) => list.productListId === toListId);
    if (toList) {
      const inList = toList.products.find(
        (product) => product.productId === productId
      );
      if (inList) {
        showSnackbar({
          header: "Error",
          message: `This item already added to list`,
          theme: "error",
        });
        return;
      }
    }
    const fromListId = listView.productListId;
    dispatch(transferProductList(fromListId, toListId, productId));
  };

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
          <Select
            items={lists
              .filter((list) => list.role !== UserPrivateVariantsEnum.VIEW)
              .filter((list) => list.productListId !== listView.productListId)
              .map((e) => {
                return {
                  viewValue: e.name,
                  value: e.productListId,
                };
              })}
            name={""}
            label={""}
            onClick={handleMove}
            value={{ viewValue: "Move", value: undefined }}
            id={`form-select-list-product-${productId}`}
            classes={{
              group: "list-product-item-btns-move",
              selectHeader: "product-list-item-move-select",
            }}
          />
          <button
            type={"submit"}
            onClick={handleDelete}
            className="form-button account-submit-btn account-submit-btn-outline auto-width-button product-list-item-delete-button"
          >
            delete
          </button>
        </div>
      )}
    </div>
  );
};
