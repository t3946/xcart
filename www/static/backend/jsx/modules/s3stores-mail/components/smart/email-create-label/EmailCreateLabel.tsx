import React, { useContext, useState } from "react";
import {
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  TextField,
  Typography,
  Button,
} from "@material-ui/core";
import { EmailLabelContext } from "@s3stores-mail/contexts/email-label-context/EmailLabelContext";
import { useDispatch } from "react-redux";
import { createLabel } from "@redux/actions";
import { LabelCreateModalColor } from "@s3stores-mail/components/simple/label-create-modal-color/LabelCreateModalColor";
import {
  ColorCreateLabel,
  SelectMenuColor,
} from "@s3stores-mail/ts/types/label";
import { ExampleCreateLabel } from "@s3stores-mail/components/ordinary/example-create-label/ExampleCreateLabel";
import {
  initColorsCreateLabel,
  initStateCreateLabelMenu,
} from "@s3stores-mail/ts/consts/email-label.const";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import { EmailInfoContext } from "@s3stores-mail/contexts/email-info-context/EmailInfoContext";

export const EmailCreateLabel: React.FC<any> = () => {
  const { modal, messageId } = useContext(EmailLabelContext);
  const { showSnackbar } = useContext(SnackbarContext);
  const [selectMenu, setSelectMenu] = useState<SelectMenuColor>(
    initStateCreateLabelMenu
  );
  const [color, setColor] = useState<ColorCreateLabel>(initColorsCreateLabel);
  const [value, setValue] = useState("");
  const { parentEmail } = useContext(EmailInfoContext);
  const dispatch = useDispatch();
  const createLabelHandler = () => {
    if (value !== "") {
      dispatch(
        createLabel(parentEmail.item.message_id, messageId, value, color)
      );
      modal.set();
    } else {
      showSnackbar("Please select label name", "error");
    }
  };
  return (
    <Dialog
      open={modal.get}
      onClose={modal.set}
      aria-labelledby="form-dialog-title"
    >
      <DialogTitle id="form-dialog-title">
        <Typography variant="h5" align="center">
          Create new label
        </Typography>
      </DialogTitle>
      <DialogContent>
        <ExampleCreateLabel color={color} />
        <TextField
          autoFocus
          margin="dense"
          id="name"
          label="Write name label"
          type="email"
          value={value}
          onChange={(event: React.ChangeEvent<HTMLInputElement>) =>
            setValue(event.target.value)
          }
          fullWidth
        />
        <LabelCreateModalColor
          selectMenu={{ get: selectMenu, set: setSelectMenu }}
          color={{ get: color, set: setColor }}
        />
      </DialogContent>
      <DialogActions>
        <Button className="schedule-send-buttons-cancel" onClick={modal.set}>
          Cancel
        </Button>
        <Button
          className="schedule-send-buttons-cancel"
          onClick={createLabelHandler}
        >
          Create
        </Button>
      </DialogActions>
    </Dialog>
  );
};
