import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { useHistory, useParams } from "react-router-dom";
import { RadioBtn } from "@client/modules/account/components/shared/RadioBtn";
import { moveProduct } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";

interface MoveProductPageURLProps {
  productId: string;
  listId: string;
}

export const MoveProductPage = () => {
  const lists = useSelector((e: AccountStore) => e.lists.lists);

  const history = useHistory();

  if (!lists) {
    history.push(`/account/your-lists`);
    return;
  }

  const params = useParams<MoveProductPageURLProps>();

  const list = lists.find((e) => e.product_list_id === params.listId);

  const product = list.products.find((e) => e.product_id === params.productId);

  const dispatch = useDispatch();

  const onChange = (value) => {
    history.push(
      `/account/your-lists/${
        lists.find((e) => e.product_list_id === params.listId).cache_url
      }`
    );
    dispatch(moveProduct(params.listId, { value }, product));
  };

  return (
    <div>
      <div className="page-label">Move product</div>
      {lists.map((e) => {
        return (
          <RadioBtn
            name="radio"
            id={"radio-item-view"}
            viewValue={<div className="move-product-label">{e.name}</div>}
            groupClasses={{
              group: "share-list-radio",
            }}
            groupValue={params.listId}
            radioValue={e.product_list_id}
            onChange={onChange}
          />
        );
      })}
    </div>
  );
};
