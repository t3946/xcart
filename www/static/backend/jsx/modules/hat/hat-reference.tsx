import React from "react";
import { Dropdown } from "react-bootstrap";
import { RoundedCornerDoubleIcon } from "@admin/icons/icons";
import { RoundedCornerIcon } from "@admin/icons/icons";
import appData from "@admin/utils/app-data";
import DropPopoverMenu from "@admin/modules/common/components/drop-popover-menu/drop-popover-menu";
import $ from "jquery";

const HatReference: React.FC<any> = function () {
  const [logo, setLogo] = React.useState(appData().hat.site.logoUrl);
  const menuRef = React.createRef();

  function logout() {
    $.ajax({
      url: "/admin/logout",
      method: "POST",
      success(res) {
        document.location.reload();
      },
    });
  }

  function timeTemplate(): any {
    const timeList = [];

    for (const time of appData().hat.time) {
      timeList.push(
        <li className="time-item time-list_item" title={time.title}>
          <span className="time-number">{time.time}</span>
          <span className="time-cation">{time.caption}</span>
        </li>
      );
    }

    return <ul className="m-0 time-list list-unstyled">{timeList}</ul>;
  }

  const closeMenu = function () {
    document.body.click();
  };

  function menuTemplate(): any {
    const items = [];

    for (const site of appData().hat.sites) {
      items.push(
        <Dropdown.Item
          className="pl-3.25 pr-3.25 pt-1 pb-1 drop-down-item clickable clickable__yellow"
          data-value={site.id}
          title={`(${site.code}) ${site.name}`}
        >
          <img
            src={"/static/backend/dist/images/icons/sites/" + site.icon}
            width={24}
            height={24}
            className="mr-4"
          />
          {site.name}
        </Dropdown.Item>
      );
    }

    return (
      <React.Fragment>
        <div
          className="select-distributor-menu-logo pl-3.25 pr-3.25 pt-3 pb-4 d-flex justify-content-between align-items-center pointer"
          onClick={closeMenu}
        >
          <img src={logo} alt={appData().hat.site.name} width="130" />
          <RoundedCornerIcon />
        </div>

        <ul className="list-unstyled mb-0 drop-popover-menu">{items}</ul>
      </React.Fragment>
    );
  }

  function loginButton(): any {
    if (!appData().hat.user) {
      return (
        <a
          href="/admin/error_message.php?antibot_error"
          className="logout-button button clickable clickable__yellow"
        >
          Log in
          <RoundedCornerDoubleIcon className="logout-button_icon ml-2.5" />
        </a>
      );
    } else {
      return (
        <React.Fragment>
          <button
            className="logout-button button clickable clickable__yellow"
            onClick={logout}
          >
            Log out
            <RoundedCornerDoubleIcon className="logout-button_icon ml-2.5" />
          </button>

          <div className="logged-as mt-2">
            {appData().hat.user} is logged in!
          </div>
        </React.Fragment>
      );
    }
  }

  function selectSiteTemplate(): any {
    if (!appData().user) {
      return (
        <img className="hat-logo" src={logo} alt={appData().hat.site.name} />
      );
    }

    return (
      <DropPopoverMenu
        button={
          <img className="hat-logo" src={logo} alt={appData().hat.site.name} />
        }
        menu={menuTemplate()}
        menuClasses="pb-3 pt-0"
        ref={menuRef}
        onSelect={(value) => {
          $.ajax({
            url: `/admin/sites/set-site/${value}`,
            method: "POST",
            dataType: "json",
            success(res) {
              setLogo(res.logoUrl);
            },
          });
        }}
      />
    );
  }

  return (
    <div className="pt-4 pb-4">
      <div className="row">
        <div className="col column__left">{selectSiteTemplate()}</div>

        <div className="col">
          <div className="column-right-wrapper">
            <div className="holiday-block hat_holiday-block">
              <div className="hat-date">{appData().hat.date}</div>
              <div className="until-holiday">{appData().hat.holiday}</div>
            </div>

            <div className="time-block">{timeTemplate()}</div>
          </div>
        </div>
        <div className="col column__right align-items-end d-flex flex-column">
          {loginButton()}
        </div>
      </div>
    </div>
  );
};

export default HatReference;
