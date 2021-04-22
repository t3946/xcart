import * as React from "react";
import { Route, Switch } from "react-router-dom";
import NavigateMenu from "./NavigateMenu";
import { BrowserRouter } from "react-router-dom";
import { ApiService } from "../../../shared/services/api.service";
import { useEffect, useState } from "preact/hooks";
import HelpCenterSection from "../help-center-section/HelpCenterSection";

const NavigateMenuRoutes: React.FC = () => {
  const api = new ApiService();
  const [menuItems, setMenuItems] = useState(undefined);

  useEffect(() => {
    api.get("http://localhost:3000/menu-items").then((data) => {
      setMenuItems(data);
    });
  }, []);

  return (
    <BrowserRouter>
      {menuItems ? (
        <div className="row">
          <div className="help-wrap">
            <NavigateMenu items={menuItems} />
            <Switch>
              {menuItems.map((item) => {
                return (
                  <Route
                    exact={true}
                    key={item.id}
                    path={item.items.route}
                    component={() => {
                      return <HelpCenterSection item={item} />;
                    }}
                  />
                );
              })}
            </Switch>
          </div>
        </div>
      ) : null}
    </BrowserRouter>
  );
};
export default NavigateMenuRoutes;
