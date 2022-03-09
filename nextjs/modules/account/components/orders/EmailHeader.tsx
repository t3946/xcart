import React from "react";
import PrintIcon from "@modules/icon/components/account/print/PrintIcon";
import ReactToPrint from "react-to-print";

interface EmailHeaderProps {
  info: any;
  contentRef: any;
}

export const EmailHeader: React.FC<EmailHeaderProps> = ({
  info,
  contentRef,
}) => {
  return (
    <div className="header-wrap info">
      <div className="d-flex align-items-center justify-content-between">
        <div>
          <span
            style={{
              fontSize: 15,
            }}
          >
            {info.subject}
          </span>
        </div>
        <div>
          <ReactToPrint
            trigger={() => <PrintIcon />}
            content={() => contentRef.current}
          />
        </div>
      </div>
    </div>
  );
};
