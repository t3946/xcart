import React from "react";
import moment from "moment";

interface ReadSwitchDto {
  readed: boolean;
  editAction: (e) => void;
  inHeader?: boolean;
  actionName: string;
}

export const ReadedSwitch: React.FC<ReadSwitchDto> = ({
  readed,
  editAction,
  inHeader = false,
  actionName,
}) => {
  return (
    <div onClick={editAction} className="readed-wrap">
      <div>
        <div className={`readed-slide ${readed ? "slide-readed" : ""}`}>
          {readed ? `Action taken by ${actionName} ` : "Action required"}
        </div>
        <div className={`readed-item ${readed ? "readed" : ""}`} />
      </div>
      {readed && inHeader && (
        <div className="readed-taked-time">
          at {moment(new Date()).format("D MMMM YYYY, h:mm:ss a")}
        </div>
      )}
    </div>
  );
};
