import { applyMiddleware, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountAddressesReducer from "../redusers/account/AddresesReduser";
import { accountStoreInitialValue } from "../../modules/account/ts/consts/account-store-initial-value";
import { AccountStoreDto } from "../../modules/account/ts/types/account-store.type";
import accountRootSaga from "../sagas/account-sagas/mainSaga";

const sagaMiddleware = createSagaMiddleware();

export const accountStore: Store<AccountStoreDto> = createStore(
  accountAddressesReducer,
  accountStoreInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);
