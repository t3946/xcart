import * as React from "react";
import { Route, Switch } from "react-router-dom";
import NavigateMenu from "./NavigateMenu";
import { BrowserRouter } from "react-router-dom";
import { ApiService } from "../../../shared/services/api.service";
import { useEffect, useState } from "preact/hooks";
import HelpCenterSection from "../help-center-section/HelpCenterSection";
import { HelpSectionItemDto } from "@/frontend/jsx/modules/help-center/ts/types";

const NavigateMenuRoutes: React.FC = () => {
  const api = new ApiService();
  const [menuItems, setMenuItems] = useState<HelpSectionItemDto[] | undefined>(
    undefined
  );

  useEffect(() => {
    api.get<HelpSectionItemDto[]>("/help/api/item-list").then((data) => {
      setMenuItems(data);
    });
  }, []);

  return (
    <BrowserRouter>
      <img src="/static/frontend/dist/images/page-bg/help-center-header.png" />
      {menuItems ? (
        <div className="container">
          <div className="row">
            <div className="help-wrap">
              <NavigateMenu menuItems={menuItems} />
              <Switch>
                {menuItems.map((item, id) => {
                  const route = id === 0 ? "/help/" : `/help/${item.menu_id}`;
                  return (
                    <Route
                      exact={true}
                      key={item.menu_id}
                      path={route}
                      component={() => {
                        return (
                          <HelpCenterSection
                            items={item.items}
                            menu_id={item.menu_id}
                            title={item.title}
                          />
                        );
                      }}
                    />
                  );
                })}
              </Switch>
            </div>
          </div>
        </div>
      ) : null}
    </BrowserRouter>
  );
};
export default NavigateMenuRoutes;
