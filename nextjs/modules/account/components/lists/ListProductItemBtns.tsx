import React from "react";
import Select from "@modules/ui/forms/select/Select";
import { useDispatch } from "react-redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";
import useSnackbar from "@modules/account/hooks/useSnackbar";
import Button, { ETheme } from "@modules/ui/forms/Button";

import Styles from "@modules/account/components/lists/ListProductItemBtns.module.scss";

interface ListProductItemBtnsProps {
  handleDelete: () => void;
  edit: boolean;
  btnLabel: string;
  mainBtnType?: ETheme;
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
  mainBtnType,
  productId,
  onMainBtnClick,
  time,
  listId,
  outOfStock,
  disabledAddToCart,
}) => {
  const dispatch = useDispatch();
  const snackbar = useSnackbar();

  const { lists, listView }: AccountListsStore = useSelectorAccount(
    (state) => state.lists
  );
  const handleMove = (e) => {
    const toListId = e.target.value.value;
    const toList = lists.find((list) => list.productListId === toListId);
    if (toList) {
      const movingItem = toList.products.find(
        (item) => item.list_items_id === productId
      );
      if (movingItem?.productType === "product") {
        const inList = toList.products.find(
          (product) => product.productId === productId
        );
        if (inList) {
          snackbar.show(`This item already added to list`);
          return;
        }
      }
    }
    const fromListId = listView.productListId;
    dispatch(transferProductList(fromListId, toListId, productId));
  };

  return (
    <div className={Styles.container}>
      <Button
        theme={mainBtnType}
        disabled={outOfStock || disabledAddToCart}
        className={cn("full-width-button", "fw-bold", Styles.button)}
        onClick={onMainBtnClick}
      >
        {btnLabel}
      </Button>
      {edit && (
        <div
          className={cn(
            Styles.btnsContainer,
            "list-product-item-btns-container"
          )}
        >
          <Select
            clearable={false}
            isSearchable={false}
            options={lists
              .filter((list) => list.role !== UserPrivateVariantsEnum.VIEW)
              .filter((list) => list.productListId !== listView.productListId)
              .map((e) => {
                return {
                  label: e.name,
                  value: e.productListId,
                };
              })}
            name={""}
            onChange={handleMove}
            value={{ label: "Move", value: undefined }}
            classes={{
              select: Styles.productListItemSelect,
              menu: Styles.productListItemSelectMenu,
              control: Styles.productListItemSelectControl,
              indicator: "py-0 ps-0 pe-1",
              indicatorSeparator: "d-none",
              option: Styles.productListItemSelectOption,
              valueContainer: "pe-lg-0",
            }}
          />
          <button
            type={"submit"}
            onClick={handleDelete}
            className={cn(
              Styles.productListItemDeleteButton,
              "form-button",
              "account-submit-btn",
              "account-submit-btn-outline",
              "product-list-item-delete-button"
            )}
          >
            delete
          </button>
        </div>
      )}
    </div>
  );
};
