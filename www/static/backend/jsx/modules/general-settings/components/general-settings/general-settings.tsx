import React from "react";
import { ConfigPanel } from "@admin/modules/general-settings/components/config-panel/config-panel";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { FraudCheckOptions } from "@admin/modules/general-settings/components/fraud-check-options/fraud-check-options";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";

export const GeneralSettings: React.FC<any> = () => {
  return (
    <div>
      <SnackBar>
        <BrowserRouter>
          <ConfigPanel />
          <hr className="hr__fraud_table" />
          <div className="select__module_container">
            <Switch>
              <Route
                path="/admin/list/Core/GeneralSettingsAdmin/Fraud_check"
                component={FraudCheckOptions}
              />
            </Switch>
          </div>
        </BrowserRouter>
      </SnackBar>
    </div>
  );
};
