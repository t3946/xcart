import React from "react";
import { Grid, IconButton } from "@material-ui/core";
import ClearIcon from "@material-ui/icons/Clear";
import { useDispatch } from "react-redux";
import { deleteFile } from "@redux/actions";

interface FileListDto {
  file: File;
}

export const EmailSendFilesListItem: React.FC<FileListDto> = ({ file }) => {
  const dispatch = useDispatch();
  return (
    <Grid
      xs={6}
      className="file-item-wrapper"
      container
      alignItems="center"
      justify={"space-between"}
    >
      <Grid xs={10}>
        <div className="file-item-text">
          <div className="file-item-name">{file.name}</div>
          <span>({file.size / 1000}kb)</span>
        </div>
      </Grid>

      <IconButton onClick={() => dispatch(deleteFile(file.name))}>
        <ClearIcon />
      </IconButton>
    </Grid>
  );
};
