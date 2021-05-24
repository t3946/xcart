import { applyMiddleware, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import rootSaga from "../sagas/emailSaga";
import emailReducer from "../reducers/emailReduce";
import { initialValues } from "@s3stores-mail/ts/consts";
import { StoreDto } from "@s3stores-mail/ts/types";
import { composeWithDevTools } from "redux-devtools-extension";

const sagaMiddleware = createSagaMiddleware();

export const emailStore: Store<StoreDto> = createStore(
  emailReducer,
  initialValues,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(rootSaga);
