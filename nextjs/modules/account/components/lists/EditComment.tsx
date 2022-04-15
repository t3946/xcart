import React from "react";
import { useFormik } from "formik";
import * as Yup from "yup";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { useDispatch, useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import cn from "classnames";
import Select from "@modules/ui/forms/select/Select";
import Label from "@modules/ui/forms/Label";
import { priorityProductSelectValuesConst } from "@modules/account/ts/consts/priority-product-select-values.const";
import SubmitCancelButtonsGroup from "@modules/account/components/shared/SubmitCancelButtonsGroup";
import { editCommentProduct } from "@redux/actions/account-actions/ListsActions";
import Styles from "@modules/account/components/lists/EditComment.module.scss";

interface IProps {
  onCloseClick: any;
  list_item_id: any;
  info: any;
}

export const EditComment: React.FC<IProps> = function (props) {
  const { onCloseClick, list_item_id, info } = props;
  const dispatch = useDispatch();
  const ideaImg = "/static/frontend/images/icons/account/idea-logo.svg";

  function submit(values: any) {
    const data = { ...values, priority: values.priority.value, list_item_id };
    dispatch(
      editCommentProduct({
        data,
        callback: onCloseClick,
      })
    );
  }

  const formik = useFormik({
    initialValues: {
      comment: info?.comment || "",
      priority:
        priorityProductSelectValuesConst.find(
          (e) => e.value === info.priority
        ) || priorityProductSelectValuesConst[2],
      needs: info?.needs || 1,
      has: info?.has || 0,
    },
    validationSchema: Yup.object().shape({
      comment: Yup.string()
        .required("Required field")
        .max(250, "The maximum comment length is 250 characters"),
      needs: Yup.number().required("Required field").min(0, "Min value - 0"),
      has: Yup.number().required("Required field").min(0, "Min value - 0"),
    }),
    onSubmit: submit,
  });
  const isLoading = useSelector((e: StoreInterface) => e.lists.listLoading);

  return (
    <div>
      <form onSubmit={formik.handleSubmit} encType="multipart/form-data">
        <div className="top-content">
          <div className="comment-input-container flex-grow-md-1 flex-grow-lg-0 me-md-5 me-lg-0">
            <div
              className={cn({
                "mb-20": !!formik.touched.comment && !!formik.errors.comment,
              })}
            >
              <Label>Comment</Label>
              <Input
                as="textarea"
                name="comment"
                maxLength={250}
                className={cn("edit-comment-text-area-input", Styles.textarea)}
                value={formik.values.comment}
                onChange={formik.handleChange}
                isInvalid={!!formik.touched.comment && !!formik.errors.comment}
                isValid={!!formik.touched.comment && !formik.errors.comment}
              />
              <Feedback className="position-absolute" type="invalid">
                {!!formik.touched.comment && formik.errors.comment}
              </Feedback>
            </div>
            {(!formik.touched.comment || !formik.errors.comment) && (
              <div className="remaining-text mt-0">
                Remaining:{" "}
                {formik.values.comment.length < 250
                  ? 250 - formik.values.comment.length
                  : 0}{" "}
                characters
              </div>
            )}
          </div>

          <div className={cn(Styles.imageContainer, "edit-comment-img-block")}>
            <img
              src={info.image || ideaImg}
              className={cn(
                Styles.image,
                "product-image",
                "w-auto",
                "w-md-100",
                "edit-comment-img"
              )}
            />
            <div className={cn("text-start", "edit-comment-name", Styles.name)}>
              {info.product?.name || info.product?.product}
            </div>
          </div>
        </div>
        <div className="edit-comment-inputs-container mb-20">
          <div>
            <Label>Priority</Label>
            <Select
              clearable={false}
              options={priorityProductSelectValuesConst}
              name={"priority"}
              label={"Priority"}
              onChange={formik.handleChange}
              value={formik.values.priority}
              classes={{
                select: ["edit-comment-select-field-container"],
              }}
            />
          </div>

          <div
            className={cn(
              "justify-content-md-end",
              "justify-content-lg-between",
              "edit-idea-text-inputs"
            )}
          >
            <div
              className={cn(
                "edit-comment-input-text-field-container",
                "edit-comment-input-text-field-needs-container",
                "me-md-20",
                "me-lg-0"
              )}
            >
              <Label>Need</Label>
              <Input
                type="number"
                name="needs"
                className={cn("list-input-edit-idea", "full-width")}
                value={formik.values.needs}
                onChange={formik.handleChange}
                isInvalid={!!formik.touched.needs && !!formik.errors.needs}
                isValid={!!formik.touched.needs && !formik.errors.needs}
              />
              <Feedback className="position-absolute mt-0" type="invalid">
                {!!formik.touched.needs && formik.errors.needs}
              </Feedback>
            </div>

            <div
              className={cn(
                "edit-comment-input-text-field-container",
                "edit-comment-input-text-field-needs-container"
              )}
            >
              <Label>Have</Label>
              <Input
                type="number"
                name="has"
                className={cn("list-input-edit-idea", "full-width")}
                value={formik.values.has}
                onChange={formik.handleChange}
                isInvalid={!!formik.touched.has && !!formik.errors.has}
                isValid={!!formik.touched.has && !formik.errors.has}
              />
              <Feedback className="position-absolute mt-0" type="invalid">
                {!!formik.touched.has && formik.errors.has}
              </Feedback>
            </div>
          </div>
        </div>
        <SubmitCancelButtonsGroup
          submitText="Confirm"
          cancelText="Cancel"
          onCancel={onCloseClick}
          disabled={isLoading}
          groupAdvancedClasses={"edit-idea-info-btns"}
        />
      </form>
    </div>
  );
};
