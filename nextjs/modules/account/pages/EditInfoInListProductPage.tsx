import React from "react";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { List } from "@modules/account/ts/types/list.type";
import { useRouter } from "next/router";

export const EditInfoInListProductPage: React.FC = () => {
  const router = useRouter();
  const { productId, productListId } = router.query;
  const lists: List[] = useSelectorAccount((state) => state.lists.lists);

  const list = lists.find(
    (list) => list.productListId === Number(productListId)
  );

  const product = list.products.find(
    (product) => product.productId === Number(productId)
  );

  const onCloseClick = () => {
    router.push(`/shopping-lists/${list.cacheUrl}`);
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${list.cacheUrl}`}
        label={"back"}
      />
      <div className="page-label">Edit comment, quantity & priority</div>
      <EditComment
        info={product}
        productId={productId}
        listId={list?.productListId}
        onCloseClick={onCloseClick}
      />
    </div>
  );
};
