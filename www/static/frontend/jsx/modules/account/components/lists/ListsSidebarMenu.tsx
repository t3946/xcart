import React, { useEffect } from "react";
import { Button } from "@material-ui/core";
import { useHistory } from "react-router";
import ArrowBackIcon from "@material-ui/icons/ArrowBack";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@client/modules/account/components/lists/CreateNewListDialog";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import { SideBarMenuItem } from "@client/modules/account/components/sidebar-menu/SideBarMenuItem";
import { getLists } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { ListsSidebarLabel } from "@client/modules/account/components/lists/ListsSidebarLabel";

export const ListsSidebarMenu: React.FC = () => {
  const history = useHistory();

  const createListDialog = useDialog();

  const dispatch = useDispatch();

  useEffect(() => {
    if (!lists) {
      dispatch(getLists());
    }
  });

  const lists = useSelector((e: StoreInterface) => e.lists.lists);

  const backOnAccount = () => {
    history.push("/account/");
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
          <SideBarMenuItem
            to={`/account/your-lists${!index ? "" : "/" + e.cache_url}`}
            label={
              <ListsSidebarLabel
                label={e.name}
                privateType={e.list_info.list_type}
              />
            }
            className={"sidebar-menu-item__lists"}
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
