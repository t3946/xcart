import React from "react";
import classnames from "classnames";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import Accordion from "react-bootstrap/Accordion";
import AccordionContext from "react-bootstrap/AccordionContext";
import { useAccordionToggle } from "react-bootstrap/AccordionToggle";
import Card from "react-bootstrap/Card";
import PlusIcon from "@client/modules/icon/components/font-awesome/plus/Light";
import MinusIcon from "@client/modules/icon/components/font-awesome/minus/Light";
import ChevronRightIcon from "@client/modules/icon/components/font-awesome/chevron-right/Light";
import { hideAction as hide } from "@client/jsx/redux/actions/account-actions/DepartmentsMenuMobileActions";
import { useDispatch } from "react-redux";

const DepartmentsMenuMobile = (props: Record<any, any>): any => {
  const dispatch = useDispatch();
  const isVisibleShadowPanel = useSelector(
    (e: Record<any, any>) => e.shadowPanel.isVisible
  );

  if (isVisibleShadowPanel === false) {
    dispatch(hide());
  }

  const departmentsMenu = useSelector(
    (e: StoreDto) => e.departmentsMenu.mobile
  );

  const isVisibleMenu = useSelector(
    (e: StoreDto) => e.departmentsMenuMobile.isVisible
  );

  const classes = {
    container: ["departments-menu-mobile", props.classes.container],
  };

  function ContextAwareToggle({ children, eventKey, index }) {
    const currentEventKey = React.useContext(AccordionContext);
    const decoratedOnClick = useAccordionToggle(eventKey);
    const isCurrentEventKey = currentEventKey === eventKey;

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

    for (const subCategory of category.subCategories) {
      items.push(
        <li className={"departments-menu-mobile-subcategory"}>
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
        <Card className={"rounded-0 border-0"}>
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
    <div
      className={classnames(classes.container, "d-flex flex-column", {
        "hat-navigation_departments-menu-mobile-visible": isVisibleMenu,
      })}
    >
      <h3 className="departments-menu-mobile-hat m-0">Departments</h3>

      <Accordion className={"departments-menu-mobile-accordion overflow-auto"}>
        {menuItemsTemplate()}
      </Accordion>
    </div>
  );
};

export default DepartmentsMenuMobile;
