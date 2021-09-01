import React, { useContext } from "react";
import ShareIcon from "@material-ui/icons/Share";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { deleteList } from "@client/jsx/redux/actions/account-actions/ListsActions";

interface ListHeaderProps {
  label: string;
  shippingList: boolean;
  listId: string;
}

export const ListHeader: React.FC<ListHeaderProps> = ({
  label,
  shippingList,
  listId,
}) => {
  const dispatch = useDispatch();

  const history = useHistory();

  const { showSnackbar } = useContext(SnackbarContext);

  const handleDeleteList = () => {
    dispatch(deleteList(listId, onRequestEnd));
  };

  const onRequestEnd = () => {
    history.push("/account/your-lists/");
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
          <div className="list-header-action-item blue">Manage List</div>
          {shippingList && (
            <div
              onClick={handleDeleteList}
              className="list-header-action-item red"
            >
              Delete List
            </div>
          )}
        </div>
      </div>

      <div className="list-header-shared-block">
        <ShareIcon className="list-header-share-btn blue" />
        <div className="list-header-share-text blue">
          Share list with others
        </div>
      </div>
    </div>
  );
};
