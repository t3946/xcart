import React, { useContext } from "react";
import ShareIcon from "@material-ui/icons/Share";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { deleteList } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { ShareListDialog } from "@client/modules/account/components/lists/ShareListDialog";
import { ManageList } from "@client/modules/account/components/lists/ManageList";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { DeleteList } from "@client/modules/account/components/lists/DeleteList";
import { MobileMenuForListItem } from "@client/modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@client/modules/account/components/lists/MobileMenuForList";

interface ListHeaderProps {
  label: string;
  shippingList: boolean;
  listId: string;
  edit: boolean;
  info: any;
}

export const ListHeader: React.FC<ListHeaderProps> = ({
  label,
  shippingList,
  listId,
  edit,
  info,
}) => {
  const dispatch = useDispatch();

  const shareDialog = useDialog();

  const manageListDialog = useDialog();

  const deleteListDialog = useDialog();

  const mobileMenuDialog = useDialog();

  const history = useHistory();

  const { showSnackbar } = useContext(SnackbarContext);

  const handleDeleteList = () => {
    history.push("/account/your-lists");
    dispatch(deleteList(listId, onRequestEnd));
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      label: "Manage list",
      onClick: () =>
        history.push(`/account/your-lists/manage-list/${info.cache_url}`),
    },
    {
      label: "Share list with others",
      onClick: () =>
        history.push(`/account/your-lists/${info.cache_url}/share-list`),
    },
    {
      label: "Delete list",
    },
  ];

  const onRequestEnd = () => {
    showSnackbar({
      header: "Success",
      message: `${label} list deleted successfully`,
      theme: "success",
    });
  };

  return (
    <div className="list-header-container">
      <div className="list-header-left-side">
        <div className="list-header-name">{label}</div>
        <img
          onClick={mobileMenuDialog.handleClickOpen}
          className="list-header-ellipsis"
          src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
        />
        <div className="list-header-actions">
          {edit && (
            <React.Fragment>
              <div
                onClick={manageListDialog.handleClickOpen}
                className="list-header-action-item blue"
              >
                Manage List
              </div>
              {shippingList && (
                <div
                  onClick={deleteListDialog.handleClickOpen}
                  className="list-header-action-item red"
                >
                  Delete List
                </div>
              )}
            </React.Fragment>
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
        <ManageList info={info} onCancelClick={manageListDialog.handleClose} />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteListDialog.open}
        title={"Confirm delete list"}
        onClose={deleteListDialog.handleClose}
      >
        <DeleteList
          confirmDelete={handleDeleteList}
          onCancelClick={deleteListDialog.handleClose}
        />
      </BootstrapDialogHOC>
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
    </div>
  );
};
