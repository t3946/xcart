import React from "react";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@redux/actions/account-actions/ListsActions";
import { ShareListInviteSection } from "@modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@modules/account/components/lists/ShareListManagePeople";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";

interface ShareList {
  onClose: () => void;
  cache: string;
}

export const ShareList: React.FC<ShareList> = ({ onClose, cache }) => {
  const snackbar = useSnackbar();

  const dispatch = useDispatch();

  const encodeUrl = (type: ShowSharedStatusEnum) => {
    dispatch(encryptUrl(type, cache, onUrlEncoded));
  };

  const onUrlEncoded = (url: string) => {
    navigator.clipboard
      .writeText(url)
      .then(() => {
        onClose();
        snackbar.show(`Url copied`);
      })
      .catch(() => {
        onClose();
        snackbar.show(`Something went wrong`, 3000, VariantsEnum.error);
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
