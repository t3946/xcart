import React, { Fragment, useContext, useEffect, useState } from "react";
import { Grid } from "@material-ui/core";
import { SnackbarContext } from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import axios from "axios";
import { DialogTablePrice } from "@admin/modules/distributor/components/dialog-table-price/dialog-table-price";
import List from "@mui/material/List";
import ListItem from "@mui/material/ListItem";
import ListItemButton from "@mui/material/ListItemButton";
import ListItemIcon from "@mui/material/ListItemIcon";
import ListItemText from "@mui/material/ListItemText";
import InsertDriveFileIcon from "@mui/icons-material/InsertDriveFile";
import {
  FileItem,
  FilesInfo,
} from "@admin/modules/distributor/ts/types/table-price.types";
import Divider from "@mui/material/Divider";
import { CircularProgress } from "@mui/material";

interface IFormPrice {
  distributorId: number;
}

export const FormPrice: React.FC<IFormPrice> = ({ distributorId }) => {
  const [files, setFiles] = useState<FilesInfo>(null);
  const [selectedFile, setSelectedFile] = useState<FileItem>(null);
  const [dialogOpen, setDialogOpen] = useState<boolean>(false);
  const [table, setTable] = useState([]);
  const [nameTable, setNameTable] = useState([]);
  const { showSnackbar } = useContext(SnackbarContext);

  const handleClose = () => {
    setDialogOpen(!dialogOpen);
    setSelectedFile(null);
    setTable([]);
    setNameTable([]);
  };
  useEffect(() => {
    try {
      axios.get(`/api/dx/get-file-list/${distributorId}`).then((response) => {
        setFiles(response.data);
      });
    } catch (e) {
      if (e.response.data) {
        showSnackbar(e.response.data.message, "error");
      }
    }
  }, []);
  const selectFile = (file: FileItem) => {
    setSelectedFile(file);
    try {
      setDialogOpen(true);
      axios
        .post(
          `/api/dx/load-file`,
          JSON.stringify({
            distributorId,
            fileId: file.id,
            folderId: files.folderId,
          })
        )
        .then((response) => {
          setTable(response.data.contentFile);
          setNameTable(response.data.tableNames);
        });
    } catch (e) {
      showSnackbar("An error has occurred, please try again", "error");
    }
  };
  return (
    <Grid
      container
      direction="column"
      justifyContent="center"
      alignItems="center"
    >
      {files ? (
        <List>
          {files.files.map((file) => (
            <Fragment>
              <ListItem disablePadding>
                <ListItemButton onClick={() => selectFile(file)}>
                  <ListItemIcon>
                    <InsertDriveFileIcon />
                  </ListItemIcon>
                  <ListItemText primary={file.name} />
                </ListItemButton>
              </ListItem>
              <Divider />
            </Fragment>
          ))}
        </List>
      ) : (
        <CircularProgress />
      )}
      {dialogOpen && (
        <DialogTablePrice
          pathFile={`${files.folderId}/${selectedFile.id}`}
          dx={distributorId}
          arTable={table}
          state={{ get: dialogOpen, set: setDialogOpen }}
          arTableName={nameTable}
          folderDx={files.folderId}
          closeHandle={handleClose}
        />
      )}
    </Grid>
  );
};
