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
    <Grid container alignItems="center" justify={"space-between"}>
      <span>{file.name}</span>
      <IconButton onClick={() => dispatch(deleteFile(file.name))}>
        <ClearIcon />
      </IconButton>
    </Grid>
  );
};
