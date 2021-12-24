import React, { Fragment } from "react";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { Form } from "react-bootstrap";
import { useDispatch } from "react-redux";
import {
  changeFraudBaseQuestionData,
  changeFraudFAQuestionData,
} from "@redux/actions/fraudSettingsActions";
import Dialog from "@mui/material/Dialog";
import {
  Button,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from "@mui/material";

interface DialogTableEdit {
  open: boolean;
  onClose: () => void;
  template: ChangeQuestionDataForm;
  onChangeTemplate: (newState: any) => void;
  type: string;
  isBase?: boolean;
}
export const DialogTableEdit: React.FC<DialogTableEdit> = ({
  open,
  onClose,
  type,
  onChangeTemplate,
  template,
  isBase = null,
}) => {
  const dispatch = useDispatch();
  const saveChangeQuestion = () => {
    switch (type) {
      case "faQuestions":
        dispatch(changeFraudFAQuestionData(template));
        break;
      case "baseQuestions":
        dispatch(changeFraudBaseQuestionData(template));
        break;
    }
    onClose();
  };
  const onChangeField = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChangeTemplate({ ...template, [event.target.name]: event.target.value });
  };
  return (
    <Dialog maxWidth={"sm"} fullWidth open={open} onClose={onClose}>
      <DialogTitle>
        Edit <b>{template.questionCode}</b> Fraud check question
      </DialogTitle>
      <DialogContent>
        <Form>
          <Form.Group className="mb-3">
            {isBase && (
              <Fragment>
                <Form.Label className="label-fraud-settings">
                  Order by
                </Form.Label>
                <Form.Control
                  value={template.orderBy}
                  type="text"
                  name="orderBy"
                  onChange={onChangeField}
                />
              </Fragment>
            )}
            <Form.Label className="label-fraud-settings">
              Question template
            </Form.Label>
            <Form.Control
              as="textarea"
              value={template.template}
              name="template"
              rows={6}
              // disabled={!isBase}
              onChange={onChangeField}
            />
            <Form.Label className="label-fraud-settings">
              Question weight
            </Form.Label>
            <Form.Control
              value={template.weight}
              type="text"
              name="weight"
              onChange={onChangeField}
            />
            {isBase && (
              <div>
                <Form.Label className="label-fraud-settings">Toggle</Form.Label>
                <Form.Control
                  as="select"
                  name="avail"
                  onChange={onChangeField}
                  value={template.avail}
                >
                  <option value={1}>Enable</option>
                  <option value={0}>Disable</option>
                </Form.Control>
              </div>
            )}
          </Form.Group>
        </Form>
      </DialogContent>
      <DialogActions>
        <Button onClick={onClose} color="primary">
          Cancel
        </Button>
        <Button onClick={saveChangeQuestion} color="primary">
          Save
        </Button>
      </DialogActions>
    </Dialog>
  );
};
