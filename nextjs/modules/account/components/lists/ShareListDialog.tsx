import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ShareList } from "@modules/account/components/lists/ShareList";

interface IProps {
  list: any;
  open: boolean;
  handleClose: () => void;
}

export const ShareListDialog: React.FC<IProps> = (props) => {
  const { list, open, handleClose } = props;

  return (
    <BootstrapDialogHOC
      show={open}
      title={"Share list with others"}
      onClose={handleClose}
    >
      <ShareList onClose={handleClose} cache={list.cache_url} />
    </BootstrapDialogHOC>
  );
};
