import React from "react";
import { useDispatch } from "react-redux";
import { undoDeleteProduct } from "@redux/actions/account-actions/ListsActions";
import { ListItem } from "@modules/account/ts/types/list.type";

interface DeleteProductPlaceholderProps {
  name: string;
  product: ListItem;
  listItemId: string;
  productListId: string;
}

export const DeleteProductPlaceholder: React.FC<DeleteProductPlaceholderProps> =
  ({ name, product, listItemId, productListId }) => {
    const dispatch = useDispatch();

    const undoDelete = () => {
      dispatch(undoDeleteProduct(productListId, listItemId, product));
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
