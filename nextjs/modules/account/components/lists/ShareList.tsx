import React from "react";
import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { useDispatch } from "react-redux";
import { getInvite } from "@redux/actions/account-actions/ListsActions";
import { ShareListInviteSection } from "@modules/account/components/lists/ShareListInviteSection";
import { ShareListManagePeople } from "@modules/account/components/lists/ShareListManagePeople";
import useSnackbar, { VariantsEnum } from "@modules/account/hooks/useSnackbar";
import { AxiosResponse } from "axios";

interface IProps {
  onClose: () => void;
  list: Record<any, any>;
}

export const ShareList: React.FC<IProps> = (props) => {
  const { onClose, list } = props;
  const snackbar = useSnackbar();
  const dispatch = useDispatch();
  const [showSharedStatus, setShowSharedStatus] = React.useState(
    ShowSharedStatusEnum.VIEW
  );

  const [sharedLink, setSharedLink] = React.useState("");

  React.useEffect(() => {
    setSharedLink("");

    dispatch(
      getInvite({
        data: {
          product_list_id: list.product_list_id,
          role: showSharedStatus,
        },
        success(res: AxiosResponse) {
          const { iv, content } = res.data;
          const url = `${window.location.origin}/account/shopping-lists/invite/${iv}/${content}`;
          setSharedLink(url);
        },
      })
    );
  }, [showSharedStatus]);

  const onCopyLink = (result: boolean) => {
    if (result) {
      onClose();
      snackbar.show(`Link copied`);
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
      <ShareListManagePeople closeDialog={onClose} list={list} />
    </div>
  );
};
