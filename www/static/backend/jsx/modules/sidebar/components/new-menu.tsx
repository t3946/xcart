import React from "react";
import classNames from "classnames";
import { Accordion, Card } from "react-bootstrap";
import ContextAwareToggle from "@admin/modules/common/components/accordion/context-aware-toggle";
import appData from "@admin/utils/app-data";
import { RoundedCorner } from "@admin/icons/icons";

const NewMenu: React.FC<any> = function (props: any) {
  const defaultActive = true;
  const [eventKey, setEventKey] = React.useState("0");
  const [isCurrentEventKey, setIsCurrentEventKey] = React.useState(false);

  const hatClasses = [
    "sidebar-menu-hat",
    { "sidebar-menu-hat_active": isCurrentEventKey },
  ];

  function linksTemplate(links): any {
    const linkItems = [];

    for (const link of links) {
      const { name, route, items } = link;

      linkItems.push(
        <li>
          <a className="sidebar-menu-link" href={route}>
            {name}
          </a>
          <div className="sidebar-menu-links__level-2">
            {items && linksTemplate(items)}
          </div>
        </li>
      );
    }

    return <ul className="sidebar-menu_links list-unstyled">{linkItems}</ul>;
  }

  function groupTemplate(group): any {
    return (
      <li className="sidebar-menu-links_group">
        <h4 className="sidebar-menu-links-group-header sidebar-menu_links-group-header">
          {group.name}
        </h4>

        {linksTemplate(group.items)}
      </li>
    );
  }

  function menuGroupsTemplate(): any {
    const groups = [];

    for (const group of appData().sidebarMenu.new) {
      groups.push(groupTemplate(group));
    }
    return (
      <ul className="sidebar-menu-links-container list-unstyled m-0">
        {groups}
      </ul>
    );
  }

  return (
      <Card className="border-0 rounded-0 sidebar-menu_section">
        <Card.Header className="p-0 border-0 m-0">
          <ContextAwareToggle
            eventKey={eventKey}
            onChange={(newEventKey) => setIsCurrentEventKey(newEventKey)}
          >
            <div className={classNames(hatClasses)}>
              <h3 className="sidebar-menu-header m-0">Modules menu</h3>
              <RoundedCorner className="sidebar-menu-header-icon" />
            </div>
          </ContextAwareToggle>
        </Card.Header>

        <Accordion.Collapse eventKey="0">
          <Card.Body className="p-0">{menuGroupsTemplate()}</Card.Body>
        </Accordion.Collapse>
      </Card>
  );
};

export default NewMenu;
