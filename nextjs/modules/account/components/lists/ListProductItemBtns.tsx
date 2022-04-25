import React from "react";
import Select from "@modules/ui/forms/select/Select";
import { useDispatch } from "react-redux";
import { AccountListsStore } from "@modules/account/ts/types/store.type";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { checkProductCollisionInList } from "@modules/account/utils/check-product-collision-in-list";
import Styles from "@modules/account/components/lists/ListProductItemBtns.module.scss";
import OutOfStock from "@components/common/catalog/out-of-stock/OutOfStock";

interface IProps {
  handleDelete: () => void;
  edit: boolean;
  btnLabel: string;
  mainBtnType?: ETheme;
  item: number;
  onMainBtnClick: () => void;
  list: any;
  outOfStock?: boolean;
  disabledAddToCart?: any;
  className?: any;
}

export const ListProductItemBtns: React.FC<IProps> = (props) => {
  const {
    handleDelete,
    edit,
    btnLabel,
    mainBtnType,
    item,
    searchLink,
    list,
    outOfStock,
    disabledAddToCart,
    onMainBtnClick,
    className,
  } = props;
  const dispatch = useDispatch();
  const snackbar = useSnackbar();
  const { lists }: AccountListsStore = useSelectorAccount(
    (state) => state.lists
  );

  function handleMove(e) {
    const toListId = e.target.value.value;
    const toList = lists.find((list) => list.product_list_id === toListId);

    if (checkProductCollisionInList(list, toList, item.list_item_id)) {
      dispatch(
        transferProductList({
          data: {
            product_list_id: toListId,
            list_item_id: item.list_item_id,
          },
        })
      );
    } else {
      snackbar.show(
        `This item already added to list`,
        3000,
        VariantsEnum.error
      );
    }
  }

  function addToCartTemplate() {
    if (outOfStock) {
      return <OutOfStock className={["d-none", "d-md-flex"]} />;
    }

    return (
      <a href={searchLink} className={"text-decoration-none"}>
        <Button
          theme={mainBtnType}
          disabled={outOfStock || disabledAddToCart}
          className={cn("full-width-button", "fw-bold", Styles.button)}
          onClick={onMainBtnClick}
        >
          {btnLabel}
        </Button>
      </a>
    );
  }

  return (
    <div className={cn(Styles.container, className)}>
      {addToCartTemplate()}

      {edit && (
        <div
          className={cn(
            Styles.btnsContainer,
            "list-product-item-btns-container"
          )}
        >
          <Select
            instanceId={`select-list_${list.product_list_id}`}
            clearable={false}
            isSearchable={false}
            options={lists
              .filter(function (list) {
                if (list.role === UserPrivateVariantsEnum.VIEW) {
                  return false;
                }

                if (list.product_list_id === props.list.product_list_id) {
                  return false;
                }

                if (props.item.product_type === "product") {
                  for (const item of list.items) {
                    if (item.product_id === props.item.product_id) {
                      return false;
                    }
                  }
                }

                return true;
              })
              .map((e) => {
                return {
                  label: e.name,
                  value: e.product_list_id,
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
