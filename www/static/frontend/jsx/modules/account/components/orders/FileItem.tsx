import React, { useState } from "react";
import { IconButton } from "@material-ui/core";
import ClearIcon from "@material-ui/icons/Clear";

interface FileItemProps {
  file: File;
  onClick: () => void;
}

export const FileItem: React.FC<FileItemProps> = ({ file, onClick }) => {
  const [opacity, setOpacity] = useState(1);
  return (
    <div style={{ opacity }} className={"file-container"}>
      <div>{file.name}</div>
      <IconButton
        onClick={() => {
          setOpacity(0);
          setTimeout(() => {
            onClick();
          }, 300);
        }}
      >
        <ClearIcon />
      </IconButton>
    </div>
  );
};
