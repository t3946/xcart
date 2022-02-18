import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { Form as RBForm } from "react-bootstrap";
import { Formik, Form, FormikHelpers } from "formik";
import { useDispatch } from "react-redux";
import { disableAction } from "@redux/actions/account-actions/TSVActions";
import { AxiosResponse } from "axios";
import { userSetAction } from "@redux/actions/account-actions/UserActions";

interface propsDto {
  show: boolean;
  onClose: () => void;
  onConfirm: () => void;
  ajaxSending: boolean;
}

const ModalTSVDisable: React.FC<any> = function (props: propsDto) {
  const dispatch = useDispatch();
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
          props.onClose();
        },
      })
    );
  }

  return (
    <BootstrapDialogHOC
      show={props.show}
      title={"Disable 2SV ?"}
      onClose={props.onClose}
    >
      <p className={"account-modal-text"}>
        By disabling Two-Step Verification, OTP will no longer be required to
        Sign-In to your account.
      </p>

      <Formik initialValues={initialValues} onSubmit={submit}>
        {function ({ isSubmitting, values, handleChange }) {
          return (
            <Form>
              <div>
                <RBForm.Group className={"mb-4"}>
                  <input
                    checked={values.deleteSettings}
                    id="confirmDisableTSVField"
                    name="deleteSettings"
                    className="form-checkbox"
                    type="checkbox"
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
                  className="admin-form-control form-button d-inline-block w-auto"
                  disabled={isSubmitting}
                  type={"submit"}
                >
                  disable
                </button>

                <button
                  className="form-button form-button__outline d-inline-block w-auto ms-12"
                  onClick={props.onClose}
                  disabled={isSubmitting}
                  type={"button"}
                >
                  cancel
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </BootstrapDialogHOC>
  );
};

export default ModalTSVDisable;
