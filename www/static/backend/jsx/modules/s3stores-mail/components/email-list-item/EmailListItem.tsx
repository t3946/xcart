import React, { useEffect } from "react";
import { Checkbox, Grid, Paper } from "@material-ui/core";
import { ReadedSwitch } from "../readed-switch/ReadedSwitch";
import CallMadeIcon from "@material-ui/icons/CallMade";
import EditIcon from "@material-ui/icons/Edit";
import { EmailType } from "@s3stores-mail/ts/consts";
import { useHistory } from "react-router-dom";
import { FavoriteButton } from "../fovorite-button/FavoriteButton";
import { useDispatch, useSelector } from "react-redux";
import { editActions, editCheckedItems, editFavorites } from "@redux/actions";
import { StoreDto } from "@s3stores-mail/ts/types";
import { emailStore } from "@redux/stores";

interface EmailListItemDto {
  name: string;
  theme: string;
  favorite: boolean;
  read: boolean;
  id: number;
  checked: boolean;
  index: number;
}

const List: React.FC<EmailListItemDto> = ({
  name,
  theme,
  favorite,
  read,
  id,
  checked,
  index,
}) => {
  const history = useHistory();

  const dispatch = useDispatch();

  const handleClick = () => {
    history.push(`/admin/forms/email-dashboard/email-info/${id}`);
  };

  const editFavorite = (e) => {
    e.stopPropagation();
    dispatch(editFavorites([id]));
  };

  const editAction = (e) => {
    e.stopPropagation();
    dispatch(editActions([id]));
  };
  return (
    <Paper
      onClick={handleClick}
      square={true}
      className={`list-item-wrap ${theme}`}
    >
      <Grid alignItems="center" container>
        <Checkbox
          checked={checked}
          onClick={(e) => {
            e.stopPropagation();
            if (e.shiftKey) {
              dispatch(editCheckedItems(index, true));
              return;
            }
            dispatch(editCheckedItems(index, false));
          }}
          className="checkbox"
          color="default"
        />
        <FavoriteButton editFavorite={editFavorite} favorite={favorite} />
        <div className="faxage-text">
          <span>FAXAGE</span>
        </div>
        <div className="text-name">
          <span>{name}</span>
        </div>
        <ReadedSwitch editAction={editAction} readed={read} />
        <div className="date">
          <span>Apr 14</span>
        </div>
        <div className={`message-type-wrap icon-${theme}`}>
          {theme === EmailType.NOTE ? <EditIcon /> : <CallMadeIcon />}
        </div>
      </Grid>
    </Paper>
  );
};

export const EmailListItem = React.memo(List);
