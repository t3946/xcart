import React, { Fragment } from "react";
import { Checkbox, Grid, Paper } from "@material-ui/core";
import CallMadeIcon from "@material-ui/icons/CallMade";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { ReadedSwitch } from "@s3stores-mail/components/simple";
import moment from "moment";
import CallReceivedIcon from "@material-ui/icons/CallReceived";
import { EmailListLabels } from "@s3stores-mail/components/ordinary/email-list-labels/email-list-labels";

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
      <div className="email-item-block">
        <div>
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
        </div>
        <div className="faxage-text">
          <span>
            {itemData.type === "inbox"
              ? itemData.from_address
              : itemData.to_address}
          </span>
        </div>
        <div
          className="subject-email-block"
          style={{
            maxWidth: itemData.contains_action ? 415 : 610,
            minWidth: itemData.contains_action ? 415 : 610,
          }}
        >
          <EmailListLabels labels={itemData.labels} />
          <span className="text-name">{itemData.subject}</span>
        </div>
        {itemData.contains_action && (
          <div className="reader-email-item">
            <ReadedSwitch
              actionName={itemData.action?.name}
              editAction={editAction}
              readed={itemData.action.action}
            />
          </div>
        )}
      </div>
      <div className="email-info-item">
        <div className="date">
          <span>{moment(itemData.date).format("D MMM")}</span>
        </div>
        <div className={`message-type-wrap icon-${theme}`}>
          {editEmailListItemIcon(itemData.type)}
        </div>
      </div>
    </Paper>
  );
};

export const EmailListItem = React.memo(List);
