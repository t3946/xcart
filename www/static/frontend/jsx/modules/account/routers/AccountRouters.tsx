import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { BreadCrumbs } from "../components/sidebar-menu/BreadCrumbs";
import { SideBarMenu } from "../components/sidebar-menu/SideBarMenu";
import { Addresses } from "../pages/Addresses";

export const AccountRouters = () => {
  return (
    <div className="account-container">
      <BrowserRouter>
        <BreadCrumbs />
        <div className="content-container">
          <SideBarMenu />
          <Switch>
            <Route exact path="/account/addresses" component={Addresses} />
          </Switch>
        </div>
      </BrowserRouter>
    </div>
  );
};
