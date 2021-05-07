import { applyMiddleware, createStore } from "redux";
import createSagaMiddleware from "redux-saga";
import rootSaga from "../sagas/emailSaga";
import emailReducer from "../reducers/emailReduce";

const sagaMiddleware = createSagaMiddleware();

const initialValues = {
  items: [],
  itemsCount: 0,
  page: 1,
  searchOptions: {},
};

export const emailStore: any = createStore(
  emailReducer,
  initialValues,
  applyMiddleware(sagaMiddleware)
);

sagaMiddleware.run(rootSaga);
