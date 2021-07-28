import React, {useState} from "react";
import {Button, Grid} from "@material-ui/core";
import {ApiService} from "@admin/modules/shared/services/api.service";
import {DropZoneFileForm} from "@admin/modules/distributor/components/field-form-price/field-drop-zone";
import DialogTablePrice from "@admin/modules/distributor/components/dialog-table-price/dialog-table-price";
import {SnackBar} from "@admin/modules/shared/components/snack-bar/SnackBar";

const api = new ApiService();
export const FormPrice: React.FC<any> = ({distributorId}) => {
    const [fileForm, setFileForm] = useState('')
    const [dialogOpen, setDialogOpen] = useState(false);
    const [table, setTable] = useState([]);
    const [nameTable, setNameTable] = useState([]);
    const onChangeHandler = (file) => {
        setFileForm(file);
    }
    const formSave = () => {
        const data = new FormData();
        data.append('d_price_list', fileForm)
        data.append('dx', distributorId);
        setDialogOpen(true);
        api.post('/api/dx/price/save', data).then(res => {
            console.log(res);
            if (res.status) {
                setTable(res.data);
                setNameTable(res.tableNames);
            }
        })
    }
    return (
        <form>
            <SnackBar>
                <Grid container direction='column' justifyContent='center' alignItems='center'>
                    <DropZoneFileForm onChange={onChangeHandler} value={fileForm}/>
                    <div className='form__price_button'>
                        <Button variant="contained" onClick={formSave}>Upload</Button>
                    </div>
                    {dialogOpen && <DialogTablePrice file={fileForm}
                                                     dx={distributorId}
                                                     arTable={table}
                                                     state={{get: dialogOpen, set: setDialogOpen}}
                                                     arTableName={nameTable}
                    />
                    }
                </Grid>
            </SnackBar>
        </form>
    )
}