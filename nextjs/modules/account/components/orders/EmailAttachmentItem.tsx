import React from "react";
import { Button, Grid } from "@material-ui/core";
import GetAppIcon from "@material-ui/icons/GetApp";

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
              <Grid alignItems="center" container>
                <img className="mini-icon" src={file.iconMini} />
                <span className={`incoming-file-footer-text `}>
                  {incomingFile.filename}
                </span>
              </Grid>
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
              <Grid container alignItems="center">
                <img className="mini-icon" src={file.iconMini} />
                <span
                  className={`hover-items-content-name ${
                    file.image && "text-image"
                  } `}
                >
                  {incomingFile.filename}
                </span>
              </Grid>
            </div>
            <Button className="hover-items-content-btn">
              <GetAppIcon />
              <span>DOWNLOAD</span>
            </Button>
          </div>
        </div>
      </a>
    </div>
  );
};
