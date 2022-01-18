import React, { useContext } from "react";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useHistory, useParams } from "react-router-dom";
import { RadioBtn } from "@modules/account/components/shared/RadioBtn";
import { moveProduct } from "@redux/actions/account-actions/ListsActions";
import Store from "@redux/stores/Store";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";

interface MoveProductPageURLProps {
  productId: string;
  listId: string;
}

export const MoveProductPage: React.FC = () => {
  const lists = useSelector((e: StoreInterface) => e.lists.lists);

  const history = useHistory();

  if (!lists) {
    history.push(`/account/your-lists`);
    return;
  }

  const { showSnackbar } = useContext(SnackbarContext);

  const params = useParams<MoveProductPageURLProps>();

  const list = lists.find((e) => e.product_list_id === params.listId);

  const product = list.products.find((e) => e.product_id === params.productId);

  const dispatch = useDispatch();

  const onChange = (value) => {
    if (value === params.listId) {
      return;
    }
    const toList = Store.getState().lists.lists.find(
      (e) => e.product_list_id === value
    );

    const productOnList = toList.products.find(
      (e) => e.product_id === product.product_id
    );
    if (productOnList) {
      showSnackbar({
        header: "Error",
        message: `This item already added to list`,
        theme: "error",
      });
      return;
    }
    history.push(
      `/account/your-lists/${
        lists.find((e) => e.product_list_id === params.listId).cache_url
      }`
    );
    dispatch(moveProduct(params.listId, { value }, product));
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${list.cache_url}`}
        label={"back"}
      />
      <div className="page-label">Move product</div>
      {lists.map((e) => {
        return (
          <RadioBtn
            name="radio"
            id={"radio-item-view"}
            viewValue={<div className="move-product-label">{e.name}</div>}
            groupClasses={{
              group: ["share-list-radio", "move-product-radio"],
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
