import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
import SideBarMenu from "../components/sidebar-menu/SideBarMenu";
import { AddressDialogHOC } from "../hoc/AddressDialogHOC";
import { Addresses } from "../pages/Addresses";
import { Transactions } from "../pages/Transactions";
import { Wallet } from "../pages/Wallet";
import { Provider } from "react-redux";
import { accountStore } from "../../../redux/stores/StoreAccount";
import LoginForm from "../../account/components/authorization/LoginForm";
import RegisterForm from "../../account/components/authorization/RegisterForm";
import TopLine from "../components/hat/TopLine";
import HatNavigation from "../components/hat/HatNavigation";
import HatSearchLine from "../components/hat/HatSearchLine";

export const AccountRouters = () => {
  return (
    <Provider store={accountStore as any}>
      <BrowserRouter>
        <TopLine />
        <HatNavigation />
        <HatSearchLine />

        <div className="account-container">
          <div className="container">
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

                <Route
                  exact
                  path="/account/payments/wallet"
                  component={Wallet}
                />

                <Route
                  exact
                  path="/account/payments/transactions"
                  component={Transactions}
                />

                <Route exact path="/account/login/" component={LoginForm} />

                <Route
                  exact
                  path="/account/register/"
                  component={RegisterForm}
                />
              </Switch>
            </div>
          </div>
        </div>
      </BrowserRouter>
    </Provider>
  );
};
