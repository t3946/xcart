import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import StarIcon from "@material-ui/icons/Star";
import { useDispatch } from "react-redux";
import { editFavorites } from "@redux/actions";
import { emailStore } from "@redux/stores";

export const StarIc: React.FC = () => {
  const dispatch = useDispatch();
  return (
    <Tooltip title="Delete">
      <IconButton
        onClick={() =>
          dispatch(editFavorites(emailStore.getState().checkedItems))
        }
      >
        <StarIcon />
      </IconButton>
    </Tooltip>
  );
};
