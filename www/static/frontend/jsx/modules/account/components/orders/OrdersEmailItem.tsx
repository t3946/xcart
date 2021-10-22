import CallReceivedIcon from "@material-ui/icons/CallReceived";
import CallMadeIcon from "@material-ui/icons/CallMade";
import React from "react";
import { Grid, Paper } from "@material-ui/core";
import moment from "moment";

interface ItemProps {
  theme: string;
  itemData: any;
  handleClick: () => void;
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

const Item: React.FC<ItemProps> = ({ itemData, theme, handleClick }) => {
  return (
    <Paper
      onClick={handleClick}
      square={true}
      className={`list-item-wrap ${theme}`}
    >
      <Grid
        zeroMinWidth
        alignItems="center"
        justifyContent="space-between"
        container
      >
        <Grid xs={3}>
          <div className="faxage-text">
            <span>
              {itemData.type === "inbox"
                ? itemData.from_address
                : itemData.to_address}
            </span>
          </div>
        </Grid>
        <Grid container justifyContent="flex-start" xs={5}>
          <div className="text-name">
            <span>{itemData.subject}</span>
          </div>
        </Grid>
        <Grid justifyContent={"center"} container xs={1} />
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

export const OrdersEmailItem = React.memo(Item);
