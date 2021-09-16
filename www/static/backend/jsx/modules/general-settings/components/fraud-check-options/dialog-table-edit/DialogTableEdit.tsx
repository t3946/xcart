import React, { Fragment, useContext } from "react";
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
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { useDispatch } from "react-redux";
import {
  changeFraudBaseQuestionData,
  changeFraudFAQuestionData,
} from "@redux/actions/fraudSettingsActions";

interface DialogTableEdit {
  state: { get: boolean; set: (newState: boolean) => void };
  form: { get: ChangeQuestionDataForm; set: (newState: any) => void };
  type: string;
}
export const DialogTableEdit: React.FC<DialogTableEdit> = ({
  state,
  form,
  type,
}) => {
  const dispatch = useDispatch();
  const { showSnackbar } = useContext(SnackbarContext);
  const saveChangeQuestion = () => {
    switch (type) {
      case "faQuestions":
        dispatch(changeFraudFAQuestionData(form.get));
        break;
      case "baseQuestions":
        dispatch(changeFraudBaseQuestionData(form.get));
        break;
    }
    showSnackbar("You have successfully update question data");
    state.set(!state.get);
  };
  const onChangeField = (event: React.ChangeEvent<HTMLInputElement>) => {
    form.set({ ...form.get, [event.target.name]: event.target.value });
  };
  return (
    <Fragment>
      {form.get && (
        <Dialog
          open={state.get}
          onClose={() => state.set(!state.get)}
          aria-labelledby="form-dialog-title"
        >
          <DialogTitle id="form-dialog-title">
            Changing data about question
          </DialogTitle>
          <DialogContent>
            <DialogContentText>
              You can change the template and the weight value of the question.
              Please do not change the dependency of the words specified in {{}}
            </DialogContentText>
            <Form>
              <Form.Group
                className="mb-3"
                controlId="exampleForm.ControlInput1"
              >
                <Form.Label>Weight</Form.Label>
                <Form.Control
                  value={form.get.weight}
                  type="text"
                  name="weight"
                  onChange={onChangeField}
                />
              </Form.Group>
              <Form.Group
                className="mb-3"
                controlId="exampleForm.ControlTextarea1"
              >
                <Form.Label>Template</Form.Label>
                <Form.Control
                  as="textarea"
                  value={form.get.template}
                  name="template"
                  rows={6}
                  onChange={onChangeField}
                />
              </Form.Group>
            </Form>
          </DialogContent>
          <DialogActions>
            <Button onClick={() => state.set(!state.get)} color="primary">
              Cancel
            </Button>
            <Button onClick={saveChangeQuestion} color="primary">
              Save
            </Button>
          </DialogActions>
        </Dialog>
      )}
    </Fragment>
  );
};
