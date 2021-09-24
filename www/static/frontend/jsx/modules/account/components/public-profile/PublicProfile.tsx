import React from "react";
import { useHistory } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { route } from "@client/jsx/utils/AppData";
import { savePublicProfileAction } from "@client/jsx/redux/actions/account-actions/ProfileActions";
import classnames from "classnames";
import TimesLightIcon from "@client/jsx/components/icons/font-awesome/times/TimesLightIcon";
import InnerPage from "@client/modules/account/components/shared/InnerPage";
import Alert from "@client/modules/account/components/shared/Alert";
import { setAlertAction } from "@client/jsx/redux/actions/account-actions/ProfileActions";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";

const PublicProfile = (): any => {
  const dispatch = useDispatch();
  const history = useHistory();
  const user = useSelector((e: StoreDto) => e.user);

  if (!user) {
    history.push(route("account:login"));
  }

  const alert = useSelector((e: AccountStore) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  const alertShowTimeMs = 3000;
  const maxKB = 10;
  const FILE_SIZE_B = maxKB * 1024;
  const SUPPORTED_FORMATS = ["image/jpg", "image/jpeg", "image/png"];
  const DEFAULT_AVATAR_IMAGE =
    "/static/frontend/images/pages/account/default-avatar.svg";
  const [isRemoveAvatar, setIsRemoveAvatar] = React.useState(false);

  const initialValues = {
    publicName: user.public_name || "",
    location: user.location || "",
    avatar_image: user.avatar_image,
  };

  const validationSchema = yup.object().shape({
    publicName: yup.string().required("Public name is a required field"),
    location: yup.string().max(64, "Password must be at most 64 characters"),
    avatar_image: yup
      .mixed()
      .test("fileSize", `Maximum uploaded file size: ${maxKB} KB`, function () {
        const fileInput: Record<any, any> =
          document.getElementById("avatar_image");

        if (!fileInput.files[0]) {
          return true;
        }

        return fileInput.files[0].size <= FILE_SIZE_B;
      })
      .test("fileType", "Unsupported File Format", function () {
        const fileInput: Record<any, any> =
          document.getElementById("avatar_image");

        if (!fileInput.files[0]) {
          return true;
        }

        return SUPPORTED_FORMATS.includes(fileInput.files[0].type);
      }),
  });

  const inputFileRef = React.useRef<HTMLInputElement>();
  const imageRef = React.useRef<HTMLImageElement>();

  function submit(values, actions) {
    const formData = new FormData();
    const fileInput: Record<any, any> = document.getElementById("avatar_image");

    formData.append("PublicProfileForm[public_name]", values.publicName);
    formData.append("PublicProfileForm[location]", values.location);
    formData.append("remove_avatar", isRemoveAvatar.toString());

    if (fileInput.files[0]) {
      formData.append("PublicProfileForm[avatar_image]", fileInput.files[0]);
    }

    dispatch(
      savePublicProfileAction({
        data: formData,

        success(res) {
          setShow(true);

          dispatch(userSetAction({ ...user, avatar_image: res.avatarUrl }));

          dispatch(
            setAlertAction({
              variant: "success",
              message: "Public profile was updated",
            })
          );

          setTimeout(() => {
            setShow(false);
            setTimeout(() => {
              dispatch(setAlertAction(null));
            }, 500);
          }, alertShowTimeMs);
        },

        error(err) {
          const errors = {};

          for (const fieldName in err.errors) {
            errors[fieldName] = err.errors[fieldName][0];
          }

          actions.setErrors(errors);
        },

        complete() {
          actions.setSubmitting(false);
        },
      })
    );
  }

  /**
   * get current selected image url
   */
  function getAvatarUrl(values): string {
    if (isRemoveAvatar === true) {
      return DEFAULT_AVATAR_IMAGE;
    }

    return values.avatar_image || user.avatar_image || DEFAULT_AVATAR_IMAGE;
  }

  function beforePageTemplate() {
    if (!alert) {
      return;
    }

    return (
      <Alert
        show={show}
        variant={alert?.variant}
        message={alert?.message}
        classes={{
          container: "pt-20 pb-5 pt-lg-0",
          alert: "account-inner-page_alert",
        }}
      />
    );
  }

  return (
    <InnerPage
      header={"Public Profile"}
      bodyClasses={"p-0"}
      beforePage={beforePageTemplate()}
    >
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
          setValues,
        }) {
          function removeAvatarButtonTemplate() {
            return (
              <div
                className={classnames(classes.removeAvatarButton)}
                onClick={() => {
                  setIsRemoveAvatar(true);
                  values.avatar_image = "";
                  setValues(values);
                }}
              >
                <TimesLightIcon className="remove-avatar-icon" />
              </div>
            );
          }

          function avatarInputChangeHandler(e) {
            handleChange(e);

            const file = inputFileRef.current.files[0];
            const fr = new FileReader();

            fr.onload = () => {
              if (typeof fr.result === "string") {
                imageRef.current.src = fr.result;
                setIsRemoveAvatar(false);
              }
            };

            if (file) {
              fr.readAsDataURL(file);
            }
          }

          const classes = {
            removeAvatarButton: [
              "public-profile_remove-avatar position-absolute",
              {
                "d-none": getAvatarUrl(values) === DEFAULT_AVATAR_IMAGE,
              },
            ],
          };

          return (
            <Form>
              <div className="content-panel">
                <p className="account-text mb-3 mb-md-4 mb-lg-20">
                  Public Profile allows you to share a little about yourself
                  with other S3 Stores customers. This is how you’ll be shown to
                  other shoppers on S3 Stores when you post Reviews, Q&A, Lists,
                  and more.
                </p>

                <div className="public-profile-fields-container">
                  <RBForm.Group
                    controlId="PublicProfileFormPublicName"
                    className={"row"}
                  >
                    <div
                      className={
                        "col-12 col-md-6 col-lg-6 text-md-end text-lg-start"
                      }
                    >
                      <RBForm.Label
                        className={
                          "form-input-label form-input-label__required mb-0"
                        }
                      >
                        Public Name
                      </RBForm.Label>

                      <RBForm.Text
                        className={
                          "auth-form-info_input-caption form-group-text d-block mb-10 mb-md-0"
                        }
                      >
                        This is required but can be different to the name
                        associated with your account ({user.name})
                      </RBForm.Text>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <RBForm.Control
                        type="text"
                        name="publicName"
                        value={values.publicName}
                        onChange={handleChange}
                        className={"form-input"}
                        isInvalid={!!touched.publicName && !!errors.publicName}
                        isValid={touched.publicName && !errors.publicName}
                      />

                      <RBForm.Control.Feedback type="invalid">
                        {errors.publicName}
                      </RBForm.Control.Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="PublicProfileFormLocation"
                    className={"row mt-20 mt-md-4 mt-lg-10"}
                  >
                    <div className="col-12 col-md-6 col-lg-6 text-md-end text-lg-start">
                      <RBForm.Label
                        className={
                          "form-input-label form-input-label__optional"
                        }
                      >
                        Location
                      </RBForm.Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <RBForm.Control
                        type="text"
                        name="location"
                        value={values.location}
                        onChange={handleChange}
                        className={"form-input"}
                        isInvalid={!!touched.location && !!errors.location}
                        isValid={touched.location && !errors.location}
                      />

                      <RBForm.Control.Feedback type="invalid">
                        {errors.location}
                      </RBForm.Control.Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="avatar_image"
                    className="mt-md-4 row"
                  >
                    <div className="d-block d-md-none d-lg-flex col-12 col-lg-6 align-items-center">
                      <RBForm.Label
                        className={
                          "form-input-label form-input-label__optional mt-20 md-lg-0"
                        }
                      >
                        Upload a public profile picture
                      </RBForm.Label>
                    </div>

                    <div className="mb-md-3 col-12 col-lg-6">
                      <div className="d-flex justify-content-center justify-content-lg-start">
                        <div className="position-relative">
                          {removeAvatarButtonTemplate()}

                          <RBForm.Control
                            type="file"
                            className="d-none"
                            accept="image/*"
                            ref={inputFileRef}
                            onChange={avatarInputChangeHandler}
                            isInvalid={!!errors.avatar_image}
                            isValid={!errors.avatar_image}
                          />

                          <div
                            className="public-profile-avatar position-relative"
                            onClick={() => {
                              inputFileRef.current.click();
                            }}
                          >
                            {removeAvatarButtonTemplate()}

                            <img
                              className="public-profile-avatar-image"
                              src={getAvatarUrl(values)}
                              alt="avatar"
                              ref={imageRef}
                            />

                            <div className="add-avatar-button public-profile-avatar_button">
                              <i className="photo-camera-icon common-icon" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <RBForm.Control.Feedback
                        type="invalid"
                        className={classnames("text-center text-lg-start", {
                          "d-block": !!errors.avatar_image,
                        })}
                      >
                        {errors.avatar_image}
                      </RBForm.Control.Feedback>
                    </div>

                    <div className="d-none d-md-block d-lg-none col-12 text-center">
                      <RBForm.Label
                        className={
                          "form-input-label form-input-label__optional text-align--center"
                        }
                      >
                        Upload a public profile picture
                      </RBForm.Label>
                    </div>
                  </RBForm.Group>
                </div>
              </div>

              <div className="account-page-footer">
                <div className="justify-content-center text-lg-start d-md-flex">
                  <button
                    type="submit"
                    className="form-button public-profile-footer-button mb-14 mb-md-0"
                    disabled={isSubmitting}
                  >
                    Submit
                  </button>

                  <button
                    type="submit"
                    className="form-button public-profile-footer-button form-button__outline ms-md-12"
                    disabled={isSubmitting}
                  >
                    not now
                  </button>
                </div>
              </div>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default PublicProfile;
