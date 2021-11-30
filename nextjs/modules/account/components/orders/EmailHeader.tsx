import React from "react";
import { Grid, IconButton, Paper, Tooltip } from "@material-ui/core";
import PrintIcon from "@material-ui/icons/Print";
import ReactToPrint from "react-to-print";

interface EmailHeaderProps {
  info: any;
  contentRef: any;
}

export const EmailHeader: React.FC<EmailHeaderProps> = ({
  info,
  contentRef,
}) => {
  console.log(contentRef);
  return (
    <Paper className="header-wrap info" square={true}>
      <Grid container justify="space-between" alignItems="center">
        <Grid xs={6}>
          <span
            style={{
              fontSize: 15,
            }}
          >
            {info.subject}
          </span>
        </Grid>
        <Grid>
          <ReactToPrint
            trigger={() => (
              <Tooltip title={"Print"}>
                <IconButton className="icon-button">
                  <PrintIcon />
                </IconButton>
              </Tooltip>
            )}
            content={() => contentRef.current}
          />
        </Grid>
      </Grid>
    </Paper>
  );
};
