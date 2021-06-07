import React, { useContext } from "react";
import { EmailListItem } from "@s3stores-mail/components/ordinary/email-list-item/EmailListItem";
import { addStyleToViewed, emailStyle } from "@s3stores-mail/utils";
import { EmailLIstContext } from "@s3stores-mail/contexts/email-list-context/EmailLIst.context";
export const EmailList: React.FC<any> = ({ emails }) => {
  const { handleItemClick, editFavorite, editAction, editChecked } = useContext(
    EmailLIstContext
  );

  return (
    <React.Fragment>
      {emails.map((email, index) => {
        return (
          <EmailListItem
            theme={
              addStyleToViewed(email.item.viewed) +
              " " +
              emailStyle(email.item?.emailType, email.item?.emailCustomer)
            }
            checked={email.checked}
            itemData={email.item}
            handleClick={() => handleItemClick(email.item.id)}
            editFavorite={(e) => editFavorite(e, email.item.id)}
            editAction={(e) => editAction(e, email.item.id)}
            editChecked={(e) => editChecked(e, index)}
          />
        );
      })}
    </React.Fragment>
  );
};
