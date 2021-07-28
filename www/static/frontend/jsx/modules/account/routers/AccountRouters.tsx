import React, { useEffect } from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
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
import TopLine from "../components/hat/TopLine";
import HatNavigation from "../components/hat/HatNavigation";
import HatSearchLine from "../components/hat/HatSearchLine";
import MobileMenu from "../components/hat/MobileMenu";
import SideBarMenu from "../components/sidebar-menu/SideBarMenu";
import { AddNewAddress } from "@modules/account/components/addresses/AddNewAddress";
import { AddressList } from "@modules/account/components/addresses/AddressList";

export const AccountRouters = () => {
  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(getTerritory());
  }, []);

  return (
    <Provider store={accountStore as any}>
      <BrowserRouter>
        <TopLine />
        <HatNavigation />
        <HatSearchLine />
        <MobileMenu />
        <BreadCrumbs />
        <div className={"container"}>
          <div className="row">
            <div className="col account-page-left-column d-none d-lg-block">
              <SideBarMenu />
            </div>

            <div className="col account-page-right-column">
              <div className="content-container">
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
                    path="/account/addresses/add"
                    component={AddAddressPage}
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
        </div>
      </BrowserRouter>
    </Provider>
  );
};
