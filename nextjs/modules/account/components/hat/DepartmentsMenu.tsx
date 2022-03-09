import React from "react";
import { route } from "@utils/AppData";
import cn from "classnames";
import LeftColumn from "@modules/layout/components/LeftColumn";
import RightColumn from "@modules/layout/components/RightColumn";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Styles from "@modules/account/components/hat/DepartmentsMenu.module.scss";
import ViewAllDepartmentsIcon from "@modules/icon/components/header/ViewAllDepartments";
import { isMobile } from "react-device-detect";

const DepartmentsMenu = (props: Record<any, any>): any => {
  const MAX_CATEGORIES_NUMBER = 11;
  const containerClasses = [props.className, Styles.departmentsMenu];
  const [selectedCategory, setSelectedCategory] = React.useState<Record<
    any,
    any
  > | null>(null);
  const [isMouseOverMenuItem, setIsMouseOverMenuItem] = React.useState(false);
  const [isMouseOverCategoryDetails, setIsMouseOverCategoryDetails] =
    React.useState(false);
  const [closeTimeout, setCloseTimeOut] = React.useState<any>(null);
  const departmentsMenu = useSelectorAccount((e) => e.departmentsMenu.desktop);

  // close menu by timeout if cursor leave menu
  if (
    !isMouseOverMenuItem &&
    !isMouseOverCategoryDetails &&
    !props.buttonHover &&
    !closeTimeout &&
    props.isVisible
  ) {
    setCloseTimeOut(
      setTimeout(() => {
        setSelectedCategory(null);
        setCloseTimeOut(null);
        props.closeMenu();
      }, 1000)
    );
  } else if (
    (isMouseOverMenuItem || isMouseOverCategoryDetails || props.buttonHover) &&
    closeTimeout
  ) {
    clearTimeout(closeTimeout);
    setCloseTimeOut(null);
  }

  if (props.isVisible === false && selectedCategory !== null) {
    setSelectedCategory(null);
  }

  React.useEffect(function () {
    return () => {
      clearTimeout(closeTimeout);
    };
  });

  function groupItemsTemplate(group: Record<any, any>): any {
    const groupItems = [];

    for (const key in group.items) {
      const { link, name } = group.items[key];

      groupItems.push(
        <li className={Styles.categoryMenuGroupItem} key={key}>
          <a
            href={link}
            className={cn(
              Styles.categoryMenuLink,
              Styles.categoryMenuLink_level_3
            )}
          >
            {name}
          </a>
        </li>
      );
    }

    return (
      <ul className={cn("list-unstyled p-0 m-0", Styles.categoryMenuGroupList)}>
        {groupItems}
      </ul>
    );
  }

  function groupsTemplate() {
    if (!selectedCategory) {
      return;
    }

    const groups = [];

    for (const i in selectedCategory.groups) {
      const group = selectedCategory.groups[i];
      const headerClasses = [
        Styles.categoryMenuLinkLevel2Header,
        {
          [Styles.categoryMenuLinkLevel2Header_underlined]:
            !!group.items.length,
        },
      ];

      const item = (
        <div
          className={cn(
            Styles.groupLinksColumn,
            Styles.categoryDetailed__groupLinksColumn
          )}
          key={i}
        >
          <h4 className={cn(headerClasses)}>
            <a
              href={group.link}
              className={cn(
                Styles.categoryMenuLink,
                Styles.categoryMenuLink_level_2
              )}
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

    for (const key in categories) {
      const category: Record<any, any> = categories[key];
      const linkClasses = [
        Styles.categoryMenuLink,
        Styles.categoryMenuLink_topLevel,
        {
          [Styles.categoryMenuLink_selected]:
            selectedCategory !== null &&
            category.id === selectedCategory.id &&
            (isMouseOverMenuItem || isMouseOverCategoryDetails),
        },
      ];

      items.push(
        <li className={cn("has-child")} key={key}>
          <a
            href={category.url}
            className={cn(linkClasses)}
            onClick={(e) => {
              if (selectedCategory !== category) {
                setSelectedCategory(category);
                e.preventDefault();
              }
            }}
            onTouchStart={(e) => {
              e.preventDefault();
            }}
            onMouseOver={() => {
              !isMobile && setSelectedCategory(category);
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
      <div className={Styles.categoryViewAll}>
        <a
          href={`${route("catalog:list")}#id${selectedCategory.id}`}
          className={Styles.categoryViewAllLink}
        >
          <ViewAllDepartmentsIcon className={Styles.categoryViewAllLinkIcon} />
          View all {selectedCategory.name} departments
        </a>
      </div>
    );
  }

  return (
    <TransitionFade show={props.isVisible}>
      <div className={cn(containerClasses)} onClick={props.closeMenu}>
        <section
          className={cn(Styles.categoryMenuListContainer, "container")}
          onClick={(e) => e.stopPropagation()}
        >
          <div className="row me-0">
            <LeftColumn className="col pe-0">
              <div
                className={cn(Styles.categoryMenuList)}
                onMouseOver={() => {
                  setIsMouseOverMenuItem(true);
                }}
                onMouseLeave={() => {
                  setIsMouseOverMenuItem(false);
                }}
              >
                <ul className="list-unstyled m-0">{topLevelMenuTemplate()}</ul>

                <div className={cn(Styles.viewAllContainer)}>
                  <a
                    href={route("catalog:list")}
                    className={cn(Styles.viewAll)}
                  >
                    <ViewAllDepartmentsIcon
                      className={Styles.categoryViewAllLinkIcon}
                    />
                    View all departments
                  </a>
                </div>
              </div>
            </LeftColumn>

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
              <RightColumn
                className={cn([
                  "position-relative",
                  Styles.categoryDetailed,
                  (isMouseOverMenuItem ||
                    isMouseOverCategoryDetails ||
                    props.buttonHover) &&
                  selectedCategory
                    ? "d-block"
                    : "d-none",
                ])}
              >
                {groupsTemplate()}
                {categoryLinkTemplate()}
              </RightColumn>
            </div>
          </div>
        </section>
      </div>
    </TransitionFade>
  );
};

export default DepartmentsMenu;
