import React from "react";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@modules/account/components/lists/CreateNewListDialog";
import Item from "@modules/account/components/sidebar-menu/Item";
import { ListsSidebarLabel } from "@modules/account/components/lists/ListsSidebarLabel";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import ArrowBackIcon from "@modules/icon/components/account/arrows/ArrowBackIcon";

export const ListsSidebarMenu: React.FC = () => {
  const router = useRouter();
  const createListDialog = useDialog();
  const lists = useSelectorAccount((e) => e.lists.lists);

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
      {lists?.map((e, index) => {
        return (
          <Item
            to={`/account/your-lists${!index ? "" : "/" + e.cache_url}`}
            label={
              <ListsSidebarLabel
                label={e.name}
                privateType={e.list_info.list_type}
              />
            }
            className={"sidebar-menu-item__lists"}
            key={index}
          />
        );
      })}

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
