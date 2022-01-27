import React, { useEffect } from "react";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@modules/account/components/lists/CreateNewListDialog";
import Item from "@modules/account/components/sidebar-menu/Item";
import { ListsSidebarLabel } from "@modules/account/components/lists/ListsSidebarLabel";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import ArrowBackIcon from "@modules/icon/components/account/arrows/ArrowBackIcon";
import { List } from "@modules/account/ts/types/list.type";
import { useDispatch } from "react-redux";
import { fetchLists } from "@redux/actions/account-actions/ListsActions";

export const ListsSidebarMenu: React.FC = () => {
  const router = useRouter();
  const createListDialog = useDialog();
  const lists: List[] = useSelectorAccount((e) => e.lists.lists);
  const dispatch = useDispatch();
  useEffect(() => {
    dispatch(fetchLists());
  }, []);
  const backOnAccount = () => {
    router.push("/");
  };

  return (
    <div>
      <button
        onClick={backOnAccount}
        className="form-button__outline back-on-account-btn"
      >
        <div className="back-account-btn-inner">
          <ArrowBackIcon />
          <div>Back on account</div>
        </div>
      </button>
      <div className="lists-sidebar-label">Shopping Lists</div>
      {lists?.map((e, index) => (
        <Item
          to={`/shopping-lists/${e.cacheUrl}`}
          label={<ListsSidebarLabel label={e.name} privateType={e.listType} />}
          className={"sidebar-menu-item__lists"}
          key={index}
        />
      ))}

      <div
        onClick={createListDialog.handleClickOpen}
        className="create-list-btn-container"
      >
        <div className="sidebar-list-cross">
          <img src="/static/frontend/images/icons/account/plus.svg" />
        </div>
        <div className="create-list-label">create a list</div>
      </div>

      <CreateNewListDialog
        open={createListDialog.open}
        handleClose={createListDialog.handleClose}
      />
    </div>
  );
};

export default ListsSidebarMenu;
