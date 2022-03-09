import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import { Form as RBForm } from "react-bootstrap";
import { disableAction } from "@redux/actions/account-actions/TSVActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons/faArrowLeft";
import { Formik, Form, FormikHelpers } from "formik";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import { AxiosResponse } from "axios";

const TSVDisable: React.FC<any> = function () {
  const dispatch = useDispatch();
  const router = useRouter();
  const initialValues = {
    deleteSettings: false,
  };

  function submit(values: any, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    dispatch(
      disableAction({
        data: values,
        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
          router.push("/login-and-security/two-step-verification-settings");
        },
      })
    );
  }

  return (
    <>
      <div className="row back-button-container">
        <div className="d-flex col">
          <button
            className={"form-button form-button__outline rounded-0 w-auto"}
            onClick={() =>
              router.push("/login-and-security/two-step-verification-settings")
            }
          >
            <FontAwesomeIcon icon={faArrowLeft} className={"me-3"} />
            <span>back</span>
          </button>
        </div>
      </div>

      <InnerPage
        header="Disable 2SV ?"
        bodyClasses={StylesLoginAndSecurity.pageBody}
      >
        <Formik initialValues={initialValues} onSubmit={submit}>
          {function ({ isSubmitting, values, handleChange }) {
            return (
              <Form>
                <div className={"px-10 px-lg-0"}>
                  <p className={"m-0"}>
                    By disabling Two-Step Verification, OTP will no longer be
                    required to Sign-In to your account.
                  </p>

                  <RBForm.Group className={"mb-4 mt-20"}>
                    <input
                      id="confirmDisableTSVField"
                      name={"deleteSettings"}
                      className="form-checkbox"
                      type="checkbox"
                      checked={values.deleteSettings}
                      onChange={handleChange}
                    />

                    <RBForm.Label
                      className={
                        "checkbox-label mb-0 align-items-center d-flex form-label account-modal-text"
                      }
                      htmlFor={"confirmDisableTSVField"}
                    >
                      Also clear my Two-Step Verification settings
                    </RBForm.Label>
                  </RBForm.Group>
                </div>

                <div className={"text-center text-md-start"}>
                  <button
                    className="admin-form-control form-button d-inline-block"
                    disabled={isSubmitting}
                    type={"submit"}
                  >
                    disable
                  </button>

                  <button
                    className="form-button form-button__outline d-inline-block mt-14 mt-lg-0"
                    onClick={() =>
                      router.push(
                        "/login-and-security/two-step-verification-settings"
                      )
                    }
                    type={"button"}
                  >
                    cancel
                  </button>
                </div>
              </Form>
            );
          }}
        </Formik>
      </InnerPage>
    </>
  );
};

export default TSVDisable;
