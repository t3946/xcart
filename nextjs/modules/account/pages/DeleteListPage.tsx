import React from "react";
import { useHistory, useParams } from "react-router-dom";
import Store from "@redux/stores/Store";
import { DeleteList } from "@modules/account/components/lists/DeleteList";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

interface ManageListPageURLParams {
  listHash: string;
}

export const DeleteListPage: React.FC = () => {
  const params = useParams<ManageListPageURLParams>();

  const dispatch = useDispatch();
  const { showSnackbar } = useContext(SnackbarContext);

  const history = useHistory();

  const lists = Store.getState().lists.lists;

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
