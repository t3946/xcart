import React from "react";
import classNames from "classnames";
import { Accordion, Card } from "react-bootstrap";
import ContextAwareToggle from "@admin/modules/common/components/accordion/context-aware-toggle";
import appData from "@admin/utils/app-data";
import { RoundedCornerIcon } from "@admin/icons/icons";

const OldMenu: React.FC<any> = function (props: any) {
  function sectionLinksTemplate(links): any {
    const menuItems = [];

    for (const link of links) {
      menuItems.push(
        <li>
          <a className="sidebar-menu-link__old" href={link.route}>
            {link.name}
          </a>
        </li>
      );
    }

    return (
      <ul className="sidebar-menu-links-container list-unstyled m-0">
        {menuItems}
      </ul>
    );
  }

  function sectionTemplate(group, sectionEventKey): any {
    const defaultActive = false;
    const { name, links } = group;
    const [eventKey, setEventKey] = React.useState("0");
    const [isCurrentEventKey, setIsCurrentEventKey] = React.useState(false);

    const hatClasses = [
      "sidebar-menu-hat",
      { "sidebar-menu-hat_active": isCurrentEventKey },
    ];

    return (
      <Card className="border-0 rounded-0 sidebar-menu_section">
        <Card.Header className="p-0 border-0 m-0">
          <ContextAwareToggle
            eventKey={sectionEventKey}
            onChange={(newEventKey) => setIsCurrentEventKey(newEventKey)}
          >
            <div className={classNames(hatClasses)}>
              <h3 className="sidebar-menu-header m-0">{name}</h3>
              <RoundedCornerIcon className="sidebar-menu-header-icon" />
            </div>
          </ContextAwareToggle>
        </Card.Header>

        <Accordion.Collapse eventKey={sectionEventKey}>
          <Card.Body className="p-0">{sectionLinksTemplate(links)}</Card.Body>
        </Accordion.Collapse>
      </Card>
    );
  }

  function sectionsTemplate(): any {
    const sections = [];
    const oldMenu = appData().sidebarMenu.old;

    for (let i = 0; i < oldMenu.length; i++) {
      const menuSection = oldMenu[i];

      sections.push(sectionTemplate(menuSection, i + 1));
    }

    return sections;
  }

  return <React.Fragment>{sectionsTemplate()}</React.Fragment>;
};

export default OldMenu;
