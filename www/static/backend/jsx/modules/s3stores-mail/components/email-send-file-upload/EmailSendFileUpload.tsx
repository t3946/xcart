import React, { useCallback } from "react";
import { useDropzone } from "react-dropzone";
import { IconButton } from "@material-ui/core";
import AttachmentIcon from "@material-ui/icons/Attachment";
import { useDispatch } from "react-redux";
import { addFile } from "@redux/actions";

export const EmailSendFileUpload: React.FC = () => {
  const dispatch = useDispatch();
  const onDrop = useCallback(([acceptedFile]) => {
    dispatch(addFile(acceptedFile));
  }, []);

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
