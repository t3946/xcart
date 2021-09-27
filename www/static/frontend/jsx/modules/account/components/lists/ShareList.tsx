import React, { useContext } from "react";
import { ShowSharedStatusEnum } from "@client/modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { ShareListInviteSection } from "@client/modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@client/modules/account/components/lists/ShareListManagePeople";
import { useParams } from "react-router-dom";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { List } from "@client/modules/account/ts/types/list.type";

interface ShareListProps {
  onClose: () => void;
}

export const ShareList: React.FC<ShareListProps> = ({ onClose }) => {
  const { showSnackbar } = useContext(SnackbarContext);

  const { id }: { id: string } = useParams();

  let list: List | undefined;

  if (!id) {
    list = accountStore.getState().lists.lists[0];
  }

  const dispatch = useDispatch();

  const encodeUrl = (type: ShowSharedStatusEnum) => {
    dispatch(encryptUrl(type, id, onUrlEncoded));
  };

  const onUrlEncoded = (url: string) => {
    navigator.clipboard
      .writeText(url)
      .then(() => {
        onClose();
        showSnackbar({
          header: "Success",
          message: `Url copied`,
          theme: "success",
        });
      })
      .catch(() => {
        onClose();
        showSnackbar({
          header: "Error",
          message: `Something went wrong`,
          theme: "error",
        });
      });
  };

  return (
    <div>
      <ShareListInviteSection onCopyLinkFunc={encodeUrl} />
      <hr className="share-list-center-line" />
      <ShareListManagePeople closeDialog={onClose} id={id || list.cache_url} />
    </div>
  );
};
