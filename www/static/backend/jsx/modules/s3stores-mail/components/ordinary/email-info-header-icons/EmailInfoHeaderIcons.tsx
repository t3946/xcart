import { IconConstruct } from "@s3stores-mail/components/simple/icon-construct/IconConstruct";
import React, { useContext } from "react";
import ReplyIcon from "@material-ui/icons/Reply";
import ForwardIcon from "@material-ui/icons/Forward";
import PrintIcon from "@material-ui/icons/Print";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import ReactToPrint from "react-to-print";
import MailOutlineIcon from "@material-ui/icons/MailOutline";
import MarkunreadIcon from "@material-ui/icons/Markunread";
import ExpandLessIcon from "@material-ui/icons/ExpandLess";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import StarIcon from "@material-ui/icons/Star";
import StarBorderIcon from "@material-ui/icons/StarBorder";
import LabelOutlinedIcon from "@material-ui/icons/LabelOutlined";
import { EmailDto } from "@s3stores-mail/ts/types/email.type";
import { EmailThreadContext } from "@s3stores-mail/contexts/email-thread-context/EmailThread.context";
interface EmailInfoHeaderIcons {
  addLabel: { get: boolean; set: () => void };
}
export const EmailInfoHeaderIcons: React.FC<EmailInfoHeaderIcons> = ({
  addLabel,
}) => {
  const { handleReply, editViewed, handleForward, editFavoriteItem } =
    useContext(EmailInfoContext);
  const { emailInfo, templateRef, open } = useContext(EmailThreadContext);
  return (
    <React.Fragment>
      <IconConstruct onClick={() => handleReply(emailInfo)} title="Reply">
        <ReplyIcon />
      </IconConstruct>
      <IconConstruct onClick={() => handleForward(emailInfo)} title="Forward">
        <ForwardIcon />
      </IconConstruct>
      <ReactToPrint
        trigger={() => (
          <IconConstruct title="Print">
            <PrintIcon />
          </IconConstruct>
        )}
        content={() => templateRef.current}
      />
      <IconConstruct
        onClick={() => {
          editViewed();
        }}
        title={emailInfo.viewed ? "Make unviewed  " : "Make viewed"}
      >
        {emailInfo.viewed ? <MailOutlineIcon /> : <MarkunreadIcon />}
      </IconConstruct>
      <IconConstruct
        onClick={() => editFavoriteItem(emailInfo.id)}
        title={
          emailInfo.favorite ? "Remove from favorites" : "Add to favorites"
        }
      >
        {emailInfo.favorite ? (
          <StarIcon className="favorites" />
        ) : (
          <StarBorderIcon />
        )}
      </IconConstruct>
      <IconConstruct onClick={addLabel.set} title="Add label">
        <LabelOutlinedIcon />
      </IconConstruct>
      <IconConstruct
        onClick={() => open.set(emailInfo)}
        title={open.get ? "Hide Email" : "Open email"}
      >
        {open.get ? <ExpandLessIcon /> : <ExpandMoreIcon />}
      </IconConstruct>
    </React.Fragment>
  );
};
