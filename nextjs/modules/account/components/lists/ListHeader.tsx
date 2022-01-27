import React, { useContext, Fragment } from "react";
import { useRouter } from "next/router";
import { useDialog } from "@modules/account/hooks/useDialog";
import { ShareListDialog } from "@modules/account/components/lists/ShareListDialog";
import { ManageList } from "@modules/account/components/lists/ManageList";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { List } from "@modules/account/ts/types/list.type";
import { deleteList } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import ShareIcon from "@modules/icon/components/account/share/ShareIcon";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface ListHeaderProps {
  isShoppingList: boolean;
}

export const ListHeader: React.FC<ListHeaderProps> = ({ isShoppingList }) => {
  const shareDialog = useDialog();
  const list: List = useSelectorAccount((state) => state.lists.listView);
  const manageListDialog = useDialog();

  const deleteListDialog = useDialog();

  const mobileMenuDialog = useDialog();

  const { showSnackbar } = useContext(SnackbarContext);

  const router = useRouter();

  const dispatch = useDispatch();

  const onRequestEnd = () => {
    showSnackbar({
      header: "Success",
      message: `${list.name} list deleted successfully`,
      theme: "success",
    });
  };
  const edit = list.role !== UserPrivateVariantsEnum.VIEW;

  const handleDeleteList = () => {
    // router.push("/your-lists");
    dispatch(deleteList(list.productListId, onRequestEnd));
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      label: "Manage list",
      onClick: () =>
        router.push(`/shopping-lists/action-list/manage-list/${list.cacheUrl}`),
    },
    {
      label: "Add idea",
      onClick: () =>
        router.push(`/shopping-lists/action-list/add-idea/${list.cacheUrl}`),
    },
    {
      label: "Share list with others",
      onClick: () =>
        router.push(`/shopping-lists/action-list/share-list/${list.cacheUrl}`),
    },
  ];
  const mobileItemDelete = {
    label: "Delete list",
    onClick: () =>
      router.push(`/shopping-lists/action-list/delete-list/${list.cacheUrl}`),
  };

  return (
    <div className="list-header-container">
      <div className="list-header-left-side">
        <img
          className="list-header-private-type-img"
          src={`/static/frontend/images/icons/account/list-${list.listType}.svg`}
        />
        <div className="list-header-name">{list.name}</div>
        {edit && (
          <img
            onClick={mobileMenuDialog.handleClickOpen}
            className="list-header-ellipsis"
            src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
          />
        )}

        <div className="list-header-actions">
          {edit && (
            <Fragment>
              <div
                onClick={manageListDialog.handleClickOpen}
                className="list-header-action-item blue"
              >
                Manage List
              </div>
              {!isShoppingList && (
                <div
                  onClick={deleteListDialog.handleClickOpen}
                  className="list-header-action-item red"
                >
                  Delete List
                </div>
              )}
            </Fragment>
          )}
        </div>
      </div>
      {edit && (
        <div className="list-header-shared-block">
          <ShareIcon className="list-header-share-btn blue" />
          <div
            className="list-header-share-text blue"
            onClick={shareDialog.handleClickOpen}
          >
            Share list with others
          </div>
        </div>
      )}
      <ShareListDialog
        open={shareDialog.open}
        handleClose={shareDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={manageListDialog.open}
        title={"Manage list"}
        onClose={manageListDialog.handleClose}
      >
        <ManageList info={list} onCancelClick={manageListDialog.handleClose} />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteListDialog.open}
        title={"Confirm delete list"}
        onClose={deleteListDialog.handleClose}
      >
        <ConfirmDelete
          deleteType="list"
          onDeleteClick={handleDeleteList}
          onCancelClick={deleteListDialog.handleClose}
        />
      </BootstrapDialogHOC>
      <MobileMenuForList
        items={
          isShoppingList
            ? mobileDialogItems
            : [...mobileDialogItems, mobileItemDelete]
        }
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
    </div>
  );
};
