import React from "react";
import { Checkbox, Grid, Paper } from "@material-ui/core";
import CallMadeIcon from "@material-ui/icons/CallMade";
import EditIcon from "@material-ui/icons/Edit";
import { EmailType } from "../../../ts/consts";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { ReadedSwitch } from "@s3stores-mail/components/simple";
import moment from "moment";

interface EmailListItemDto {
  name: string;
  theme: string;
  favorite: boolean;
  read: boolean;
  id: number;
  checked: boolean;
  index: number;
}

const List: React.FC<any> = ({
  itemData,
  theme,
  checked,
  handleClick,
  editFavorite,
  editAction,
  editChecked,
}) => {
  return (
    <Paper
      onClick={handleClick}
      square={true}
      className={`list-item-wrap ${theme}`}
    >
      <Grid alignItems="center" container>
        <Checkbox
          checked={checked}
          onClick={editChecked}
          className="checkbox"
          color="default"
        />
        <FavoriteButton
          editFavorite={editFavorite}
          favorite={itemData.favorite}
        />
        <div className="faxage-text">
          <span>FAXAGE</span>
        </div>
        <div className="text-name">
          <span>{itemData.subject}</span>
        </div>
        <ReadedSwitch
          actionName={itemData.action?.name}
          editAction={editAction}
          readed={itemData.action.action}
        />
        <div className="date">
          <span>{moment(itemData.date).format("D MMM")}</span>
        </div>
        <div className={`message-type-wrap icon-${theme}`}>
          {theme === EmailType.NOTE ? <EditIcon /> : <CallMadeIcon />}
        </div>
      </Grid>
    </Paper>
  );
};

export const EmailListItem = React.memo(List);
