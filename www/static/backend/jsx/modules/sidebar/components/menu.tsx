import React from "react";
import NewMenu from "./new-menu";
import OldMenu from "./old-menu";
import { Accordion } from "react-bootstrap";

const Menu: React.FC<any> = function (props: any) {
  return (
    <div>
      <Accordion defaultActiveKey={"0"}>
        <NewMenu />
        <OldMenu />
      </Accordion>
    </div>
  );
};

export default Menu;
