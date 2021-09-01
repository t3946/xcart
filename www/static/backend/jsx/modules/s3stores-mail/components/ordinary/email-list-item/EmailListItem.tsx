import React from "react";
import { Checkbox, Grid, Paper } from "@material-ui/core";
import CallMadeIcon from "@material-ui/icons/CallMade";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { ReadedSwitch } from "@s3stores-mail/components/simple";
import moment from "moment";
import CallReceivedIcon from "@material-ui/icons/CallReceived";

interface EmailListItemDto {
  name: string;
  theme: string;
  favorite: boolean;
  read: boolean;
  id: number;
  checked: boolean;
  index: number;
}

function editEmailListItemIcon(type: string) {
  switch (type) {
    case "inbox": {
      return <CallReceivedIcon />;
    }

    default: {
      return <CallMadeIcon />;
    }
  }
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
      <Grid zeroMinWidth alignItems="center" container>
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
        <Grid xs={3}>
          <div className="faxage-text">
            <span>
              {itemData.type === "inbox"
                ? itemData.from_address
                : itemData.to_address}
            </span>
          </div>
        </Grid>
        <Grid
          container
          justify="flex-start"
          xs={itemData.contains_action ? 3 : 5}
        >
          <div className="text-name">
            <span>{itemData.subject}</span>
          </div>
        </Grid>
        <Grid
          justify={"center"}
          container
          xs={itemData.contains_action ? 3 : 1}
        >
          {itemData.contains_action && (
            <ReadedSwitch
              actionName={itemData.action?.name}
              editAction={editAction}
              readed={itemData.action.action}
            />
          )}
        </Grid>

        <div className="date">
          <span>{moment(itemData.date).format("D MMM")}</span>
        </div>
        <div className={`message-type-wrap icon-${theme}`}>
          {editEmailListItemIcon(itemData.type)}
        </div>
      </Grid>
    </Paper>
  );
};

export const EmailListItem = React.memo(List);
