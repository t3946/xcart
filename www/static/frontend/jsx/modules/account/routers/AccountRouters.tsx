import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
import { SideBarMenu } from "../components/sidebar-menu/SideBarMenu";
import { AddressDialogHOC } from "../hoc/AddressDialogHOC";
import { Addresses } from "../pages/Addresses";

export const AccountRouters = () => {
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
          </Switch>
        </div>
      </BrowserRouter>
    </div>
  );
};
