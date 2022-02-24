import React from "react";
import { useDispatch } from "react-redux";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";
import { checkProductCollisionInList } from "@modules/account/utils/check-product-collision-in-list";

export const MoveProductPage: React.FC = () => {
  const router = useRouter();
  const { productListId, list_items_id } = router.query;
  let { lists } = useSelectorAccount((state) => state.lists);
  if (lists === null) {
    lists = [];
  }

  const snackbar = useSnackbar();
  const list = lists.find((e) => e.productListId === Number(productListId));
  const dispatch = useDispatch();

  function onChange(value: number) {
    if (value === Number(productListId)) {
      return;
    }
    const toList = lists.find((e) => e.productListId === value);

    if (checkProductCollisionInList(list, toList, Number(list_items_id))) {
      dispatch(
        transferProductList(Number(productListId), value, Number(list_items_id))
      );
      router.push(`/shopping-lists/${toList.cacheUrl}`);
    } else {
      snackbar.show(
        `This item already added to list`,
        3000,
        VariantsEnum.error
      );
    }
  }

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.cacheUrl}`}
        label={"back"}
      />
      <div className="page-label">Move product</div>
      {lists.map((e) => (
        <RadioBtn
          name="radio"
          key={`${e.productListId}`}
          id="radio-item-view"
          viewValue={<div className="move-product-label">{e.name}</div>}
          groupClasses={{
            group: ["share-list-radio", "move-product-radio"],
          }}
          groupValue={productListId}
          radioValue={e.productListId}
          onChange={onChange}
        />
      ))}
    </div>
  );
};
