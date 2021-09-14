import React from "react";
import { Link, useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import classnames from "classnames";
import { getCountryByCode } from "@client/jsx/utils/Countries";
import Alert from "@client/modules/account/components/shared/Alert";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { setAlertAction } from "@client/jsx/redux/actions/account-actions/LoginAndSecurityActions";
import InnerPage from "@client/modules/account/components/shared/InnerPage";

const LoginAndSecurity = (): any => {
  const dispatch = useDispatch();
  const history = useHistory();
  const user = useSelector((e: StoreDto) => e.user);
  const countries = useSelector((e: AccountStore) => e.countries);
  const alert = useSelector((e: AccountStore) => e.loginAndSecurity.alert);
  const [show, setShow] = React.useState(alert !== null);
  const ALERT_SHOW_TIME_MS = 3000;

  if (!user) {
    history.push(route("account:login"));
    return;
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
      route: route("account:two-step-verification-settings"),
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

  if (alert) {
    setTimeout(() => {
      setShow(false);
      setTimeout(() => {
        dispatch(setAlertAction(null));
      }, 500);
    }, ALERT_SHOW_TIME_MS);
  }

  function formatPhoneNumber() {
    const phoneCountry = getCountryByCode(user.phone_country_code, countries);

    if (!phoneCountry) {
      return;
    }

    const countryPrefix = "+" + phoneCountry.phone_code;
    return user.phone.replace(countryPrefix, `${countryPrefix} `);
  }

  function settingsItemsTemplate() {
    const items = [];

    for (const listItem of listItems) {
      items.push(
        <li className="login-and-security-settings-item login-and-security-settings_item">
          <div className="login-and-security-settings-item-container">
            <div className={"login-and-security-settings-item-text"}>
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

            <button
              onClick={() => history.push(listItem.route)}
              className="form-button form-button__outline login-and-security-edit-button d-block d-md-inline-block mt-12 mt-md-0"
            >
              edit
            </button>
          </div>
        </li>
      );
    }

    return items;
  }

  return (
    <>
      <InnerPage
        beforePage={
          <Alert
            show={show}
            variant={alert?.variant}
            message={alert?.message}
            classes={{ container: "pt-20 pt-lg-0 pb-5" }}
          />
        }
        header={"Login & security"}
        bodyClasses={"content-panel login-and-security-content-panel p-0"}
        footer={
          <button
            className={
              "admin-form-control form-button w-md-auto d-inline-block"
            }
            onClick={() => history.push(route("account:dashboard"))}
          >
            done
          </button>
        }
      >
        <ul className={"list-unstyled m-0"}>{settingsItemsTemplate()}</ul>
      </InnerPage>
    </>
  );
};

export default LoginAndSecurity;
