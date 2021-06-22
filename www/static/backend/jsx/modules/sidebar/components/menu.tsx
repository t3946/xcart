import React from "react";
import NewMenu from "./new-menu";
import OldMenu from "./old-menu";
import { Accordion } from "react-bootstrap";
import appData from "@admin/utils/app-data";

const Menu: React.FC<any> = function (props: any) {
  /**
   * search current route in menu sections and return menu section
   * eventKey if it has current route else return first section event key
   */
  function getActiveKey() {
    const currentRoute = document.location.pathname;

    //search in new menu
    for (const group of appData().sidebarMenu.new) {
      for (const item of group.items) {
        if (item.route === currentRoute) {
          return "0";
        }
      }
    }

    //search in old menu
    for (let i = 0; i < appData().sidebarMenu.old.length; i++) {
      const links = appData().sidebarMenu.old[i].links;

      for (const link of links) {
        if (link.route === currentRoute) {
          return (i + 1).toString();
        }
      }
    }

    return "0";
  }

  return (
    <div>
      <Accordion defaultActiveKey={getActiveKey()}>
        {appData().sidebarMenu.new && <NewMenu />}
        {appData().sidebarMenu.old && <OldMenu />}
      </Accordion>
    </div>
  );
};

export default Menu;
