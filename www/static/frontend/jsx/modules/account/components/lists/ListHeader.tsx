import React, { useContext } from "react";
import ShareIcon from "@material-ui/icons/Share";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { deleteList } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import { ShareListDialog } from "@client/modules/account/components/lists/ShareListDialog";

interface ListHeaderProps {
  label: string;
  shippingList: boolean;
  listId: string;
  edit: boolean;
}

export const ListHeader: React.FC<ListHeaderProps> = ({
  label,
  shippingList,
  listId,
  edit,
}) => {
  const dispatch = useDispatch();

  const shareDialog = useDialog();

  const history = useHistory();

  const { showSnackbar } = useContext(SnackbarContext);

  const handleDeleteList = () => {
    history.push("/account/your-lists");
    dispatch(deleteList(listId, onRequestEnd));
  };

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
        <div className="list-header-actions">
          {edit && (
            <React.Fragment>
              <div className="list-header-action-item blue">Manage List</div>
              {shippingList && (
                <div
                  onClick={handleDeleteList}
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
    </div>
  );
};
