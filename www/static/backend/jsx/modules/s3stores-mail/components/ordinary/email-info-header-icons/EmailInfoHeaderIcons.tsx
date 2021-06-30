import { IconConstruct } from "@s3stores-mail/components/simple/icon-construct/IconConstruct";
import React, { useContext } from "react";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import PrintIcon from "@material-ui/icons/Print";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { EditViewStateIcon } from "@s3stores-mail/components/simple/edit-view-state-icon/EditViewStateIcon";
import ReactToPrint from "react-to-print";
import MailOutlineIcon from "@material-ui/icons/MailOutline";
import MarkunreadIcon from "@material-ui/icons/Markunread";
import { IconButton } from "@material-ui/core";
import StarIcon from "@material-ui/icons/Star";
import StarBorderIcon from "@material-ui/icons/StarBorder";

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
      <IconConstruct
        onClick={() => {
          editViewed();
        }}
        title={emailInfo.item.viewed ? "Make unviewed  " : "Make viewed"}
      >
        {emailInfo.item.viewed ? <MailOutlineIcon /> : <MarkunreadIcon />}
      </IconConstruct>
      <IconConstruct
        onClick={editFavorite}
        title={
          emailInfo.item.favorite ? "Remove from favorites" : "Add to favorites"
        }
      >
        {emailInfo.item.favorite ? (
          <StarIcon className="favorites" />
        ) : (
          <StarBorderIcon />
        )}
      </IconConstruct>
    </React.Fragment>
  );
};
