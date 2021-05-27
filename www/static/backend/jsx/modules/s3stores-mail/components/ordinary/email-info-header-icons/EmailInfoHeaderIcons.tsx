import { IconConstruct } from "@s3stores-mail/components/simple/icon-construct/IconConstruct";
import React, { useContext } from "react";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import PrintIcon from "@material-ui/icons/Print";
import MarkunreadIcon from "@material-ui/icons/Markunread";
import StarIcon from "@material-ui/icons/Star";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { FavoriteButton } from "@s3stores-mail/components/simple";

export const EmailInfoHeaderIcons = () => {
  const { handleReply, handleForward, emailInfo, editFavorite } = useContext(
    EmailInfoContext
  );
  console.log(emailInfo);
  return (
    <React.Fragment>
      <IconConstruct onClick={handleReply} title="reply">
        <ReplyIcon />
      </IconConstruct>
      <IconConstruct onClick={handleForward} title="forward">
        <ForwardIcon />
      </IconConstruct>
      <IconConstruct title="print">
        <PrintIcon />
      </IconConstruct>
      <IconConstruct title="mark unread">
        <MarkunreadIcon />
      </IconConstruct>
      <FavoriteButton
        favorite={emailInfo.favorite}
        editFavorite={() => editFavorite(emailInfo.id)}
      />
    </React.Fragment>
  );
};
