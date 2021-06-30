import React, { useContext } from "react";
import { Formik } from "formik";
import {
  Button,
  Checkbox,
  FormControlLabel,
  Grid,
  TextField,
} from "@material-ui/core";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { initialValues } from "@s3stores-mail/ts/consts";
import { EmailDatePicker } from "@s3stores-mail/components/smart/email-date-picker/EmailDatePicker";
import { EmailSearchDialogContext } from "@s3stores-mail/contexts/email-search-dialog-context/EmailSearchDialog.context";
import { makeStyles } from "@material-ui/core/styles";

export const EmailSearchForm: React.FC<any> = () => {
  const formValues = useSelector((state: StoreDto) => state.searchOptions);

  const { editSearchValues } = useContext(EmailSearchDialogContext);

  const useStyles = makeStyles({
    input: {
      padding: "13.5px 14px !important",
    },
    label: {
      transform: "translate(14px, 16px) scale(1)",
    },
  });
  return (
    <div>
      <Formik onSubmit={null} initialValues={formValues} enableReinitialize>
        {({ values, handleChange, handleSubmit, setFieldValue, resetForm }) => (
          <form onSubmit={handleSubmit}>
            <TextField
              name="to"
              className="email-search-input"
              fullWidth
              label="To"
              onChange={handleChange}
              value={values.to}
              variant="outlined"
              InputProps={{
                classes: {
                  input: useStyles().input,
                },
              }}
              InputLabelProps={{
                classes: {
                  root: useStyles().label,
                },
              }}
            />
            <TextField
              name="from"
              className="email-search-input"
              fullWidth
              label="From"
              onChange={handleChange}
              value={values.from}
              variant="outlined"
              InputProps={{
                classes: {
                  input: useStyles().input,
                },
              }}
              InputLabelProps={{
                classes: {
                  root: useStyles().label,
                },
              }}
            />
            <TextField
              name="subject"
              className="email-search-input"
              fullWidth
              label="Subject"
              onChange={handleChange}
              value={values.subject}
              variant="outlined"
              InputProps={{
                classes: {
                  input: useStyles().input,
                },
              }}
              InputLabelProps={{
                classes: {
                  root: useStyles().label,
                },
              }}
            />
            <Grid alignItems="center" container justify="space-between">
              <Grid xs={5}>
                <EmailDatePicker
                  name="dateAfter"
                  max={new Date()}
                  label="Date After"
                  value={values.dateAfter}
                  handleDateChange={(e) => setFieldValue("dateAfter", e)}
                  InputProps={{
                    classes: {
                      input: useStyles().input,
                    },
                  }}
                  InputLabelProps={{
                    classes: {
                      root: useStyles().label,
                    },
                  }}
                />
              </Grid>
              <Grid container alignItems="center" justify="center" xs={1}>
                <span>To</span>
              </Grid>
              <Grid xs={5}>
                <EmailDatePicker
                  min={values.dateAfter}
                  max={new Date()}
                  name="dateBefore"
                  label="Date Before"
                  value={values.dateBefore}
                  handleDateChange={(e) => setFieldValue("dateBefore", e)}
                  InputProps={{
                    classes: {
                      input: useStyles().input,
                    },
                  }}
                  InputLabelProps={{
                    classes: {
                      root: useStyles().label,
                    },
                  }}
                />
              </Grid>
            </Grid>
            <FormControlLabel
              control={
                <Checkbox
                  checked={values.hasAttachment}
                  name="hasAttachment"
                  color="default"
                  onClick={() =>
                    setFieldValue("hasAttachment", !values.hasAttachment)
                  }
                />
              }
              label="Has attachment"
            />
            <Grid
              className="email-search-form-buttons"
              container
              justify="space-between"
            >
              <Button
                className="schedule-send-buttons-send"
                onClick={() => editSearchValues(values)}
              >
                Search
              </Button>
              <Button
                className="schedule-send-buttons-cancel"
                onClick={() => {
                  resetForm();
                  editSearchValues(initialValues.searchOptions);
                }}
              >
                Clear Options
              </Button>
            </Grid>
          </form>
        )}
      </Formik>
    </div>
  );
};
