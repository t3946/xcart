import React from "react";
import classnames from "classnames";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import Accordion from "react-bootstrap/Accordion";
import AccordionContext from "react-bootstrap/AccordionContext";
import { useAccordionButton } from "react-bootstrap/AccordionButton";
import Card from "react-bootstrap/Card";
import PlusIcon from "@modules/icon/components/font-awesome/plus/Light";
import MinusIcon from "@modules/icon/components/font-awesome/minus/Light";
import ChevronRightIcon from "@modules/icon/components/font-awesome/chevron-right/Light";
import cn from "classnames";
import Styles from "@modules/account/components/hat/DepartmentsMenuMobile.module.scss";

const DepartmentsMenuMobile: React.FC = (): React.ReactElement => {
  const departmentsMenu = useSelector(
    (e: StoreDto) => e.departmentsMenu.mobile
  );

  const isVisibleMenu = useSelector(
    (e: StoreDto) => e.departmentsMenuMobile.isVisible
  );

  const classes = {
    container: [
      "departments-menu-mobile",
      "hat-navigation_departments-menu-mobile",
      "d-flex",
      "flex-column",
      {
        "hat-navigation_departments-menu-mobile-visible": isVisibleMenu,
      },
    ],
  };

  function ContextAwareToggle({ children, eventKey, index }) {
    const currentEventKey = React.useContext(AccordionContext);
    const decoratedOnClick = useAccordionButton(eventKey);

    const isCurrentEventKey = currentEventKey.activeEventKey === eventKey;

    function icon() {
      if (isCurrentEventKey) {
        return (
          <MinusIcon
            className={"departments-menu-mobile-accordion-header-icon"}
          />
        );
      } else {
        return (
          <PlusIcon
            className={"departments-menu-mobile-accordion-header-icon"}
          />
        );
      }
    }

    return (
      <Card.Header
        className={classnames(
          "departments-menu-mobile-accordion-header rounded-0 d-flex justify-content-between align-items-center",
          {
            "departments-menu-mobile-accordion-header__first": index === 0,
          }
        )}
        onClick={decoratedOnClick}
      >
        {children}
        {icon()}
      </Card.Header>
    );
  }

  function subcategoryItemsTemplate(category) {
    const items = [];

    for (const [index, subCategory] of category.subCategories.entries()) {
      items.push(
        <li
          key={`${subCategory.link}_${index}`}
          className={"departments-menu-mobile-subcategory"}
        >
          <a
            className="departments-menu-mobile-subcategory-link text-black"
            href={subCategory.link}
          >
            <div>
              <span className="subcategory-link-text">{subCategory.name}</span>

              <span className="subcategory-product-counter ms-2">
                ({subCategory.activeProductCount})
              </span>
            </div>
            <ChevronRightIcon
              className={"departments-menu-mobile-accordion-header-icon"}
            />
          </a>
        </li>
      );
    }

    return items;
  }

  function menuItemsTemplate() {
    const menuItems = [];

    for (let i = 0; i < departmentsMenu.length; i++) {
      const category = departmentsMenu[i];

      if (category.activeProductCount === 0) {
        continue;
      }

      let header;

      if (category.subCategories.length === 0) {
        header = (
          <a className={"text-black"} href={category.url}>
            <Card.Header
              className={classnames(
                "departments-menu-mobile-accordion-header rounded-0 d-flex justify-content-between align-items-center",
                {
                  "departments-menu-mobile-accordion-header__first": i === 0,
                }
              )}
            >
              {category.name}
            </Card.Header>
          </a>
        );
      } else {
        header = (
          <ContextAwareToggle eventKey={i.toString()} index={i}>
            {category.name}
          </ContextAwareToggle>
        );
      }

      menuItems.push(
        <Card key={`${i}_${category.name}`} className={"rounded-0 border-0"}>
          {header}

          <Accordion.Collapse eventKey={i.toString()}>
            <Card.Body className={"departments-menu-mobile-accordion-body p-0"}>
              <ul className={"list-unstyled m-0"}>
                {subcategoryItemsTemplate(category)}
              </ul>
            </Card.Body>
          </Accordion.Collapse>
        </Card>
      );
    }

    return menuItems;
  }

  return (
    <div className={classnames(classes.container)}>
      <h3 className="departments-menu-mobile-hat m-0">Departments</h3>

      <Accordion
        className={cn(Styles.departmentsMenuMobileAccordion, "overflow-auto")}
      >
        {menuItemsTemplate()}
      </Accordion>
    </div>
  );
};

export default DepartmentsMenuMobile;
