import React, { useContext } from "react";
import { NavLink, Redirect } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";
import classnames from "classnames";
import { getCountryByCode } from "@client/jsx/utils/Countries";

const LoginAndSecurity = (): any => {
  const user = useSelector((e: StoreDto) => e.user);
  const { showSnackbar } = useContext(SnackbarContext);
  const countries = useSelector((e: any) => e.countries);

  function formatPhoneNumber() {
    const phoneCountry = getCountryByCode(user.phone_country_code, countries);
    const countryPrefix = "+" + phoneCountry.phone_code;
    return user.phone.replace(countryPrefix, `${countryPrefix} `);
  }

  const listItems = [
    {
      title: "full name",
      caption: user.name,
      route: route("account:edit-name"),
    },
    {
      title: "email",
      caption: user.email,
      route: route("account:edit-email"),
    },
    {
      title: "mobile phone number",
      caption: formatPhoneNumber(),
      route: route("account:edit-phone"),
    },
    {
      title: "password",
      caption: "********",
      route: route("account:edit-password"),
    },
    {
      title: "two-step verification (2SV) settings",
      caption: "Manage your Two-Step Verification (2SV) Authenticators",
      classes: {
        caption: "settings-item-caption__small",
      },
      route: "",
    },
    {
      title: "secure your account",
      caption:
        "If you think your S3 Stores account has been compromised, follow these steps to make your account more secure",
      classes: {
        caption: "settings-item-caption__small",
      },
      route: "",
    },
  ];

  function settingsItemsTemplate() {
    const items = [];

    for (const listItem of listItems) {
      items.push(
        <li className="login-and-security-settings-item">
          <div className="d-flex align-items-center justify-content-between">
            <div>
              <b className="settings-item-title">{listItem.title}:</b>
              <br />
              <span
                className={classnames(
                  "settings-item-caption",
                  listItem.classes?.caption
                )}
              >
                {listItem.caption}
              </span>
            </div>

            <NavLink
              to={listItem.route}
              exact={true}
              className="common-link login-and-security_submit-button d-inline-block text-decoration-none"
            >
              <button className={"form-button form-button__outline w-auto"}>
                edit
              </button>
            </NavLink>
          </div>
        </li>
      );
    }

    return items;
  }

  return (
    <>
      <div className="page-label">Login & security</div>
      {!user && <Redirect to={route("account:login")} />}

      <div className="content-panel login-and-security-settings-panel p-0">
        <ul className={"list-unstyled m-0"}>{settingsItemsTemplate()}</ul>
      </div>

      <NavLink
        to={route("account:dashboard")}
        exact={true}
        className="common-link login-and-security_submit-button d-inline-block mt-4 text-decoration-none"
      >
        <button
          className={"admin-form-control form-button w-md-auto d-inline-block"}
        >
          done
        </button>
      </NavLink>
    </>
  );
};

export default LoginAndSecurity;
