import React, { useContext } from "react";
import { useDispatch } from "react-redux";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";

export const MoveProductPage: React.FC = () => {
  const router = useRouter();
  const { productListId, productId } = router.query;
  let { lists } = useSelectorAccount((state) => state.lists);

  if (lists === null) {
    lists = [];
  }

  const { showSnackbar } = useContext(SnackbarContext);
  const list = lists.find((e) => e.productListId === Number(productListId));
  const dispatch = useDispatch();

  function onChange(value: number) {
    if (value === Number(productListId)) {
      return;
    }

    const toList = lists.find((e) => e.productListId === value);

    if (toList) {
      const productOnList = toList.products.find(
        (e) => e.productId === Number(productId)
      );
      if (productOnList) {
        showSnackbar({
          header: "Error",
          message: `This item already added to list`,
          theme: "error",
        });
        return;
      }
      dispatch(
        transferProductList(Number(productListId), value, Number(productId))
      );
      router.push(`/shopping-lists/${toList.cacheUrl}`);
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
