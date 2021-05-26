import React from "react";

interface ReadSwitchDto {
  readed: boolean;
  editAction: (e) => void;
}

export const ReadedSwitch: React.FC<ReadSwitchDto> = ({
  readed,
  editAction,
}) => {
  return (
    <div onClick={editAction} className="readed-wrap">
      <div>
        <div className={`readed-slide ${readed ? "slide-readed" : ""}`}>
          {readed ? "Action taken by Zouhair" : "Action required"}
        </div>
        <div className={`readed-item ${readed ? "readed" : ""}`} />
      </div>
    </div>
  );
};
