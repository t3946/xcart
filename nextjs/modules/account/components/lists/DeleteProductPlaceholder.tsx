import React from "react";
import { useDispatch } from "react-redux";
import { undoDeleteProduct } from "@redux/actions/account-actions/ListsActions";
import { ListItem } from "@modules/account/ts/types/list.type";

interface IProps {
  name: string;
  listItem: ListItem;
}

export const DeleteProductPlaceholder: React.FC<IProps> = (props) => {
  const { name, listItem } = props;
  const dispatch = useDispatch();

  function undoDelete() {
    dispatch(
      undoDeleteProduct({ data: { list_item_id: listItem.list_item_id } })
    );
  }

  return (
    <div className="deleted-product-container w-100 d-none d-md-block">
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
