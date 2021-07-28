import { applyMiddleware, combineReducers, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountAddressesReducer from "../reduсers/account/AddresesReduсer";
import { accountStoreInitialValue } from "../../modules/account/ts/consts/account-store-initial-value";
import { AccountStoreDto } from "../../modules/account/ts/types/account-store.type";
import accountRootSaga from "../sagas/account-sagas/MainSaga";
import accountSharedReducer from "../reduсers/account/SharedReduсer";
import WalletReducer from "../reduсers/account/WalletReducer";

const sagaMiddleware = createSagaMiddleware();

export const accountStore: Store<AccountStoreDto> = createStore(
  combineReducers({
    addresses: accountAddressesReducer,
    main: accountSharedReducer,
    wallet: WalletReducer,
  }),
  accountStoreInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);
