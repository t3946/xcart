import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
import { SideBarMenu } from "../components/sidebar-menu/SideBarMenu";
import { AddressDialogHOC } from "../hoc/AddressDialogHOC";
import { Addresses } from "../pages/Addresses";
import { Transactions } from "../pages/Transactions";
import { Wallet } from "../pages/Wallet";
import { Provider } from "react-redux";
import { accountStore } from "../../../redux/stores/StoreAccount";

export const AccountRouters = () => {
  return (
    <Provider store={accountStore as any}>
      <div className="account-container">
        <BrowserRouter>
          <BreadCrumbs />
          <div className="content-container">
            <SideBarMenu />
            <Switch>
              <Route
                exact
                path="/account/addresses"
                component={AddressDialogHOC(
                  <Addresses />,
                  <AddAddressDialog />
                )}
              />
              <Route exact path="/account/payments/wallet" component={Wallet} />
              <Route
                exact
                path="/account/payments/transactions"
                component={Transactions}
              />
            </Switch>
          </div>
        </BrowserRouter>
      </div>
    </Provider>
  );
};
