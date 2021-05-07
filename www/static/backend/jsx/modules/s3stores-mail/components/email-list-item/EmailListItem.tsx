import React, { useState } from "react";
import { Checkbox, Grid, IconButton, Paper } from "@material-ui/core";
import StarBorderIcon from "@material-ui/icons/StarBorder";
import StarIcon from "@material-ui/icons/Star";
import { ReadedSwitch } from "../readed-switch/ReadedSwitch";
import CallMadeIcon from "@material-ui/icons/CallMade";
import EditIcon from "@material-ui/icons/Edit";
import { EmailType } from "../../ts/consts/email-type.const";
import { useHistory } from "react-router-dom";

export const EmailListItem = ({ name, theme, favorite, read, id }) => {
  const [favorites, setFavorites] = useState(favorite);

  const history = useHistory();

  const favoritesHandleClick = () => {
    setFavorites(!favorites);
  };

  const handleClick = () => {
    history.push(`/admin/forms/email-dashboard/email-info/${id}`);
  };

  return (
    <Paper
      onClick={handleClick}
      square={true}
      className={`list-item-wrap ${theme}`}
    >
      <Grid container>
        <Checkbox className="checkbox" color="default" />
        <IconButton onClick={favoritesHandleClick}>
          {favorites ? <StarIcon className="favorites" /> : <StarBorderIcon />}
        </IconButton>
        <div className="faxage-text">
          <span>FAXAGE</span>
        </div>
        <div className="text-name">
          <span>{name}</span>
        </div>
        <ReadedSwitch readed={read} />
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
