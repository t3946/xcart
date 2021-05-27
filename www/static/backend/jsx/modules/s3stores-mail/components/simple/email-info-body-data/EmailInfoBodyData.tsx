import React, { useRef, useState } from "react";
import { Grid } from "@material-ui/core";
import { Iframe } from "@s3stores-mail/components/smart/iframe/Iframe";

export const EmailInfoBodyData = ({ data }) => {
  return (
    <React.Fragment>
      <Grid container justify="space-between">
        <Grid className="email-title-wrap">
          <Grid container>
            <span className="email-info-from">from:</span>
            <span className="email-info-title-text">
              FAXAGE support@faxage.com
            </span>
          </Grid>
          <Grid container>
            <span className="email-info-to">To:</span>
            <span className="email-info-title-text">
              faxage800@s3stores.com reply-to: support@faxage.com
            </span>
          </Grid>
        </Grid>
        <Grid>
          <Grid container>
            <span className="email-info-from">from:</span>
            <span className="email-info-title-text">
              FAXAGE support@faxage.com
            </span>
          </Grid>
        </Grid>
      </Grid>
      <Iframe src={data.body} />
    </React.Fragment>
  );
};
