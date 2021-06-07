import React from "react";
import { IncomingFileItem } from "@s3stores-mail/components/ordinary/incoming-file-item/IncomingFileItem";
import { AttachmentDto } from "../../../ts/types/email.type";

interface FilesListDto {
  files: AttachmentDto[];
}

export const IncomingFilesList: React.FC<FilesListDto> = ({ files }) => {
  return (
    <div className="attachment-list-wrapper">
      {files.map((e) => {
        if (e.cid === null) {
          return (
            <div>
              <IncomingFileItem incomingFile={e} />{" "}
            </div>
          );
        }
      })}
    </div>
  );
};
