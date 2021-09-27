import React from "react";
import { useDispatch } from "react-redux";
import { undoDeleteProduct } from "@client/jsx/redux/actions/account-actions/ListsActions";

export const DeleteProductPlaceholder = ({
  name,
  product,
  list_items_id,
  product_list_id,
}) => {
  const dispatch = useDispatch();

  const undoDelete = () => {
    dispatch(undoDeleteProduct(product_list_id, list_items_id, product));
  };
  return (
    <div className="deleted-product-container">
      <div className="deleted-product-content">
        <p className="delete-product-name">{name}</p>
        <div className="deleted-product-actions">
          <div className={"d-flex"}>
            <img
              src={"/static/frontend/images/icons/account/check-mark-red.svg"}
            />
            <div className="deleted-product-label">Deleted</div>
          </div>

          <div onClick={undoDelete} className="list-name">
            Undo
          </div>
        </div>
      </div>
    </div>
  );
};
