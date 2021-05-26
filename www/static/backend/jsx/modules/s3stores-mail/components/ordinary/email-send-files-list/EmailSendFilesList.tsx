import React from "react";
import { EmailSendFilesListItem } from "../../simple/email-send-files-list-item/EmailSendFilesListItem";

export const EmailSendFilesList: React.FC<any> = ({ files }) => {
  return (
    <div>
      {files.map((file) => {
        return <EmailSendFilesListItem file={file} />;
      })}
    </div>
  );
};
