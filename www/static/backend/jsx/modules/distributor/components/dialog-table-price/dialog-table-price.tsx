import React, {useContext, useEffect, useState} from 'react';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import {Grid, Typography} from "@material-ui/core";
import LoadingDialog from "@admin/modules/distributor/components/dialog-table-price/loading";
import {ApiService} from "@admin/modules/shared/services/api.service";
import {SnackbarContext} from "@s3stores-mail/contexts/snackbar/Snackbar.context";
import {TabsTable} from "@admin/modules/distributor/components/dialog-table-price/tabs-table";

interface IDialogTablePrice{
    state: {get: any, set: any},
    arTable: [],
    file: object,
    dx: number,
    arTableName: []
}
export const DialogTablePrice: React.FC<IDialogTablePrice> = ({state, arTable, file, dx, arTableName}) => {
    const api = new ApiService();
    const [select, setSelect] = useState({});
    const [loading, setLoading] = useState(true);
    const {showSnackbar} = useContext(SnackbarContext);

    const onSaveHandler = () => {
        const data = new FormData();
        data.append('file', file);
        data.append('select', JSON.stringify(select));
        data.append('dx', dx);
        setLoading(true);
        api.post('/api/dx/products-price/save', data).then(res => {
            if (res) {
                state.set(false);
                showSnackbar(`You have successfully updated ${res.countUpdate} products`, "success");
            }
        });
    }

    const onChangeSelectHandler = (event) => {
        if (event.target.value === '') {
            return;
        }
        setSelect(prev => ({...prev, ...{[event.target.id]: event.target.value}}));
    }
    useEffect(() => {
        if (arTable.length) {
            setLoading(false);
        }
    }, [arTable])
    useEffect(() => {
        api.get(`/api/dx/column/get/${dx}`).then((res: {}) => {
            setSelect(res);
        })
    }, [])

    return (
        <Dialog
            fullWidth={true}
            maxWidth='xl'
            open={state.get}
            aria-labelledby="max-width-dialog-title"
        >
            <Typography align='center' variant='h6'>Price List</Typography>
            <DialogContent>
                {
                    !loading && arTable.length
                        ? (<TabsTable arTable={arTable} arTableName={arTableName} select={{get: select, set: onChangeSelectHandler}}/>)
                        : (<LoadingDialog/>)
                }
            </DialogContent>
            <DialogActions>
                <Grid container justify='center'>
                    <Button disabled={loading} color="primary" onClick={onSaveHandler}>
                        Save
                    </Button>
                </Grid>
            </DialogActions>
        </Dialog>
    );
}