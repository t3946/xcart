import React, { Fragment } from "react";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from "@material-ui/core";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { Form } from "react-bootstrap";
import { useDispatch } from "react-redux";
import {
  changeFraudBaseQuestionData,
  changeFraudFAQuestionData,
} from "@redux/actions/fraudSettingsActions";

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
    <Dialog open={open} onClose={onClose} aria-labelledby="form-dialog-title">
      <DialogTitle id="form-dialog-title">
        Edit {template.questionCode} Fraud check question
      </DialogTitle>
      <DialogContent>
        <DialogContentText>
          Below you can edit question template and question weight.
        </DialogContentText>
        <Form>
          <Form.Group className="mb-3" controlId="exampleForm.ControlTextarea1">
            <Form.Label>Question template</Form.Label>
            <Form.Control
              as="textarea"
              value={template.template}
              name="template"
              rows={6}
              onChange={onChangeField}
            />
          </Form.Group>
          <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
            <Form.Label>Question weight</Form.Label>
            <Form.Control
              value={template.weight}
              type="text"
              name="weight"
              onChange={onChangeField}
            />
          </Form.Group>
          {isBase && (
            <Form.Group className="mb-3" controlId="exampleForm.ControlInput1">
              <Form.Label>Order by</Form.Label>
              <Form.Control
                value={template.orderBy}
                type="text"
                name="orderBy"
                onChange={onChangeField}
              />
            </Form.Group>
          )}
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
