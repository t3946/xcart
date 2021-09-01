import React from "react";
import { useDropzone } from "react-dropzone";
import { IconButton } from "@material-ui/core";
import AttachmentIcon from "@material-ui/icons/Attachment";

export const EmailSendFileUpload: React.FC<any> = ({ onDrop }) => {
  const { getInputProps, open } = useDropzone({
    noClick: true,
    noKeyboard: true,
    multiple: true,
    onDrop,
  });

  return (
    <div className="a">
      <input {...getInputProps()} />
      <IconButton onClick={open}>
        <AttachmentIcon />
      </IconButton>
    </div>
  );
};
