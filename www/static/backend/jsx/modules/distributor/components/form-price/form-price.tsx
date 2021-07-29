import React, {useContext, useState} from "react";
import {Button, Grid} from "@material-ui/core";
import {ApiService} from "@admin/modules/shared/services/api.service";
import {DropZoneFileForm} from "@admin/modules/distributor/components/field-form-price/field-drop-zone";
import {SnackbarContext} from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import {DialogTablePrice} from "@admin/modules/distributor/components/dialog-table-price/dialog-table-price";

interface IFormPrice {
    distributorId: number
}
const api = new ApiService();
export const FormPrice: React.FC<IFormPrice> = ({distributorId}) => {
    const [fileForm, setFileForm] = useState('')
    const [dialogOpen, setDialogOpen] = useState<Boolean>(false);
    const [table, setTable] = useState([]);
    const [nameTable, setNameTable] = useState([]);
    const {showSnackbar} = useContext(SnackbarContext);
    const onChangeHandler = (file) => {
        setFileForm(file);
    }
    const formSave = () => {
        const data = new FormData();
        data.append('d_price_list', fileForm)
        data.append('dx', distributorId);
        setDialogOpen(true);
        api.post('/api/dx/price/save', data).then(res => {
            if (res.status) {
                setTable(res.data);
                setNameTable(res.tableNames);
            } else {
                showSnackbar('An error has occurred, please try again', 'error')
            }
        })
    }
    return (
        <form>
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
        </form>
    )
}