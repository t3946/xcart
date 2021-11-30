import React from "react";
import { useHistory, useParams } from "react-router-dom";
import { CreateNewList } from "@modules/account/components/lists/CreateNewList";
import { addProduct } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface AddListPageURLParams {
  productId: string;
}

export const AddListPage: React.FC = () => {
  const history = useHistory();

  const params = useParams<AddListPageURLParams>();

  const dispatch = useDispatch();

  const onCancelClick = () => {
    if (params?.productId) {
      window.history.go(-1);
      return;
    }
    history.push(`/account/your-lists`);
  };

  const onCreateList = (list: any) => {
    if (!params?.productId) {
      history.push(`/account/your-lists/${list.cache_url}`);
      return;
    }
    dispatch(
      addProduct(
        list.product_list_id,
        window.appData?.product_info?.product?.productid,
        null,
        () =>
          window.location.assign(
            `/account/your-lists/add-product-to-list/false/${list.product_list_id}/${window.appData?.product_info?.product?.productcode}`
          )
      )
    );
  };

  return (
    <div>
      <MobileMenuBackBtn redirectUrl={`/account/your-lists`} label={"back"} />
      <div className="page-label">Create list</div>
      <CreateNewList
        productId={window.appData?.product_info?.product?.productid}
        onCancelBtnClick={onCancelClick}
        actionType={"product"}
        onCreateList={onCreateList}
      />
    </div>
  );
};
