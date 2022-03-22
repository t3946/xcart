import React from "react";
import { Formik, Form } from "formik";
import * as yup from "yup";
import { useDispatch, useSelector } from "react-redux";
import InnerPage from "@components/common/inner-page/InnerPage";
import appData from "@utils/AppData";
import SelectRating from "@modules/account/components/review/SelectRating";
import { Form as RBForm } from "react-bootstrap";
import StoreInterface from "@modules/account/ts/types/store.type";
import cn from "classnames";
import {
  createReviewAction,
  getVideoHeaderAction,
} from "@redux/actions/account-actions/ReviewActions";
import Files from "@modules/account/components/review/Files";
import validatorMaxFileSize from "@utils/yup/validatorMaxFileSize";
import validatorFileFormat from "@utils/yup/validatorFileFormat";
import Styles from "@modules/account/components/review/ReviewForm.module.scss";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import Label from "@modules/ui/forms/Label";
import Textarea from "@modules/ui/forms/Textarea";
import getStoreUrl from "@utils/getStoreUrl";
import { DEFAULT_ATTRIBUTE } from "@mui/system/cssVars/getInitColorSchemeScript";

interface IProps {
  product: number;
}

const ReviewForm: React.FC<IProps> = (props: IProps): any => {
  const { product } = props;
  const dispatch = useDispatch();
  const user = useSelector((e: StoreInterface) => e.user);
  if (!user) {
    return null;
  }
  const [files, setFiles] = React.useState([]);
  const initialValues = {
    overall: 0,
    headLine: "",
    textBody: "",
    publicName: user.public_name || "S3 Stores Customer",
    videoLink: "",
    files: null,
  };

  const supportedFormats = {
    images: ["image/jpg", "image/jpeg", "image/png"],
    videos: ["video/mp4", "video/ogg", "video/webm"],
  };
  const inputFileRef = React.useRef<HTMLInputElement>();
  const maxImageSizeMB = 10;
  const maxVideoSizeMB = 20;
  const maxAttachments = 10;

  const [attachmentsNumber, setAttachmentsNumber] = React.useState(0);

  const validationSchema = yup.object().shape({
    overall: yup.number(),
    headLine: yup.string().required("Headline is a required field"),
    textBody: yup.string().required("Review text line is a required field"),
    publicName: yup.string(),
    videoLink: yup.string().nullable(true),
    files: yup
      .mixed()
      .test(
        "fileSize",
        `Maximum uploaded file size: ${maxImageSizeMB} MB for images and ${maxVideoSizeMB} MB for videos`,
        validatorMaxFileSize(inputFileRef, [
          {
            formats: supportedFormats.images,
            maxSizeMB: maxImageSizeMB,
          },
          {
            formats: supportedFormats.videos,
            maxSizeMB: maxVideoSizeMB,
          },
        ])
      )
      .test(
        "fileType",
        "Unsupported file format",
        validatorFileFormat(inputFileRef, [
          ...supportedFormats.images,
          ...supportedFormats.videos,
        ])
      )
      .test("maxFiles", `Maximum ${maxAttachments} attachments`, function () {
        const newAttachmentsNumber =
          attachmentsNumber + inputFileRef.current.files.length;

        return newAttachmentsNumber <= maxAttachments;
      }),
  });
  const ratings = appData.ratings.ratings;
  const [isCheckFileLink, setIsCheckFileLink] = React.useState(false);
  const [isSubmitting, setIsSubmitting] = React.useState(false);

  ratings.features.forEach(function (e) {
    initialValues[e.slug] = 0;
    validationSchema[e.slug] = yup.number();
  });

  function userAvatarTemplate() {
    if (user) {
      const DEFAULT_AVATAR_IMAGE =
        "/static/frontend/images/pages/account/default-avatar.svg";
      const avatar =
        (user.avatar_image && getStoreUrl(user.avatar_image)) ||
        DEFAULT_AVATAR_IMAGE;

      return (
        <img
          src={avatar}
          className={"mobile-menu-avatar form-review-avatar"}
          alt={""}
        />
      );
    }
  }

  function submit(values, actions) {
    const data = new FormData();

    for (let i = 0; i < files.length; i++) {
      data.append(`files[${i}]`, files[i]);
    }

    data.append("header", values.headLine);
    data.append("body", values.textBody);
    data.append("productId", product.productid);
    data.append("videoLink", values.videoLink);

    const fdRatings = {
      overall: values.overall,
    };

    ratings.features.forEach(function (e) {
      fdRatings[e.slug] = values[e.slug];
    });

    data.append("ratings", JSON.stringify(fdRatings));

    setIsSubmitting(true);

    dispatch(
      createReviewAction({
        data,

        success(res) {
          if (res.errors) {
            actions.setErrors(res.errors);
          }

          window.location.href = `/product/${product.productid}/`;
        },
      })
    );
  }

  function featuresRatingsTemplate(values, handleChange, setValues) {
    const templates = [];

    ratings.features.forEach(function (e, i) {
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
            key={`feature-rating-${i}`}
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
                bodyClasses={"content-panel py-4"}
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
                      {product.group_mask}
                      {product.product}
                    </p>

                    <div className="d-md-none mt-2">
                      {overallRatingTemplate(values, handleChange, setValues)}
                    </div>
                  </div>
                </div>

                <div
                  className={cn(
                    "account-inner-page-divider",
                    Styles.accountInnerPage__divider
                  )}
                />

                <div className={"d-none d-md-block"}>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Overall rating
                  </h2>

                  {overallRatingTemplate(values, handleChange, setValues)}

                  <div
                    className={cn(
                      "account-inner-page-divider",
                      Styles.accountInnerPage__divider
                    )}
                  />
                </div>

                <div>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Rate features
                  </h2>

                  <div>
                    {featuresRatingsTemplate(values, handleChange, setValues)}
                  </div>
                </div>

                <div
                  className={cn(
                    "account-inner-page-divider",
                    Styles.accountInnerPage__divider
                  )}
                />

                <div>
                  <h2 className={"account-inner-page-header-2 mb-1"}>
                    Add a photo or video
                  </h2>
                  <p className={"form-review-comment"}>
                    Shoppers find images and videos more helpful than text
                    alone.
                  </p>

                  <RBForm.Group
                    controlId="videoLink"
                    className={"w-100 form-group_full-width mb-20"}
                  >
                    <Files
                      handleChange={handleChange}
                      setFiles={setFiles}
                      inputRef={inputFileRef}
                      imagesFormats={supportedFormats.images}
                      videosFormats={supportedFormats.videos}
                      maxImageSize={maxImageSizeMB}
                      maxVideoSize={maxVideoSizeMB}
                      setAttachmentsNumber={setAttachmentsNumber}
                      maxFiles={maxAttachments}
                    />

                    {!!errors.files && (
                      <Feedback type="invalid">{errors.files}</Feedback>
                    )}
                  </RBForm.Group>

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

                    <Input
                      value={values.videoLink}
                      name="videoLink"
                      onChange={handleChange}
                      isInvalid={!!touched.videoLink && !!errors.videoLink}
                      isValid={touched.videoLink && !errors.videoLink}
                      placeholder={"Video link"}
                      disabled={isCheckFileLink || isSubmitting}
                      onBlur={() => {
                        videoLinkChangeHandler(
                          values,
                          errors,
                          setErrors,
                          touched,
                          setTouched
                        );
                      }}
                    />

                    <Feedback type="invalid">{errors.videoLink}</Feedback>
                  </RBForm.Group>
                </div>

                <div
                  className={cn(
                    "account-inner-page-divider",
                    Styles.accountInnerPage__divider
                  )}
                />

                <div>
                  <div className="mb-3">
                    <Label>
                      <h2 className={"account-inner-page-header-2 mb-1"}>
                        Add a headline
                      </h2>
                      <Input
                        name={"headLine"}
                        onChange={handleChange}
                        isInvalid={!!touched.headLine && !!errors.headLine}
                        isValid={touched.headLine && !errors.headLine}
                        placeholder={"What’s most important to know?"}
                        disabled={isSubmitting}
                      />
                    </Label>

                    {!!touched.headLine && !!errors.headLine && (
                      <Feedback type="invalid">{errors.headLine}</Feedback>
                    )}
                  </div>

                  <Label>
                    <h2 className={"account-inner-page-header-2"}>
                      Add a written review
                    </h2>
                    <Textarea
                      name="textBody"
                      onChange={handleChange}
                      isInvalid={!!touched.textBody && !!errors.textBody}
                      isValid={touched.textBody && !errors.textBody}
                      placeholder={
                        "What did you like or dislike? What did you use this product for?"
                      }
                      disabled={isSubmitting}
                    />
                  </Label>
                  {!!touched.textBody && !!errors.textBody && (
                    <Feedback type="invalid">{errors.textBody}</Feedback>
                  )}

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
