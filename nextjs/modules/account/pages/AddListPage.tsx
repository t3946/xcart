import React from "react";
import { CreateNewList } from "@modules/account/components/lists/CreateNewList";
import { addProduct } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useRouter } from "next/router";

export const AddListPage: React.FC = () => {
  const router = useRouter();
  const { productId } = router.query;
  const dispatch = useDispatch();

  const onCancelClick = () => {
    if (productId) {
      window.history.go(-1);
      return;
    }
  };

  const onCreateList = (list: any) => {
    if (!productId) {
      router.push(`/shopping-lists/${list.cache_url}`);
      return;
    }
    dispatch(
      addProduct(
        list.product_list_id,
        window.appData?.product_info?.product?.productid,
        null,
        () =>
          window.location.assign(
            `/account/shopping-lists/add-product-to-list/false/${list.product_list_id}/${window.appData?.product_info?.product?.productcode}`
          )
      )
    );
  };

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/shopping-lists`} label={"back"} />
      <div className="page-label">Create list</div>
      <CreateNewList
        onCancelBtnClick={onCancelClick}
        actionType="product"
        onCreateList={onCreateList}
      />
    </div>
  );
};
