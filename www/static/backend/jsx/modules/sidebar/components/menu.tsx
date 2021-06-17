import React from "react";
import NewMenu from "./new-menu";
import OldMenu from "./old-menu";
import { Accordion } from "react-bootstrap";
import appData from "@admin/utils/app-data";

const Menu: React.FC<any> = function (props: any) {
  return (
    <div>
      <Accordion defaultActiveKey={"0"}>
        {appData().sidebarMenu.new && <NewMenu />}
        {appData().sidebarMenu.old && <OldMenu />}
      </Accordion>
    </div>
  );
};

export default Menu;
