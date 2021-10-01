import React, { useContext } from "react";
import { useHistory, useParams } from "react-router-dom";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { ConfirmDelete } from "@client/modules/account/components/lists/ConfirmDelete";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";
import { deleteList } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";

interface ManageListPageURLParams {
  listHash: string;
}

export const DeleteListPage: React.FC = () => {
  const params = useParams<ManageListPageURLParams>();

  const dispatch = useDispatch();
  const { showSnackbar } = useContext(SnackbarContext);

  const history = useHistory();

  const lists = accountStore.getState().lists.lists;

  if (!lists) {
    history.push("/account/your-lists/");
  }

  const list = lists.find((e) => e.cache_url === params.listHash);

  const onCancelClick = () => {
    history.push(`/account/your-lists/${list.cache_url}`);
  };

  const onRequestEnd = () => {
    showSnackbar({
      header: "Success",
      message: `${list.name} list deleted successfully`,
      theme: "success",
    });
  };

  const handleDeleteList = () => {
    history.push("/account/your-lists");
    dispatch(deleteList(list.product_list_id, onRequestEnd));
  };

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/account/your-lists/${params.listHash}`}
        label={"back"}
      />
      <div className="page-label">Delete list</div>
      <ConfirmDelete
        onDeleteClick={handleDeleteList}
        onCancelClick={onCancelClick}
        deleteType="list"
      />
    </div>
  );
};
