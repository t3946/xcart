import React from "react";
import { useDropzone } from "react-dropzone";

interface FileDropProps {
  onDrop: ([acceptedFile]: any) => void;
}

export const FileDrop: React.FC<FileDropProps> = ({ onDrop, children }) => {
  const { getInputProps, open } = useDropzone({
    noClick: true,
    noKeyboard: true,
    multiple: true,
    onDrop,
  });
  return (
    <div>
      <input {...getInputProps()} />
      <div onClick={open}>{children}</div>
    </div>
  );
};
