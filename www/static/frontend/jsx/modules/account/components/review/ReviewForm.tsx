import React from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import InnerPage from "@client/modules/account/components/shared/InnerPage";
import appData from "@client/jsx/utils/AppData";
import SelectRating from "@client/modules/account/components/review/SelectRating";
import { Form as RBForm } from "react-bootstrap";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import Camera from "@client/jsx/modules/icon/components/account/camera/Camera";
import {
  createReviewAction,
  getVideoHeaderAction,
} from "@client/jsx/redux/actions/account-actions/ReviewActions";
import Files from "@client/jsx/modules/account/components/review/Files";

const ReviewForm = (): any => {
  const product = appData.review.product;
  const dispatch = useDispatch();
  const user = useSelector((e: StoreInterface) => e.user);
  const [files, setFiles] = React.useState([]);
  const initialValues = {
    overall: 0,
    headLine: "",
    textBody: "",
    publicName: user.public_name,
    videoLink: "",
  };
  const validationSchema = yup.object().shape({
    overall: yup.number(),
    headLine: yup.string().required("Headline is a required field"),
    textBody: yup.string().required("Review text line is a required field"),
    publicName: yup.string(),
    videoLink: yup.string().nullable(true),
  });
  const ratings = appData.ratings.ratings;
  const [isCheckFileLink, setIsCheckFileLink] = React.useState(false);
  const [isSubmitting, setIsSubmitting] = React.useState(false);

  ratings.features.forEach(function (e) {
    initialValues[e.slug] = 0;
    validationSchema[e.slug] = yup.number();
  });

  function userAvatarTemplate() {
    if (user && user.avatar_image) {
      return (
        <img
          src={user.avatar_image}
          className={"mobile-menu-avatar form-review-avatar"}
          alt={""}
        />
      );
    }

    return (
      <i
        className={
          "mobile-menu-sign-in-icon navigation-login-button__not-logged common-icon form-review-avatar"
        }
      />
    );
  }

  function submit(values) {
    const form = new FormData();

    for (let i = 0; i < files.length; i++) {
      form.append(`files[${i}]`, files[i]);
    }

    form.append("header", values.headLine);
    form.append("body", values.textBody);
    form.append("productId", product.productid);
    form.append("videoLink", values.videoLink);

    const fdRatings = {
      overall: values.overall,
    };

    ratings.features.forEach(function (e) {
      fdRatings[e.slug] = values[e.slug];
    });

    form.append("ratings", JSON.stringify(fdRatings));

    setIsSubmitting(true);

    dispatch(
      createReviewAction({
        form,

        success(res) {
          setIsSubmitting(false);
        },
      })
    );
  }

  function featuresRatingsTemplate(values, handleChange, setValues) {
    const templates = [];

    ratings.features.forEach(function (e) {
      templates.push(
        <div className={"form-review__feature-rating"}>
          <div className={"form-review-feature-title mb-1"}>{e.name}</div>

          <SelectRating
            name={e.slug}
            value={parseInt(values[e.slug])}
            handleChange={handleChange}
            reset={() => {
              values[e.slug] = 0;
              setValues(values);
            }}
            classes={{
              star: "form-review-star form-review-star_feature",
              container: "form-review-rating-container",
            }}
          />
        </div>
      );
    });

    return templates;
  }

  function overallRatingTemplate(values, handleChange, setValues) {
    return (
      <SelectRating
        name={ratings.overall.slug}
        value={parseInt(values.overall)}
        handleChange={handleChange}
        reset={() => {
          setValues({ ...values, overall: 0 });
        }}
        classes={{
          star: "form-review-star form-review-star_overall",
          container:
            "form-review-rating-container form-review-rating-container_overall",
        }}
      />
    );
  }

  function videoLinkChangeHandler(
    values,
    errors,
    setErrors,
    touched,
    setTouched
  ) {
    if (values.videoLink === "") {
      touched.videoLink = false;
      setTouched(touched);
      return;
    }

    setIsCheckFileLink(true);

    dispatch(
      getVideoHeaderAction({
        form: {
          videoFileUrl: values.videoLink,
        },

        success(res) {
          errors.videoLink = res?.errors[0] || null;
          setErrors(errors);
          setIsCheckFileLink(false);
          touched.videoLink = true;
          setTouched(touched);
        },
      })
    );
  }

  return (
    <div>
      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {function ({
          setValues,
          values,
          errors,
          touched,
          handleChange,
          setErrors,
          setTouched,
        }) {
          return (
            <Form>
              <InnerPage
                header={"Create review"}
                headerClasses={"text-center text-lg-start"}
                bodyClasses={"content-panel"}
                footerClasses={"d-flex"}
                footer={
                  <button
                    type={"submit"}
                    className="form-button w-100 w-md-auto"
                    disabled={isSubmitting}
                  >
                    submit
                  </button>
                }
              >
                <div className={"d-flex"}>
                  <div
                    className={
                      "align-items-center d-flex form-review-product-image-container justify-content-center form-review__image"
                    }
                  >
                    <img
                      className={"form-review-product-image"}
                      src={product.image}
                      alt={product.product}
                      width={"100"}
                      height={"100"}
                    />
                  </div>

                  <div>
                    <p className={"form-review-product-name m-0"}>
                      {product.group_mask + product.product}
                    </p>

                    <div className="d-md-none mt-2">
                      {overallRatingTemplate(values, handleChange, setValues)}
                    </div>
                  </div>
                </div>

                <div className="account-inner-page-divider account-inner-page__divider" />

                <div className={"d-none d-md-block"}>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Overall rating
                  </h2>

                  {overallRatingTemplate(values, handleChange, setValues)}

                  <div className="account-inner-page-divider account-inner-page__divider" />
                </div>

                <div>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Rate features
                  </h2>

                  <div>
                    {featuresRatingsTemplate(values, handleChange, setValues)}
                  </div>
                </div>

                <div className="account-inner-page-divider account-inner-page__divider" />

                <div>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Add a photo or video
                  </h2>

                  <p className={"form-review-comment"}>
                    Shoppers find images and videos more helpful than text
                    alone.
                  </p>

                  <Files setFiles={setFiles} />

                  <div className="d-md-none form-review-add-file-button_mobile d-flex align-items-center justify-content-center">
                    <Camera />
                  </div>

                  <RBForm.Group
                    controlId="videoLink"
                    className={"w-100 form-group_full-width"}
                  >
                    <RBForm.Label>
                      <h2 className={"account-inner-page-header-2 mb-1"}>
                        Link on video
                      </h2>

                      <p className={"form-review-comment"}>
                        You can add video by link. Just past link on video in
                        field below.
                      </p>
                    </RBForm.Label>

                    <RBForm.Control
                      type="text"
                      name="videoLink"
                      value={values.videoLink}
                      className={"form-input"}
                      isInvalid={!!touched.videoLink && !!errors.videoLink}
                      isValid={touched.videoLink && !errors.videoLink}
                      onChange={handleChange}
                      onBlur={() => {
                        videoLinkChangeHandler(
                          values,
                          errors,
                          setErrors,
                          touched,
                          setTouched
                        );
                      }}
                      placeholder={"Video link"}
                      disabled={isCheckFileLink || isSubmitting}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.videoLink}
                    </RBForm.Control.Feedback>
                  </RBForm.Group>
                </div>

                <div className="account-inner-page-divider account-inner-page__divider" />

                <div>
                  <RBForm.Group
                    controlId="headLine"
                    className={"w-100 form-group_full-width"}
                  >
                    <RBForm.Label>
                      <h2 className={"account-inner-page-header-2 mb-1"}>
                        Add a headline
                      </h2>
                    </RBForm.Label>

                    <RBForm.Control
                      type="text"
                      name="headLine"
                      value={values.headLine}
                      onChange={handleChange}
                      className={"form-input"}
                      isInvalid={!!touched.headLine && !!errors.headLine}
                      isValid={touched.headLine && !errors.headLine}
                      placeholder={"What’s most important to know?"}
                      disabled={isSubmitting}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.headLine}
                    </RBForm.Control.Feedback>
                  </RBForm.Group>

                  <RBForm.Group
                    controlId="textBody"
                    className={
                      "w-100 form-group_full-width mt-3 mt-md-4 mt-lg-20"
                    }
                  >
                    <RBForm.Label>
                      <h2 className={"account-inner-page-header-2"}>
                        Add a written review
                      </h2>
                    </RBForm.Label>

                    <RBForm.Control
                      as="textarea"
                      name="textBody"
                      value={values.textBody}
                      onChange={handleChange}
                      className={"form-input form-review-text-body"}
                      isInvalid={!!touched.textBody && !!errors.textBody}
                      isValid={touched.textBody && !errors.textBody}
                      placeholder={
                        "What did you like or dislike? What did you use this product for?"
                      }
                      disabled={isSubmitting}
                    />

                    <RBForm.Control.Feedback type="invalid">
                      {errors.textBody}
                    </RBForm.Control.Feedback>
                  </RBForm.Group>

                  <h2
                    className={
                      "account-inner-page-header-2 mt-3 mt-md-4 mt-lg-20"
                    }
                  >
                    Choose your public name
                  </h2>

                  <p className="mb-3 form-review-comment">
                    This is how you’ll appear to other customers
                  </p>

                  <div className="d-flex align-items-center">
                    {userAvatarTemplate()}

                    <input
                      type={"text"}
                      className={
                        "form-input ms-3 ms-md-20 ms-lg-2 form-review-public-name-input"
                      }
                      value={values.publicName}
                      disabled
                    />
                  </div>

                  <p className="mb-0 mt-2 form-review-comment">
                    Don’t worry, you can always change this on your profile
                  </p>
                </div>
              </InnerPage>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default ReviewForm;
