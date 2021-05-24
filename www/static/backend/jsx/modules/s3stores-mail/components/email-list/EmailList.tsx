import React from "react";
import { EmailListItem } from "../email-list-item/EmailListItem";
import { emailStyle } from "@s3stores-mail/utils";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { SceletonEmailList } from "@s3stores-mail/components/sceleton-email-list/SceletonEmailList";

export const EmailList: React.FC = () => {
  const emails = useSelector((state: StoreDto) => {
    return state.items;
  });

  const loading = useSelector((state: StoreDto) => state.loading);

  return (
    <div>
      {loading ? (
        <SceletonEmailList itemsCount={20} />
      ) : (
        emails.map((email, index) => {
          return (
            <EmailListItem
              theme={emailStyle(
                email.item?.emailType,
                email.item?.emailCustomer
              )}
              name={email.item.subject}
              favorite={email.item.favorite}
              read={email.item.action.action}
              key={email.item.id}
              id={email.item.id}
              checked={email.checked}
              index={index}
            />
          );
        })
      )}
    </div>
  );
};
