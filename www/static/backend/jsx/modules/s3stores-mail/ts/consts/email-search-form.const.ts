import { createStyles, makeStyles, Theme } from "@material-ui/core/styles";

export const initialFormValues = {
  from: "",
  to: "",
  subject: "",
  words: "",
  doesntHave: "",
  dateRange: "",
  label: null,
};
export const selectStyles = makeStyles((theme: Theme) =>
  createStyles({
    formControl: {
      margin: theme.spacing(1),
      minWidth: 120,
      width: "100%",
    },
    selectEmpty: {
      marginTop: theme.spacing(2),
    },
  })
);
