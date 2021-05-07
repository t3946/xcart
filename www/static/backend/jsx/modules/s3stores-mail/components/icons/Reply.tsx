import React from "react";
import ReplyIcon from "@material-ui/icons/Reply";
import { IconButton, Tooltip } from "@material-ui/core";

export const ReplyIc = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <ReplyIcon />
      </IconButton>
    </Tooltip>
  );
};
