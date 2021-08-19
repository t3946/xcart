import React from "react";
import { useSelector } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import classnames from "classnames";
import { StoreDto } from "@s3stores-mail/ts/types";

const DepartmentsMenu = (props: Record<any, any>): any => {
  const MAX_CATEGORIES_NUMBER = 11;
  const containerClasses = [
    props.className,
    "departments-menu",
    props.isVisible ? "d-block" : "d-none",
  ];
  const [selectedCategory, setSelectedCategory] = React.useState(null);
  const [isMouseOverMenuItem, setIsMouseOverMenuItem] = React.useState(false);
  const [isMouseOverCategoryDetails, setIsMouseOverCategoryDetails] =
    React.useState(false);
  const [closeTimeout, setCloseTimeOut] = React.useState(null);
  const departmentsMenu = useSelector((e: StoreDto) => e.departmentsMenu);

  // close menu by timeout if cursor leave menu
  if (
    !isMouseOverMenuItem &&
    !isMouseOverCategoryDetails &&
    !closeTimeout &&
    props.isVisible
  ) {
    setCloseTimeOut(
      setTimeout(() => {
        setSelectedCategory(null);
        props.closeMenu();
      }, 1000)
    );
  } else if (
    (isMouseOverMenuItem || isMouseOverCategoryDetails) &&
    closeTimeout
  ) {
    clearTimeout(closeTimeout);
    setCloseTimeOut(null);
  }

  React.useEffect(function () {
    return () => {
      clearTimeout(closeTimeout);
    };
  });

  function groupItemsTemplate(group: Record<any, any>): any {
    const groupItems = [];

    for (const item of group.items) {
      groupItems.push(
        <li className="category-menu-group-item">
          <a
            href={item.link}
            className="category-menu-link category-menu-link__level-3"
          >
            {item.name}
          </a>
        </li>
      );
    }

    return (
      <ul className="list-unstyled p-0 m-0 category-menu-group-list">
        {groupItems}
      </ul>
    );
  }

  function groupsTemplate() {
    if (!selectedCategory) {
      return;
    }

    const groups = [];

    for (const group of selectedCategory.groups) {
      const headerClasses = [
        "category-menu-link-level-2-header",
        {
          "category-menu-link-level-2-header__underlined": !!group.items.length,
        },
      ];

      const item = (
        <div className="group-links-column mb-3">
          <h4 className={classnames(headerClasses)}>
            <a
              href={group.link}
              className="category-menu-link category-menu-link__level-2"
            >
              {group.name}
            </a>
          </h4>
          {groupItemsTemplate(group)}
        </div>
      );

      if (group.items.length > 0) {
        groups.unshift(item);
      } else {
        groups.push(item);
      }
    }

    return groups;
  }

  function topLevelMenuTemplate() {
    const items = [];
    const categories = departmentsMenu.slice(0, MAX_CATEGORIES_NUMBER);

    for (const category of categories) {
      const linkClasses = [
        "category-menu-link category-menu-link__top-level",
        {
          "category-menu-link__selected":
            selectedCategory !== null &&
            category.id === selectedCategory.id &&
            (isMouseOverMenuItem || isMouseOverCategoryDetails),
        },
      ];

      items.push(
        <li className="category-menu-item has-child">
          <a
            href={category.url}
            className={classnames(linkClasses)}
            onMouseOver={() => {
              setSelectedCategory(category);
            }}
          >
            {category.name}
          </a>
        </li>
      );
    }

    return items;
  }

  function categoryLinkTemplate() {
    if (!selectedCategory) {
      return;
    }

    return (
      <div className="category-view-all">
        <a
          href={`${route("catalog:list")}#id${selectedCategory.id}`}
          className="category-view-all-link"
        >
          View all {selectedCategory.name} departments
        </a>
      </div>
    );
  }

  return (
    <div className={classnames(containerClasses)} onClick={props.closeMenu}>
      <section
        className="category-menu-list-container container"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="row me-0">
          <div className="account-page-left-column account-page-left-column__departments-menu col pe-0">
            <div
              className="category-menu-list"
              onMouseOver={() => {
                setIsMouseOverMenuItem(true);
              }}
              onMouseLeave={() => {
                setIsMouseOverMenuItem(false);
              }}
            >
              <ul className="no-bullet list-unstyled m-0">
                {topLevelMenuTemplate()}
              </ul>

              <div className="view-all-container">
                <a href={route("catalog:list")} className="view-all">
                  View all departments
                </a>
              </div>
            </div>
          </div>

          <div
            className="p-0 col"
            onMouseOver={() => setIsMouseOverCategoryDetails(true)}
            onMouseLeave={() => setIsMouseOverCategoryDetails(false)}
            onClick={() => {
              if (selectedCategory === null) {
                props.closeMenu();
              }
            }}
          >
            <div
              className={classnames([
                "account-page-right-column bg-white h-100 category-detailed pt-2 pb-4 position-relative",
                (isMouseOverMenuItem || isMouseOverCategoryDetails) &&
                selectedCategory
                  ? "d-block"
                  : "d-none",
              ])}
            >
              {groupsTemplate()}
              {categoryLinkTemplate()}
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default DepartmentsMenu;
