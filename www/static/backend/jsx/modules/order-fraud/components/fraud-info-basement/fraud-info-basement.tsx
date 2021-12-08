import React, { Fragment } from "react";
import { Grid } from "@mui/material";
import { FraudInfoStatuses } from "@admin/modules/order-fraud/components/fraud-info-basement/fraud-info-statuses";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
export const FraudInfoBasement: React.FC = () => {
  const template = useSelector(
    (state: FraudCheckStore) => state.data.settings.template
  );
  return (
    <Fragment>
      <div className="basement-fraud-title-wrapper">
        <div className="basement-fraud-title">Fraud check expert section</div>
      </div>
      <div className="info-principle-wrapper">
        <Grid container direction="column" justifyContent="flex-start">
          <div
            dangerouslySetInnerHTML={{
              __html: template,
            }}
          />
          <FraudInfoStatuses />
        </Grid>
      </div>
    </Fragment>
  );
};
