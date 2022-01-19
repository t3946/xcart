import React from "react";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import { Form as RBForm, OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import * as yup from "yup";
import { Form, Formik, FormikHelpers } from "formik";
import { confirmCodeAction } from "@redux/actions/account-actions/TSVActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import Link from "next/link";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import Styles from "@modules/account/components/login-and-security/TSVAddNewApp.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AxiosResponse } from "axios";

interface IProps {
  tsv: {
    url: string;
    secret: string;
  };
}

const TSVAddNewApp = (props: IProps): any => {
  const { tsv } = props;
  const user = useSelectorAccount((e) => e.user);
  const router = useRouter();
  const dispatch = useDispatch();
  const initialValues = {
    code: "",
  };

  const validationSchema = yup.object().shape({
    code: yup.string().required("Code required"),
  });

  if (!user) {
    return null;
  }

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    dispatch(
      confirmCodeAction({
        data: values,

        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
          router.push("/login-and-security/two-step-verification-settings");
        },

        error(err: any) {
          actions.setErrors(err);
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  return (
    <div>
      <div className="account-page_hat">
        <h1 className={"md-0 text-center text-lg-start"}>
          Add a second 2SV authenticator
        </h1>

        <p>
          If you would like to add another backup method, you can do so. If you
          don't have access to your preferred method, you can use a backup
          method in order to sign in.
        </p>
      </div>

      <div className="content-panel">
        <h2>Authenticator App</h2>

        <p>
          Generate OTP using an application. No network connectivity required.
        </p>

        <p>
          Rather than having a One Time Password (OTP) texted to you every time
          you Sign-In, you will use an Authenticator app on your phone to
          generate an OTP. You will enter the generated OTP at Sign-In the same
          way as with texted OTP.
        </p>

        <ol>
          <li>
            <b>Open</b> your Authenticator App.
            <OverlayTrigger
              trigger="click"
              placement="top"
              delay={{ show: 250, hide: 1000 }}
              overlay={
                <Tooltip
                  id="tooltip-details"
                  className={"common-tooltip common-tooltip__login-form"}
                >
                  <h2 className={"common-tooltip-header"}>
                    <b>Your phone number</b>
                  </h2>

                  <p className={"text-align--left auth-form-info mb-0"}>
                    This is the number listed as your Mobile Phone Number in
                    Account Settings. During 2SV challenges, this phone number
                    will be included as an option to receive the One Time
                    Password (OTP). To change your phone number,{" "}
                    <Link
                      href={{
                        pathname: "/login-and-security/edit-phone",
                        query: {
                          from: "/login-and-security/two-step-verification-add-new",
                        },
                      }}
                    >
                      click here
                    </Link>
                  </p>
                </Tooltip>
              }
            >
              <span className={"common-link ms-lg-2 d-block d-lg-inline-block"}>
                Need an app
                <FontAwesomeIcon
                  className={"ms-1 two-step-learn-more"}
                  icon={faQuestionCircle}
                />
              </span>
            </OverlayTrigger>
          </li>

          <li>
            <b>Add</b> an account within the app, and scan the barcode below.
            <div>
              <div>
                <img
                  className={"tsv-qr-code my-12 my-md-14"}
                  src={tsv.url}
                  alt="Scan QR Code"
                  width="120"
                  height="120"
                />
              </div>

              <OverlayTrigger
                placement="top"
                trigger="click"
                delay={{ show: 250, hide: 250 }}
                overlay={
                  <Tooltip
                    id="tooltip-details"
                    className={"common-tooltip common-tooltip__login-form"}
                  >
                    <h2 className={"common-tooltip-header"}>
                      <b>Can't scan the barcode?</b>
                    </h2>

                    <div className={"text-align--left auth-form-info mb-0"}>
                      <ol className={"mb-0"}>
                        <li>
                          Open your Authenticator App and select "Manually add
                          account" from the menu.
                        </li>
                        <li>
                          In "Enter account name" type your full email address.
                        </li>
                        <li>
                          In "Enter your key" type the following key (space not
                          required):
                          <br />
                          <b>{tsv.secret}</b>
                        </li>
                        <li>Set key type to "Time based"</li>
                        <li>Tap Add</li>
                      </ol>
                    </div>
                  </Tooltip>
                }
              >
                <span className={"common-link"}>
                  Can't scan the barcode?
                  <FontAwesomeIcon
                    className={"ms-1 two-step-learn-more"}
                    icon={faQuestionCircle}
                  />
                </span>
              </OverlayTrigger>
            </div>
          </li>

          <li>
            <b>Enter OTP.</b> After you've scanned the barcode, enter the OTP
            generated by the app:
          </li>
        </ol>

        <div className="row">
          <div className="col-12">
            <Formik
              initialValues={initialValues}
              validationSchema={validationSchema}
              onSubmit={submit}
            >
              {function ({
                isSubmitting,
                values,
                errors,
                touched,
                handleChange,
              }) {
                return (
                  <Form>
                    <RBForm.Group
                      controlId="ChangePassword"
                      className="row mb-4 mb-md-0"
                    >
                      <div className={"col-12"}>
                        <Input
                          type="text"
                          name="code"
                          value={values.code}
                          onChange={handleChange}
                          className={[Styles.tsvCodeField, "d-inline-block"]}
                          isInvalid={!!touched.code && !!errors.code}
                          isValid={touched.code && !errors.code}
                          autoComplete={"off"}
                        />

                        <button
                          className={
                            "admin-form-control form-button form-button__wide w-md-auto d-none d-md-inline-block ms-md-3"
                          }
                          disabled={isSubmitting}
                        >
                          Verify OTP and continue
                        </button>

                        <Feedback type="invalid">
                          {touched.code && errors.code}
                        </Feedback>
                      </div>
                    </RBForm.Group>

                    <div className="content-panel_footer d-md-none">
                      <button
                        className={
                          "admin-form-control form-button form-button__wide w-md-auto d-inline-block"
                        }
                      >
                        Verify OTP and continue
                      </button>
                    </div>
                  </Form>
                );
              }}
            </Formik>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TSVAddNewApp;
