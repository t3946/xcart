import createSagaMiddleware from "redux-saga";
import { applyMiddleware, createStore, Store } from "redux";
import { composeWithDevTools } from "redux-devtools-extension";
import fraudCheckSaga from "@redux/sagas/fraudCheckSaga";
import fraudCheckReducer from "@redux/reducers/fraudCheckReducer";
import { initialFraudCheckStore } from "@admin/modules/order-fraud/ts/consts/initial";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";

const sagaMiddleware = createSagaMiddleware();

export const fraudCheckStore: Store<FraudCheckStore> = createStore(
  fraudCheckReducer,
  initialFraudCheckStore,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(fraudCheckSaga);
