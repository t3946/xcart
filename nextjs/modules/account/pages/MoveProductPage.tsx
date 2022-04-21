import React from "react";
import { useDispatch } from "react-redux";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import { transferProductList } from "@redux/actions/account-actions/ListsActions";
import { checkProductCollisionInList } from "@modules/account/utils/check-product-collision-in-list";

interface IProps {
  list: any;
  listItem: any;
}

export const MoveProductPage: React.FC<IProps> = (props) => {
  const { list, listItem } = props;
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);
  const snackbar = useSnackbar();
  const dispatch = useDispatch();

  function onChange(value: number) {
    if (value === list.product_list_id) {
      return;
    }

    const toList = lists.find((list) => list.product_list_id === value);

    if (!toList) {
      router.push("/shopping-lists");
      return;
    }

    if (checkProductCollisionInList(list, toList, listItem.list_item_id)) {
      dispatch(
        transferProductList({
          data: {
            product_list_id: toList.product_list_id,
            list_item_id: listItem.list_item_id,
          },
        })
      );

      router.push(`/shopping-lists/${toList.product_list_id}`);
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
      {lists
        .filter((item) => item.product_list_id !== list.product_list_id)
        .map((list) => (
          <RadioBtn
            name="radio"
            key={`${list.product_list_id}`}
            id="radio-item-view"
            viewValue={<div className="move-product-label">{list.name}</div>}
            groupClasses={{
              group: ["share-list-radio", "move-product-radio"],
            }}
            groupValue={props.list.product_list_id}
            radioValue={list.product_list_id}
            onChange={onChange}
          />
        ))}
    </div>
  );
};
