import React, { useContext } from "react";
import { Grid } from "@material-ui/core";
import { Iframe } from "@s3stores-mail/components/smart/iframe/Iframe";
import moment from "moment";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";
import { EmailDto } from "../../../ts/types/email.type";

export const EmailInfoBodyData: React.FC<{ data: EmailDto }> = ({ data }) => {
  const { componentRef } = useContext(EmailInfoContext);
  return (
    <div ref={componentRef}>
      <Grid container justify="space-between">
        <Grid className="email-title-wrap">
          <Grid container>
            <span className="email-info-from">from:</span>
            <span className="email-info-title-text">{data.from_address}</span>
          </Grid>
          <Grid container>
            <span className="email-info-to">To:</span>
            <span className="email-info-title-text">{data.to_address}</span>
          </Grid>
        </Grid>
        <Grid>
          <Grid container>
            <span className="email-info-title-text">
              {moment(data.date).format("ddd, MMM, h:mm")}
              &nbsp;
              {`(${moment(data.date).fromNow()})`}
            </span>
          </Grid>
        </Grid>
      </Grid>
      <Iframe src={data.body} />
    </div>
  );
};
