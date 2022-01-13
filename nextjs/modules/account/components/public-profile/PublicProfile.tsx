import React from "react";
import Link from "next/link";
import { useDispatch } from "react-redux";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import * as yup from "yup";
import { savePublicProfileAction } from "@redux/actions/account-actions/ProfileActions";
import classnames from "classnames";
import TimesLightIcon from "@modules/components/icons/font-awesome/times/TimesLightIcon";
import InnerPage from "@modules/account/components/shared/InnerPage";
import Alert from "@modules/account/components/shared/Alert";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import AvatarEditor from "@modules/account/components/public-profile/AvatarEditor";
import dataURItoBlob from "@utils/dataURItoBlob";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const PublicProfile = (): any => {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  if (!user) {
    return null;
  }

  const dispatch = useDispatch();
  const alert = useSelectorAccount((e) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  const alertShowTimeMs = 3000;
  const maxMB = 10;
  const SUPPORTED_FORMATS = ["image/jpg", "image/jpeg", "image/png"];
  const DEFAULT_AVATAR_IMAGE =
    "/static/frontend/images/pages/account/default-avatar.svg";
  const [isRemoveAvatar, setIsRemoveAvatar] = React.useState(false);
  const [avatarRaw, setAvatarRaw] = React.useState("");
  const [isOpenAvatarEditor, setIsOpenAvatarEditor] = React.useState(false);
  const [avatarDataUrl, setAvatarDataUrl] = React.useState("");

  const initialValues = {
    publicName: user.public_name || "",
    location: user.location || "",
    avatar_image: user.avatar_image,
  };

  const nameRegex = /^[A-Za-z][A-Za-z0-9 .\-']+$/;
  const inputFileRef = React.useRef<HTMLInputElement>();
  const imageRef = React.useRef<HTMLImageElement>();
  const imagePreviewRef = React.useRef<HTMLDivElement>();

  const validationSchema = yup.object().shape({
    publicName: yup
      .string()
      .matches(nameRegex, "Incorrect name")
      .min(3, "Public name must be at least 3 characters")
      .max(32, "Public name must be at most 32 characters")
      .required("Public name is a required field"),
    location: yup.string(),
    avatar_image: yup
      .mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxMB} MB`,
        validatorMaxFileSize(inputFileRef, maxMB)
      )
      .test(
        "fileType",
        "Unsupported File Format",
        validatorFileFormat(inputFileRef, SUPPORTED_FORMATS)
      ),
  });

  function submit(values, actions) {
    const formData = new FormData();

    formData.append("PublicProfileForm[public_name]", values.publicName);
    formData.append("PublicProfileForm[location]", values.location);
    formData.append("remove_avatar", isRemoveAvatar.toString());

    if (avatarDataUrl) {
      const blob = dataURItoBlob(avatarDataUrl);
      const avatarFile = new File([blob], "avatar.jpg");

      formData.append("PublicProfileForm[avatar_image]", avatarFile);
    }

    dispatch(
      savePublicProfileAction({
        data: formData,

        success(res) {
          setShow(true);
          user.public_name = values.publicName;
          user.location = values.location;

          dispatch(userSetAction({ ...user, avatar_image: res.avatarUrl }));

          dispatch(
            setAlertAction({
              variant: "success",
              message: "Public profile was updated",
            })
          );

          setTimeout(() => {
            setTimeout(() => {
              setShow(false);
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
  function getAvatarUrl(): string {
    if (isRemoveAvatar === true) {
      return DEFAULT_AVATAR_IMAGE;
    }

    return user.avatar_image || DEFAULT_AVATAR_IMAGE;
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

  function avatarEditorTemplate() {
    function imageEditHandler(dataUrl: string) {
      imageRef.current.src = dataUrl;
      setAvatarDataUrl(dataUrl);
    }

    if (isOpenAvatarEditor && !show) {
      return (
        <AvatarEditor
          imageRaw={avatarRaw}
          imageChange={imageEditHandler}
          preview={imagePreviewRef.current}
        />
      );
    }
  }

  function avatarImageTemplate() {
    const classes = {
      image: [
        "public-profile-avatar-image",
        {
          "d-none": isOpenAvatarEditor,
        },
      ],
      imagePreview: [
        "avatar-preview",
        "public-profile-avatar-image",
        {
          "d-none": !isOpenAvatarEditor,
        },
      ],
    };

    return (
      <>
        <img
          className={classnames(classes.image)}
          src={`/${getAvatarUrl()}`}
          alt="avatar"
          ref={imageRef}
        />

        <div className={classnames(classes.imagePreview)} ref={imagePreviewRef}>
          <img src="" alt="" />
        </div>
      </>
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
                  setIsOpenAvatarEditor(false);
                  setAvatarDataUrl("");
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
                setAvatarRaw(fr.result);
                setIsOpenAvatarEditor(true);
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
                "d-none": getAvatarUrl() === DEFAULT_AVATAR_IMAGE,
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
                      <Label className={"mb-0"} required>
                        Public Name
                      </Label>

                      <RBForm.Text
                        className={
                          "auth-form-info_input-caption form-group-text d-block mb-10 my-md-0 mt-lg-10"
                        }
                      >
                        This is required but can be different to the name
                        associated with your account ({user.name})
                      </RBForm.Text>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        name="publicName"
                        value={values.publicName}
                        onChange={handleChange}
                        isInvalid={!!touched.publicName && !!errors.publicName}
                        isValid={touched.publicName && !errors.publicName}
                        autoComplete={"off"}
                      />
                      <Feedback type="invalid">{errors.publicName}</Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="PublicProfileFormLocation"
                    className={"row mt-20 mt-md-4 mt-lg-10"}
                  >
                    <div className="col-12 col-md-6 col-lg-6 text-md-end text-lg-start">
                      <Label className={"mb-0"} optional>
                        Location
                      </Label>
                    </div>

                    <div className={"col-12 col-md-6 col-lg-6"}>
                      <Input
                        name="location"
                        value={values.location}
                        onChange={handleChange}
                        isInvalid={!!touched.location && !!errors.location}
                        isValid={touched.location && !errors.location}
                        autoComplete={"off"}
                      />
                      <Feedback type="invalid">{errors.location}</Feedback>
                    </div>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="avatar_image"
                    className="mt-20 mt-md-4 row"
                  >
                    <div className="d-block d-md-none d-lg-flex col-12 col-lg-6 align-items-center">
                      <Label className={"mt-20 md-lg-0"} optional>
                        Upload a public profile picture
                      </Label>
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

                            {avatarImageTemplate()}

                            <div className="add-avatar-button public-profile-avatar_button">
                              <i className="photo-camera-icon common-icon" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <Feedback
                        type="invalid"
                        className={classnames("text-center text-lg-start", {
                          "d-block": !!errors.avatar_image,
                        })}
                      >
                        {errors.avatar_image}
                      </Feedback>
                    </div>

                    <div className="d-none d-md-block d-lg-none col-12 text-center">
                      <Label className={"text-align--center"} optional>
                        Upload a public profile picture
                      </Label>
                    </div>
                  </RBForm.Group>

                  {!errors.avatar_image && avatarEditorTemplate()}
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

                  <Link href={"/dashboard"}>
                    <a className={"text-decoration-none"}>
                      <button
                        type="button"
                        className="form-button public-profile-footer-button form-button__outline ms-md-12"
                        disabled={isSubmitting}
                      >
                        not now
                      </button>
                    </a>
                  </Link>
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
