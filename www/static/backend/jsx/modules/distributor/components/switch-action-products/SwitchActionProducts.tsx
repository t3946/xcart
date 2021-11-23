import React from "react";
import { Switch, Typography } from "@material-ui/core";
import Box from "@mui/material/Box";
import Stack from "@mui/material/Stack";
interface SwitchActionProducts {
  state: boolean;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  text: string;
}
export const SwitchActionProducts: React.FC<SwitchActionProducts> = ({
  state,
  onChange,
  text,
}) => {
  return (
    <Box sx={{ py: 1 }}>
      <Stack alignItems="center" justifyContent="center" direction="row">
        <Typography variant="body2">{text}</Typography>
        <Switch
          checked={state}
          onChange={(e) => onChange(e)}
          name="checked"
          inputProps={{
            "aria-label": "secondary checkbox",
          }}
        />
      </Stack>
    </Box>
  );
};
