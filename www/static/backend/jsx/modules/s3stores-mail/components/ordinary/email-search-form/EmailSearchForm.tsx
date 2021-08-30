import React, { useContext } from "react";
import { Formik } from "formik";
import {
  Button,
  Checkbox,
  FormControlLabel,
  Grid,
  ListItemText,
  TextField,
} from "@material-ui/core";
import Input from "@material-ui/core/Input";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { initialValues, selectStyles } from "@s3stores-mail/ts/consts";
import { EmailDatePicker } from "@s3stores-mail/components/smart/email-date-picker/EmailDatePicker";
import { EmailSearchDialogContext } from "@s3stores-mail/contexts/email-search-dialog-context/EmailSearchDialog.context";
import { makeStyles } from "@material-ui/core/styles";
import FormControl from "@material-ui/core/FormControl";
import InputLabel from "@material-ui/core/InputLabel";
import Select from "@material-ui/core/Select";
import MenuItem from "@material-ui/core/MenuItem";
import Chip from "@material-ui/core/Chip";
import { name } from "i18next-intervalplural-postprocessor";

export const EmailSearchForm: React.FC<any> = () => {
  const formValues = useSelector((state: StoreDto) => state.searchOptions);
  const labelList = useSelector((state: StoreDto) => state.labelsList);
  console.log(labelList);
  const classes = selectStyles();

  const { editSearchValues } = useContext(EmailSearchDialogContext);

  const useStyles = makeStyles({
    input: {
      padding: "13.5px 14px !important",
    },
    label: {
      transform: "translate(14px, 16px) scale(1)",
    },
    chips: {
      display: "flex",
      flexWrap: "wrap",
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
            <FormControl
              fullWidth
              variant="outlined"
              className={`${classes.formControl} email-search-input`}
            >
              {/*              <InputLabel id="demo-simple-select-outlined-label">
                Label
              </InputLabel>
              <Select
                native
                name="label"
                onChange={(evt) =>
                  setFieldValue(
                    "label",
                    [].slice
                      .call(evt.target.selectedOptions)
                      .map((option) => option.value)
                  )
                }
                fullWidth
                multiple
                input={<Input />}
                renderValue={(selected) => (selected as string[]).join(", ")}
                MenuProps={MenuProps}
                value={values.label}
                label="Age"
              >
                {labelList.map((label) => (
                  <MenuItem key={label.label_id} value={label.label_id}>
                    <Checkbox checked={true} />
                    <ListItemText primary={label.label_id} />
                  </MenuItem>
                ))}
              </Select>*/}
              <InputLabel id="demo-mutiple-checkbox-label">Label</InputLabel>
              <Select
                labelId="demo-mutiple-checkbox-label"
                id="demo-mutiple-checkbox"
                name="label"
                multiple
                value={values.label || []}
                onChange={(evt) => {
                  setFieldValue("label", evt.target.value);
                }}
                input={<Input />}
                renderValue={(selected) => {
                  return selected
                    .map((select) => {
                      const label = labelList.find(
                        (lbl) => lbl.label_id === select
                      );
                      if (label) {
                        return label.name;
                      }
                      return select;
                    })
                    .join(", ");
                }}
              >
                {labelList.map((label) => (
                  <MenuItem key={label.id} value={label.label_id}>
                    <Checkbox
                      checked={
                        values.label && values.label.includes(label.label_id)
                      }
                    />
                    <ListItemText primary={label.name} />
                  </MenuItem>
                ))}
              </Select>
            </FormControl>
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
