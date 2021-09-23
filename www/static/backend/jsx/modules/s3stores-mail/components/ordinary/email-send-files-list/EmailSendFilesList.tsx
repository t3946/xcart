import React from "react";
import { EmailSendFilesListItem } from "@s3stores-mail/components/simple";

export const EmailSendFilesList: React.FC<any> = ({ files }) => {
  return (
    <div className="email-send-files">
      {files.map((file) => {
        return <EmailSendFilesListItem file={file} />;
      })}
    </div>
  );
};
