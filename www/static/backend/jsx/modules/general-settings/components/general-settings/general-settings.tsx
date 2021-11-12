import React, { useEffect, Fragment, useContext } from "react";
import { ConfigPanel } from "@admin/modules/general-settings/components/config-panel/config-panel";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { FraudCheckOptions } from "@admin/modules/general-settings/components/fraud-check-options/fraud-check-options";
import { useSelector } from "react-redux";
import { StoreGeneralSettings } from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { Divider } from "@mui/material";

export const GeneralSettings: React.FC<any> = () => {
  const alert = useSelector((state: StoreGeneralSettings) => state.alert);
  const { showSnackbar } = useContext(SnackbarContext);
  useEffect(() => {
    if (alert.state) {
      showSnackbar(alert.message, alert.status);
    }
  }, [alert.state]);
  return (
    <Fragment>
      <BrowserRouter>
        <ConfigPanel />
        <Divider sx={{ my: 2 }} />
        <div className="select__module_container">
          <Switch>
            <Route
              path="/admin/list/Core/GeneralSettingsAdmin/Fraud_check"
              component={FraudCheckOptions}
            />
          </Switch>
        </div>
      </BrowserRouter>
    </Fragment>
  );
};
