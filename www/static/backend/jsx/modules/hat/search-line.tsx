import React from "react";
import DropPopoverMenu from "@admin/modules/common/components/drop-popover-menu/drop-popover-menu";
import appData from "@admin/utils/app-data";
import { RoundedCornerIcon } from "@admin/icons/rounded-corner";
import { EmailSelect } from "@s3stores-mail/components/smart/email-select/EmailSelect";
import { HatSelect } from "@admin/modules/hat/hat-select";

const SearchLine: React.FC<any> = function (props: any) {
  const menuRef = React.createRef();

  const selectOptions = [
    {
      value: "id",
      viewValue: "Order # / Amazon order ID",
    },
    {
      value: "order_po",
      viewValue: "PO",
    },
    {
      value: "zip",
      viewValue: "Zip code",
    },
  ];

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

          <div className="col align-items-center d-flex justify-content-between state-line_column-right">
            <div className="row flex-grow-1">
              <div className="col-5">
                <form
                  action="/admin/dashboard/fast-search"
                  method="GET"
                  className="align-items-center d-flex"
                >
                  <HatSelect
                    name="search_type"
                    items={selectOptions}
                    className="mr-1"
                  />

                  <input
                    id="search-string"
                    type="text"
                    name="search_string"
                    className="search-string mr-1"
                    autoComplete="off"
                  />

                  <button type="submit" className="search-button">
                    Search
                  </button>
                </form>
              </div>

              <div className="col-4 align-items-center d-flex">
                <div className="hat_customer-care d-flex align-items-center">
                  <img
                    className="common-icon"
                    src="/static/backend/dist/images/icons/hat/search-line/heart.svg"
                    alt="🖤"
                  />

                  <a
                    className="customer-care-link ml-2.25"
                    href="/admin/dashboard"
                  >
                    Customer Care Dashboard
                  </a>
                </div>
              </div>

              <div className="col-3 d-flex justify-content-end">
                <form
                  action="/admin/dashboard/fast-search"
                  method="GET"
                  className="align-items-center d-flex"
                >
                  <input
                    id="search-string"
                    type="text"
                    name="search_string"
                    placeholder="Product SKU"
                    className="search-string mr-1"
                    autoComplete="off"
                  />

                  <button type="submit" className="search-button">
                    Search
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SearchLine;
