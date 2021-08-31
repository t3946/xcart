import React, { Fragment, useContext } from "react";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  TextField,
} from "@material-ui/core";
import { ChangeQuestionDataForm } from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { Form, FormControl, InputGroup } from "react-bootstrap";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { ResponseQuestionUpdate } from "@admin/modules/general-settings/ts/types/fraud-check/response";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/data-table";

interface DialogTableEdit {
  state: { get: boolean; set: (newState: boolean) => void };
  form: { get: ChangeQuestionDataForm; set: (newState: any) => void };
  type: string;
}
const api = new ApiService();
export const DialogTableEdit: React.FC<DialogTableEdit> = ({
  state,
  form,
  type,
}) => {
  const { showSnackbar } = useContext(SnackbarContext);
  const saveChangeQuestion = () => {
    api
      .post("/api/question/fa/update", JSON.stringify(form.get))
      .then((response: ResponseQuestionUpdate) => {
        if (response.update) {
          showSnackbar("You have successfully changed");
          setData((prev) => {
            const question: TableDataResponse = prev[type]["data"].find(
              (question) => question.questionId === form.get.questionId
            );
            if (question) {
              question.template = form.get.template;
              question.value = form.get.weight;
            }
            return { ...prev };
          });
          state.set(false);
        } else {
          showSnackbar("An error occurred while updating the data");
        }
      });
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
                <Form.Label>Email address</Form.Label>
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
