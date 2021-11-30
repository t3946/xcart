import React from "react";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { useHistory, useParams } from "react-router-dom";
import Store from "@redux/stores/Store";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface EditInfoInListProductPageURLParams {
  productId: string;
  listHash: string;
}

export const EditInfoInListProductPage: React.FC = () => {
  const params = useParams<EditInfoInListProductPageURLParams>();

  const history = useHistory();

  const lists = Store.getState().lists.lists;

  if (!lists) {
    history.push(`/account/your-lists/${params.listHash}`);
    return;
  }

  const list = lists.find((e) => e.cache_url === params.listHash);

  const product = list.products.find(
    (product) => product.product_id === params.productId
  );

  const onCloseClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${params.listHash}`}
        label={"back"}
      />
      <div className="page-label">Edit comment, quantity & priority</div>
      <EditComment
        info={product}
        productId={params.productId}
        listId={list.product_list_id}
        onCloseClick={onCloseClick}
      />
    </div>
  );
};
