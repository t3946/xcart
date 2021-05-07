import React from "react";
import {
  Button,
  FormControl,
  Grid,
  Input,
  InputLabel,
  MenuItem,
  Paper,
  Select,
} from "@material-ui/core";

export const EmailInfoData = ({ data }) => {
  const [age, setAge] = React.useState<number | string>("");

  const handleChange = (event: React.ChangeEvent<{ value: unknown }>) => {
    setAge(Number(event.target.value) || "");
  };

  return (
    <Paper elevation={0} square={true} className="email-info-data-wrapper">
      <span>{data.title}</span>
      <Grid container>
        <span>From:</span>
        <span>FAXAGE support@faxage.com</span>
      </Grid>
      <Grid container>
        <span>To:</span>
        <span>faxage800@s3stores.com reply-to: support@faxage.com</span>
      </Grid>
      <span>
        You have received a new 2 page fax on FAXAGE from (707)792-1362. A copy
        is attached for your reference. You may also visit http://www.faxage.
        com to log in and work with your faxes.
      </span>
      <Grid container>
        <Grid xs={2}>
          <Button variant="outlined">1</Button>
        </Grid>
        <Grid xs={2}>
          <FormControl>
            <InputLabel htmlFor="grouped-native-select">Grouping</InputLabel>
            <select
              value={age}
              onChange={handleChange}
              defaultValue=""
              id="grouped-native-select"
            >
              <option aria-label="None" value="" />
              <optgroup label="Category 1">
                <option value={1}>Option 1</option>
                <option value={2}>Option 2</option>
              </optgroup>
              <optgroup label="Category 2">
                <option value={3}>Option 3</option>
                <option value={4}>Option 4</option>
              </optgroup>
            </select>
          </FormControl>
        </Grid>
        <Grid container xs={8} justify="flex-end">
          <Button variant="outlined">1</Button>
        </Grid>
      </Grid>
    </Paper>
  );
};
