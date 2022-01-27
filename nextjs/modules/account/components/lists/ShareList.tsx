import React, { useContext } from "react";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@redux/actions/account-actions/ListsActions";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import { ShareListInviteSection } from "@modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@modules/account/components/lists/ShareListManagePeople";

interface ShareList {
  onClose: () => void;
  cache: string;
}

export const ShareList: React.FC<ShareList> = ({ onClose, cache }) => {
  const { showSnackbar } = useContext(SnackbarContext);

  const dispatch = useDispatch();

  const encodeUrl = (type: ShowSharedStatusEnum) => {
    dispatch(encryptUrl(type, cache, onUrlEncoded));
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
      <ShareListManagePeople closeDialog={onClose} id={cache} />
    </div>
  );
};
