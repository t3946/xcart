import React from "react";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { encryptUrl } from "@redux/actions/account-actions/ListsActions";
import { ShareListInviteSection } from "@modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@modules/account/components/lists/ShareListManagePeople";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import { AxiosResponse } from "axios";

interface ShareList {
  onClose: () => void;
  cache: string;
}

export const ShareList: React.FC<ShareList> = ({ onClose, cache }) => {
  const snackbar = useSnackbar();
  const dispatch = useDispatch();
  const [showSharedStatus, setShowSharedStatus] = React.useState(
    ShowSharedStatusEnum.VIEW
  );

  const [sharedLink, setSharedLink] = React.useState("");

  React.useEffect(() => {
    setSharedLink("");
    dispatch(
      encryptUrl({
        data: {
          hash: cache,
          role: showSharedStatus,
        },
        success(res: AxiosResponse) {
          const { tag, text } = res.data;
          const url = `http://${window.location.hostname}/account/shopping-lists/invite/${tag}/${text}`;
          setSharedLink(url);
        },
      })
    );
  }, [showSharedStatus]);

  const onCopyLink = (result: boolean) => {
    if (result) {
      onClose();
      snackbar.show(`Url copied`);
    } else {
      onClose();
      snackbar.show(`Something went wrong`, 3000, VariantsEnum.error);
    }
  };

  return (
    <div>
      <ShareListInviteSection
        showSharedStatus={showSharedStatus}
        sharedLink={sharedLink}
        setShowSharedStatus={setShowSharedStatus}
        onCopyLinkFunc={onCopyLink}
      />
      <hr className="share-list-center-line" />
      <ShareListManagePeople closeDialog={onClose} id={cache} />
    </div>
  );
};
