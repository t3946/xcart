import { applyMiddleware, combineReducers, createStore } from "redux";
import createSagaMiddleware from "redux-saga";
import rootSaga from "../sagas/fraudSettingsSaga";
import { composeWithDevTools } from "redux-devtools-extension";
import fraudSettingsReducer from "@redux/reducers/general-settings/fraudSettingsReduce";
import { initStateGeneralSettings } from "@admin/modules/general-settings/ts/consts/generalSettings";
import alertReducer from "@redux/reducers/general-settings/alertReducer";

const sagaMiddleware = createSagaMiddleware();

export const generalSettingsStore = createStore(
  combineReducers({
    fraudSettings: fraudSettingsReducer,
    alert: alertReducer,
  }),
  initStateGeneralSettings,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(rootSaga);
