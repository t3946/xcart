import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { SideBarMenu } from "../components/sidebar-menu/SideBarMenu";

export const AccountRouters = () => {
  return (
    <BrowserRouter>
      <Switch>
        <Route path="/*" component={SideBarMenu} />
      </Switch>
    </BrowserRouter>
  );
};
