import React from "react";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useDispatch } from "react-redux";
import { useSnackbar } from "@modules/account/hooks/useSnackbar";
import { useRouter } from "next/router";
import { deleteList } from "@redux/actions/account-actions/ListsActions";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";

interface IProps {
  list: any;
}

export const DeleteListPage: React.FC<IProps> = (props) => {
  const dispatch = useDispatch();
  const router = useRouter();
  const { cache } = router.query;
  const snackbar = useSnackbar();
  const { list } = props;

  function onCancelClick() {
    router.push(`/shopping-lists/${cache}`);
  }

  function onRequestEnd() {
    snackbar.show(`${list.name} list deleted successfully`);
    router.push("/shopping-lists");
  }

  function handleDeleteList() {
    dispatch(deleteList(list.productListId, onRequestEnd));
  }

  return (
    <div>
      <MobileMenuBackBtn
        redirectUrl={`/shopping-lists/${cache}`}
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
