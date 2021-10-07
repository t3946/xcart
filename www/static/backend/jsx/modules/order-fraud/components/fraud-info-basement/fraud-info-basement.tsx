import React, { Fragment, useContext, useEffect, useState } from "react";
import {
  Button,
  FormControl,
  Grid,
  InputLabel,
  MenuItem,
  Select,
} from "@material-ui/core";
import { FraudCheckOrderContext } from "@admin/modules/order-fraud/contexts/FraudCheckOrderContext";
import { BootstrapInput } from "@admin/modules/order-fraud/ts/consts/info-basement";
import { FraudInfoStatuses } from "@admin/modules/order-fraud/components/fraud-info-basement/fraud-info-statuses";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { ResponseFraudChangeStatus } from "@admin/modules/order-fraud/ts/types/response";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
const api = new ApiService();
export const FraudInfoBasement: React.FC = () => {
  const { settings, orderId } = useContext(FraudCheckOrderContext);
  const [status, setStatus] = useState<string>("N");
  const { showSnackbar } = useContext(SnackbarContext);

  const saveStatus = (): void => {
    const frm = new FormData();
    frm.append("order_id", orderId);
    frm.append("status", status);
    api
      .post("/api/order/fraud-status/update", frm)
      .then((res: ResponseFraudChangeStatus) => {
        if (res.status) {
          showSnackbar(
            "You have successfully changed fraud status of the order"
          );
        } else {
          showSnackbar(`error: ${res.error}`, "error");
        }
      });
  };
  useEffect(() => {
    if (settings.fraud_status.code) {
      setStatus(settings.fraud_status.code);
    }
  }, []);
  const onChangeSelect = (event: React.ChangeEvent<{ value: string }>) => {
    setStatus(event.target.value);
  };
  return (
    <Fragment>
      <div className="basement-fraud-title-wrapper">
        <div className="basement-fraud-title">Fraud check expert section</div>
      </div>
      <div className="info-principle-wrapper">
        <Grid container direction="column" justifyContent="flex-start">
          <div
            dangerouslySetInnerHTML={{
              __html: settings?.lang?.basement,
            }}
          />
          <FraudInfoStatuses
            saveStatus={saveStatus}
            select={{ get: status, set: onChangeSelect }}
          />
        </Grid>
      </div>
    </Fragment>
  );
};
