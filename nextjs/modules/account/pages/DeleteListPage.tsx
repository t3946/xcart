import React from "react";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useDispatch } from "react-redux";
import { useSnackbar } from "@modules/account/hooks/useSnackbar";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { List } from "@modules/account/ts/types/list.type";
import { deleteList } from "@redux/actions/account-actions/ListsActions";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";

export const DeleteListPage: React.FC = () => {
  const dispatch = useDispatch();
  const router = useRouter();
  const { cache } = router.query;
  const snackbar = useSnackbar();

  const lists: List[] = useSelectorAccount((state) => state.lists.lists);

  const list = lists.find((e) => e.cacheUrl === cache);

  const onCancelClick = () => {
    router.push(`/shopping-lists/${cache}`);
  };

  const onRequestEnd = () => {
    snackbar.show(`${list.name} list deleted successfully`);
    router.push("/shopping-lists");
  };

  const handleDeleteList = () => {
    dispatch(deleteList(list.productListId, onRequestEnd));
  };

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
