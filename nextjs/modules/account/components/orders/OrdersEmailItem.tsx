import CallReceivedIcon from "@modules/icon/components/account/call/CallReceivedIcon";
import CallMadeIcon from "@modules/icon/components/account/call/CallMadeIcon";
import React from "react";

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
    <div onClick={handleClick} className={`list-item-wrap ${theme}`}>
      <div className="d-flex align-items-center justify-content-between">
        <div>
          <div className="faxage-text">
            <span>
              {itemData.type === "inbox"
                ? itemData.from_address
                : itemData.to_address}
            </span>
          </div>
        </div>
        <div className="d-flex  justify-content-between">
          <div className="text-name">
            <span>{itemData.subject}</span>
          </div>
        </div>
        <div className="d-flex justify-content-between" />
        <div className="date">
          <span>{new Date(itemData.date)}</span>
        </div>
        <div className={`message-type-wrap icon-${theme}`}>
          {editEmailListItemIcon(itemData.type)}
        </div>
      </div>
    </div>
  );
};

export const OrdersEmailItem = React.memo(Item);
