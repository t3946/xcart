import React, { Fragment, useContext } from "react";
import { EmailLabel } from "@s3stores-mail/ts/types/email.type";
import CloseOutlinedIcon from "@material-ui/icons/CloseOutlined";
import { Grid } from "@material-ui/core";
import { useDispatch } from "react-redux";
import { editSearchOptions, getPage, setLoading } from "@admin/redux/actions";
import { emailStore } from "@redux/stores";
import { values } from "lodash";
import { initialValues } from "@s3stores-mail/ts/consts";
import { EmailRouterContext } from "@s3stores-mail/contexts/email-router-context/EmailRouter.context";
import { useHistory } from "react-router-dom";
interface EmailInfoLabels {
  labelsList: EmailLabel[];
  labelDelete: (id: number | string) => void;
}
export const EmailInfoLabels: React.FC<EmailInfoLabels> = ({
  labelsList,
  labelDelete,
}) => {
  const dispatch = useDispatch();
  const routers = useContext(EmailRouterContext);
  const history = useHistory();
  const onClickLabelHandler = (labelId: string) => {
    dispatch(
      editSearchOptions({
        ...initialValues.searchOptions,
        label: labelId,
      })
    );
    history.push(`${routers.listRouter}${1}`);
    dispatch(setLoading());
    dispatch(
      getPage(Number(1), { ...initialValues.searchOptions, label: labelId })
    );
  };
  return (
    <Fragment>
      {labelsList.length > 0 &&
        labelsList.map((label) => (
          <div
            style={{ backgroundColor: label.background_color }}
            className="header-mail-label-item"
          >
            <Grid
              justifyContent="space-between"
              container
              alignItems="center"
              direction="row"
            >
              <span
                style={{ color: label.color }}
                className="header-mail-label-text"
                onClick={() => onClickLabelHandler(label.label_id)}
              >
                {label.name}
              </span>
              <CloseOutlinedIcon
                onClick={() => labelDelete(label.label_id)}
                className="delete-label-item"
              />
            </Grid>
          </div>
        ))}
    </Fragment>
  );
};
