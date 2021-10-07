import React, { Fragment } from "react";
import { Button, Grid } from "@material-ui/core";
import {
  ColorCreateLabel,
  SelectMenuColor,
} from "@s3stores-mail/ts/types/label";
import { GithubPicker } from "react-color";
import { gmailColorList } from "@s3stores-mail/ts/consts/email-label.const";
interface LabelCreateModalColor {
  selectMenu: {
    get: SelectMenuColor;
    set: (newState: SelectMenuColor) => void;
  };
  color: { get: ColorCreateLabel; set: (newState: ColorCreateLabel) => void };
}
export const LabelCreateModalColor: React.FC<LabelCreateModalColor> = ({
  selectMenu,
  color,
}) => {
  const onChangeColor = (clr, event, name) => {
    color.set({ ...color.get, ...{ [name]: clr.hex } });
  };
  const onClickButton = (nameAttr: string) => {
    selectMenu.set({
      ...selectMenu.get,
      ...{ [nameAttr]: !selectMenu.get[nameAttr] },
    });
  };
  return (
    <Grid container justifyContent="flex-start" direction="column">
      {Object.keys(selectMenu.get).map((colorAttr) => {
        return (
          <div className="label-create-color-block">
            <Button
              className="schedule-send-buttons-cancel"
              onClick={() => onClickButton(colorAttr)}
              fullWidth
            >
              Select {colorAttr} label
            </Button>
            {selectMenu.get[colorAttr] && (
              <GithubPicker
                colors={gmailColorList}
                onChangeComplete={(clr, event) =>
                  onChangeColor(clr, event, colorAttr)
                }
                color={color[colorAttr]}
              />
            )}
          </div>
        );
      })}
    </Grid>
  );
};
