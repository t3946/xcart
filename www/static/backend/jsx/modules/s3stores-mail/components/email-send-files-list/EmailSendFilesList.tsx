import React from "react";
import { useSelector } from "react-redux";
import { EmailSendFilesListItem } from "@s3stores-mail/components/email-send-files-list-item/EmailSendFilesListItem";
import { StoreDto } from "@s3stores-mail/ts/types";

export const EmailSendFilesList: React.FC = () => {
  const files = useSelector((state: StoreDto) => state.sendData.files);
  return (
    <div>
      {files.map((file) => {
        return <EmailSendFilesListItem file={file} />;
      })}
    </div>
  );
};
