import { IconConstruct } from "@s3stores-mail/components/simple/icon-construct/IconConstruct";
import React, { useContext } from "react";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import PrintIcon from "@material-ui/icons/Print";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { EditViewStateIcon } from "@s3stores-mail/components/simple/edit-view-state-icon/EditViewStateIcon";
import ReactToPrint from "react-to-print";
import { useHistory } from "react-router-dom";
import { useSelector } from "react-redux";
import { StoreDto } from "../../../ts/types";

export const EmailInfoHeaderIcons: React.FC<any> = () => {
  const {
    handleReply,
    editViewed,
    handleForward,
    emailInfo,
    editFavorite,
    componentRef,
  } = useContext(EmailInfoContext);

  return (
    <React.Fragment>
      <IconConstruct onClick={handleReply} title="Reply">
        <ReplyIcon />
      </IconConstruct>
      <IconConstruct onClick={handleForward} title="Forward">
        <ForwardIcon />
      </IconConstruct>
      <ReactToPrint
        trigger={() => (
          <IconConstruct title="Print">
            <PrintIcon />
          </IconConstruct>
        )}
        content={() => componentRef.current}
      />
      <EditViewStateIcon
        viewed={emailInfo.viewed}
        editView={() => {
          editViewed();
        }}
      />
      <FavoriteButton
        favorite={emailInfo.favorite}
        editFavorite={editFavorite}
      />
    </React.Fragment>
  );
};
