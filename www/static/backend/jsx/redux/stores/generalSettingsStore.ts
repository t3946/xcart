import { applyMiddleware, combineReducers, createStore } from "redux";
import createSagaMiddleware from "redux-saga";
import rootSaga from "../sagas/fraudSettingsSaga";
import { composeWithDevTools } from "redux-devtools-extension";
import fraudSettingsReducer from "@redux/reducers/fraudSettingsReduce";
import { initStateGeneralSettings } from "@admin/modules/general-settings/ts/consts/generalSettings";

const sagaMiddleware = createSagaMiddleware();

export const generalSettingsStore = createStore(
  combineReducers({
    fraudSettings: fraudSettingsReducer,
  }),
  initStateGeneralSettings,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(rootSaga);
