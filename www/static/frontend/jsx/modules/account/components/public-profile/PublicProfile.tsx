import React from "react";
import { Redirect } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import * as yup from "yup";
import { route } from "@client/jsx/utils/AppData";
import { savePublicProfileAction } from "@client/jsx/redux/actions/account-actions/ProfileActions";
import classnames from "classnames";

const PublicProfile = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const FILE_SIZE_B = 100 * 1024;
  const SUPPORTED_FORMATS = ["image/jpg", "image/jpeg", "image/png"];
  const DEFAULT_AVATAR_IMAGE =
    "/static/frontend/images/pages/account/default-avatar.svg";

  const initialValues = {
    publicName: "Coach",
    location: "Couch",
    avatar_image: null,
  };

  const validationSchema = yup.object().shape({
    publicName: yup.string().required("Public name is a required field"),
    location: yup.string().max(64, "Password must be at most 64 characters"),
    avatar_image: yup
      .mixed()
      .test("fileSize", "Maximum uploaded file size: 100 KB", function () {
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

  const inputFileRef = React.useRef();

  function submit(values, actions) {
    const formData = new FormData();
    const fileInput: Record<any, any> = document.getElementById("avatar_image");

    formData.append("PublicProfileForm[publicName]", values.publicName);
    formData.append("PublicProfileForm[location]", values.location);
    formData.append("PublicProfileForm[avatar_image]", fileInput.files[0]);

    dispatch(
      savePublicProfileAction({
        data: formData,

        success(res) {},

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

  function showSelectedImage() {
    const img = document.getElementsByClassName(
      "public-profile-avatar-image"
    )[0];
    const avatar_image: Record<any, any> =
      document.getElementById("avatar_image");
    const file = avatar_image.files[0];

    img.setAttribute("src", URL.createObjectURL(file));
  }

  return (
    <>
      {!user && <Redirect to={route("account:login")} />}

      <h1 className="page-label">Public Profile</h1>

      <div className="public-profile-content">
        <p className="account-text">
          Public Profile allows you to share a little about yourself with other
          S3 Stores customers. This is how you’ll be shown to other shoppers on
          S3 Stores when you post Reviews, Q&A, Lists, and more.
        </p>

        <Formik
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={submit}
        >
          {function ({ isSubmitting, values, errors, touched, handleChange }) {
            console.log(errors);
            return (
              <Form>
                <RBForm.Group
                  controlId="PublicProfileFormPublicName"
                  className={"row m-0"}
                >
                  <div
                    className={
                      "col-12 col-md-6 col-lg-12 pr-md-3 p-0 pl-0 pr-lg-0 text-md-right text-lg-left"
                    }
                  >
                    <RBForm.Label
                      className={
                        "form-input-label form-input-label__required mb-0"
                      }
                    >
                      Public Name
                    </RBForm.Label>

                    <div className="form-input-caption mb-2.5">
                      This is required but can be different to the name
                      associated with your account {user.name}
                    </div>
                  </div>

                  <div
                    className={
                      "col-12 col-md-6 col-lg-12 p-0 pr-0 pl-md-3 pl-lg-0"
                    }
                  >
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
                  className={"row m-0"}
                >
                  <div className="col-12 col-md-6 col-lg-12 pr-md-3 p-0 pl-0 pr-lg-0 text-md-right text-lg-left">
                    <RBForm.Label
                      className={"form-input-label form-input-label__optional"}
                    >
                      Location
                    </RBForm.Label>
                  </div>

                  <div
                    className={
                      "col-12 col-md-6 col-lg-12 p-0 pr-0 pl-md-3 pl-lg-0"
                    }
                  >
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

                <RBForm.Group controlId="avatar_image" className="mb-3 mt-md-4">
                  <RBForm.Label
                    className={
                      "form-input-label form-input-label__optional d-block d-md-none d-lg-block"
                    }
                  >
                    Upload a public profile picture
                  </RBForm.Label>

                  <div className="mb-md-3">
                    <div className="d-flex justify-content-center">
                      <RBForm.Control
                        type="file"
                        className="d-none"
                        accept="image/*"
                        ref={inputFileRef}
                        onChange={(e) => {
                          handleChange(e);
                          showSelectedImage();
                        }}
                        isInvalid={
                          !!touched.avatar_image && !!errors.avatar_image
                        }
                        isValid={touched.avatar_image && !errors.avatar_image}
                      />
                      <div
                        className="public-profile-avatar position-relative"
                        onClick={() => {
                          inputFileRef.current.click();
                        }}
                      >
                        <img
                          className="public-profile-avatar-image"
                          src={user.avatar_image || DEFAULT_AVATAR_IMAGE}
                          alt="avatar"
                        />

                        <div className="add-avatar-button public-profile-avatar_button">
                          <i className="photo-camera-icon common-icon" />
                        </div>
                      </div>
                    </div>

                    <RBForm.Control.Feedback
                      type="invalid"
                      className={classnames("text-md-center", {
                        "d-block":
                          !!errors.avatar_image && touched.avatar_image,
                      })}
                    >
                      {errors.avatar_image}
                    </RBForm.Control.Feedback>
                  </div>

                  <RBForm.Label
                    className={
                      "form-input-label form-input-label__optional d-none d-md-block d-lg-none text-align--center "
                    }
                  >
                    Upload a public profile picture
                  </RBForm.Label>
                </RBForm.Group>

                <button
                  type="submit"
                  className="admin-form-control form-button"
                  disabled={isSubmitting}
                >
                  Submit
                </button>
              </Form>
            );
          }}
        </Formik>
      </div>
    </>
  );
};

export default PublicProfile;
/**
 * У меня есть выбор  картинки в форме. Я думаю сделать так, чтобы после выбора — картинка отправилась на сервер и там сохранилось с пометкой "Временная" (это значит, что её видно лишь в текущем сеансе редактирования формы), после чего сервер отдал ссылку на картинку и я её вывел в форме. если была нажата кнопка submit, то пометка "временная "у картинки убиралась.
 */
