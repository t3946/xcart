import React from "react";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@redux/actions/account-actions/ListsActions";
import { ShareListInviteSection } from "@modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@modules/account/components/lists/ShareListManagePeople";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import {AxiosResponse} from "axios";

interface ShareList {
  onClose: () => void;
  cache: string;
}

export const ShareList: React.FC<ShareList> = ({ onClose, cache }) => {
  const snackbar = useSnackbar();

  const dispatch = useDispatch();

  const encodeUrl = (privateType: ShowSharedStatusEnum) => {
    // dispatch(encryptUrl(privateType, hash, onUrlEncoded));
    dispatch(
      encryptUrl({
        data: {
          hash: cache,
          privateType,
        },
        success(res: AxiosResponse) {
          const { tag, text } = res.data;
          const url = `http://${window.location.hostname}/account/shopping-lists/invite/${tag}/${text}`;

          window.navigator.clipboard
            .writeText(url)
            .then(() => {
              onClose();
              snackbar.show(`Url copied`);
            })
            .catch(() => {
              onClose();
              snackbar.show(`Something went wrong`, 3000, VariantsEnum.error);
            });
        },
      })
    );
  };

  return (
    <div>
      <ShareListInviteSection onCopyLinkFunc={encodeUrl} />
      <hr className="share-list-center-line" />
      <ShareListManagePeople closeDialog={onClose} id={cache} />
    </div>
  );
};
