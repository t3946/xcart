import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ShareList } from "@modules/account/components/lists/ShareList";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface ShareListDialogProps {
  open: boolean;
  handleClose: () => void;
}

export const ShareListDialog: React.FC<ShareListDialogProps> = ({
  open,
  handleClose,
}) => {
  const { cacheUrl } = useSelectorAccount((state) => state.lists.listView);
  return (
    <BootstrapDialogHOC
      show={open}
      title={"Share list with others"}
      onClose={handleClose}
    >
      <ShareList onClose={handleClose} cache={cacheUrl} />
    </BootstrapDialogHOC>
  );
};
