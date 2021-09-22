import React from "react";
import { EditComment } from "@client/modules/account/components/lists/EditComment";
import { useHistory, useParams } from "react-router-dom";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";

interface EditInfoInListProductPageURLParams {
  productId: string;
  listId: string;
}

export const EditInfoInListProductPage: React.FC = () => {
  const params = useParams<EditInfoInListProductPageURLParams>();

  const history = useHistory();

  const list = accountStore
    .getState()
    .lists.lists.find((e) => e.product_list_id === params.listId);

  const product = list.products.find(
    (product) => product.product_id === params.productId
  );

  const onCloseClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <div className="page-label">Edit comment, quantity & priority</div>
      <EditComment
        info={product}
        productId={params.productId}
        listId={params.listId}
        onCloseClick={onCloseClick}
      />
    </div>
  );
};
