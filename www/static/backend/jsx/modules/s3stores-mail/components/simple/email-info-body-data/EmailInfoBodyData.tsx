import React from "react";
import { Grid } from "@material-ui/core";

export const EmailInfoBodyData = () => {
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

      <span className="email-info-text">
        You have received a new 2 page fax on FAXAGE from (707)792-1362. A copy
        is attached for your reference. You may also visit http://www.faxage.
        com to log in and work with your faxes.
      </span>
    </React.Fragment>
  );
};
