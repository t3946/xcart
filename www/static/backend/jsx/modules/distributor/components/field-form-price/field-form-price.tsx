import React, { useState } from "react";
import {Grid, Typography} from "@material-ui/core";
import {SelectFieldForm} from "@admin/modules/distributor/components/field-form-price/field-select";
import {InputFieldForm} from "@admin/modules/distributor/components/field-form-price/field-input";
import {FileFieldForm} from "@admin/modules/distributor/components/field-form-price/field-file";
import {LabelFieldForm} from "@admin/modules/distributor/components/field-form-price/field-label";

export const FormField: React.FC<any> = ({ type, label, inputProps }) => {
    let itemChild = null;
    switch (type) {
        case 'select':
            console.log(inputProps);
            itemChild = <SelectFieldForm {...inputProps}/>
            break;
        case 'file':
            itemChild = <FileFieldForm {...inputProps}/>
            break;
        case 'label':
            itemChild = <LabelFieldForm {...inputProps}/>
            break;
        default:
            itemChild = <InputFieldForm {...inputProps}/>
            break;
    }
    return (
        <Grid
            container
            direction="column"
            justifyContent="center"
            alignItems="center"
        >
            <Typography variant='h6' align='left'>{label}</Typography>
            {itemChild}
        </Grid>
    )
}