import React from 'react';
import {Button} from "@material-ui/core";

export const FileFieldForm: React.FC<any> = ({value, onChange}) => {
    return (
        <>
            <input
                accept="image/*"
                style={{display: 'none'}}
                id="contained-button-file"
                type="file"
                onChange={onChange}
                value={value}
            />
            <label htmlFor="contained-button-file">
                <Button variant="contained" color="primary" component="span">
                    Upload
                </Button>
            </label>
        </>
    );
}