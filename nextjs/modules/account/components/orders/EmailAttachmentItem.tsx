import React from "react";

interface EmailAttachmentItem {
  file: any;
}

export const EmailAttachmentItem: React.FC<EmailAttachmentItem> = ({
  file,
}) => {
  return (
    <div className="incoming-file">
      <a
        download={incomingFile.filename}
        style={{
          textDecoration: "none",
        }}
        href={`/${incomingFile.attachment}`}
      >
        <div>
          <div className="incoming-file-img">
            {file.image && <img className="items-img" src={`  /${file.src}`} />}
            {!file.image && (
              <div className="file-icon-wrap">
                <img src={file?.icon} />
              </div>
            )}
          </div>
          <div className="incoming-file-footer">
            <span>
              <div className="d-flex align-center">
                <img className="mini-icon" src={file.iconMini} />
                <span className={`incoming-file-footer-text `}>
                  {incomingFile.filename}
                </span>
              </div>
            </span>
          </div>
        </div>
        <div className="hover-items">
          {file.image && (
            <div className="hover-items-img-wrap">
              <img className="items-img img-hover" src={`  /${file.src}`} />
            </div>
          )}
          <div className="hover-items-icon-background" />
          <div className="hover-items-content">
            <div className="hover-items-content-text-wrapper">
              <div className="d-flex  align-center">
                <img className="mini-icon" src={file.iconMini} />
                <span
                  className={`hover-items-content-name ${
                    file.image && "text-image"
                  } `}
                >
                  {incomingFile.filename}
                </span>
              </div>
            </div>
            <button className="form-button hover-items-content-btn">
              <span>DOWNLOAD</span>
            </button>
          </div>
        </div>
      </a>
    </div>
  );
};
