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
import getStoreUrl from "@utils/getStoreUrl";

interface IProps {
  onCloseClick: any;
  list_item_id: any;
  listItem: any;
}

export const EditComment: React.FC<IProps> = function (props) {
  const { onCloseClick, list_item_id, listItem } = props;
  const dispatch = useDispatch();

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
      comment: listItem?.comment || "",
      priority:
        priorityProductSelectValuesConst.find(
          (e) => e.value === listItem.priority
        ) || priorityProductSelectValuesConst[2],
      needs: listItem?.needs || 1,
      has: listItem?.has || 0,
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

  function getImageUrl() {
    let imageUrl;

    switch (listItem.product_type) {
      case "product":
        imageUrl = getStoreUrl(listItem.product.images[0].path);
        break;
      case "idea":
        imageUrl = "/static/frontend/images/icons/account/idea-logo.svg";
        break;
    }

    return imageUrl;
  }

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
              src={getImageUrl()}
              className={cn(
                Styles.image,
                "product-image",
                "w-auto",
                "w-md-100",
                "edit-comment-img"
              )}
            />
            <div className={cn("text-start", "edit-comment-name", Styles.name)}>
              {listItem.product?.name || listItem.product?.product}
            </div>
          </div>
        </div>

        <div className={cn(Styles.inputsGroup)}>
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

          <div>
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

          <div>
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

        <div className={"mt-3"}>
          <SubmitCancelButtonsGroup
            submitText="Confirm"
            cancelText="Cancel"
            onCancel={onCloseClick}
            disabled={isLoading}
            groupAdvancedClasses={"justify-content-center justify-content-lg-start"}
            submitAdvancedClasses={"w-md-auto"}
            cancelAdvancedClasses={"w-md-auto d-none d-md-block"}
          />
        </div>
      </form>
    </div>
  );
};
