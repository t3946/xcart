import React, { useContext } from "react";
import { FavoriteButton } from "@s3stores-mail/components/simple";
import { EditViewStateIcon } from "@s3stores-mail/components/simple/edit-view-state-icon/EditViewStateIcon";
import { EmailListHeaderContext } from "@s3stores-mail/contexts/email-list-header-context/EmailListHeader.context";
import RefreshIcon from "@material-ui/icons/Refresh";
import { IconConstruct } from "../../simple/icon-construct/IconConstruct";

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
      <EditViewStateIcon viewed={moreViewed} editView={editViewed} />
      <FavoriteButton favorite={moreFavorites} editFavorite={editFavorite} />
      <IconConstruct onClick={refreshEmails} title="Refresh">
        <RefreshIcon />
      </IconConstruct>
    </React.Fragment>
  );
};
