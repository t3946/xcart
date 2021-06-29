import React from "react";
import { IconButton } from "@material-ui/core";
import MarkunreadIcon from "@material-ui/icons/Markunread";
import MailOutlineIcon from "@material-ui/icons/MailOutline";

export const EditViewStateIcon: React.FC<any> = ({ viewed, editView }) => {
  return (
    <IconButton onClick={editView}>
      {viewed ? <MailOutlineIcon /> : <MarkunreadIcon />}
    </IconButton>
  );
};
