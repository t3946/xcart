import React, { useState } from "react";
import { EmailListItem } from "../email-list-item/EmailListItem";
import { emailStyle } from "../../utils/setEmailItemStyle";
import { useSelector } from "react-redux";

export const EmailList = () => {
  const emails = useSelector((state: any) => {
    return state.items;
  });

  return (
    <div>
      {emails.map((e) => {
        return (
          <EmailListItem
            theme={emailStyle(e.emailType, e.emailCustomer)}
            name={e.title}
            favorite={e.favorite}
            read={e.read}
            key={e.id}
            id={e.id}
          />
        );
      })}
    </div>
  );
};
