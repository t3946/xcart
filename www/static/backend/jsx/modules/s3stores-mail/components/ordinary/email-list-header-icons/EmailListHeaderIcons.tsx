import React, { useContext } from "react";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { EditViewStateIcon } from "@s3stores-mail/components/simple/edit-view-state-icon/EditViewStateIcon";
import { EmailListHeaderContext } from "@s3stores-mail/contexts/email-list-header-context/EmailListHeader.context";
import RefreshIcon from "@material-ui/icons/Refresh";
import { IconConstruct } from "../../simple/icon-construct/IconConstruct";
import MailOutlineIcon from "@material-ui/icons/MailOutline";
import MarkunreadIcon from "@material-ui/icons/Markunread";
import StarIcon from "@material-ui/icons/Star";
import StarBorderIcon from "@material-ui/icons/StarBorder";

export const EmailInfoHeaderIcons: React.FC = () => {
  const {
    editViewed,
    moreFavorites,
    moreViewed,
    editFavorite,
    refreshEmails,
  } = useContext(EmailListHeaderContext);
  return (
    <React.Fragment>
      <IconConstruct
        onClick={() => {
          editViewed();
        }}
        title={moreViewed ? "Make unviewed  " : "Make viewed"}
      >
        {moreViewed ? <MailOutlineIcon /> : <MarkunreadIcon />}
      </IconConstruct>
      <IconConstruct
        onClick={editFavorite}
        title={moreFavorites ? "Remove from favorites" : "Add to favorites"}
      >
        {moreFavorites ? (
          <StarIcon className="favorites" />
        ) : (
          <StarBorderIcon />
        )}
      </IconConstruct>
      <IconConstruct onClick={refreshEmails} title="Refresh">
        <RefreshIcon />
      </IconConstruct>
    </React.Fragment>
  );
};
