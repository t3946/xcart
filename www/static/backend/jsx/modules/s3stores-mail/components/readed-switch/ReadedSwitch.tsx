import React, { useState } from "react";

export const ReadedSwitch = ({ readed }) => {
  const [read, setRead] = useState(readed);

  const handleClick = () => {
    setRead(!read);
  };

  return (
    <div onClick={handleClick} className="readed-wrap">
      <div>
        <div className={`readed-slide ${read ? "slide-readed" : ""}`}>
          {read ? "Action taken by Zouhair" : "Action required"}
        </div>
        <div className={`readed-item ${read ? "readed" : ""}`} />
      </div>
    </div>
  );
};
