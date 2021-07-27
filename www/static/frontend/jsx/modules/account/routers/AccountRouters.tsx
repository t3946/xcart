import React, { useEffect } from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
import { SideBarMenu } from "../components/sidebar-menu/SideBarMenu";
import { AddressDialogHOC } from "../hoc/AddressDialogHOC";
import { Addresses } from "../pages/Addresses";
import { Transactions } from "../pages/Transactions";
import { Wallet } from "../pages/Wallet";
import { Provider, useDispatch } from "react-redux";
import { accountStore } from "../../../redux/stores/StoreAccount";
import LoginForm from "../../account/components/authorization/LoginForm";
import RegisterForm from "../../account/components/authorization/RegisterForm";
import { AddAddressPage } from "../pages/AddAddressPage";
import { getTerritory } from "../../../redux/actions/account-actions/MainActions";

export const AccountRouters = () => {
  console.log(accountStore.getState());
  const dispatch = useDispatch();
  useEffect(() => {
    dispatch(getTerritory());
  }, []);
  return (
    <div className="account-container">
      <BrowserRouter>
        <BreadCrumbs />
        <div className="content-container">
          <SideBarMenu />
          <Switch>
            <Route
              exact
              path="/account/addresses"
              component={AddressDialogHOC(<Addresses />, <AddAddressDialog />)}
            />
            <Route
              exact
              path="/account/addresses/add"
              component={AddAddressPage}
            />
            <Route exact path="/account/payments/wallet" component={Wallet} />

            <Route
              exact
              path="/account/payments/transactions"
              component={Transactions}
            />

            <Route exact path="/account/login/" component={LoginForm} />

            <Route exact path="/account/register/" component={RegisterForm} />
          </Switch>
        </div>
      </BrowserRouter>
    </div>
  );
};
