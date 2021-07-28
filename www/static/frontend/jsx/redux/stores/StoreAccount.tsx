import { applyMiddleware, combineReducers, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountAddressesReducer from "../redusers/account/AddresesReduser";
import { accountStoreInitialValue } from "../../modules/account/ts/consts/account-store-initial-value";
import { AccountStoreDto } from "../../modules/account/ts/types/account-store.type";
import accountRootSaga from "../sagas/account-sagas/MainSaga";
import accountSharedReducer from "../redusers/account/SharedReduser";
import WalletReducer from "../redusers/account/WalletReducer";
import MobileMenuReducer from "../redusers/account/MobileMenuReducer";

const sagaMiddleware = createSagaMiddleware();

export const accountStore: Store<AccountStoreDto> = createStore(
  combineReducers({
    addresses: accountAddressesReducer,
    main: accountSharedReducer,
    wallet: WalletReducer,
    mobileMenu: MobileMenuReducer,
  }),
  accountStoreInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);
