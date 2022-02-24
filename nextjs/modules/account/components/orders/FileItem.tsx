import React, { useState } from "react";
import ClearIcon from "@modules/icon/components/account/clear/ClearIcon";

interface FileItemProps {
  file: File;
  onClick: () => void;
}

export const FileItem: React.FC<FileItemProps> = ({ file, onClick }) => {
  const [opacity, setOpacity] = useState(1);
  return (
    <div style={{ opacity }} className={"file-container"}>
      <div>{file.name}</div>
      <div
        onClick={() => {
          setOpacity(0);
          setTimeout(() => {
            onClick();
          }, 300);
        }}
      >
        <ClearIcon />
      </div>
    </div>
  );
};
