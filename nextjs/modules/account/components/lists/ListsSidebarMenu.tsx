import React, { useEffect } from "react";
import { useDialog } from "@modules/account/hooks/useDialog";
import { CreateNewListDialog } from "@modules/account/components/lists/CreateNewListDialog";
import Item from "@modules/account/components/sidebar-menu/Item";
import { ListsSidebarLabel } from "@modules/account/components/lists/ListsSidebarLabel";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import ArrowBackIcon from "@modules/icon/components/account/arrows/ArrowBackIcon";
import { useDispatch } from "react-redux";
import { fetchLists } from "@redux/actions/account-actions/ListsActions";

import Styles from "@modules/account/components/lists/ListsSidebarMenu.module.scss";

interface IProps {
  list?: any;
}

export const ListsSidebarMenu: React.FC<IProps> = (props) => {
  const { list } = props;
  const router = useRouter();
  const createListDialog = useDialog();
  const storeLists = useSelectorAccount((e) => e.lists);
  const lists = storeLists.lists;
  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(fetchLists());
  }, []);

  const backOnAccount = () => {
    router.push("/dashboard");
  };

  return (
    <div>
      <button
        onClick={backOnAccount}
        className="form-button__outline back-on-account-btn cursor-pointer"
      >
        <div className="back-account-btn-inner">
          <ArrowBackIcon />
          <div>Back to account</div>
        </div>
      </button>

      <div className="lists-sidebar-label">Shopping Lists</div>
      {lists?.map((e, index) => (
        <Item
          to={`/shopping-lists/${e.product_list_id}`}
          label={
            <ListsSidebarLabel
              label={e.name}
              isPrivate={list.roles.length === 0}
            />
          }
          className={[
            "sidebar-menu-item__lists",
            "py-2",
            {
              [Styles.listItem_active]:
                e.product_list_id === list?.product_list_id,
            },
          ]}
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
