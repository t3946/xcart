import React from 'react';
import {ConfigPanel} from "@admin/modules/general-settings/components/config-panel/config-panel";
import {BrowserRouter, Route, Switch} from "react-router-dom";
import {FraudCheckOptions} from "@admin/modules/general-settings/components/fraud-check-options/fraud-check-options";

export const GeneralSettings: React.FC<any> = () => {
    return (
        <div>
            <BrowserRouter>
                <ConfigPanel/>
                <Switch>
                    <Route path='/admin/list/Core/GeneralSettingsAdmin/Fraud_check'
                           component={FraudCheckOptions}></Route>
                </Switch>
            </BrowserRouter>
        </div>
    )
}