import React, { useContext, useState } from "react";
import { ShareListBlock } from "@client/modules/account/components/lists/ShareListBlock";
import { ShowSharedStatusEnum } from "@client/modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@client/jsx/redux/actions/account-actions/ListsActions";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import { ShareListInviteSection } from "@client/modules/account/components/lists/ShareListInviteSection";

interface ShareListProps {
  onClose: () => void;
}

export const ShareList: React.FC<ShareListProps> = ({ onClose }) => {
  const { showSnackbar } = useContext(SnackbarContext);

  const dispatch = useDispatch();

  const encodeUrl = (type: ShowSharedStatusEnum) => {
    dispatch(encryptUrl(type, onUrlEncoded));
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
    </div>
  );
};
