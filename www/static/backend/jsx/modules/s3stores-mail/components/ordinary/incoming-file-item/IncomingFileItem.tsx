import React from "react";
import { Button, Grid } from "@material-ui/core";
import GetAppIcon from "@material-ui/icons/GetApp";
import { checkFileExtension } from "../../../utils/check-file-extension";

export const IncomingFileItem: React.FC<any> = ({ incomingFile }) => {
  const file = checkFileExtension(
    incomingFile.filename,
    incomingFile.attachment
  );
  return (
    <div className="incoming-file">
      <a
        download={incomingFile.filename}
        style={{
          textDecoration: "none",
        }}
        href={`https://i1.s3stores.com/${incomingFile.attachment}`}
      >
        <div>
          <div className="incoming-file-img">
            {file.image && (
              <img
                className="items-img"
                src={`https://i1.s3stores.com/${file.src}`}
              />
            )}
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
              <img
                className="items-img img-hover"
                src={`https://i1.s3stores.com/${file.src}`}
              />
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
