import React from "react";
import DropPopoverMenu from "@admin/modules/common/components/drop-popover-menu/drop-popover-menu";
import appData from "@admin/utils/app-data";
import { RoundedCornerIcon } from "@admin/icons/rounded-corner";

const SearchLine: React.FC<any> = function (props: any) {
  const menuRef = React.createRef();

  const menuListHatTemplate = function () {
    return (
      <div className="search-line-menu-hat" onClick={closeMenu}>
        <span>Quick links</span>
        <RoundedCornerIcon />
      </div>
    );
  };

  const closeMenu = function () {
    document.body.click();
  };

  function menuListItemsTemplate() {
    const links = [];

    for (const link of appData().hat.quickLinks) {
      links.push(
        <li>
          <a className="quick-link" href={link.route}>
            {link.title}
          </a>
        </li>
      );
    }

    return <ul className="list-unstyled m-0">{links}</ul>;
  }

  function menuListTemplate() {
    return (
      <div>
        {menuListHatTemplate()}
        {menuListItemsTemplate()}
      </div>
    );
  }

  function menuButtonTemplate() {
    return (
      <div className="search-line-menu-button d-flex align-items-center">
        <b>Quick links</b>
      </div>
    );
  }

  return (
    <div className="search-line">
      <div className="container">
        <div className="row">
          <div className="col layout-column__left">
            <DropPopoverMenu
              button={menuButtonTemplate()}
              menu={menuListTemplate()}
              menuClasses="pb-2 pt-0"
              ref={menuRef}
            />
          </div>
          <div className="col layout-column__right align-items-center d-flex">
            <form className="align-items-center d-flex">
              <label className="search-label m-0" htmlFor="search-string">
                <b>Order # / PO # / Zip code / SKU</b>
              </label>

              <input
                id="search-string"
                type="text"
                name="search-string"
                className="search-string ml-2 mr-1"
                size="18"
              />

              <button type="submit" className="search-button">
                Search
              </button>
            </form>

            <div className="hat_customer-care">
              <img
                className="common-icon"
                src="/static/backend/dist/images/icons/hat/search-line/heart.svg"
                alt="🖤"
              />

              <a className="customer-care-link ml-2.25" href="/admin/dashboard">
                Customer Care Dashboard
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SearchLine;
